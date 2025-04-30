<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

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
        //
        Inertia::share([
            'auth.user' => fn () => Auth::check() ? [
                'nom' => Auth::user()->nom,
                'prenom' => Auth::user()->prenom,
                'role' => Auth::user()->role,
                'email' => Auth::user()->email,
            ] : null,
        ]);
    }
}
