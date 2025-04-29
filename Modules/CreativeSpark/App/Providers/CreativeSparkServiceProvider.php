<?php

namespace Modules\CreativeSpark\App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\CreativeSpark\Services\CreativeSparkApiServiceInterface;
use Modules\CreativeSpark\Services\Implementations\CreativeSparkApiService;

class CreativeSparkServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(CreativeSparkApiServiceInterface::class, CreativeSparkApiService::class);
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
        $this->mergeConfigFrom(__DIR__ . "/../../config/config.php", 'creative-spark');
    }
}
