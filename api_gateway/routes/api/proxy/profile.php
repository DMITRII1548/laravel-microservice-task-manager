<?php

use App\Http\Controllers\Proxy\ProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('users')
    ->middleware('auth:api')
    ->group(function () {
        Route::apiSingleton('profile', ProfileController::class)
            ->creatable()
            ->destroyable();
    });
