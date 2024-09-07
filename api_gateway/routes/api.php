<?php

use App\Http\Controllers\AuthController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user/{user}', function (User $user) {
    return $user;
})->middleware('auth:api');

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
