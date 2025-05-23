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

    // Admin parts and options
    Route::post('/products/{product}/parts', [AdminProductApiController::class, 'storePart']);
    Route::post('/parts/{part}/options', [AdminProductApiController::class, 'storeOption']);
    Route::get('/parts/{part}', [AdminProductApiController::class, 'getPart']);

});