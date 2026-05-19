<?php

use App\Enums\OrderStatus;
use App\Http\Controllers\Admin\AddressController;
use App\Http\Controllers\Admin\CatalogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\Shop\CheckoutController;
use App\Http\Controllers\Shop\LoginController as ShopLoginController;
use App\Http\Controllers\Shop\OrderController as ShopOrderController;
use App\Http\Controllers\Shop\ProductController as ShopProductController;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('customer.login'));

// Admin auth
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminLoginController::class, 'login']);
    Route::post('logout', [AdminLoginController::class, 'logout'])->name('logout');

    Route::middleware(['auth:admin', 'role:admin'])->group(function () {
        Route::get('dashboard', function () {
            return view('admin.dashboard', [
                'totalProducts' => Product::active()->count(),
                'totalCustomers' => Customer::count(),
                'pendingOrders' => Order::where('status', OrderStatus::Pending)->count(),
                'totalOrders' => Order::count(),
            ]);
        })->name('dashboard');

        // Catálogos
        Route::resource('catalogs', CatalogController::class);
        Route::post('catalogs/{catalog}/toggle', [CatalogController::class, 'toggleActive'])->name('catalogs.toggle');

        // Categorias
        Route::resource('categories', CategoryController::class);

        // Produtos
        Route::resource('products', ProductController::class);
        Route::post('products/{product}/toggle', [ProductController::class, 'toggleActive'])->name('products.toggle');

        // Clientes + Moradas aninhadas
        Route::resource('customers', CustomerController::class);
        Route::post('customers/{customer}/toggle-blocked', [CustomerController::class, 'toggleBlocked'])->name('customers.toggle-blocked');
        Route::resource('customers.addresses', AddressController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);

        // Encomendas
        Route::resource('orders', OrderController::class)->only(['index', 'show']);
        Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    });
});

// Shop auth
Route::prefix('shop')->name('shop.')->group(function () {
    Route::get('login', [ShopLoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [ShopLoginController::class, 'login']);
    Route::post('logout', [ShopLoginController::class, 'logout'])->name('logout');

    Route::middleware(['auth', 'role:customer', 'customer.active'])->group(function () {
        // Catálogo
        Route::resource('products', ShopProductController::class)->only(['index', 'show']);

        // Carrinho
        Route::prefix('cart')->name('cart.')->group(function () {
            Route::get('/', [CartController::class, 'index'])->name('index');
            Route::post('add/{product}', [CartController::class, 'add'])->name('add');
            Route::patch('update/{productId}', [CartController::class, 'update'])->name('update');
            Route::delete('remove/{productId}', [CartController::class, 'remove'])->name('remove');
        });

        // Checkout
        Route::get('checkout', [CheckoutController::class, 'show'])->name('checkout.show');
        Route::post('checkout', [CheckoutController::class, 'store'])->name('checkout.store');

        // Encomendas
        Route::resource('orders', ShopOrderController::class)->only(['index', 'show']);
    });
});

// Alias para o guard padrão do Laravel apontar para o login de cliente
Route::get('login', fn () => redirect()->route('shop.login'))->name('customer.login');
