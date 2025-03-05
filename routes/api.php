<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TshirtController;

Route::controller(TshirtController::class)->group(function () {
    Route::get("/tshirt", "index");
    Route::post("/tshirt", "store");
    Route::get("/tshirt/{id}", "show");
    Route::post("/tshirt/{id}", "store");
    Route::delete("/tshirt/{id}", "destroy");
});
