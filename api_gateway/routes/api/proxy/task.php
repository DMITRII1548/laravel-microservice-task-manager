<?php

use App\Http\Controllers\Proxy\TaskController;
use Illuminate\Support\Facades\Route;

Route::prefix('users')
    ->middleware('auth:api')
    ->group(function () {
        Route::resource('tasks', TaskController::class);

        Route::prefix('tasks')->group(function () {
            Route::patch('{task}/status/next', [TaskController::class, 'updateToNextStatus']);
            Route::patch('{task}/status/back', [TaskController::class, 'updateToBackStatus']);
        });
    });
