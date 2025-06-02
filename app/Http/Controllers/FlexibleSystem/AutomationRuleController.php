<?php

namespace App\Http\Controllers\FlexibleSystem;

use App\Http\Controllers\Controller;
use App\Services\FlexibleSystem\AutomationRuleService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AutomationRuleController extends Controller
{
    protected $service;

    public function __construct(AutomationRuleService $service)
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
            'code' => 'required|string|max:50|unique:automation_rules',
            'trigger_event' => 'required|string',
            'conditions' => 'nullable|json',
            'actions' => 'required|json',
            'schedule' => 'nullable|json',
            'retry_policy' => 'nullable|json',
            'is_active' => 'boolean',
            'priority' => 'integer',
            'translations' => 'required|array',
            'translations.*.locale' => 'required|string|size:2',
            'translations.*.name' => 'required|string',
            'translations.*.description' => 'nullable|string',
            'translations.*.success_message' => 'nullable|string',
            'translations.*.error_message' => 'nullable|string'
        ]);

        $rule = $this->service->createWithTranslations(
            $request->except('translations'),
            $request->input('translations')
        );

        return response()->json([
            'message' => 'Automation rule created successfully',
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
            'code' => 'string|max:50|unique:automation_rules,code,' . $id,
            'trigger_event' => 'string',
            'conditions' => 'nullable|json',
            'actions' => 'json',
            'schedule' => 'nullable|json',
            'retry_policy' => 'nullable|json',
            'is_active' => 'boolean',
            'priority' => 'integer',
            'translations' => 'array',
            'translations.*.locale' => 'required|string|size:2',
            'translations.*.name' => 'required|string',
            'translations.*.description' => 'nullable|string',
            'translations.*.success_message' => 'nullable|string',
            'translations.*.error_message' => 'nullable|string'
        ]);

        $rule = $this->service->updateWithTranslations(
            $id,
            $request->except('translations'),
            $request->input('translations', [])
        );

        return response()->json([
            'message' => 'Automation rule updated successfully',
            'data' => $rule
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);
        return response()->json(['message' => 'Automation rule deleted successfully']);
    }

    public function getActiveRules(): JsonResponse
    {
        $rules = $this->service->getActiveRules();
        return response()->json(['data' => $rules]);
    }

    public function getByTriggerEvent(string $event): JsonResponse
    {
        $rules = $this->service->getByTriggerEvent($event);
        return response()->json(['data' => $rules]);
    }

    public function getScheduledRules(): JsonResponse
    {
        $rules = $this->service->getScheduledRules();
        return response()->json(['data' => $rules]);
    }

    public function executeRule(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'context' => 'required|array'
        ]);

        $rule = $this->service->findById($id);
        $result = $this->service->executeRule($rule, $request->input('context'));

        return response()->json($result);
    }
}
