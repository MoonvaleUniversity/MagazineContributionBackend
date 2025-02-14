<?php

namespace Modules\Faculty\App\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Faculty\Services\FacultyApiInterface;
use Modules\Faculty\Services\Implementations\FacultyApiService;

class FacultyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(FacultyApiInterface::class,FacultyApiService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../../routes/api_v1.0.php');
    }
}
