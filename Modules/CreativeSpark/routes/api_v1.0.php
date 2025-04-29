<?php

use Illuminate\Support\Facades\Route;
use Modules\CreativeSpark\App\Http\Controllers\CreativeSparkApiController;

Route::resource('creative-sparks', CreativeSparkApiController::class);
