<?php

namespace Modules\Users\Student\App\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Users\Student\Services\Implementations\StudentApiService;
use Modules\Users\Student\Services\StudentApiInterface;

class StudentServiceProvier extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(StudentApiInterface::class, StudentApiService::class);
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
