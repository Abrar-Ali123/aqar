<?php

use App\Http\Controllers\Facility\CustomizeController;

Route::prefix('{locale}/facilities/{facility}/customize')->middleware(['auth', 'role:facility_owner'])->group(function () {
    Route::get('/', [CustomizeController::class, 'editor'])->name('facilities.customize.editor');
    Route::get('/preview', [CustomizeController::class, 'preview'])->name('facilities.customize.preview');
    Route::post('/save', [CustomizeController::class, 'save'])->name('facilities.customize.save');
    Route::get('/components', [CustomizeController::class, 'components'])->name('facilities.customize.components');
    Route::post('/revert/{revision}', [CustomizeController::class, 'revertToRevision'])->name('facilities.customize.revert');
});
