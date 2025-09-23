<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        foreach (glob(app_path('Helpers/*.php')) as $filename) {
            require_once $filename;
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Define a global gate for Super Admin role
        Gate::before(function ($user, $ability): ?true {
            return $user->hasRole('Super Admin') ? true : null;
        });

        $router = $this->app->make(Router::class);
        $router->pushMiddlewareToGroup('web', \App\Http\Middleware\SetLocale::class);

    }
}
