<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Authentication\App\Http\Controllers\AuthenticationController;

Route::get('/users',[AuthenticationController::class, 'index']);
Route::post('/login',[AuthenticationController::class, 'login']);
Route::post('/register',[AuthenticationController::class, 'register']);

Route::get('/email_verifying/{id}', [AuthenticationController::class,'verifyEmail']);
Route::post('/email_verification_sending/{id}', [AuthenticationController::class,'verifyPost']);
Route::get('/confirmed_email_verification/{id}', [AuthenticationController::class, 'verificationPage'])->name('verificationPage');
Route::post('/confirmed_email_verification/{id}', [AuthenticationController::class, 'verificationPost']);

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthenticationController::class, 'logout']);
});
