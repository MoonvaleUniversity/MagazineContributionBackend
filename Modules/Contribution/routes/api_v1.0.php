<?php

use Illuminate\Support\Facades\Route;
use Modules\Contribution\App\Http\Controllers\ContributionApiController;

Route::apiResource('contributions', ContributionApiController::class)->middleware('auth');
Route::get('/emailAuto', [ContributionApiController::class, 'emailAuto']);
Route::post('/published/{id}', [ContributionApiController::class, 'publish']);
Route::post('/download/{id}', [ContributionApiController::class, 'downloadZipFile']);
Route::post('/contributions/{contribution}/comment', [ContributionApiController::class, 'comment'])->middleware('auth');
Route::post('/contributions/{contribution}/delete-comment', [ContributionApiController::class, 'deleteComment'])->middleware('auth');
Route::post('contributions/{contribution}/get-comment', [ContributionApiController::class, 'getComment'])->middleware('auth');
Route::post('/contributions/{contribution}/vote', [ContributionApiController::class, 'voteContribution'])->middleware('auth');
Route::post('/contributions/{contribution}/save', [ContributionApiController::class, 'saveContribution'])->middleware('auth');
Route::post('/contributions/{contribution}/review', [ContributionApiController::class, 'reviewContribution'])->middleware('auth');
