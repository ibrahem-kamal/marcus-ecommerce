<?php
// Protected routes for admins
use App\Http\Controllers\Api\Admin\AdminDashboardApiController;
use App\Http\Controllers\Api\Admin\AdminProductApiController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('admin')->group(function () {

    // Admin dashboard
    Route::get('/dashboard', [AdminDashboardApiController::class, 'index']);

    // Admin products
    Route::apiResource('products', AdminProductApiController::class);

    Route::post('/products/{product}/parts', [AdminProductApiController::class, 'storePart']);
    Route::get('/parts/{part}', [AdminProductApiController::class, 'showPart']);
    Route::put('/parts/{part}', [AdminProductApiController::class, 'updatePart']);
    Route::delete('/parts/{part}', [AdminProductApiController::class, 'destroyPart']);
    Route::post('/parts/{part}/options', [AdminProductApiController::class, 'storeOption']);
    Route::get('/options/{option}', [AdminProductApiController::class, 'showOption']);
    Route::put('/options/{option}', [AdminProductApiController::class, 'updateOption']);
    Route::delete('/options/{option}', [AdminProductApiController::class, 'destroyOption']);

    // Admin option combinations
    Route::get('/products/{product}/combinations', [AdminProductApiController::class, 'getOptionCombinations']);
    Route::post('/products/{product}/combinations', [AdminProductApiController::class, 'storeOptionCombination']);
    Route::put('/combinations/{combinationId}', [AdminProductApiController::class, 'updateOptionCombination']);
    Route::delete('/combinations/{combinationId}', [AdminProductApiController::class, 'destroyOptionCombination']);

    // Admin price rules
    Route::get('/products/{product}/price-rules', [AdminProductApiController::class, 'getPriceRules']);
    Route::post('/products/{product}/price-rules', [AdminProductApiController::class, 'storePriceRule']);
    Route::put('/price-rules/{priceRuleId}', [AdminProductApiController::class, 'updatePriceRule']);
    Route::delete('/price-rules/{priceRuleId}', [AdminProductApiController::class, 'destroyPriceRule']);

});