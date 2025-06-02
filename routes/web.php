<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{HomeController, FacilityController, ProductController, LanguageController};
use App\Models\Language;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// إعادة توجيه الصفحة الرئيسية للغة الافتراضية
Route::get('/', function () {
    $defaultLanguage = \App\Models\Language::getDefaultLanguage();
    return redirect('/' . ($defaultLanguage?->code ?? 'ar'));
});

// إعادة توجيه جميع المسارات التي لا تحتوي على رمز اللغة
Route::fallback(function () {
    $defaultLanguage = \App\Models\Language::getDefaultLanguage();
    $locale = $defaultLanguage?->code ?? 'ar';
    
    // الحصول على المسار الحالي
    $path = request()->path();
    
    // إعادة التوجيه إلى نفس المسار مع إضافة رمز اللغة
    return redirect("/{$locale}/{$path}");
});

// مسار تبديل اللغة
Route::get('language/switch/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

// مسارات المصادقة
Route::middleware('guest')->group(function () {
    Route::get('login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [\App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login.submit');
    Route::post('login/register', [\App\Http\Controllers\Auth\LoginController::class, 'loginOrRegister'])->name('login.loginOrRegister');
    Route::get('register', [\App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [\App\Http\Controllers\Auth\RegisterController::class, 'register'])->name('register.submit');
});

Route::post('logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// المسارات مع بريفكس اللغة
Route::prefix('{locale}')
    ->where(['locale' => '[a-zA-Z]{2}'])
    ->group(function () {

        // الصفحة الرئيسية
        Route::get('/', [HomeController::class, 'index'])->name('home');

        // صفحة الحساب
        Route::get('/account', App\Http\Livewire\AccountManagementComponent::class)->name('account');

        // صفحة الحساب
        Route::get('/account', App\Http\Livewire\AccountManagementComponent::class)->name('account');

        // مسارات المنشآت العامة (بدون قيود)
        Route::get('facilities', [FacilityController::class, 'index'])->name('facilities.index');
        Route::get('facilities/{facility}', [FacilityController::class, 'show'])->name('facilities.show');

        // مسارات المنتجات
        Route::prefix('products')->group(function () {
            Route::controller(ProductController::class)->group(function () {
                // عرض قائمة المنتجات
                Route::get('/', 'index')->name('products.index');
                // عرض تفاصيل منتج محدد
                Route::get('/{product}', 'show')->name('products.show');
                // عرض منتجات منشأة محددة
                Route::get('/facility/{facility}', 'facilityProducts')->name('products.facility');
                // البحث في المنتجات
                Route::get('/search', 'search')->name('products.search');
            });
        });
    });
