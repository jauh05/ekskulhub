<?php

namespace App\Providers;

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
        // Di balik proxy HTTPS, Laravel mengira request datang lewat HTTP.
        // Akibatnya URL fetch dan form dibuat http:// dan diblokir browser (mixed content).
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
