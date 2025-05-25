<?php

use Illuminate\Support\Facades\Route;

// Admin SPA Routes - All admin routes will be handled by the Vue SPA
Route::get('/admin/{any?}', function () {
    return view('admin');
})->where('any', '.*')->name('admin.spa');

// App SPA Routes - All app routes will be handled by the Vue SPA
Route::get('/{any?}', function () {
    return view('app');
})->where('any', '^(?!admin/).*$')->name('app.spa');