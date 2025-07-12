<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\FacilityController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::post('/authenticate-or-register', [AccountController::class, 'authenticateOrRegister']);


// لوحة التحكم
Route::middleware(['web', 'auth'])->group(function () {
    // الوصول للوحة التحكم
    Route::middleware(['authorize:permission,access_dashboard'])->group(function () {
        Route::view('/dashboard', 'dashboard.index')->name('dashboard');
        
        // التقارير والإحصائيات
        Route::middleware(['authorize:permission,view_reports'])->group(function () {
            Route::get('/reports/sales', 'ReportController@sales')->name('reports.sales');
            Route::get('/reports/users', 'ReportController@users')->name('reports.users');
            Route::get('/reports/analytics', 'ReportController@analytics')->name('reports.analytics');
        });
    });

    // إكمال الملف الشخصي
    Route::get('/complete-profile', [AccountController::class, 'showCompleteProfile'])->name('complete-profile');
    Route::post('/complete-profile', [AccountController::class, 'completeProfile']);
});

// مسارات التحقق والتسجيل
Route::prefix('{locale}')
    ->whereAlpha('locale')
    ->middleware(['localize', 'web', 'throttle:6,1'])
    ->group(function () {
        Route::prefix('account')->group(function () {
            Route::post('verify', [AccountController::class, 'verify'])->name('account.verify');
            Route::post('verify-code', [AccountController::class, 'verifyCode'])->name('account.verify-code');
            Route::post('register', [AccountController::class, 'register'])->name('account.register');
        });
    });

// المسارات المحلية (متعددة اللغات)
Route::prefix('{locale}')
    ->whereAlpha('locale')
    ->middleware('localize')
    ->group(function () {

        // الصفحة الرئيسية
        Route::controller(HomeController::class)->group(function () {
            Route::get('/', 'index')->name('home');
        });

        // صفحة تسجيل الدخول وإدارة الحساب
        Route::view('/account', 'account.index');

        // Profile routes
        Route::middleware(['auth'])->group(function () {
            Route::get('/profile/edit', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
            Route::put('/profile/update', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
        });

        // إدارة الحساب (للمستخدمين المسجلين)
        Route::middleware(['auth'])->prefix('account')->group(function () {
            Route::get('/profile', [AccountController::class, 'profile'])->name('account.profile');
            Route::put('/profile', [AccountController::class, 'updateProfile'])->name('account.profile.update');
            Route::get('/settings', [AccountController::class, 'settings'])->name('account.settings');
            Route::put('/settings', [AccountController::class, 'updateSettings'])->name('account.settings.update');
            
            // إعدادات الأمان
            Route::middleware(['authorize:permission,manage_security'])->group(function () {
                Route::post('/security/2fa/enable', [AccountController::class, 'enable2FA'])->name('account.2fa.enable');
                Route::post('/security/2fa/disable', [AccountController::class, 'disable2FA'])->name('account.2fa.disable');
                Route::post('/security/devices/revoke', [AccountController::class, 'revokeDevice'])->name('account.devices.revoke');
            });
        });

        // تسجيل الخروج
        Route::post('/logout', function () {
            auth()->logout();
            return redirect()->route('home', ['locale' => app()->getLocale()]);
        })->name('logout');

        // الفئات
        Route::controller(CategoryController::class)
            ->prefix('categories')
            ->name('categories.')
            ->group(function () {
                // المسارات العامة
                Route::get('/', 'index')->name('index');
                Route::get('/{category}', 'show')->name('show');

                // مسارات الإدارة
                Route::middleware(['auth', 'authorize:permission,manage_categories'])->group(function () {
                    Route::post('/', 'store')->name('store');
                    Route::put('/{category}', 'update')->name('update');
                    Route::delete('/{category}', 'destroy')->name('destroy');
                    Route::post('/{category}/status', 'updateStatus')->name('status.update');
                });
            });

        // المنتجات
        Route::controller(ProductController::class)
            ->prefix('products')
            ->name('products.')
            ->group(function () {
                // المسارات العامة
                Route::get('/', 'index')->name('index');
                Route::get('/{product}', 'show')->name('show');
                Route::get('/facility/{facility}', 'facilityProducts')->name('facility');

                // إدارة المنتجات
                Route::middleware(['auth', 'authorize:permission,manage_products'])->group(function () {
                    Route::post('/', 'store')->name('store');
                    Route::put('/{product}', 'update')->name('update');
                    Route::delete('/{product}', 'destroy')->name('destroy');
                    Route::post('/{product}/status', 'updateStatus')->name('status.update');
                    Route::post('/{product}/featured', 'toggleFeatured')->name('featured.toggle');
                });

                // التقييمات والمراجعات
                Route::middleware(['auth'])->group(function () {
                    Route::post('/{product}/reviews', 'addReview')->name('reviews.store');
                    Route::put('/reviews/{review}', 'updateReview')->name('reviews.update');
                    Route::delete('/reviews/{review}', 'deleteReview')->name('reviews.destroy');
                });
            });

        // البحث
        Route::get('/search', [ProductController::class, 'search'])->name('search');

        // مسارات المنشآت
        // مسارات المنشآت (CRUD)
                Route::get('facilities', [FacilityController::class, 'index'])->name('facilities.index');
        Route::get('facilities/create', [FacilityController::class, 'create'])->name('facilities.create');
        Route::post('facilities', [FacilityController::class, 'store'])->name('facilities.store');
        Route::get('facilities/{facility}', [FacilityController::class, 'show'])->name('facilities.show');
        Route::get('facilities/{facility}/edit', [FacilityController::class, 'edit'])->name('facilities.edit');
        Route::put('facilities/{facility}', [FacilityController::class, 'update'])->name('facilities.update');
        Route::delete('facilities/{facility}', [FacilityController::class, 'destroy'])->name('facilities.destroy');
    });


// 🔐 لوحة التحكم الإدارية
Route::middleware(['auth:sanctum', 'verified'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // إعدادات النظام
        Route::middleware(['authorize:permission,manage_system_settings'])->group(function () {
            Route::controller(\App\Http\Controllers\Admin\AiSettingsController::class)
                ->prefix('settings')
                ->name('settings.')
                ->group(function () {
                    Route::get('/ai', 'index')->name('ai.index');
                    Route::put('/ai', 'update')->name('ai.update');
                });

            Route::controller(\App\Http\Controllers\Admin\SystemSettingsController::class)
                ->prefix('settings')
                ->name('settings.')
                ->group(function () {
                    Route::get('/general', 'index')->name('general.index');
                    Route::put('/general', 'update')->name('general.update');
                    Route::get('/security', 'security')->name('security.index');
                    Route::put('/security', 'updateSecurity')->name('security.update');
                });
        });

        // إدارة المستخدمين
        Route::middleware(['authorize:permission,manage_users'])->group(function () {
            Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
            Route::post('users/{user}/status', [\App\Http\Controllers\Admin\UserController::class, 'updateStatus'])->name('users.status.update');
        });

        // إدارة الأدوار والصلاحيات
        Route::middleware(['authorize:permission,manage_roles'])->group(function () {
            Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class);
            Route::resource('permissions', \App\Http\Controllers\Admin\PermissionController::class);
        });
    });



 
    