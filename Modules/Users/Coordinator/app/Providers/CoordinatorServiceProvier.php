<?php

namespace Modules\Users\Coordinator\App\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Users\Coordinator\Services\CoordinatorApiInterface;
use Modules\Users\Coordinator\Services\Implementations\CoordinatorApiService;

class CoordinatorServiceProvier extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(CoordinatorApiInterface::class, CoordinatorApiService::class);
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
