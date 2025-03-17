<?php

use Illuminate\Support\Facades\Route;
use Modules\Users\Admin\App\Http\Controllers\AdminApiController;

route::apiResource('admins',AdminApiController::class);
