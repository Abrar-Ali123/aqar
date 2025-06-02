<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FlexibleSystem\{
    SystemComponentController,
    DynamicFieldController,
    BusinessRuleController,
    UiTemplateController,
    AutomationRuleController
};

Route::prefix('api/flexible-system')->middleware(['api'])->group(function () {
    // System Components Routes
    Route::prefix('components')->group(function () {
        Route::get('/', [SystemComponentController::class, 'index']);
        Route::post('/', [SystemComponentController::class, 'store']);
        Route::get('/{id}', [SystemComponentController::class, 'show']);
        Route::put('/{id}', [SystemComponentController::class, 'update']);
        Route::delete('/{id}', [SystemComponentController::class, 'destroy']);
        Route::get('/active', [SystemComponentController::class, 'getActiveComponents']);
        Route::get('/core', [SystemComponentController::class, 'getCoreComponents']);
        Route::get('/type/{type}', [SystemComponentController::class, 'getByType']);
    });

    // Dynamic Fields Routes
    Route::prefix('fields')->group(function () {
        Route::get('/', [DynamicFieldController::class, 'index']);
        Route::post('/', [DynamicFieldController::class, 'store']);
        Route::get('/{id}', [DynamicFieldController::class, 'show']);
        Route::put('/{id}', [DynamicFieldController::class, 'update']);
        Route::delete('/{id}', [DynamicFieldController::class, 'destroy']);
        Route::get('/searchable', [DynamicFieldController::class, 'getSearchableFields']);
        Route::get('/filterable', [DynamicFieldController::class, 'getFilterableFields']);
        Route::get('/type/{type}', [DynamicFieldController::class, 'getByFieldType']);
        Route::get('/required', [DynamicFieldController::class, 'getRequiredFields']);
        Route::post('/validate', [DynamicFieldController::class, 'validateFieldValue']);
    });

    // Business Rules Routes
    Route::prefix('rules')->group(function () {
        Route::get('/', [BusinessRuleController::class, 'index']);
        Route::post('/', [BusinessRuleController::class, 'store']);
        Route::get('/{id}', [BusinessRuleController::class, 'show']);
        Route::put('/{id}', [BusinessRuleController::class, 'update']);
        Route::delete('/{id}', [BusinessRuleController::class, 'destroy']);
        Route::get('/active', [BusinessRuleController::class, 'getActiveRules']);
        Route::get('/priority', [BusinessRuleController::class, 'getByPriorityRange']);
        Route::post('/evaluate', [BusinessRuleController::class, 'evaluateRules']);
    });

    // UI Templates Routes
    Route::prefix('templates')->group(function () {
        Route::get('/', [UiTemplateController::class, 'index']);
        Route::post('/', [UiTemplateController::class, 'store']);
        Route::get('/{id}', [UiTemplateController::class, 'show']);
        Route::put('/{id}', [UiTemplateController::class, 'update']);
        Route::delete('/{id}', [UiTemplateController::class, 'destroy']);
        Route::get('/active', [UiTemplateController::class, 'getActiveTemplates']);
        Route::post('/find-by-components', [UiTemplateController::class, 'findByComponents']);
        Route::get('/responsive', [UiTemplateController::class, 'getResponsiveTemplates']);
        Route::post('/{id}/render', [UiTemplateController::class, 'renderTemplate']);
        Route::post('/validate', [UiTemplateController::class, 'validateTemplate']);
    });

    // Automation Rules Routes
    Route::prefix('automation')->group(function () {
        Route::get('/', [AutomationRuleController::class, 'index']);
        Route::post('/', [AutomationRuleController::class, 'store']);
        Route::get('/{id}', [AutomationRuleController::class, 'show']);
        Route::put('/{id}', [AutomationRuleController::class, 'update']);
        Route::delete('/{id}', [AutomationRuleController::class, 'destroy']);
        Route::get('/active', [AutomationRuleController::class, 'getActiveRules']);
        Route::get('/event/{event}', [AutomationRuleController::class, 'getByTriggerEvent']);
        Route::get('/scheduled', [AutomationRuleController::class, 'getScheduledRules']);
        Route::post('/{id}/execute', [AutomationRuleController::class, 'executeRule']);
    });
});
