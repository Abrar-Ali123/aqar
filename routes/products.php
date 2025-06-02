<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProductController,
    ReviewController,
    FavoriteController
};

// مسارات المنتجات
Route::group(['prefix' => 'products', 'as' => 'products.'], function () {
    // عرض المنتجات
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/category/{category}', [ProductController::class, 'category'])->name('category');
    Route::get('/search', [ProductController::class, 'search'])->name('search');
    Route::get('/{product}', [ProductController::class, 'show'])->name('show');
    
    // التقييمات
    Route::middleware(['auth'])->group(function () {
        Route::post('/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
        Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
        Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    });
    
    // المفضلة
    Route::middleware(['auth'])->prefix('favorites')->name('favorites.')->group(function () {
        Route::post('/{product}', [FavoriteController::class, 'toggle'])->name('toggle');
        Route::get('/', [FavoriteController::class, 'index'])->name('index');
    });
});
