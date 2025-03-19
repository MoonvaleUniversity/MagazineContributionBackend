<?php

use Illuminate\Support\Facades\Route;
use Modules\Users\Guest\App\Http\Controllers\GuestApiController;

route::apiResource('guests',GuestApiController::class);
