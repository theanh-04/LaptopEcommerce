<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LaptopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/laptops', [LaptopController::class, 'index'])->name('laptops.index');
Route::get('/laptops/{slug}', [LaptopController::class, 'show'])->name('laptops.show');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

Route::get('/checkout', [OrderController::class, 'checkout'])->name('order.checkout');
Route::post('/order', [OrderController::class, 'store'])->name('order.store');
Route::get('/order/success', [OrderController::class, 'success'])->name('order.success');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/employees', [AdminController::class, 'employees'])->name('employees');
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::get('/orders/{id}', [AdminController::class, 'getOrderDetail'])->name('orders.detail');
    Route::post('/orders/{id}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.updateStatus');
    Route::get('/inventory', [AdminController::class, 'inventory'])->name('inventory');
    Route::get('/inventory/create', [AdminController::class, 'createProduct'])->name('inventory.create');
    Route::post('/inventory', [AdminController::class, 'storeProduct'])->name('inventory.store');
    Route::get('/inventory/{id}/edit', [AdminController::class, 'editProduct'])->name('inventory.edit');
    Route::put('/inventory/{id}', [AdminController::class, 'updateProduct'])->name('inventory.update');
    Route::delete('/inventory/{id}', [AdminController::class, 'deleteProduct'])->name('inventory.delete');
    Route::post('/inventory/{id}/stock', [AdminController::class, 'updateStock'])->name('inventory.updateStock');
    Route::get('/inventory/filter', [AdminController::class, 'filterInventory'])->name('inventory.filter');
    Route::get('/pos', [AdminController::class, 'pos'])->name('pos');
    Route::post('/pos/payment', [AdminController::class, 'processPayment'])->name('pos.payment');
    
    // Brand Management
    Route::get('/brands', [AdminController::class, 'brandsIndex'])->name('brands.index');
    Route::get('/brands/create', [AdminController::class, 'brandsCreate'])->name('brands.create');
    Route::post('/brands', [AdminController::class, 'brandsStore'])->name('brands.store');
    Route::get('/brands/{id}/edit', [AdminController::class, 'brandsEdit'])->name('brands.edit');
    Route::put('/brands/{id}', [AdminController::class, 'brandsUpdate'])->name('brands.update');
    Route::delete('/brands/{id}', [AdminController::class, 'brandsDelete'])->name('brands.delete');
});
