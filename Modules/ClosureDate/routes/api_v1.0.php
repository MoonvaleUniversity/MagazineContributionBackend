<?php

use Modules\ClosureDate\App\Http\Controllers\ClosureDateApiController;
use Illuminate\Support\Facades\Route;

Route::post('/deadline/{id}', [ClosureDateApiController::class, 'store']);
