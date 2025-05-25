<?php

use App\Http\Controllers\Api\Customer\ProductApiController;
use Illuminate\Support\Facades\Route;

// Customer-facing API routes
Route::get('/products', [ProductApiController::class, 'index'])->name('customer.products.index');
Route::get('/products/{product}', [ProductApiController::class, 'show'])->name('customer.products.show');