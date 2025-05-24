<?php
// Protected routes for admins
use App\Http\Controllers\Api\Admin\AdminDashboardApiController;
use App\Http\Controllers\Api\Admin\AdminOptionCombinationApiController;
use App\Http\Controllers\Api\Admin\AdminPartApiController;
use App\Http\Controllers\Api\Admin\AdminPartOptionApiController;
use App\Http\Controllers\Api\Admin\AdminPriceRuleApiController;
use App\Http\Controllers\Api\Admin\AdminProductApiController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum','auth.admin'])->prefix('admin')->group(function () {

    // Admin dashboard
    Route::get('/dashboard', [AdminDashboardApiController::class, 'index'])->name('admin.dashboard');

    // Admin products
    Route::apiResource('products', AdminProductApiController::class)->names([
        'index' => 'admin.products.index',
        'store' => 'admin.products.store',
        'show' => 'admin.products.show',
        'update' => 'admin.products.update',
        'destroy' => 'admin.products.destroy',
    ]);

    Route::post('/products/{product}/parts', [AdminPartApiController::class, 'store'])->name('admin.products.parts.store');
    Route::get('/parts/{part}', [AdminPartApiController::class, 'show'])->name('admin.parts.show');
    Route::put('/parts/{part}', [AdminPartApiController::class, 'update'])->name('admin.parts.update');
    Route::delete('/parts/{part}', [AdminPartApiController::class, 'destroy'])->name('admin.parts.destroy');
    Route::post('/parts/{part}/options', [AdminPartOptionApiController::class, 'store'])->name('admin.parts.options.store');
    Route::get('/options/{option}', [AdminPartOptionApiController::class, 'show'])->name('admin.options.show');
    Route::put('/options/{option}', [AdminPartOptionApiController::class, 'update'])->name('admin.options.update');
    Route::delete('/options/{option}', [AdminPartOptionApiController::class, 'destroy'])->name('admin.options.destroy');

    // Admin option combinations
    Route::get('/products/{product}/combinations', [AdminOptionCombinationApiController::class, 'index'])->name('admin.products.combinations.index');
    Route::post('/products/{product}/combinations', [AdminOptionCombinationApiController::class, 'store'])->name('admin.products.combinations.store');
    Route::put('/combinations/{combination}', [AdminOptionCombinationApiController::class, 'update'])->name('admin.combinations.update');
    Route::delete('/combinations/{combination}', [AdminOptionCombinationApiController::class, 'destroy'])->name('admin.combinations.destroy');

    // Admin price rules
    Route::get('/products/{product}/price-rules', [AdminPriceRuleApiController::class, 'index'])->name('admin.products.price-rules.index');
    Route::post('/products/{product}/price-rules', [AdminPriceRuleApiController::class, 'store'])->name('admin.products.price-rules.store');
    Route::put('/price-rules/{priceRule}', [AdminPriceRuleApiController::class, 'update'])->name('admin.price-rules.update');
    Route::delete('/price-rules/{priceRule}', [AdminPriceRuleApiController::class, 'destroy'])->name('admin.price-rules.destroy');

});
