<?php

use Illuminate\Support\Facades\Route;
use Modules\Contribution\App\Http\Controllers\ContributionApiController;

Route::apiResource('contributions', ContributionApiController::class)->only('index','store');
Route::get('/emailAuto', [ContributionApiController::class,'emailAuto']);
