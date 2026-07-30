<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\User;
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
        // Now $siteVersion is available in every Blade file automatically
        View::share('pageTitle', '');
        View::share('menu', 0);
        \View::share('sigPath', asset('storage/signatures/RUIZ01.png'));
        \View::share('dateFmt', 'M d, Y'); // [cite: 38]

        Gate::define('superadmin', function (User $user) {
            return strtolower($user->role->name) == 'superadmin';
        });

        Gate::define('auditor', function (User $user) {
            return strtolower($user->role->name) == 'auditor';
        });
    }
}
