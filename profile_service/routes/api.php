<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::apiSingleton('users.profile', ProfileController::class)
    ->creatable()
    ->destroyable();
