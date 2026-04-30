<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\WorkOrderController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->middleware('login.limit');

    // Password Reset
    Route::get('forgot-password', [ForgotPasswordController::class, 'show'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'send'])->name('password.email');
    Route::get('reset-password/{token}', [ResetPasswordController::class, 'show'])->name('password.reset');
    Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// Landing page publik (tidak perlu login)
Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::middleware(['auth', 'active'])->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    // Email Verification
    Route::get('email/verify', [EmailVerificationController::class, 'notice'])->name('verification.notice');
    Route::get('email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])->middleware('signed')->name('verification.verify');
    Route::post('email/resend', [EmailVerificationController::class, 'resend'])->middleware('throttle:6,1')->name('verification.send');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/data', [DashboardController::class, 'data'])->name('dashboard.data');

    // Profile
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo.update');
    Route::delete('profile/photo', [ProfileController::class, 'deletePhoto'])->name('profile.photo.delete');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::post('profile/email', [ProfileController::class, 'requestEmailChange'])->name('profile.email.request');
    Route::get('profile/email', [ProfileController::class, 'emailPage'])->name('profile.email');
    Route::post('profile/email/verify', [ProfileController::class, 'verifyEmailOtp'])->name('profile.email.verify');

    // Admin only
    Route::middleware('admin')->group(function () {
        Route::get('admin/users', [RegisterController::class, 'users'])->name('admin.users');
        Route::get('admin/users/create', [RegisterController::class, 'show'])->name('admin.register');
        Route::post('admin/users', [RegisterController::class, 'store'])->name('admin.register.store');
        Route::patch('admin/users/{user}/toggle', [RegisterController::class, 'toggleActive'])->name('admin.users.toggle');
    });

    // Generate API token from web session
    Route::post('api/v1/profile/token', [App\Http\Controllers\Api\ProfileController::class, 'createToken']);

    // Products
    Route::resource('products', ProductController::class);
    Route::delete('products/images/{image}', [ProductController::class, 'destroyImage'])->name('products.images.destroy');
    Route::get('products/{product}/barcode', [ProductController::class, 'barcode'])->name('products.barcode');

    // Stock
    Route::get('stock', [StockController::class, 'index'])->name('stock.index');
    Route::get('stock/mutations', [StockController::class, 'mutations'])->name('stock.mutations');
    Route::post('stock/adjust', [StockController::class, 'adjust'])->name('stock.adjust');

    // Sales
    Route::get('sales/pos', [SaleController::class, 'pos'])->name('sales.pos');
    Route::post('sales/{sale}/return', [SaleController::class, 'returnSale'])->name('sales.return');
    Route::get('sales/{sale}/invoice-pdf', [SaleController::class, 'invoicePdf'])->name('sales.invoice.pdf');
    Route::resource('sales', SaleController::class)->only(['index', 'store', 'show']);

    // Purchase Orders
    Route::post('purchase-orders/{purchaseOrder}/receive', [PurchaseOrderController::class, 'receive'])->name('purchase-orders.receive');
    Route::resource('purchase-orders', PurchaseOrderController::class)->only(['index', 'create', 'store', 'show'])->parameters(['purchase-orders' => 'purchaseOrder']);

    // Work Orders
    Route::patch('work-orders/{workOrder}/status', [WorkOrderController::class, 'updateStatus'])->name('work-orders.status');
    Route::resource('work-orders', WorkOrderController::class)->only(['index', 'create', 'store', 'show'])->parameters(['work-orders' => 'workOrder']);

    // Suppliers
    Route::resource('suppliers', SupplierController::class)->except(['destroy']);

    // Reports — specific sub-routes MUST be declared before the generic 'sales' route
    Route::prefix('reports')->name('reports.')->group(function () {
        // Export routes first to avoid being shadowed by the 'sales' GET route
        Route::get('sales/pdf', [ReportController::class, 'exportSalesPdf'])->name('sales.pdf');
        Route::get('sales/excel', [ReportController::class, 'exportSalesExcel'])->name('sales.excel');
        Route::get('stock/excel', [ReportController::class, 'exportStockExcel'])->name('stock.excel');

        Route::get('sales', [ReportController::class, 'sales'])->name('sales');
        Route::get('stock', [ReportController::class, 'stock'])->name('stock');
        Route::get('profit', [ReportController::class, 'profit'])->name('profit');
    });
});
