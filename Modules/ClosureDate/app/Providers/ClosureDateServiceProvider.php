<?php

namespace Modules\ClosureDate\App\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\ClosureDate\Services\ClosureDateApiInterface;
use Modules\ClosureDate\Services\Implementations\ClosureDateApiService;

class ClosureDateServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ClosureDateApiInterface::class, ClosureDateApiService::class);
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
