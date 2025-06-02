<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;

// المسارات العامة - بدون أي قيود
Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
