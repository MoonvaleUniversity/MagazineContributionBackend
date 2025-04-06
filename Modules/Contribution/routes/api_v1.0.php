<?php

use Illuminate\Support\Facades\Route;
use Modules\Contribution\App\Http\Controllers\ContributionApiController;

Route::apiResource('contributions', ContributionApiController::class)->middleware('auth');
Route::prefix('/contributions')->middleware('auth')->controller(ContributionApiController::class)->group(function () {
    Route::get('/emailAuto', [ContributionApiController::class, 'emailAuto']);
    Route::post('/{contribution}/publish',  'publish');
    Route::post('/download/{id}', [ContributionApiController::class, 'downloadZipFile']);
});
