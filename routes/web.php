<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\IconController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserInterfaceController;
use App\Http\Middleware\CheckPermission;
use App\Livewire\AccountManagementComponent;
use App\Livewire\CategoryManager;
use App\Livewire\PermissionForm;
use App\Livewire\RoleForm;
use App\Livewire\UploadImageComponent;
use App\Livewire\AttributeManager;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\FeatureController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [UserInterfaceController::class, 'index'])->name('home');
Route::get('/facility/{facility}', [FacilityController::class, 'show'])->name('facility.show');

Route::resource('loans', LoanController::class);

Route::post('/register-submit', [AccountManagementComponent::class, 'register'])->name('register.submit');
Route::get('/account', AccountManagementComponent::class)->name('account.index');
Route::get('/account', AccountManagementComponent::class)->name('login');
Route::post('/login-submit', [AccountManagementComponent::class, 'login'])->name('login.submit');
Route::post('/login-register-submit', [AccountManagementComponent::class, 'loginOrRegister'])->name('login.loginOrRegister');


Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('index');

    // مسارات المنتجات
    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/products/', [AdminProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');
    Route::get('/product/{id}', [AdminProductController::class, 'show'])->name('products.show');
    Route::get('/products/delete/{product}', [AdminProductController::class, 'delete'])->name('products.delete');
    Route::post('/products/update-status/{product}', [AdminProductController::class, 'updateStatus'])->name('products.update-status');
    Route::delete('/products/delete-image/{product}', [AdminProductController::class, 'deleteImage'])->name('products.delete-image');
    Route::delete('/products/delete-gallery-image/{product}/{index}', [AdminProductController::class, 'deleteGalleryImage'])->name('products.delete-gallery-image');

    // مسارات الفئات
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // مسارات المنشآت
    Route::get('facilities', [FacilityController::class, 'index'])->name('facilities.index');
    Route::get('facilities/create', [FacilityController::class, 'create'])->name('facilities.create');
    Route::post('facilities', [FacilityController::class, 'store'])->name('facilities.store');
    Route::get('facilities/edit', [FacilityController::class, 'edit'])->name('facilities.edit');
    Route::post('facilities/{id}', [FacilityController::class, 'update'])->name('facilities.update');
    Route::delete('facilities/{id}', [FacilityController::class, 'destroy'])->name('facilities.destroy');

    Route::resource('banks', BankController::class);

    // مسارات الخصائص
    Route::get('/attributes', [AttributeController::class, 'index'])->name('attributes.index');
    Route::get('/attributes/create', [AttributeController::class, 'create'])->name('attributes.create');
    Route::post('/attributes', [AttributeController::class, 'store'])->name('attributes.store');
    Route::get('/attributes/{attribute}', [AttributeController::class, 'show'])->name('attributes.show');
    Route::get('/attributes/{attribute}/edit', [AttributeController::class, 'edit'])->name('attributes.edit');
    Route::put('/attributes/{attribute}', [AttributeController::class, 'update'])->name('attributes.update');
    Route::delete('/attributes/{attribute}', [AttributeController::class, 'destroy'])->name('attributes.destroy');
    Route::post('/attributes/by-category', [AttributeController::class, 'getAttributesByCategory'])->name('attributes.by-category');

    // مسارات الميزات
    Route::get('/features', [FeatureController::class, 'index'])->name('features.index');
    Route::get('/features/create', [FeatureController::class, 'create'])->name('features.create');
    Route::post('/features', [FeatureController::class, 'store'])->name('features.store');
    Route::get('/features/{feature}', [FeatureController::class, 'show'])->name('features.show');
    Route::get('/features/{feature}/edit', [FeatureController::class, 'edit'])->name('features.edit');
    Route::put('/features/{feature}', [FeatureController::class, 'update'])->name('features.update');
    Route::delete('/features/{feature}', [FeatureController::class, 'destroy'])->name('features.destroy');

    // Permissions Routes
    Route::resource('permissions', \App\Http\Controllers\Admin\PermissionController::class);

    // Roles Routes
    Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class);
});


Route::prefix('user')->group(function () {
    Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::put('/profile/update', [UserController::class, 'updateProfile'])->name('user.profile.update');
});



Route::middleware([CheckPermission::class])->prefix('/{facility}')->group(function () {
    Route::resource('/projects', ProjectController::class);

    Route::resource('/statuses', StatusController::class);

    Route::get('/index', [FacilityController::class, 'index'])->name('facilities.index');
    Route::get('/edit', [FacilityController::class, 'edit'])->name('facilities.edit');
    Route::post('/{id}', [FacilityController::class, 'update'])->name('facilities.update');
    Route::delete('/', [FacilityController::class, 'destroy'])->name('facilities.destroy');


    Route::get('/bookings', [BookingController::class, 'showBookings'])->name('facility.bookings');
    Route::post('/book-product/{product}', [BookingController::class, 'store'])->name('book.product');
    Route::post('/book-product/cash/{product}', [BookingController::class, 'store'])->name('booking.cash');
    Route::post('/book-product/bank/{product}', [BookingController::class, 'store'])->name('booking.bank');


    Route::get('/search/products', [ProductController::class, 'search'])->name('products.search');
    Route::get('/roles', RoleForm::class)->name('roles.index');
    Route::get('/permissions', PermissionForm::class)->name('permissions.index');

    Route::get('/icons', [IconController::class, 'index'])->name('icons.index');
    Route::get('/loans', [LoanController::class, 'facilityLoans'])->name('facility.loans.index');

    Route::get('/upload', UploadImageComponent::class)->name('upload.upload');
});





Route::get('/attributes', AttributeManager::class)->name('attributes.index');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



