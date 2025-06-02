<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    DashboardController,
    LanguageController,
    TranslationController,
    UserController,
    RoleController,
    ProductController,
    FacilityController,
    OrderController
};

// مسارات لوحة التحكم
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // الرئيسية
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // إدارة اللغات
    Route::resource('languages', LanguageController::class);
    Route::post('languages/update-status/{language}', [LanguageController::class, 'updateStatus'])->name('languages.update-status');
    
    // إدارة الترجمات
    Route::resource('translations', TranslationController::class);
    Route::get('translations/export', [TranslationController::class, 'export'])->name('translations.export');
    Route::post('translations/import', [TranslationController::class, 'import'])->name('translations.import');
    
    // إدارة المستخدمين
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    
    // إدارة المنتجات
    Route::resource('products', ProductController::class);
    Route::post('products/update-status/{product}', [ProductController::class, 'updateStatus'])->name('products.update-status');
    
    // إدارة المنشآت
    Route::resource('facilities', FacilityController::class);
    Route::post('facilities/update-status/{facility}', [FacilityController::class, 'updateStatus'])->name('facilities.update-status');
    
    // إدارة الطلبات
    Route::resource('orders', OrderController::class);
    Route::post('orders/update-status/{order}', [OrderController::class, 'updateStatus'])->name('orders.update-status');
});
