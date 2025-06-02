<?php

namespace App\Http\Controllers\FlexibleSystem;

use App\Http\Controllers\Controller;
use App\Services\FlexibleSystem\DynamicFieldService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DynamicFieldController extends Controller
{
    protected $service;

    public function __construct(DynamicFieldService $service)
    {
        $this->service = $service;
    }

    public function index(): JsonResponse
    {
        $fields = $this->service->all();
        return response()->json(['data' => $fields]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:dynamic_fields',
            'field_type' => 'required|string|max:50',
            'validation_rules' => 'nullable|json',
            'ui_settings' => 'nullable|json',
            'default_value' => 'nullable|json',
            'is_required' => 'boolean',
            'is_searchable' => 'boolean',
            'is_filterable' => 'boolean',
            'is_sortable' => 'boolean',
            'dependencies' => 'nullable|json',
            'order' => 'integer',
            'translations' => 'required|array',
            'translations.*.locale' => 'required|string|size:2',
            'translations.*.name' => 'required|string',
            'translations.*.description' => 'nullable|string',
            'translations.*.placeholder' => 'nullable|string',
            'translations.*.help_text' => 'nullable|string'
        ]);

        $field = $this->service->createWithTranslations(
            $request->except('translations'),
            $request->input('translations')
        );

        return response()->json([
            'message' => 'Dynamic field created successfully',
            'data' => $field
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $field = $this->service->findById($id);
        return response()->json(['data' => $field]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'code' => 'string|max:50|unique:dynamic_fields,code,' . $id,
            'field_type' => 'string|max:50',
            'validation_rules' => 'nullable|json',
            'ui_settings' => 'nullable|json',
            'default_value' => 'nullable|json',
            'is_required' => 'boolean',
            'is_searchable' => 'boolean',
            'is_filterable' => 'boolean',
            'is_sortable' => 'boolean',
            'dependencies' => 'nullable|json',
            'order' => 'integer',
            'translations' => 'array',
            'translations.*.locale' => 'required|string|size:2',
            'translations.*.name' => 'required|string',
            'translations.*.description' => 'nullable|string',
            'translations.*.placeholder' => 'nullable|string',
            'translations.*.help_text' => 'nullable|string'
        ]);

        $field = $this->service->updateWithTranslations(
            $id,
            $request->except('translations'),
            $request->input('translations', [])
        );

        return response()->json([
            'message' => 'Dynamic field updated successfully',
            'data' => $field
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);
        return response()->json(['message' => 'Dynamic field deleted successfully']);
    }

    public function getSearchableFields(): JsonResponse
    {
        $fields = $this->service->getSearchableFields();
        return response()->json(['data' => $fields]);
    }

    public function getFilterableFields(): JsonResponse
    {
        $fields = $this->service->getFilterableFields();
        return response()->json(['data' => $fields]);
    }

    public function getByFieldType(string $type): JsonResponse
    {
        $fields = $this->service->getByFieldType($type);
        return response()->json(['data' => $fields]);
    }

    public function getRequiredFields(): JsonResponse
    {
        $fields = $this->service->getRequiredFields();
        return response()->json(['data' => $fields]);
    }

    public function validateFieldValue(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'field_type' => 'required|string',
            'value' => 'required'
        ]);

        $isValid = $this->service->validateFieldValue(
            $request->input('field_type'),
            $request->input('value')
        );

        return response()->json([
            'valid' => $isValid
        ]);
    }

    public function storeWithNewTranslationSystem(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'translations' => 'required|array',
            'translations.*.locale' => 'required|string|size:2',
            'translations.*.name' => 'required|string',
            'translations.*.description' => 'nullable|string',
            'translations.*.placeholder' => 'nullable|string',
            'translations.*.help_text' => 'nullable|string',
            'type' => 'required|string|in:text,textarea,number,date,select,multiselect,checkbox,radio,file',
            'validation_rules' => 'nullable|string',
            'default_value' => 'nullable|string',
            'options' => 'required_if:type,select,multiselect,radio|array',
            'is_required' => 'boolean',
            'is_active' => 'boolean',
            'order' => 'nullable|integer',
        ]);

        $field = $this->service->createWithNewTranslationSystem(
            $request->except('translations'),
            $request->input('translations')
        );

        return response()->json([
            'message' => 'Dynamic field created successfully',
            'data' => $field
        ], 201);
    }

    public function updateWithNewTranslationSystem(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'translations' => 'array',
            'translations.*.locale' => 'required|string|size:2',
            'translations.*.name' => 'required|string',
            'translations.*.description' => 'nullable|string',
            'translations.*.placeholder' => 'nullable|string',
            'translations.*.help_text' => 'nullable|string',
            'type' => 'string|in:text,textarea,number,date,select,multiselect,checkbox,radio,file',
            'validation_rules' => 'nullable|string',
            'default_value' => 'nullable|string',
            'options' => 'required_if:type,select,multiselect,radio|array',
            'is_required' => 'boolean',
            'is_active' => 'boolean',
            'order' => 'nullable|integer',
        ]);

        $field = $this->service->updateWithNewTranslationSystem(
            $id,
            $request->except('translations'),
            $request->input('translations', [])
        );

        return response()->json([
            'message' => 'Dynamic field updated successfully',
            'data' => $field
        ]);
    }
}
