<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if(!$this->app->isLocal()) {
            $this->app['url']->forceScheme('https');
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // Khai báo model tùy chỉnh cho PersonalAccessToken
        Sanctum::$personalAccessTokenModel = \App\Models\System\PersonalAccessToken::class;
    }
}
