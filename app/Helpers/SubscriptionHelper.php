<?php

namespace App\Helpers;

use Carbon\Carbon;

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

        if (!$subscription) {
            return $result;
        }

        // Tenta obter dados atualizados do Stripe
        try {
            $stripeSub = $subscription->asStripeSubscription();
        } catch (\Throwable $e) {
            $stripeSub = null;
        }

        // 1️⃣ Verifica se está em período de trial
        if ($subscription->onTrial()) {
            $endsAt = $subscription->trial_ends_at ?? null;

            if (!$endsAt && $stripeSub && !empty($stripeSub->trial_end)) {
                $endsAt = Carbon::createFromTimestamp($stripeSub->trial_end);
            }

            if ($endsAt && now()->lt($endsAt)) {
                $result['type'] = 'trial';
                $result['is_trial'] = true;
                $result['ends_at'] = $endsAt;
            }
        }
        // 2️⃣ Verifica se está em período de graça (grace)
        elseif ($subscription->onGracePeriod() && ! $subscription->onTrial()) {
            $endsAt = $subscription->ends_at ?? null;

            if (!$endsAt && $stripeSub && !empty($stripeSub->cancel_at)) {
                $endsAt = Carbon::createFromTimestamp($stripeSub->cancel_at);
            }

            // Só considera grace se houver pelo menos 1 hora restante
            if ($endsAt && now()->lt($endsAt) && now()->diffInHours($endsAt) > 0) {
                $result['type'] = 'grace';
                $result['is_grace'] = true;
                $result['ends_at'] = $endsAt;
            }
        }

        // 3️⃣ Calcula dias, horas e minutos restantes
        if ($result['ends_at'] instanceof Carbon && now()->lt($result['ends_at'])) {
            $diff = now()->diff($result['ends_at']);
            $result['days'] = (int) $diff->d;
            $result['hours'] = (int) $diff->h;
            $result['minutes'] = (int) $diff->i;
        }

        return $result;
    }
}