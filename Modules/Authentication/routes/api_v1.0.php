<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Authentication\App\Http\Controllers\AuthenticationApiController;

Route::post('/login',[AuthenticationApiController::class, 'login']);
Route::post('/register',[AuthenticationApiController::class, 'register']);

Route::get('/email_verifying/{id}', [AuthenticationApiController::class,'verifyEmail']);
Route::post('/email_verification_sending/{id}', [AuthenticationApiController::class,'verifyPost']);

Route::get('/login-user', [AuthenticationApiController::class, 'loginUser'])->middleware('auth:sanctum');
