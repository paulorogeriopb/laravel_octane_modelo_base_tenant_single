<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

if (! function_exists('pageTitle')) {
    function pageTitle(): string
    {
        $routeName = Route::currentRouteName();

        if (! $routeName) {
            return __('Página');
        }

        $prefix = explode('.', $routeName)[0] ?? $routeName;

        // Busca o título no arquivo de tradução rotas.php (ex: rotas.users)
        $title = __('rotas.'.$prefix);

        // Se não encontrar, cria um título automático como fallback
        if ($title === 'rotas.'.$prefix) {
            // fallback para título automático formatado
            return Illuminate\Support\Str::title(str_replace('-', ' ', $prefix));
        }

        return $title;
    }
}
