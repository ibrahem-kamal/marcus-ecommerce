<?php
// Protected routes for admins
use App\Http\Controllers\Api\Admin\AdminDashboardApiController;

Route::middleware('auth:sanctum')->prefix('admin')->group(function () {

    // Admin dashboard
    Route::get('/dashboard', [AdminDashboardApiController::class, 'index']);

});