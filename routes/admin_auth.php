<?php

use App\Http\Controllers\Api\Admin\AdminAuthApiController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    // Admin user
    Route::get('/user', [AdminAuthApiController::class, 'user']);
    Route::post('/logout', [AdminAuthApiController::class, 'logout']);
});

Route::post('/admin/login', [AdminAuthApiController::class, 'login'])->middleware('guest:admin');