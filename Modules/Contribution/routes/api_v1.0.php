<?php

use Illuminate\Support\Facades\Route;
use Modules\Contribution\App\Http\Controllers\ContributionApiController;

Route::apiResource('contributions', ContributionApiController::class)->middleware('auth');
Route::get('/emailAuto', [ContributionApiController::class, 'emailAuto']);
Route::post('/published/{id}', [ContributionApiController::class, 'publish']);
Route::post('/download/{id}', [ContributionApiController::class, 'downloadZipFile']);
Route::post('/comment/{contribution}', [ContributionApiController::class, 'comment'])->middleware('auth');
Route::post('/delete-comment/{contribution}', [ContributionApiController::class, 'deleteComment'])->middleware('auth');
Route::post('/get-comment/{contribution}', [ContributionApiController::class, 'getComment'])->middleware('auth');
