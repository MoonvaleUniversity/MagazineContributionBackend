<?php

use Illuminate\Support\Facades\Route;
use Modules\Faculty\App\Http\Controllers\FacultyApiController;

Route::apiResource('faculties', FacultyApiController::class);
