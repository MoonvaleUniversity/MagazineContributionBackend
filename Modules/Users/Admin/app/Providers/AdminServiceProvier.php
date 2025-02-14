<?php

namespace Modules\Users\Admin\App\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Users\Admin\Services\AdminApiInterface;
use Modules\Users\Admin\Services\Implementations\AdminApiService;

class AdminServiceProvier extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(AdminApiInterface::class, AdminApiService::class);
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
