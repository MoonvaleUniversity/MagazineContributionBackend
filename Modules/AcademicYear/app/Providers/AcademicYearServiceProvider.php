<?php

namespace Modules\AcademicYear\App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\AcademicYear\Services\AcademicYearApiServiceInterface;
use Modules\AcademicYear\Services\Implementations\AcademicYearApiService;

class AcademicYearServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(AcademicYearApiServiceInterface::class, AcademicYearApiService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        Route::prefix('api/v1')
            ->middleware(['api', 'auth:sanctum']) // Apply any middleware if needed
            ->group(function () {
                require __DIR__ . '/../../routes/api_v1.0.php';
            });
    }
}
