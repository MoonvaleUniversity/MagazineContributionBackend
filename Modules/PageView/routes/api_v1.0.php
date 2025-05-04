<?php

use Illuminate\Support\Facades\Route;
use Modules\PageView\App\Http\Controllers\PageViewApiController;

// Route::apiResource('page-views', PageViewApiController::class);
Route::apiResource('/page-views', PageViewApiController::class);
    // Route::post('/update-time', [PageViewApiController::class, 'updateTime']);
    // Route::post('/end-session', [PageViewApiController::class, 'endSession']);
    Route::get('/most-visited-pages', [PageViewApiController::class, 'mostVisitedPages']);
    // Route::get('/most-active-users', [PageViewApiController::class, 'mostActiveUsers']);
