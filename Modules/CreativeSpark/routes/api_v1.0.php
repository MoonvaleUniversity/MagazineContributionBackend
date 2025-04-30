<?php

use Illuminate\Support\Facades\Route;
use Modules\CreativeSpark\App\Http\Controllers\CreativeSparkApiController;

Route::apiResource('creative-sparks', CreativeSparkApiController::class);
