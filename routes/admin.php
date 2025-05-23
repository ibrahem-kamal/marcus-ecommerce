<?php

use Illuminate\Support\Facades\Route;

// Admin SPA Routes - All admin routes will be handled by the Vue SPA
Route::get('/admin/{any?}', function () {
    return view('admin');
})->where('any', '.*')->name('admin.spa');
