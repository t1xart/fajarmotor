<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\PurchaseOrderController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\StockController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\WorkOrderController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.')->group(function () {

    Route::post('auth/login', [AuthController::class, 'login']);
    Route::post('auth/register', [AuthController::class, 'register']);

    Route::middleware('auth:sanctum')->group(function () {

        Route::get('auth/me', [AuthController::class, 'me']);
        Route::post('auth/logout', [AuthController::class, 'logout']);

        Route::get('dashboard', [DashboardController::class, 'index']);

        // Profile
        Route::get('profile', [ProfileController::class, 'show']);
        Route::put('profile', [ProfileController::class, 'update']);
        Route::post('profile/photo', [ProfileController::class, 'updatePhoto']);
        Route::delete('profile/photo', [ProfileController::class, 'deletePhoto']);
        Route::put('profile/password', [ProfileController::class, 'updatePassword']);

        Route::apiResource('products', ProductController::class)->names([
            'index' => 'api.products.index',
            'store' => 'api.products.store',
            'show' => 'api.products.show',
            'update' => 'api.products.update',
            'destroy' => 'api.products.destroy',
        ]);

        Route::get('stock', [StockController::class, 'index']);
        Route::get('stock/mutations', [StockController::class, 'mutations']);
        Route::post('stock/adjust', [StockController::class, 'adjust']);

        Route::get('sales', [SaleController::class, 'index']);
        Route::post('sales', [SaleController::class, 'store']);
        Route::get('sales/{sale}', [SaleController::class, 'show']);

        Route::get('purchase-orders', [PurchaseOrderController::class, 'index']);
        Route::post('purchase-orders', [PurchaseOrderController::class, 'store']);
        Route::get('purchase-orders/{purchaseOrder}', [PurchaseOrderController::class, 'show']);
        Route::post('purchase-orders/{purchaseOrder}/receive', [PurchaseOrderController::class, 'receive']);

        Route::get('work-orders', [WorkOrderController::class, 'index']);
        Route::post('work-orders', [WorkOrderController::class, 'store']);
        Route::get('work-orders/{workOrder}', [WorkOrderController::class, 'show']);
        Route::patch('work-orders/{workOrder}/status', [WorkOrderController::class, 'updateStatus']);

        Route::apiResource('suppliers', SupplierController::class)->names([
            'index' => 'api.suppliers.index',
            'store' => 'api.suppliers.store',
            'show' => 'api.suppliers.show',
            'update' => 'api.suppliers.update',
            'destroy' => 'api.suppliers.destroy',
        ])->only(['index', 'show', 'store', 'update']);

        Route::prefix('reports')->group(function () {
            Route::get('sales', [ReportController::class, 'sales']);
            Route::get('stock', [ReportController::class, 'stock']);
            Route::get('profit', [ReportController::class, 'profit']);
        });
    });
});
