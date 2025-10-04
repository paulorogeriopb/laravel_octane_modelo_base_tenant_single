<?php

namespace App\Http\Controllers\Subscription;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Helpers\SubscriptionHelper;
use App\Models\Plan;
use Carbon\Carbon;
use Stripe\StripeClient;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Tela de checkout da assinatura
     */
    public function checkout()
    {
        $subscription = auth()->user()->subscription('default');

        if ($subscription && ($subscription->valid() || $subscription->onGracePeriod())) {
            return redirect()->route('subscriptions.account');
        }

        $plan = session('plan');

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

            $trialDays = 0; // Ajuste conforme necessidade

            $subBuilder = $request->user()->newSubscription('default', $plan->stripe_id);

            if ($trialDays > 0) {
                $subBuilder->trialDays($trialDays);
            }

            $subBuilder->create($request->token);

            return redirect()->route('subscriptions.account')
                ->with('success', __('Assinatura criada com sucesso!'));
        } catch (\Exception $e) {
            Log::error('Erro criando assinatura: ' . $e->getMessage());
            return back()->with('error', __('Erro ao criar assinatura: ') . $e->getMessage());
        }
    }

    /**
     * Conta do assinante (detalhes + faturas)
     */
    public function account()
    {
        $user = Auth::user();
        $subscription = $user->subscription('default');

        $currentPlan = null;
        $nextPayment = null;
        $nextAmount = null;
        $planEndDate = null;
        $statusLabel = 'Sem assinatura';
        $statusClass = 'bg-gray-200 text-gray-800';
        $isTrial = false;
        $trialDaysLeft = 0;
        $trialHours = 0;
        $trialMinutes = 0;
        $card = null;

        if ($subscription) {
            try {
                $stripeSub = $subscription->asStripeSubscription();
            } catch (\Throwable $e) {
                $stripeSub = null;
            }

            // Calcula período de trial ou grace
            try {
                $remainingPayload = SubscriptionHelper::remainingTime($subscription);
                if (!($remainingPayload['is_trial'] ?? false) && !($remainingPayload['is_grace'] ?? false)) {
                    $remainingPayload = null;
                }
            } catch (\Throwable $e) {
                $remainingPayload = null;
            }

            // Define status da assinatura
            if ($remainingPayload && ($remainingPayload['is_trial'] ?? false)) {
                $statusLabel = 'Período de teste';
                $statusClass = 'bg-yellow-200 text-yellow-800';
                $planEndDate = $remainingPayload['ends_at'];
                $isTrial = true;
                $trialDaysLeft = $remainingPayload['days'];
                $trialHours = $remainingPayload['hours'];
                $trialMinutes = $remainingPayload['minutes'];
            } elseif ($subscription->onGracePeriod() || ($stripeSub?->cancel_at_period_end ?? false)) {
                $statusLabel = 'Cancelamento agendado';
                $statusClass = 'bg-red-200 text-red-800';
                $planEndDate = $subscription->ends_at ?? Carbon::createFromTimestamp($stripeSub->current_period_end ?? time());
            } elseif ($subscription->active() && !$subscription->onGracePeriod()) {
                $statusLabel = 'Assinatura ativa';
                $statusClass = 'bg-green-200 text-green-800';
                if (!empty($stripeSub?->current_period_end)) {
                    $planEndDate = Carbon::createFromTimestamp($stripeSub->current_period_end);
                }
            } elseif ($stripeSub?->status === 'canceled') {
                $statusLabel = 'Cancelada';
                $statusClass = 'bg-red-200 text-red-800';
                $planEndDate = $subscription->ends_at ?? null;
            }

            // Plano atual
            $item = $subscription->items()->first();
            if ($item) {
                $planModel = Plan::where('stripe_id', $item->stripe_price)->first();
                $currentPlan = [
                    'name' => $planModel->name ?? $item->stripe_price,
                    'price' => $planModel?->price_br ?? number_format(($item->price ?? 0)/100, 2, ',', '.'),
                    'interval' => $planModel?->interval ?? 'Mês',
                    'features' => $planModel?->features ? json_decode($planModel->features, true) : [],
                    'limits' => $planModel?->limits ? json_decode($planModel->limits, true) : [],
                    'trial_days' => $planModel?->trial_days ?? ($item->trial_days ?? 0),
                ];
            }

            // Próxima cobrança
            try {
                $stripe = new StripeClient(env('STRIPE_SECRET'));
                $preview = $stripe->invoices->createPreview([
                    'customer' => $user->stripe_id,
                    'subscription' => $subscription->stripe_id,
                ]);

                if (!empty($preview)) {
                    $nextPayment = !empty($preview->next_payment_attempt)
                        ? Carbon::createFromTimestamp($preview->next_payment_attempt)
                        : $planEndDate;
                    $nextAmount = number_format(($preview->amount_due ?? 0)/100, 2, ',', '.');
                }
            } catch (\Throwable $e) {
                $nextAmount = $nextAmount ?? number_format(($subscription->items()->first()?->price ?? 0)/100, 2, ',', '.');
            }

            // Cartão
            try {
                $pm = null;
                if (!empty($stripeSub?->default_payment_method)) {
                    $pm = $stripe->paymentMethods->retrieve($stripeSub->default_payment_method);
                } else {
                    $customer = $stripe->customers->retrieve($user->stripe_id);
                    if (!empty($customer->invoice_settings->default_payment_method)) {
                        $pm = $stripe->paymentMethods->retrieve($customer->invoice_settings->default_payment_method);
                    }
                }

                if (!empty($pm) && ($pm->type ?? '') === 'card') {
                    $card = [
                        'brand' => $pm->card->brand ?? null,
                        'last4' => $pm->card->last4 ?? null,
                        'exp_month' => $pm->card->exp_month ?? null,
                        'exp_year' => $pm->card->exp_year ?? null,
                    ];
                }
            } catch (\Throwable $e) {
                $card = null;
            }
        }

        // Faturas
        $invoices = $user->invoices()->map(function ($invoice) {
            $stripeInvoice = $invoice->asStripeInvoice();
            $invoice->status_label = match ($stripeInvoice->status) {
                'paid' => 'Pago',
                'open' => 'Pendente',
                default => ucfirst($stripeInvoice->status),
            };
            $invoice->status_class = match ($stripeInvoice->status) {
                'paid' => 'px-2 py-1 text-xs text-green-800 bg-green-200 rounded',
                'open' => 'px-2 py-1 text-xs text-red-800 bg-red-200 rounded',
                default => 'px-2 py-1 text-xs text-gray-800 bg-gray-200 rounded',
            };
            $invoice->formatted_total = number_format($stripeInvoice->total / 100, 2, ',', '.');
            $invoice->formatted_date = Carbon::createFromTimestamp($stripeInvoice->created)->format('d/m/Y');
            $invoice->hosted_url = $stripeInvoice->hosted_invoice_url ?? null;
            return $invoice;
        });

        return view('subscriptions.account', compact(
            'invoices',
            'currentPlan',
            'nextPayment',
            'nextAmount',
            'planEndDate',
            'subscription',
            'statusLabel',
            'statusClass',
            'isTrial',
            'trialDaysLeft',
            'trialHours',
            'trialMinutes',
            'card'
        ));
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
     * Reativar assinatura em período de graça ou cancelamento agendado
     */
   public function resume()
{
    $user = auth()->user();
    $subscription = $user->subscription('default');

    if (!$subscription) {
        return redirect()->route('subscriptions.account')
            ->with('error', __('Nenhuma assinatura encontrada.'));
    }

    $stripe = new StripeClient(env('STRIPE_SECRET'));
    $stripeSub = $subscription->asStripeSubscription();

    if ($subscription->onGracePeriod() || ($stripeSub->cancel_at_period_end ?? false)) {
        // Reativa a assinatura definindo cancel_at_period_end para false
        $stripe->subscriptions->update($subscription->stripe_id, [
            'cancel_at_period_end' => false,
        ]);

        return redirect()->route('subscriptions.account')
            ->with('success', __('Sua assinatura foi reativada.'));
    }

    return redirect()->route('subscriptions.account')
        ->with('error', __('A assinatura não pode ser reativada.'));
}

    /**
     * Mostrar planos disponíveis
     */
    public function showPlans()
    {
        $plans = Plan::where('active', true)->get();
        $plans->transform(function ($plan) {
            $plan->limits = $plan->limits ? json_decode($plan->limits, true) : [];
            $plan->features = $plan->features ? json_decode($plan->features, true) : [];
            return $plan;
        });

        return view('subscriptions.plans', ['plans' => $plans]);
    }

    /**
     * Alterar plano da assinatura
     */
    public function updatePlan(Request $request)
    {
        $request->validate([
            'plan' => 'required',
        ]);

        $user = $request->user();
        $plan = Plan::where('id', $request->plan)
            ->orWhere('stripe_id', $request->plan)
            ->first();

        if (!$plan) {
            return back()->with('error', 'Plano inválido.');
        }

        $subscription = $user->subscription('default');
        if (!$subscription || !$subscription->active()) {
            return back()->with('error', 'Você não possui uma assinatura ativa.');
        }

        try {
            if (!$user->stripe_id) {
                $user->createAsStripeCustomer();
            }

            $subscription->swap($plan->stripe_id);
            $subscription->invoice();

            return redirect()->route('subscriptions.account')
                ->with('success', 'Plano alterado e cobrado com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao trocar plano: ' . $e->getMessage());
            return back()->with('error', 'Erro ao alterar plano: ' . $e->getMessage());
        }
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
}
