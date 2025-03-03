<?php

use Illuminate\Support\Facades\Route;
use Modules\ClosureDate\App\Http\Controllers\ClosureDateApiController;

Route::apiResource('closure-dates', ClosureDateApiController::class);
