<?php

namespace App\Http\Controllers\Subscription;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentMethodController extends Controller
{
    /**
     * Lista os métodos de pagamento do usuário
     */
    public function index(Request $request)
    {
        $user = $request->user();

        return view('payment-methods.index', [
            'paymentMethods' => $user->paymentMethods(),
            'defaultPaymentMethod' => $user->defaultPaymentMethod(),
            'intent' => $user->createSetupIntent(),
        ]);
    }

    /**
     * Adiciona novo método de pagamento
     */
    public function store(Request $request)
{
    try {
        $user = $request->user();
        $paymentMethodId = $request->input('token'); // token vindo do Stripe.js

        if (! $paymentMethodId) {
            return back()->with('error', 'Método de pagamento inválido.');
        }

        // Adiciona o cartão
        $user->addPaymentMethod($paymentMethodId);

        // Define como padrão
        $user->updateDefaultPaymentMethod($paymentMethodId);

        return back()->with('success', 'Novo cartão adicionado e definido como padrão!');
    } catch (\Exception $e) {
        Log::error("Erro ao adicionar cartão: {$e->getMessage()}");
        return back()->with('error', 'Erro ao adicionar novo cartão: ' . $e->getMessage());
    }
}

    /**
     * Define cartão como padrão
     */
    public function setDefault(Request $request, $paymentMethodId)
    {
        try {
            $user = $request->user();
            $paymentMethod = $user->findPaymentMethod($paymentMethodId);

            if (!$paymentMethod) {
                return back()->with('error', 'Método de pagamento não encontrado.');
            }

            $user->updateDefaultPaymentMethod($paymentMethod->id);

            // 🔹 Atualiza a assinatura ativa
            if ($user->subscribed('default')) {
                $user->subscription('default')->updateDefaultPaymentMethod($paymentMethod->id);
            }

            return back()->with('success', 'Cartão atualizado como padrão!');
        } catch (\Exception $e) {
            Log::error("Erro ao definir cartão padrão: {$e->getMessage()}");
            return back()->with('error', 'Erro ao definir cartão padrão: ' . $e->getMessage());
        }
    }

    /**
     * Remove um método de pagamento
     */
    public function destroy(Request $request, $paymentMethodId)
    {
        try {
            $user = $request->user();
            $paymentMethod = $user->findPaymentMethod($paymentMethodId);

            if (!$paymentMethod) {
                return back()->with('error', 'Método de pagamento não encontrado.');
            }

            if ($paymentMethod->id === $user->defaultPaymentMethod()?->id) {
                return back()->with('error', 'Você não pode remover o cartão padrão. Adicione outro antes.');
            }

            $paymentMethod->delete();

            return back()->with('success', 'Cartão removido com sucesso!');
        } catch (\Exception $e) {
            Log::error("Erro ao remover cartão: {$e->getMessage()}");
            return back()->with('error', 'Erro ao remover cartão: ' . $e->getMessage());
        }
    }
}
