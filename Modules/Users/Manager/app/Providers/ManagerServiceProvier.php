<?php

namespace Modules\Users\Manager\App\Providers;

use Illuminate\Support\ServiceProvider;

class ManagerServiceProvier extends ServiceProvider
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
