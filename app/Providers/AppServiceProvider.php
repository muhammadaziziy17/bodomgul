<?php

namespace App\Providers;

use App\Support\Locales;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        // `route()` yordamchisi til parametrini o'zi to'ldirishi uchun.
        URL::defaults(['locale' => Locales::default()]);
    }
}
