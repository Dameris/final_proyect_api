<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TshirtController;
use App\Http\Controllers\RoleController;

// NO AUTH ROUTES
Route::get('/', [DashboardController::class, "index"]);

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->group(function () {
    // AUTH ROUTES
    Route::get('/dashboard', [DashboardController::class, "dashboard"])->name('dashboard');
    Route::resource('/tshirts', TshirtController::class);
    Route::resource('/roles', RoleController::class);
});
