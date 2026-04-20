<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LaptopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\PosController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\ReportController;

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/laptops', [LaptopController::class, 'index'])->name('laptops.index');
Route::get('/laptops/{slug}', [LaptopController::class, 'show'])->name('laptops.show');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

Route::get('/checkout', [OrderController::class, 'checkout'])->name('order.checkout');
Route::post('/order', [OrderController::class, 'store'])->name('order.store');
Route::get('/order/success', [OrderController::class, 'success'])->name('order.success');
Route::post('/order/check-promo', [OrderController::class, 'checkPromo'])->name('order.checkPromo');
Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('order.myOrders');

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [\App\Http\Controllers\ProfileController::class, 'changePasswordForm'])->name('profile.changePassword');
    Route::post('/profile/change-password', [\App\Http\Controllers\ProfileController::class, 'changePassword'])->name('profile.changePassword.post');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::redirect('/', '/admin/dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Orders Management
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders');
    Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.detail');
    Route::post('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::post('/orders/{id}/assign', [AdminOrderController::class, 'assignEmployee'])->name('orders.assign');
    Route::get('/orders/{id}/invoice', [AdminOrderController::class, 'printInvoice'])->name('orders.invoice');
    
    // Inventory Management
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory');
    Route::get('/inventory/create', [InventoryController::class, 'create'])->name('inventory.create');
    Route::post('/inventory', [InventoryController::class, 'store'])->name('inventory.store');
    Route::get('/inventory/{id}/edit', [InventoryController::class, 'edit'])->name('inventory.edit');
    Route::put('/inventory/{id}', [InventoryController::class, 'update'])->name('inventory.update');
    Route::delete('/inventory/{id}', [InventoryController::class, 'destroy'])->name('inventory.delete');
    Route::post('/inventory/{id}/stock', [InventoryController::class, 'updateStock'])->name('inventory.updateStock');
    Route::get('/inventory/filter', [InventoryController::class, 'filter'])->name('inventory.filter');
    
    // POS
    Route::get('/pos', [PosController::class, 'index'])->name('pos');
    Route::post('/pos/payment', [PosController::class, 'processPayment'])->name('pos.payment');
    Route::post('/pos/apply-promo', [PosController::class, 'applyPromoCode'])->name('pos.applyPromo');
    
    // Brand Management
    Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');
    Route::get('/brands/create', [BrandController::class, 'create'])->name('brands.create');
    Route::post('/brands', [BrandController::class, 'store'])->name('brands.store');
    Route::get('/brands/{id}/edit', [BrandController::class, 'edit'])->name('brands.edit');
    Route::put('/brands/{id}', [BrandController::class, 'update'])->name('brands.update');
    Route::delete('/brands/{id}', [BrandController::class, 'destroy'])->name('brands.delete');
    
    // Promotions Management
    Route::get('/promotions', [PromotionController::class, 'index'])->name('promotions');
    Route::get('/promotions/create', [PromotionController::class, 'create'])->name('promotions.create');
    Route::post('/promotions', [PromotionController::class, 'store'])->name('promotions.store');
    Route::get('/promotions/{id}/edit', [PromotionController::class, 'edit'])->name('promotions.edit');
    Route::put('/promotions/{id}', [PromotionController::class, 'update'])->name('promotions.update');
    Route::delete('/promotions/{id}', [PromotionController::class, 'destroy'])->name('promotions.delete');
    Route::post('/promotions/{id}/toggle', [PromotionController::class, 'toggle'])->name('promotions.toggle');
    
    // Customers Management
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers');
    Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/{id}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{id}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{id}', [CustomerController::class, 'destroy'])->name('customers.delete');
    Route::get('/customers/search', [CustomerController::class, 'search'])->name('customers.search');
    Route::get('/customers/{id}/detail', [CustomerController::class, 'show'])->name('customers.detail');
    
    // Employees Management
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees');
    Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
    Route::get('/employees/{id}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('/employees/{id}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('/employees/{id}', [EmployeeController::class, 'destroy'])->name('employees.delete');
    Route::get('/employees/search', [EmployeeController::class, 'search'])->name('employees.search');
    
    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
});
