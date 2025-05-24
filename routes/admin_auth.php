<?php

use App\Http\Controllers\Api\Admin\AdminAuthApiController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum','auth.admin'])->prefix('admin')->group(function () {
    // Admin user
    Route::get('/user', [AdminAuthApiController::class, 'user'])->name('admin.user');
    Route::post('/logout', [AdminAuthApiController::class, 'logout'])->name('admin.logout');
});

Route::post('/admin/login', [AdminAuthApiController::class, 'login'])->middleware('guest')->name('admin.login');
