<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    FacilityController,
    FacilityReviewController,
    FacilityBookingController
};

// مسارات المنشآت
Route::prefix('facilities')->name('facilities.')->group(function () {
    // عرض المنشآت
    Route::get('/', [FacilityController::class, 'index'])->name('index');
    Route::get('/page-builder/{facility}', [FacilityController::class, 'pageBuilder'])
    ->name('facilities.page-builder');
    Route::get('/templates', [WebsiteTemplateController::class, 'index'])
    ->name('website-templates.index');

    Route::get('/templates/{template}/preview', [WebsiteTemplateController::class, 'preview'])
    ->name('website-templates.preview');

    Route::post('/templates/{template}/apply', [WebsiteTemplateController::class, 'apply'])
    ->name('website-templates.apply');

    Route::get('/template-builder/create', [TemplateBuilderController::class, 'create'])
    ->name('template-builder.create');

    Route::post('/template-builder', [TemplateBuilderController::class, 'store'])
    ->name('template-builder.store');

    Route::get('/template-builder/{template}/edit', [TemplateBuilderController::class, 'edit'])
    ->name('template-builder.edit');

    Route::put('/template-builder/{template}', [TemplateBuilderController::class, 'update'])
    ->name('template-builder.update');

    Route::get('/category/{category}', [FacilityController::class, 'category'])->name('category');
    Route::get('/search', [FacilityController::class, 'search'])->name('search');
    Route::get('/{facility}', [FacilityController::class, 'show'])->name('show')->where('facility', '[0-9]+');

    // محرر الصفحات
    Route::get('/{facility}/page-builder', App\Livewire\PageBuilder::class)->name('page-builder')->where('facility', '[0-9]+');
    
    // الحجوزات
    Route::middleware(['auth'])->group(function () {
        Route::post('/{facility}/book', [FacilityBookingController::class, 'store'])->name('book');
        Route::get('/bookings', [FacilityBookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{booking}', [FacilityBookingController::class, 'show'])->name('bookings.show');
        Route::put('/bookings/{booking}', [FacilityBookingController::class, 'update'])->name('bookings.update');
        Route::delete('/bookings/{booking}', [FacilityBookingController::class, 'destroy'])->name('bookings.destroy');
    });
    
    // التقييمات
    Route::middleware(['auth'])->group(function () {
        Route::post('/{facility}/reviews', [FacilityReviewController::class, 'store'])->name('reviews.store');
        Route::put('/reviews/{review}', [FacilityReviewController::class, 'update'])->name('reviews.update');
        Route::delete('/reviews/{review}', [FacilityReviewController::class, 'destroy'])->name('reviews.destroy');
    });
});
