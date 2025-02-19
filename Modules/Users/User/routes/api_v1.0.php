<?php

use Modules\Users\User\App\Http\Controllers\UserApiController;
use Illuminate\Support\Facades\Route;

Route::apiResource('/users',UserApiController::class);
