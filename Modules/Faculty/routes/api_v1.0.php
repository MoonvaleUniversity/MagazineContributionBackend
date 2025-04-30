<?php

use Illuminate\Support\Facades\Route;
use Modules\Faculty\App\Http\Controllers\FacultyApiController;

Route::get('faculties', [FacultyApiController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('faculties', [FacultyApiController::class, 'store']);
    Route::get('faculties/{faculty}', [FacultyApiController::class, 'show']);
    Route::put('faculties/{faculty}', [FacultyApiController::class, 'update']);
    Route::delete('faculties/{faculty}', [FacultyApiController::class, 'destroy']);
});
