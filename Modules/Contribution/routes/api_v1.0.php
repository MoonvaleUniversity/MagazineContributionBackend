<?php

use Illuminate\Support\Facades\Route;
use Modules\Contribution\App\Http\Controllers\ContributionApiController;


Route::post('/upload_file/{id}', [ContributionApiController::class, 'store']);
