<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\ProductQuestionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Vendor\OrderController as VendorOrderController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Vendor\ReportController as VendorReportController;

/*
|--------------------------------------------------------------------------
| ROUTE Publik (Tamu & Semua User)
|--------------------------------------------------------------------------
*/
Route::get('/', [ProductController::class, 'indexPublic'])->name('home');

// Rute Autentikasi (Login, Register, Logout, dll)
require __DIR__.'/auth.php';

// Etalase Produk (Publik)
Route::get('/products', [ProductController::class, 'indexPublic'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');


/*
|--------------------------------------------------------------------------
| ROUTE Untuk SEMUA User yang Login (Admin, Vendor, User)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/products/{product}/questions', [ProductQuestionController::class, 'store'])->name('questions.store');
});


/*
|--------------------------------------------------------------------------
| ROUTE KHUSUS Admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    Route::resource('categories', CategoryController::class);
    Route::get('/shops', [ShopController::class, 'index'])->name('shops.index');
    Route::put('/shops/{shop}', [ShopController::class, 'update'])->name('shops.update');
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/download-summary', [AdminOrderController::class, 'downloadSummary'])->name('orders.downloadSummary');
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
});


/*
|--------------------------------------------------------------------------
| ROUTE KHUSUS Vendor
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:vendor'])->prefix('vendor')->name('vendor.')->group(function () {
    
    Route::resource('products', ProductController::class);
    Route::get('/orders', [VendorOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/download-summary', [VendorOrderController::class, 'downloadSummary'])->name('orders.downloadSummary');
    Route::put('/orders/{order}', [VendorOrderController::class, 'update'])->name('orders.update');
    Route::post('/questions/{question}/reply', [ProductQuestionController::class, 'reply'])->name('questions.reply');
    Route::get('/reports', [VendorReportController::class, 'index'])->name('reports.index');
});


/*
|--------------------------------------------------------------------------
| ROUTE KHUSUS User (Pelanggan)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:user'])->group(function () {
    
    Route::get('/shops/create', [ShopController::class, 'create'])->name('shops.create');
    Route::post('/shops', [ShopController::class, 'store'])->name('shops.store');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'store'])->name('cart.store');
    Route::delete('/cart/delete/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');
    
    Route::get('/checkout', [OrderController::class, 'create'])->name('checkout.create');
    Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.store');
    
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{order}/resend-invoice', [OrderController::class, 'resendInvoice'])->name('orders.resendInvoice');
    Route::post('/orders/{order}/complete', [OrderController::class, 'complete'])->name('orders.complete');

    Route::get('/orders/{order}/pay', [OrderController::class, 'showPayment'])->name('orders.pay');
    Route::post('/orders/{order}/pay', [OrderController::class, 'processPayment'])->name('orders.processPayment');

    Route::post('/products/{product}/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
});