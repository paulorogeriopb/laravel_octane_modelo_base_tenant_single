<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

if (! function_exists('renderBreadcrumb')) {
    function renderBreadcrumb(): string
    {
        $routeName = Route::currentRouteName();

        if (! $routeName) {
            return '';
        }

        $segments = explode('.', $routeName);
        $breadcrumb = '<nav class="text-sm text-gray-400 dark:text-gray-600">';

        if (Route::has('dashboard')) {
            $breadcrumb .= '<a href="'.route('dashboard').'" class="hover:underline hover:text-cor-padrao dark:hover:text-cor-claro">'.trans('rotas.dashboard').'</a>';
        } else {
            $breadcrumb .= '<span>'.trans('rotas.dashboard').'</span>';
        }

        if ($routeName === 'dashboard') {
            return $breadcrumb.'</nav>';
        }

        $breadcrumb .= ' / ';

        $firstSegment = $segments[0];
        $indexRoute = $firstSegment.'.index';

        if (Route::has($indexRoute)) {
            try {
                $route = Route::getRoutes()->getByName($indexRoute);
                $requiredParameters = collect($route?->parameterNames() ?? [])->filter(fn ($p): bool => ! Str::endsWith($p, '?'));

                if ($requiredParameters->isEmpty()) {
                    $breadcrumb .= '<a href="'.route($indexRoute).'" class="hover:underline hover:text-cor-padrao dark:hover:text-cor-claro">'.trans('rotas.'.$firstSegment).'</a>';
                } else {
                    $breadcrumb .= '<span>'.trans('rotas.'.$firstSegment).'</span>';
                }
            } catch (Throwable) {
                $breadcrumb .= '<span>'.trans('rotas.'.$firstSegment).'</span>';
            }
        } else {
            $breadcrumb .= '<span>'.trans('rotas.'.$firstSegment).'</span>';
        }

        if (count($segments) === 2 && $segments[1] === 'index') {
            return $breadcrumb.'</nav>';
        }

        for ($i = 1; $i < count($segments); $i++) {
            $breadcrumb .= ' / <span>'.trans('rotas.'.$segments[$i]).'</span>';
        }

        return $breadcrumb.'</nav>';

    }
}
