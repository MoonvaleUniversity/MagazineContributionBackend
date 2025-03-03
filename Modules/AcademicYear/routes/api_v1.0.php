<?php

use Illuminate\Support\Facades\Route;
use Modules\AcademicYear\App\Http\Controllers\AcademicYearApiController;

Route::apiResource('academic-years', AcademicYearApiController::class);
