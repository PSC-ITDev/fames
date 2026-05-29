<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
    }
}
