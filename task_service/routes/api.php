<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::apiResource('users.tasks', TaskController::class);

Route::prefix('users/{user}/tasks/{task}')
    ->controller(TaskController::class)
    ->group(function () {
        Route::prefix('status')->group(function () {
            Route::patch('next', 'toNextStatus')->name('users.tasks.update.status.next');
            Route::patch('back', 'toBackStatus')->name('users.tasks.update.status.back');
        });
    });
