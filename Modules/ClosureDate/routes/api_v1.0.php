<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\ClosureDate\App\Http\Controllers\ClosureDateApiController;

Route::get('/get_closure', [ClosureDateApiController::class, 'index']);
Route::get('/show_closure/{id}', [ClosureDateApiController::class, 'show']);
Route::post('/post_closure', [ClosureDateApiController::class, 'store']);
Route::put('/update_closure/{id}', [ClosureDateApiController::class, 'update']);
Route::delete('/delete_closure/{id}', [ClosureDateApiController::class, 'destroy']);
Route::post('/lock_closure/{id}', [ClosureDateApiController::class, 'lock']);
