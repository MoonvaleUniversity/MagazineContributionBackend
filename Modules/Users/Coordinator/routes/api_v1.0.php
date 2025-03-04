<?php

use Illuminate\Support\Facades\Route;
use Modules\Users\Coordinator\App\Http\Controllers\CoordinatorApiController;

Route::apiResource('coordinators', CoordinatorApiController::class);
