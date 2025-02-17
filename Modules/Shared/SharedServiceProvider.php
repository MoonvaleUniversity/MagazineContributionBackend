<?php

namespace Modules\Shared;

use Illuminate\Support\ServiceProvider;
use Modules\Shared\FileUpload\FileUploadServiceInterface;
use Modules\Shared\FileUpload\FileUploadService;

class SharedServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(FileUploadServiceInterface::class, FileUploadService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
