<?php

use Illuminate\Support\Facades\Route;
use Modules\Users\Guest\App\Http\Controllers\GuestApiController;

Route::apiResource('guests',GuestApiController::class);
Route::post('guests/{guest}/approve', [GuestApiController::class, 'approve']);
