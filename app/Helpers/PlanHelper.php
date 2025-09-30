<?php

namespace App\Helpers;

class PlanHelper
{
    public static function getPlanByStripeId(?string $stripeId): ?array
    {
        if (!$stripeId) return null;

        foreach (config('plans') as $plan) {
            if ($plan['stripe_id'] === $stripeId) {
                return $plan;
            }
        }

        return null;
    }

    public static function getPlanByKey(string $key): ?array
    {
        $plans = config('plans');
        return $plans[$key] ?? null;
    }
}