<?php

use App\Http\Controllers\FacilityController;
use App\Http\Controllers\IconController;
use App\Http\Controllers\ProductController;
use App\Livewire\AttributeManager;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/facilities/index', [FacilityController::class, 'index'])->name('facilities.index');
Route::get('/facilities/create', [FacilityController::class, 'create'])->name('facilities.create');
Route::post('/facilities', [FacilityController::class, 'store'])->name('facilities.store');
Route::get('/facilities/{id}/edit', [FacilityController::class, 'edit'])->name('facilities.edit');
Route::post('/facilities/{id}', [FacilityController::class, 'update'])->name('facilities.update');
Route::delete('/facilities/{facility}', [FacilityController::class, 'destroy'])->name('facilities.destroy');

Route::get('/attributes', AttributeManager::class)->name('attributes.index');
Route::get('/roles', App\Livewire\RoleForm::class)->name('roles.index');
Route::get('/permissions', App\Livewire\PermissionForm::class)->name('permissions.index');
Route::get('/category', App\Livewire\CategoryManager::class)->name('categories.index');
Route::get('/img', App\Livewire\UploadImageComponent::class)->name('upload.upload');
Route::get('/icons', [IconController::class, 'index'])->name('icons.index');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/products/create', [ProductController::class, 'store'])->name('products.store');
Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');
Route::get('/search/products', [ProductController::class, 'search'])->name('search.products');
Route::get('/products/delete/{product}', [ProductController::class, 'delete'])->name('products.delete');
