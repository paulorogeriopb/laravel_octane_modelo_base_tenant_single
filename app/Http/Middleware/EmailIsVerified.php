<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EmailIsVerified
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // Permite acesso às rotas de request/verify-code
       $allowedRoutes = [
            'email/verification',
            'email/verification/*',
            'email/verify-code',
            'email/verify-code/*',
            'forgot-password-code',
            'forgot-password-code/*',
            'reset-password-code',
            'reset-password-code/*',
        ];

      if ($user && !$user->hasVerifiedEmail() && !$request->is(...$allowedRoutes)) {
    return redirect()->route('email-verification.form');
}

        return $next($request);
    }
}