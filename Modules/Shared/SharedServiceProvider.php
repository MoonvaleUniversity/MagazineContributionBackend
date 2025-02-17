<?php

namespace Modules\Shared;

use Modules\Shared\Email\EmailService;
use Illuminate\Support\ServiceProvider;
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
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
