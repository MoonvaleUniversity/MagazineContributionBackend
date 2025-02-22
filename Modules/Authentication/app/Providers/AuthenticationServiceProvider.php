<?php

namespace Modules\Authentication\App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\Authentication\Services\AuthenticationApiServiceInterface;
use Modules\Authentication\Services\Implementations\AuthenticationApiService;

class AuthenticationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(AuthenticationApiServiceInterface::class, AuthenticationApiService::class);
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
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
    }
}
