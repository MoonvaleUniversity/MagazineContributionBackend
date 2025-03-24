<?php

use Illuminate\Support\Facades\Route;
use Modules\Users\Manager\App\Http\Controllers\ManagerApiController;

route::apiResource('managers',ManagerApiController::class);
