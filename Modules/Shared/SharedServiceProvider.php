<?php

namespace Modules\Shared;

use Modules\Shared\Cache\CacheService;
use Modules\Shared\Email\EmailService;
use Illuminate\Support\ServiceProvider;
use Modules\Shared\Cache\CacheServiceInterface;
use Modules\Shared\Email\EmailServiceInterface;
use Modules\Shared\FileUpload\FileUploadService;
use Modules\Shared\FileUpload\FileUploadServiceInterface;


class SharedServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(FileUploadServiceInterface::class, FileUploadService::class);
        $this->app->bind(EmailServiceInterface::class, EmailService::class);
        $this->app->bind('mv_cache', CacheService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
