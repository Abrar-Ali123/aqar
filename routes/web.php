<?php

use App\Http\Controllers\FacilityController;
use App\Http\Controllers\IconController;
use App\Http\Controllers\ProductController;
use App\Livewire\AttributeManager;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/account', App\Livewire\AccountManagementComponent::class)->name('account.index');
Route::get('/account', App\Livewire\AccountManagementComponent::class)->name('login');
Route::post('/login-submit', [App\Livewire\AccountManagementComponent::class, 'loginOrRegister'])->name('login.submit');

Route::get('/facilities/create', [FacilityController::class, 'create'])->name('facilities.create');
Route::post('/facilities', [FacilityController::class, 'store'])->name('facilities.store');

Route::middleware([\App\Http\Middleware\CheckPermission::class])->prefix('/{facility}')->group(function () {
    Route::get('/index', [FacilityController::class, 'index'])->name('facilities.index');
    Route::get('/edit', [FacilityController::class, 'edit'])->name('facilities.edit');
    Route::post('/{id}', [FacilityController::class, 'update'])->name('facilities.update');
    Route::delete('/', [FacilityController::class, 'destroy'])->name('facilities.destroy');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products/create', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::get('/product/{id}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/products/delete/{product}', [ProductController::class, 'delete'])->name('products.delete');

    Route::get('/search/products', [ProductController::class, 'search'])->name('products.search');
    Route::get('/attributes', AttributeManager::class)->name('attributes.index');
    Route::get('/roles', App\Livewire\RoleForm::class)->name('roles.index');
    Route::get('/permissions', App\Livewire\PermissionForm::class)->name('permissions.index');
    Route::get('/categories', App\Livewire\CategoryManager::class)->name('categories.index');

    Route::get('/icons', [IconController::class, 'index'])->name('icons.index');
    Route::get('/upload', App\Livewire\UploadImageComponent::class)->name('upload.upload');
});
