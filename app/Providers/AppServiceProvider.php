<?php

namespace App\Providers;

use App\Services\Socket\SocketService;
use App\Services\Socket\SocketServiceImplement;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SocketService::class, function ($app) {
            return new SocketServiceImplement();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
