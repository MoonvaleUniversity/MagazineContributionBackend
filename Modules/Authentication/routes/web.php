<?php

use Illuminate\Support\Facades\Route;
use Modules\Authentication\App\Http\Controllers\AuthenticationApiController;

Route::get('/confirmed_email_verification/{id}', [AuthenticationApiController::class, 'verificationPage'])->name('verificationPage');
Route::post('/confirmed_email_verification/{id}', [AuthenticationApiController::class, 'verificationPost']);
