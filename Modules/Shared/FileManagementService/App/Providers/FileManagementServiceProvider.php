<?php

namespace Modules\Shared\FileManagementService\App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\Shared\FileManagementService\Services\FileManagementApiServiceInterface;
use Modules\Shared\FileManagementService\Services\Implementations\FileManagementApiService;

class FileManagementServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(FileManagementApiServiceInterface::class, FileManagementApiService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        Route::prefix('api/v1')
            ->middleware('api') // Apply any middleware if needed
            ->group(function () {
                require __DIR__ . '/../../routes/api_v1.0.php';
            });
    }
}
