<?php

namespace Modules\Users\Guest\App\Providers;

use Illuminate\Support\ServiceProvider;

class GuestServiceProvier extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/api_v1.0.php');
    }
}
