<?php

namespace Modules\Article\App\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Article\Services\ArticleApiInterface;
use Modules\Article\Services\Implementations\ArticleApiService;

class ArticleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ArticleApiInterface::class, ArticleApiService::class);
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
