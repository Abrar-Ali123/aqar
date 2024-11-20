<?php

use App\Http\Controllers\BankController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\IconController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\StatusController;
use App\Livewire\AttributeManager;
use App\Livewire\CategoryManager;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [FacilityController::class, 'home'])->name('home');
Route::get('/facility/{facility}', [FacilityController::class, 'show'])->name('facility.show');

Route::resource('banks', BankController::class);
Route::resource('loans', LoanController::class);

Route::post('/register-submit', [App\Livewire\AccountManagementComponent::class, 'register'])->name('register.submit');
Route::get('/account', App\Livewire\AccountManagementComponent::class)->name('account.index');
Route::get('/account', App\Livewire\AccountManagementComponent::class)->name('login');
Route::post('/login-submit', [App\Livewire\AccountManagementComponent::class, 'login'])->name('login.submit');
Route::post('/login-register-submit', [App\Livewire\AccountManagementComponent::class, 'loginOrRegister'])->name('login.loginOrRegister');
Route::post('/book-product/{product}', [BookingController::class, 'store'])->name('book.product');
Route::post('/book-product/cash/{product}', [BookingController::class, 'store'])->name('booking.cash');
Route::post('/book-product/bank/{product}', [BookingController::class, 'store'])->name('booking.bank');
Route::get('/facilities/create', [FacilityController::class, 'create'])->name('facilities.create');
Route::post('/facilities', [FacilityController::class, 'store'])->name('facilities.store');

Route::middleware([\App\Http\Middleware\CheckPermission::class])->prefix('/{facility}')->group(function () {
    Route::resource('/projects', ProjectController::class);

    Route::resource('/statuses', StatusController::class);

    Route::get('/index', [FacilityController::class, 'index'])->name('facilities.index');
    Route::get('/edit', [FacilityController::class, 'edit'])->name('facilities.edit');
    Route::post('/{id}', [FacilityController::class, 'update'])->name('facilities.update');
    Route::delete('/', [FacilityController::class, 'destroy'])->name('facilities.destroy');

    // تعريف مسارات المنتجات
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products/create', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::get('/product/{id}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/products/delete/{product}', [ProductController::class, 'delete'])->name('products.delete');

    Route::get('/bookings', [BookingController::class, 'showBookings'])->name('facility.bookings');

    // مسارات البحث والسمات والأدوار
    Route::get('/search/products', [ProductController::class, 'search'])->name('products.search');
    Route::get('/roles', App\Livewire\RoleForm::class)->name('roles.index');
    Route::get('/permissions', App\Livewire\PermissionForm::class)->name('permissions.index');

    // مسارات الأيقونات والرفع
    Route::get('/icons', [IconController::class, 'index'])->name('icons.index');
    Route::get('/loans', [LoanController::class, 'facilityLoans'])->name('facility.loans.index');

    Route::get('/upload', App\Livewire\UploadImageComponent::class)->name('upload.upload');
});
Route::get('/attributes', AttributeManager::class)->name('attributes.index');

Route::get('/cat', CategoryManager::class)->name('categories.index');
