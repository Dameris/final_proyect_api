<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TshirtController;
use App\Http\Controllers\JoggerController;

Route::controller(TshirtController::class)->group(function () {
    Route::get("/tshirt", "index");
    Route::post("/tshirt", "store");
    Route::get("/tshirt/{id}", "show");
    Route::post("/tshirt/{id}", "store");
    Route::delete("/tshirt/{id}", "destroy");
});

Route::controller(JoggerController::class)->group(function () {
    Route::get("/jogger", "index");
    Route::post("/jogger", "store");
    Route::get("/jogger/{id}", "show");
    Route::post("/jogger/{id}", "store");
    Route::delete("/jogger/{id}", "destroy");
});