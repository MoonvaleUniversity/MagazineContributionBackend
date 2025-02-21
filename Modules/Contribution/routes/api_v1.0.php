<?php

use Illuminate\Support\Facades\Route;
use Modules\Contribution\App\Http\Controllers\ContributionApiController;

Route::get('/get_file', [ContributionApiController::class, 'index']);
Route::post('/upload_file/{id}', [ContributionApiController::class, 'store']);
