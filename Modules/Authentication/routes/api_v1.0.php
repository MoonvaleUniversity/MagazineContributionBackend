<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Authentication\App\Http\Controllers\AuthenticationController;

Route::post('/api/login',[AuthenticationController::class, 'login']);
Route::post('/api/register',[AuthenticationController::class, 'register']);

Route::get('/api/email_verifying/{id}', [AuthenticationController::class,'verifyEmail']);
Route::post('/api/email_verification_sending/{id}', [AuthenticationController::class,'verifyPost']);
Route::get('/api/confirmed_email_verification/{id}', [AuthenticationController::class, 'verificationPage'])->name('verificationPage');
Route::post('/api/confirmed_email_verification/{id}', [AuthenticationController::class, 'verificationPost']);


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
