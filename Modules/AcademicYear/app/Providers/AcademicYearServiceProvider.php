<?php

namespace Modules\AcademicYear\App\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\AcademicYear\Services\AcademicYearApiInterface;
use Modules\AcademicYear\Services\Implementations\AcademicYearApiService;

class AcademicYearServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(AcademicYearApiInterface::class, AcademicYearApiService::class);
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
