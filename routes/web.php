<?php

use App\Http\Controllers\Admin\LoginController as AdminLoginController;
use App\Http\Controllers\Shop\LoginController as ShopLoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('customer.login'));

// Admin auth
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminLoginController::class, 'login']);
    Route::post('logout', [AdminLoginController::class, 'logout'])->name('logout');

    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::get('dashboard', fn () => view('admin.dashboard'))->name('dashboard');
    });
});

// Shop auth
Route::prefix('shop')->name('shop.')->group(function () {
    Route::get('login', [ShopLoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [ShopLoginController::class, 'login']);
    Route::post('logout', [ShopLoginController::class, 'logout'])->name('logout');

    Route::middleware(['auth', 'role:customer', 'customer.active'])->group(function () {
        Route::get('products', fn () => view('shop.products'))->name('products.index');
    });
});

// Alias para o guard padrão do Laravel apontar para o login de cliente
Route::get('login', fn () => redirect()->route('shop.login'))->name('customer.login');
