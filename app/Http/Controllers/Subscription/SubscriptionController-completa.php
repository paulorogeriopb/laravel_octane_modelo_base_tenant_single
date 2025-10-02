<?php

namespace App\Http\Controllers\Subscription;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Stripe\Stripe;
use Stripe\Invoice;
use App\Models\Plan;


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

        // Se já está ativo ou em período de carência, vai pra conta
        if ($subscription && ($subscription->valid() || $subscription->onGracePeriod())) {
            return redirect()->route('subscriptions.account');
        }

         $plan = session('plan');


        // Caso contrário, mostra checkout
        return view('subscriptions.checkout', [
            'intent' => auth()->user()->createSetupIntent(),
            'plan' => $plan,
        ]);
    }


    /**
     * Criar assinatura inicial
     */
    public function store(Request $request)
    {
        try {

            $plan = session('plan');

            if (!$plan) {
                return back()->with('error', __('Escolha um plano antes de realizar a assinatura.'));
            }

            $request->user()
                ->newSubscription('default', $plan->stripe_id)
                 ->trialDays(7)
                ->create($request->token);

            return redirect()->route('subscriptions.account')
                ->with('success', __('Assinatura criada com sucesso!'));
        } catch (\Exception $e) {
            return back()->with('error', __('Erro ao criar assinatura: ') . $e->getMessage());
        }
    }



    /**
     * Conta do assinante (detalhes + faturas)
     */
 public function account()
    {
        $user = auth()->user();
        $currentPlan = null;
        $nextPayment = null;
        $nextAmount = null;
        $planEndDate = null;
        $isTrial = false;
        $statusLabel = 'Sem assinatura';
        $statusClass = 'bg-gray-200 text-gray-800';

        $subscription = $user->subscription('default');

        if ($subscription) {
            // Status da assinatura
            if ($subscription->onTrial()) {
                $statusLabel = 'Em Trial';
                $statusClass = 'bg-yellow-200 text-yellow-800';
                $isTrial = true;
                $nextPayment = $subscription->trial_ends_at;
                $nextAmount = 'Grátis';
                $planEndDate = $subscription->trial_ends_at;
            } elseif ($subscription->active()) {
                $statusLabel = 'Ativa';
                $statusClass = 'bg-blue-200 text-green-800';
                $stripeSubscription = $subscription->asStripeSubscription();

                if ($stripeSubscription?->current_period_end) {
                    $planEndDate = Carbon::createFromTimestamp($stripeSubscription->current_period_end);
                }

                // Próximo pagamento usando fatura futura
                try {
                    Stripe::setApiKey(env('STRIPE_SECRET'));

                   $upcomingInvoice = $stripeClient->invoices->createPreview([
    'customer'     => $subscription->stripe_customer,
    'subscription' => $subscription->stripe_id,
]);

                    if ($upcomingInvoice) {
                        $nextPayment = isset($upcomingInvoice->next_payment_attempt)
                            ? Carbon::createFromTimestamp($upcomingInvoice->next_payment_attempt)
                            : $planEndDate;

                        $nextAmount = number_format(($upcomingInvoice->amount_due ?? 0) / 100, 2, ',', '.');
                    }
                } catch (\Exception $e) {
                    $nextPayment = $planEndDate;
                    $nextAmount = number_format(($subscription->items()->first()?->price ?? 0) / 100, 2, ',', '.');
                }
            } elseif ($subscription->cancelled()) {
                $statusLabel = 'Cancelada';
                $statusClass = 'bg-red-200 text-red-800';
                $planEndDate = $subscription->ends_at;
            } else {
                $statusLabel = 'Inativa';
                $statusClass = 'bg-gray-200 text-gray-800';
                $planEndDate = $subscription->ends_at;
            }

            // Informações do plano
            $item = $subscription->items()->first();
            if ($item) {
                $planModel = Plan::where('stripe_id', $item->stripe_price)->first();

                $currentPlan = [
                    'name'       => $planModel->name ?? $item->stripe_price,
                    'price'      => $planModel?->price_br ?? number_format(($item->price ?? 0) / 100, 2, ',', '.'),
                    'interval'   => $subscription->stripe_price_interval ?? 'Mês',
                    'trial_days' => $item->trial_days ?? 0,
                ];
            }
        }

        // Mapear faturas com dados do Stripe para status correto e valores formatados
        $invoices = $user->invoices()->map(function ($invoice) {
            $stripeInvoice = $invoice->asStripeInvoice();

            if ($stripeInvoice->status === 'paid') {
                $invoice->status_label = 'Pago';
                $invoice->status_class = 'px-2 py-1 text-xs text-green-800 bg-green-200 rounded';
            } elseif ($stripeInvoice->status === 'open') {
                $invoice->status_label = 'Pendente';
                $invoice->status_class = 'px-2 py-1 text-xs text-red-800 bg-red-200 rounded';
            } else {
                $invoice->status_label = ucfirst($stripeInvoice->status);
                $invoice->status_class = 'px-2 py-1 text-xs text-gray-800 bg-gray-200 rounded';
            }

            $invoice->formatted_total = number_format($stripeInvoice->total / 100, 2, ',', '.');
            $invoice->formatted_date = Carbon::createFromTimestamp($stripeInvoice->created)->format('d/m/Y');

            return $invoice;
        });

        return view('subscriptions.account', compact(
            'invoices',
            'currentPlan',
            'nextPayment',
            'nextAmount',
            'planEndDate',
            'isTrial',
            'subscription',
            'statusLabel',
            'statusClass'
        ));
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

            // Carrega todos os planos ativos
        $plans = Plan::where('active', true)->get();

        // Decodifica JSON de limits e features para cada plano
        $plans->transform(function ($plan) {
            $plan->limits = $plan->limits ? json_decode($plan->limits, true) : [];
            $plan->features = $plan->features ? json_decode($plan->features, true) : [];
            return $plan;
        });

        return view('subscriptions.plans', [
            'plans' => $plans,
        ]);
    }

    /**
     * Alterar plano
     */
    public function updatePlan(Request $request)
    {

    }
}
