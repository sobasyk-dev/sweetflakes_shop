<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\AdminController;


/*
|--------------------------------------------------------------------------
| Public & Guest Routes
|--------------------------------------------------------------------------
*/

// --- Public Pages ---
Route::get('/', [Controller::class, 'index'])->name('index');

// 1. Customer Signup
// Route::get('/customer/signup', [Controller::class, 'showCustomerSignup'])->name('customer.cs_signup');
// We point the POST to the same URL as the GET for consistency
// Route::post('/customer/signup', [Controller::class, 'customerSignup'])->name('customer.customerSignup');

// 2. Secret Admin Signup
Route::get('/admin/signup', [Controller::class, 'showAdminSignup'])->name('admin.ad_signup');
// Pointing this POST to its own URL so it stays hidden
Route::post('/admin/signup', [Controller::class, 'adminSignup'])->name('admin.adminSignup');

// --- Customer Auth ---
// Route::get('/customer/login', [Controller::class, 'showCustomerLogin'])->name('customer.cs_login');
// Route::post('/customer/login', [Controller::class, 'customerLogin'])->name('customer.loginProcess');

// --- Admin Auth ---
Route::get('/admin/login', [Controller::class, 'showAdminLogin'])->name('admin.ad_login');
Route::post('/admin/login', [Controller::class, 'adminLogin'])->name('admin.loginProcess');

// --- CustomerLogout ---
Route::post('/customer/logout', [Controller::class, 'customerLogout'])->name('customer.logout');

// --- Admin Logout ---
Route::post('/admin/logout', [Controller::class, 'adminLogout'])->name('admin.logout');



/*
|--------------------------------------------------------------------------
| Customer Routes (Requires Auth)
|--------------------------------------------------------------------------
*/
// Route::middleware(['auth', 'customer'])->prefix('customer')->group(function () {
    
    Route::get('customer/welcome', [MenuController::class, 'cs_welcome'])->name('customer.cs_welcome');
    Route::get('customer/set-method/{method}', [MenuController::class, 'setOrderMethod'])->name('customer.set_method');
    Route::get('customer/menu', [MenuController::class, 'cs_menu'])->name('customer.cs_menu');
    Route::get('customer/cart', [MenuController::class, 'cs_cart'])->name('customer.cs_cart');
    Route::post('customer/cart/store', [MenuController::class, 'cs_cart_store'])->name('customer.cs_cart_store');
    Route::post('customer/cart/update/{id}', [MenuController::class, 'cs_cart_update'])->name('customer.cs_cart_update');
    Route::post('customer/cart/remove/{id}', [MenuController::class, 'cs_cart_remove'])->name('customer.cs_cart_remove');
    Route::post('customer/prepare', [MenuController::class, 'preparePayment'])->name('customer.cs_payment.prepare');
    Route::get('customer/payment', [MenuController::class, 'cs_payment'])->name('customer.cs_payment');
    Route::post('customer/order/store', [MenuController::class, 'storeOrder'])->name('customer.storeOrder');
    Route::get('customer/order-complete/{order_number}', [MenuController::class, 'cs_order_complete'])
    ->name('customer.cs_order_complete');
    Route::get('customer/order/history', [MenuController::class, 'cs_orders'])->name('customer.cs_orders');
// });

/*
|--------------------------------------------------------------------------
| Admin Routes (Requires Auth & Admin Role)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // Dashboard & Analysis
    Route::get('/dashboard', [AdminController::class, 'ad_dashboard'])->name('admin.ad_dashboard');

    // POS System
    Route::get('/pos', [AdminController::class, 'ad_pos'])->name('admin.ad_pos');
    Route::post('/pos/checkout', [AdminController::class, 'pos_checkout'])->name('admin.ad_pos.checkout');
    Route::get('/orders', [AdminController::class, 'ad_orders'])->name('admin.ad_orders');
    Route::patch('/orders/{id}', [AdminController::class, 'update_order_status'])->name('admin.orders.update');
    
    // Inventory Management
    Route::get('/inventory', [ProductController::class, 'ad_inventory'])->name('admin.ad_inventory');
    Route::get('/inventory/create', [ProductController::class, 'ad_create'])->name('admin.ad_create');
    Route::post('/inventory', [ProductController::class, 'ad_store'])->name('admin.ad_store');
    Route::get('/inventory/{product}/edit', [ProductController::class, 'ad_edit'])->name('admin.ad_edit');
    Route::put('/inventory/{product}/update', [ProductController::class, 'ad_update'])->name('admin.ad_update');
    Route::delete('/inventory/{product}/delete', [ProductController::class, 'ad_delete'])->name('admin.ad_delete');
});