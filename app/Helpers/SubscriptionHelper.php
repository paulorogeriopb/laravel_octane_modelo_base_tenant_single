<?php

namespace App\Helpers;

use Carbon\Carbon;
use Stripe\StripeClient;

class SubscriptionHelper
{
    /**
     * Retorna dados de tempo restante (trial ou grace) para uma assinatura Cashier.
     *
     * @param  \Laravel\Cashier\Subscription|null  $subscription
     * @return array
     *    [
     *       'type' => 'trial'|'grace'|null,
     *       'ends_at' => Carbon|null,
     *       'days' => int,
     *       'hours' => int,
     *       'minutes' => int,
     *       'is_trial' => bool,
     *       'is_grace' => bool,
     *    ]
     */
    public static function remainingTime($subscription): array
    {
        $result = [
            'type'      => null,
            'ends_at'   => null,
            'days'      => 0,
            'hours'     => 0,
            'minutes'   => 0,
            'is_trial'  => false,
            'is_grace'  => false,
        ];

        if (! $subscription) {
            return $result;
        }

        // Recarrega dados Stripe (tenta obter atualizações)
        try {
            $stripeSub = $subscription->asStripeSubscription();
        } catch (\Throwable $e) {
            $stripeSub = null;
        }

        // Detecta trial
        if ($subscription->onTrial()) {
            $endsAt = $subscription->trial_ends_at;

            // fallback para Stripe raw
            if (! $endsAt && $stripeSub && ! empty($stripeSub->trial_end)) {
                $endsAt = \Carbon\Carbon::createFromTimestamp($stripeSub->trial_end);
            }

            $result['type'] = 'trial';
            $result['is_trial'] = true;
            $result['ends_at'] = $endsAt;
        }
        // Detecta período de graça (grace/cancel_at)
        elseif ($subscription->onGracePeriod()) {
            $endsAt = $subscription->ends_at;

            if (! $endsAt && $stripeSub && ! empty($stripeSub->cancel_at)) {
                $endsAt = \Carbon\Carbon::createFromTimestamp($stripeSub->cancel_at);
            }

            $result['type'] = 'grace';
            $result['is_grace'] = true;
            $result['ends_at'] = $endsAt;
        } else {
            // Também cobrir caso o Stripe tenha cancel_at definido, mesmo sem Cashier marcar grace
            if ($stripeSub && ! empty($stripeSub->cancel_at)) {
                $endsAt = \Carbon\Carbon::createFromTimestamp($stripeSub->cancel_at);
                $result['type'] = 'grace';
                $result['is_grace'] = true;
                $result['ends_at'] = $endsAt;
            }
        }

        // Se temos um ends_at válido e no futuro, calcula dias/horas/minutos
        if ($result['ends_at'] instanceof Carbon && now()->lt($result['ends_at'])) {
            $diff = now()->diff($result['ends_at']);
            $result['days'] = (int) $diff->d;
            $result['hours'] = (int) $diff->h;
            $result['minutes'] = (int) $diff->i;
        } else {
            // se terminou ou não existe, mantemos zeros
            $result['days'] = 0;
            $result['hours'] = 0;
            $result['minutes'] = 0;
            // se ends_at passou, podemos nullificar ends_at para evitar mostrar datas passadas
            if ($result['ends_at'] instanceof Carbon && now()->gte($result['ends_at'])) {
                $result['ends_at'] = null;
                $result['type'] = null;
                $result['is_trial'] = false;
                $result['is_grace'] = false;
            }
        }

        return $result;
    }
}