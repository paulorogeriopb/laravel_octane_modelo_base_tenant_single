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
     * Checkout de assinatura
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

            $trialDays = property_exists($plan, 'trial_days') ? (int)$plan->trial_days : 0;

            $subBuilder = $request->user()
                ->newSubscription('default', $plan->stripe_id);

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
        $user = auth()->user();
        $subscription = $user->subscription('default');

        // Defaults
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
        $graceDaysLeft = 0;
        $graceHours = 0;
        $graceMinutes = 0;
        $remainingPayload = ['days'=>0,'hours'=>0,'minutes'=>0,'ends_at'=>null,'type'=>null];

        if ($subscription) {
            try {
                $stripeSub = $subscription->asStripeSubscription();
            } catch (\Throwable $e) {
                $stripeSub = null;
            }

            // Calcula tempo restante via helper
            $remainingPayload = SubscriptionHelper::remainingTime($subscription);

            if ($remainingPayload['is_trial'] ?? false) {
                $statusLabel = 'Período de teste';
                $statusClass = 'bg-yellow-200 text-yellow-800';
                $planEndDate = $remainingPayload['ends_at'];
                $isTrial = true;
                $trialDaysLeft = $remainingPayload['days'];
                $trialHours = $remainingPayload['hours'];
                $trialMinutes = $remainingPayload['minutes'];
            } elseif ($remainingPayload['is_grace'] ?? false) {
                $statusLabel = 'Cancelada (Período de Graça)';
                $statusClass = 'bg-orange-200 text-orange-800';
                $planEndDate = $remainingPayload['ends_at'];
                $graceDaysLeft = $remainingPayload['days'];
                $graceHours = $remainingPayload['hours'];
                $graceMinutes = $remainingPayload['minutes'];
            } elseif ($subscription->active()) {
                $statusLabel = 'Assinatura ativa';
                $statusClass = 'bg-blue-200 text-green-800';
                if ($stripeSub?->current_period_end) {
                    $planEndDate = Carbon::createFromTimestamp($stripeSub->current_period_end);
                }
            } elseif ($subscription->cancelled()) {
                $statusLabel = 'Cancelada';
                $statusClass = 'bg-red-200 text-red-800';
                $planEndDate = $subscription->ends_at;
            }

            // Plano atual
            $item = $subscription->items()->first();
            if ($item) {
                $planModel = Plan::where('stripe_id', $item->stripe_price)->first();
                $currentPlan = [
                    'name' => $planModel->name ?? $item->stripe_price,
                    'price' => $planModel?->price_br ?? number_format(($item->price ?? 0)/100,2,',','.'),
                    'interval' => $subscription->stripe_price_interval ?? 'Mês',
                    'features' => $planModel?->features ? json_decode($planModel->features,true) : [],
                    'limits' => $planModel?->limits ? json_decode($planModel->limits,true) : [],
                    'trial_days' => $planModel?->trial_days ?? ($item->trial_days ?? 0),
                ];
            }

            // Próxima fatura via Stripe Preview
            try {
                $stripeClient = new StripeClient(env('STRIPE_SECRET'));
                $preview = $stripeClient->invoices->createPreview([
                    'customer' => $subscription->stripe_customer,
                    'subscription' => $subscription->stripe_id,
                ]);

                if (!empty($preview)) {
                    $nextPayment = !empty($preview->next_payment_attempt)
                        ? Carbon::createFromTimestamp($preview->next_payment_attempt)
                        : $planEndDate;

                    $nextAmount = number_format(($preview->amount_due ?? 0)/100,2,',','.');
                }
            } catch (\Throwable $e) {
                $nextAmount = $nextAmount ?? number_format(($subscription->items()->first()?->price ?? 0)/100,2,',','.');
            }
        }

        // Mapear faturas
        $invoices = $user->invoices()->map(function($invoice){
            $stripeInvoice = $invoice->asStripeInvoice();
            $invoice->status_label = match($stripeInvoice->status) {
                'paid' => 'Pago',
                'open' => 'Pendente',
                default => ucfirst($stripeInvoice->status),
            };
            $invoice->status_class = match($stripeInvoice->status) {
                'paid' => 'px-2 py-1 text-xs text-green-800 bg-green-200 rounded',
                'open' => 'px-2 py-1 text-xs text-red-800 bg-red-200 rounded',
                default => 'px-2 py-1 text-xs text-gray-800 bg-gray-200 rounded',
            };
            $invoice->formatted_total = number_format($stripeInvoice->total/100,2,',','.');
            $invoice->formatted_date = Carbon::createFromTimestamp($stripeInvoice->created)->format('d/m/Y');
            $invoice->hosted_url = $stripeInvoice->hosted_invoice_url ?? null;
            return $invoice;
        });

        return view('subscriptions.account', compact(
            'invoices','currentPlan','nextPayment','nextAmount','planEndDate',
            'subscription','statusLabel','statusClass','remainingPayload',
            'isTrial','trialDaysLeft','trialHours','trialMinutes',
            'graceDaysLeft','graceHours','graceMinutes'
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
        $plans = Plan::where('active', true)->get();

        $plans->transform(function($plan){
            $plan->limits = $plan->limits ? json_decode($plan->limits,true) : [];
            $plan->features = $plan->features ? json_decode($plan->features,true) : [];
            return $plan;
        });

        return view('subscriptions.plans', ['plans'=>$plans]);
    }

    /**
     * Alterar plano
     */
    public function updatePlan(Request $request)
    {
        $request->validate([
            'plan' => 'required',
        ]);

        $planInput = $request->input('plan');
        $plan = Plan::where('id',$planInput)->orWhere('stripe_id',$planInput)->first();

        if (!$plan) {
            return back()->with('error','Plano inválido.');
        }

        $subscription = auth()->user()->subscription('default');

        if (!$subscription || !$subscription->active()) {
            return back()->with('error','Você não tem uma assinatura ativa.');
        }

        try {
            $subscription->swap($plan->stripe_id);

            if (!empty($plan->trial_days) && (int)$plan->trial_days > 0) {
                try {
                    $stripeClient = new StripeClient(env('STRIPE_SECRET'));
                    $trialEndsAt = now()->addDays((int)$plan->trial_days)->timestamp;
                    $stripeClient->subscriptions->update($subscription->stripe_id, ['trial_end' => $trialEndsAt]);
                } catch (\Throwable $e) {
                    Log::warning('Não foi possível aplicar trial ao novo plano: '.$e->getMessage());
                }
            }

            return redirect()->route('subscriptions.account')
                ->with('success','Plano alterado com sucesso!');
        } catch (\Throwable $e) {
            Log::error('Erro ao trocar plano: '.$e->getMessage());
            return back()->with('error','Erro ao alterar plano: '.$e->getMessage());
        }
    }
}
