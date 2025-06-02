<?php

namespace App\Http\Controllers\FlexibleSystem;

use App\Http\Controllers\Controller;
use App\Services\FlexibleSystem\BusinessRuleService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BusinessRuleController extends Controller
{
    protected $service;

    public function __construct(BusinessRuleService $service)
    {
        $this->service = $service;
    }

    public function index(): JsonResponse
    {
        $rules = $this->service->all();
        return response()->json(['data' => $rules]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:business_rules',
            'conditions' => 'required|json',
            'actions' => 'required|json',
            'priority' => 'integer',
            'is_active' => 'boolean',
            'error_handling' => 'nullable|json',
            'logging_settings' => 'nullable|json',
            'translations' => 'required|array',
            'translations.*.locale' => 'required|string|size:2',
            'translations.*.name' => 'required|string',
            'translations.*.description' => 'nullable|string',
            'translations.*.error_message' => 'nullable|string',
            'translations.*.success_message' => 'nullable|string'
        ]);

        $rule = $this->service->createWithTranslations(
            $request->except('translations'),
            $request->input('translations')
        );

        foreach (array_keys(config('app.locales')) as $locale) {
            $translation = $rule->translations()->where('locale', $locale)->firstOrCreate([]);
            $translation->name = $request->input("translations.{$locale}.name");
            $translation->description = $request->input("translations.{$locale}.description", '');
            $translation->save();
        }

        return response()->json([
            'message' => 'Business rule created successfully',
            'data' => $rule
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $rule = $this->service->findById($id);
        return response()->json(['data' => $rule]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'code' => 'string|max:50|unique:business_rules,code,' . $id,
            'conditions' => 'json',
            'actions' => 'json',
            'priority' => 'integer',
            'is_active' => 'boolean',
            'error_handling' => 'nullable|json',
            'logging_settings' => 'nullable|json',
            'translations' => 'array',
            'translations.*.locale' => 'required|string|size:2',
            'translations.*.name' => 'required|string',
            'translations.*.description' => 'nullable|string',
            'translations.*.error_message' => 'nullable|string',
            'translations.*.success_message' => 'nullable|string'
        ]);

        $rule = $this->service->updateWithTranslations(
            $id,
            $request->except('translations'),
            $request->input('translations', [])
        );

        foreach (array_keys(config('app.locales')) as $locale) {
            $translation = $rule->translations()->where('locale', $locale)->firstOrCreate([]);
            $translation->name = $request->input("translations.{$locale}.name");
            $translation->description = $request->input("translations.{$locale}.description", '');
            $translation->save();
        }

        return response()->json([
            'message' => 'Business rule updated successfully',
            'data' => $rule
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);
        return response()->json(['message' => 'Business rule deleted successfully']);
    }

    public function getActiveRules(): JsonResponse
    {
        $rules = $this->service->getActiveRules();
        return response()->json(['data' => $rules]);
    }

    public function getByPriorityRange(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'min_priority' => 'required|integer',
            'max_priority' => 'required|integer|gte:min_priority'
        ]);

        $rules = $this->service->getByPriorityRange(
            $request->input('min_priority'),
            $request->input('max_priority')
        );

        return response()->json(['data' => $rules]);
    }

    public function evaluateRules(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'context' => 'required|array'
        ]);

        $results = $this->service->evaluateRules($request->input('context'));

        return response()->json(['data' => $results]);
    }
}
