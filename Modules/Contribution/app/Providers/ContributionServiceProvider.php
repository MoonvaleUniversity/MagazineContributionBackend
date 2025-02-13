<?php

namespace Modules\Contribution\App\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Contribution\Services\ContributionApiInterface;
use Modules\Contribution\Services\Implementations\ContributionApiService;

class ContributionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ContributionApiInterface::class, ContributionApiService::class);
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
