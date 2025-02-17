<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/users',[AuthController::class, 'index']);
Route::post('/login',[AuthController::class, 'login']);
Route::post('/register',[AuthController::class, 'register']);

// Verify Session
Route::get('/email_verifying/{id}', [AuthController::class,'verifyEmail']);
Route::post('/email_verification_sending/{id}', [AuthController::class,'verifyPost']);
Route::get('/confirmed_email_verification/{id}', [AuthController::class, 'verificationPage'])->name('verificationPage');
Route::post('/confirmed_email_verification/{id}', [AuthController::class, 'verificationPost']);


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
