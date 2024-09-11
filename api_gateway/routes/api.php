<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Proxy\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::post('register', [AuthController::class, 'register'])->name('auth.register');
Route::post('login', [AuthController::class, 'login'])->name('auth.login');

Route::prefix('users')
    ->middleware('auth:api')
    ->group(function () {
        Route::resource('tasks', TaskController::class);

        Route::prefix('tasks')->group(function () {
            Route::patch('{task}/status/next', [TaskController::class, 'updateToNextStatus']);
            Route::patch('{task}/status/back', [TaskController::class, 'updateToBackStatus']);
        });
    });
