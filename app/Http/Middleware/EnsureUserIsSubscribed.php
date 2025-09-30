<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsSubscribed
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Se não estiver logado → redireciona para login
        if (! $user) {
            return redirect()->route('login');
        }

        // Se for superadmin → ignora checagem
        if ($user->hasRole('superadmin')) {
            return $next($request);
        }

        // Pega assinatura
        $subscription = $user->subscription('default');

        // Se não tiver assinatura ou carência expirou → redireciona pro checkout
        if (! $subscription || ! $subscription->valid()) {
            return redirect()->route('subscriptions.checkout');
        }

        return $next($request);
    }
}