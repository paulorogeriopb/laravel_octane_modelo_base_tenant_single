<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // Pega da sessão ou query ou fallback
        $locale = session('locale', $request->query('locale', config('app.locale')));

        // Se for array, pega o primeiro valor
        if (is_array($locale)) {
            Log::warning('SetLocale: locale veio como array', ['locale' => $locale]);
            $locale = reset($locale);
        }

        // Garante que seja string
        if (!is_string($locale) || empty($locale)) {
            $locale = config('app.locale');
        }

        // Idiomas válidos
        $availableLocales = ['pt_BR', 'en', 'es'];
        if (!in_array($locale, $availableLocales)) {
            $locale = config('app.locale');
        }

        // Define o locale
        app()->setLocale($locale);

        return $next($request);
    }
}