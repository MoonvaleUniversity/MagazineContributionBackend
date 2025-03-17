<?php

use Illuminate\Support\Facades\Route;
use Modules\Users\Student\App\Http\Controllers\StudentApiController;

route::apiResource('students',StudentApiController::class);
