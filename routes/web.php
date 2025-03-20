<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TshirtController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FaqsController;
use App\Http\Controllers\ShippingController;

// NO AUTH ROUTES
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/tshirts', [TshirtController::class, 'index'])->name('tshirts.index');
Route::get('/tshirts/{tshirt}', [TshirtController::class, 'show'])->name('tshirts.show');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/signup', [AuthController::class, 'showSignupForm'])->name('signup');
Route::post('/signup', [AuthController::class, 'signup']);

Route::get('/contact', [ContactController::class, 'showContactForm'])->name('contact');
Route::post('/contact', [ContactController::class, 'contact']);

Route::get('/about', [AboutController::class, 'show'])->name('about');

Route::get('/faqs', [FaqsController::class, 'show'])->name('faqs');

Route::get("/shipping", [ShippingController::class, 'show'])->name('shipping');

Route::get('/auth/check', [AuthController::class, 'checkSession']);

Route::middleware(['auth', 'verified',])->group(function () {
    Route::resource('/tshirts', TshirtController::class)->except(['index', 'show']);
    Route::get('/tshirts/create', [TshirtController::class, 'create'])->name('tshirts.create');
    Route::get('/tshirts/{tshirt}/edit', [TshirtController::class, 'edit'])->name('tshirts.edit');
    Route::put('/tshirts/{tshirt}', [TshirtController::class, 'update'])->name('tshirts.update');

    Route::resource('/roles', RoleController::class);

    Route::get('/user-profile', [UserProfileController::class, 'showUserProfile'])->name('profile');
    Route::post('/user-profile', [UserProfileController::class, 'update'])->name('profile.update');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
