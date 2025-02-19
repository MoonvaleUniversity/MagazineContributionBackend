<?php

use Modules\Users\User\App\Http\Controllers\UserApiController;
use Illuminate\Support\Facades\Route;

Route::get('/users',[UserApiController::class, 'index']);
