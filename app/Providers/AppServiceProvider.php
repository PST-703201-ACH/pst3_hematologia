<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('es-admin', function ($user) {
            return $user->id_rol === 1;
        });

        Gate::define('es-medico', function ($user) {
            return $user->id_rol === 2;
        });
    }
}
