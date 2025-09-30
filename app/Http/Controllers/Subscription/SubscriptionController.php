<?php

namespace App\Http\Controllers\Subscription;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\PlanHelper;
use Stripe\StripeClient;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Checkout de assinatura
     */
    public function checkout()
    {
        $subscription = auth()->user()->subscription('default');

        if ($subscription && ($subscription->valid() || $subscription->onGracePeriod())) {
            return redirect()->route('subscriptions.account');
        }

        return view('subscriptions.checkout', [
            'intent' => auth()->user()->createSetupIntent(),
        ]);
    }

    /**
     * Criar assinatura inicial
     */
    public function store(Request $request)
    {
        try {
            $defaultPlanId = config('plans.start.stripe_id');

            $request->user()
                ->newSubscription('default', $defaultPlanId)
                ->create($request->token);

            return redirect()->route('subscriptions.account')
                ->with('success', __('Assinatura criada com sucesso!'));
        } catch (\Exception $e) {
            return back()->with('error', __('Erro ao criar assinatura: ') . $e->getMessage());
        }
    }

    /**
     * Tela inicial da assinatura
     */
    public function start()
    {
        return view('subscriptions.start');
    }

    /**
     * Conta do assinante (detalhes + faturas)
     */
    public function account()
    {
        $user = auth()->user();
        $invoices = $user->invoices();

        $currentPlanId = $user->subscription('default')?->stripe_price;
        $currentPlan = PlanHelper::getPlanByStripeId($currentPlanId);

        return view('subscriptions.account', [
            'invoices' => $invoices,
            'currentPlan' => $currentPlan,
        ]);
    }

    /**
     * Download de fatura
     */
    public function invoiceDownload($invoiceId)
    {
        return Auth::user()->downloadInvoice($invoiceId, [
            'vendor' => config('app.name'),
            'product' => 'Assinatura Mensal',
        ]);
    }

    /**
     * Cancelar assinatura
     */
    public function cancel()
    {
        $subscription = auth()->user()->subscription('default');

        if ($subscription && $subscription->active()) {
            $subscription->cancel();
        }

        return redirect()->route('subscriptions.account')
            ->with('success', __('Sua assinatura foi cancelada.'));
    }

    /**
     * Reativar assinatura em período de graça
     */
    public function resume()
    {
        $subscription = auth()->user()->subscription('default');

        if ($subscription && $subscription->onGracePeriod()) {
            $subscription->resume();
        }

        return redirect()->route('subscriptions.account')
            ->with('success', __('Sua assinatura foi reativada.'));
    }

    /**
     * Mostrar planos disponíveis
     */
    public function showPlans()
    {
        $plans = config('plans');
        $currentPlanId = auth()->user()->subscription('default')?->stripe_price;

        return view('subscriptions.plans', [
            'plans' => $plans,
            'currentPlanId' => $currentPlanId,
        ]);
    }

    /**
     * Alterar plano
     */
    public function updatePlan(Request $request)
    {
        $request->validate([
            'plan' => 'required|string',
        ]);

        $plan = PlanHelper::getPlanByKey($request->plan);

        if (!$plan) {
            return back()->with('error', __('Plano inválido.'));
        }

        try {
            auth()->user()->subscription('default')->swap($plan['stripe_id']);

            return redirect()->route('subscriptions.account')
                ->with('success', __('Plano alterado com sucesso!'));
        } catch (\Exception $e) {
            return back()->with('error', __('Erro ao alterar plano: ') . $e->getMessage());
        }
    }
}
