<?php
// Protected routes for admins
use App\Http\Controllers\Api\Admin\AdminDashboardApiController;
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

    Route::post('/products/{product}/parts', [AdminProductApiController::class, 'storePart'])->name('admin.products.parts.store');
    Route::get('/parts/{part}', [AdminProductApiController::class, 'showPart'])->name('admin.parts.show');
    Route::put('/parts/{part}', [AdminProductApiController::class, 'updatePart'])->name('admin.parts.update');
    Route::delete('/parts/{part}', [AdminProductApiController::class, 'destroyPart'])->name('admin.parts.destroy');
    Route::post('/parts/{part}/options', [AdminProductApiController::class, 'storeOption'])->name('admin.parts.options.store');
    Route::get('/options/{option}', [AdminProductApiController::class, 'showOption'])->name('admin.options.show');
    Route::put('/options/{option}', [AdminProductApiController::class, 'updateOption'])->name('admin.options.update');
    Route::delete('/options/{option}', [AdminProductApiController::class, 'destroyOption'])->name('admin.options.destroy');

    // Admin option combinations
    Route::get('/products/{product}/combinations', [AdminProductApiController::class, 'getOptionCombinations'])->name('admin.products.combinations.index');
    Route::post('/products/{product}/combinations', [AdminProductApiController::class, 'storeOptionCombination'])->name('admin.products.combinations.store');
    Route::put('/combinations/{combinationId}', [AdminProductApiController::class, 'updateOptionCombination'])->name('admin.combinations.update');
    Route::delete('/combinations/{combinationId}', [AdminProductApiController::class, 'destroyOptionCombination'])->name('admin.combinations.destroy');

    // Admin price rules
    Route::get('/products/{product}/price-rules', [AdminProductApiController::class, 'getPriceRules'])->name('admin.products.price-rules.index');
    Route::post('/products/{product}/price-rules', [AdminProductApiController::class, 'storePriceRule'])->name('admin.products.price-rules.store');
    Route::put('/price-rules/{priceRuleId}', [AdminProductApiController::class, 'updatePriceRule'])->name('admin.price-rules.update');
    Route::delete('/price-rules/{priceRuleId}', [AdminProductApiController::class, 'destroyPriceRule'])->name('admin.price-rules.destroy');

});
