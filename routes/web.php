<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\InventoryExportController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\StockAdjustmentController;
use App\Http\Controllers\Admin\StockMovementController;
use App\Http\Controllers\Admin\WarehouseLocationController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductCatalogController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/products', [ProductCatalogController::class, 'index'])->name('catalog.index');
    Route::get('/products/{product}', [ProductCatalogController::class, 'show'])->name('catalog.show');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::prefix('admin')
        ->name('admin.')
        ->middleware('role:admin')
        ->group(function () {
            Route::resource('categories', CategoryController::class)->except(['show']);
            Route::resource('warehouse-locations', WarehouseLocationController::class)->except(['show']);
            Route::resource('products', AdminProductController::class);

            Route::get('products-export/csv', [InventoryExportController::class, 'products'])->name('products.export');
            Route::get('products/{product}/stock-adjustments/create', [StockAdjustmentController::class, 'create'])->name('stock-adjustments.create');
            Route::post('products/{product}/stock-adjustments', [StockAdjustmentController::class, 'store'])->name('stock-adjustments.store');
            Route::get('stock-movements', [StockMovementController::class, 'index'])->name('stock-movements.index');
            Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
        });
});
