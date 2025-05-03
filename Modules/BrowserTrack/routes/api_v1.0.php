<?php

use Illuminate\Support\Facades\Route;
use Modules\BrowserTrack\App\Http\Controllers\BrowserApiController;

// Route::apiResource('page-views', PageViewApiController::class);
Route::apiResource('/browser_tracks', BrowserApiController::class);
