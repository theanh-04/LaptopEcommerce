<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LaptopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;

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

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::redirect('/', '/admin/dashboard');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/employees', [AdminController::class, 'employees'])->name('employees');
    Route::get('/employees/create', [AdminController::class, 'employeesCreate'])->name('employees.create');
    Route::post('/employees', [AdminController::class, 'employeesStore'])->name('employees.store');
    Route::get('/employees/{id}/edit', [AdminController::class, 'employeesEdit'])->name('employees.edit');
    Route::put('/employees/{id}', [AdminController::class, 'employeesUpdate'])->name('employees.update');
    Route::delete('/employees/{id}', [AdminController::class, 'employeesDelete'])->name('employees.delete');
    Route::get('/employees/search', [AdminController::class, 'employeesSearch'])->name('employees.search');
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::get('/orders/{id}', [AdminController::class, 'getOrderDetail'])->name('orders.detail');
    Route::post('/orders/{id}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.updateStatus');
    Route::post('/orders/{id}/assign', [AdminController::class, 'assignEmployee'])->name('orders.assign');
    Route::get('/orders/{id}/invoice', [AdminController::class, 'printInvoice'])->name('orders.invoice');
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
    Route::post('/pos/apply-promo', [AdminController::class, 'applyPromoCode'])->name('pos.applyPromo');
    
    // Brand Management
    Route::get('/brands', [AdminController::class, 'brandsIndex'])->name('brands.index');
    Route::get('/brands/create', [AdminController::class, 'brandsCreate'])->name('brands.create');
    Route::post('/brands', [AdminController::class, 'brandsStore'])->name('brands.store');
    Route::get('/brands/{id}/edit', [AdminController::class, 'brandsEdit'])->name('brands.edit');
    Route::put('/brands/{id}', [AdminController::class, 'brandsUpdate'])->name('brands.update');
    Route::delete('/brands/{id}', [AdminController::class, 'brandsDelete'])->name('brands.delete');
    
    // Promotions Management
    Route::get('/promotions', [AdminController::class, 'promotions'])->name('promotions');
    Route::get('/promotions/create', [AdminController::class, 'promotionsCreate'])->name('promotions.create');
    Route::post('/promotions', [AdminController::class, 'promotionsStore'])->name('promotions.store');
    Route::get('/promotions/{id}/edit', [AdminController::class, 'promotionsEdit'])->name('promotions.edit');
    Route::put('/promotions/{id}', [AdminController::class, 'promotionsUpdate'])->name('promotions.update');
    Route::delete('/promotions/{id}', [AdminController::class, 'promotionsDelete'])->name('promotions.delete');
    Route::post('/promotions/{id}/toggle', [AdminController::class, 'promotionsToggle'])->name('promotions.toggle');
    
    // Customers Management
    Route::get('/customers', [AdminController::class, 'customers'])->name('customers');
    Route::get('/customers/create', [AdminController::class, 'customersCreate'])->name('customers.create');
    Route::post('/customers', [AdminController::class, 'customersStore'])->name('customers.store');
    Route::get('/customers/{id}/edit', [AdminController::class, 'customersEdit'])->name('customers.edit');
    Route::put('/customers/{id}', [AdminController::class, 'customersUpdate'])->name('customers.update');
    Route::delete('/customers/{id}', [AdminController::class, 'customersDelete'])->name('customers.delete');
    Route::get('/customers/search', [AdminController::class, 'customersSearch'])->name('customers.search');
    Route::get('/customers/{id}/detail', [AdminController::class, 'customersDetail'])->name('customers.detail');
    
    // Reports
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
});
