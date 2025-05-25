<?php

use App\Http\Controllers\Api\Customer\CartApiController;
use App\Http\Controllers\Api\Customer\ProductApiController;
use Illuminate\Support\Facades\Route;

// Customer-facing API routes
Route::get('/products', [ProductApiController::class, 'index'])->name('customer.products.index');
Route::get('/products/{product}', [ProductApiController::class, 'show'])->name('customer.products.show');

// Cart routes
Route::post('/cart', [CartApiController::class, 'addToCart'])->name('customer.cart.add');
Route::get('/cart/validate', [CartApiController::class, 'getCart'])->name('customer.cart.validate');
