<?php

namespace App\Http\Controllers\FlexibleSystem;

use App\Http\Controllers\Controller;
use App\Services\FlexibleSystem\SystemComponentService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SystemComponentController extends Controller
{
    protected $service;

    public function __construct(SystemComponentService $service)
    {
        $this->service = $service;
    }

    public function index(): JsonResponse
    {
        $components = $this->service->all();
        return response()->json(['data' => $components]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:system_components',
            'type' => 'required|string|in:module,feature,workflow,form,report,ui,integration',
            'is_core' => 'boolean',
            'is_active' => 'boolean',
            'settings' => 'nullable|json',
            'requirements' => 'nullable|json',
            'order' => 'integer',
            'translations' => 'required|array',
            'translations.*.locale' => 'required|string|size:2',
            'translations.*.name' => 'required|string',
            'translations.*.description' => 'nullable|string'
        ]);

        $component = $this->service->createWithTranslations(
            $request->except('translations'),
            $request->input('translations')
        );

        return response()->json([
            'message' => 'Component created successfully',
            'data' => $component
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $component = $this->service->findById($id);
        return response()->json(['data' => $component]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'code' => 'string|max:50|unique:system_components,code,' . $id,
            'type' => 'string|in:module,feature,workflow,form,report,ui,integration',
            'is_core' => 'boolean',
            'is_active' => 'boolean',
            'settings' => 'nullable|json',
            'requirements' => 'nullable|json',
            'order' => 'integer',
            'translations' => 'array',
            'translations.*.locale' => 'required|string|size:2',
            'translations.*.name' => 'required|string',
            'translations.*.description' => 'nullable|string'
        ]);

        $component = $this->service->updateWithTranslations(
            $id,
            $request->except('translations'),
            $request->input('translations', [])
        );

        return response()->json([
            'message' => 'Component updated successfully',
            'data' => $component
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);
        return response()->json(['message' => 'Component deleted successfully']);
    }

    public function getActiveComponents(): JsonResponse
    {
        $components = $this->service->getActiveComponents();
        return response()->json(['data' => $components]);
    }

    public function getCoreComponents(): JsonResponse
    {
        $components = $this->service->getCoreComponents();
        return response()->json(['data' => $components]);
    }

    public function getByType(string $type): JsonResponse
    {
        $components = $this->service->getByType($type);
        return response()->json(['data' => $components]);
    }

    public function storeWithNewTranslationSystem(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'translations' => 'required|array',
            'translations.*.name' => 'required|string',
            'translations.*.description' => 'nullable|string',
            'type' => 'required|string|in:module,feature,workflow,form,report,ui,integration',
            'is_core' => 'boolean',
            'is_active' => 'boolean',
            'settings' => 'nullable|json',
            'requirements' => 'nullable|json',
            'order' => 'integer',
        ]);

        $component = $this->service->create([
            'type' => $request->type,
            'is_core' => $request->boolean('is_core'),
            'is_active' => $request->boolean('is_active'),
            'settings' => $request->input('settings'),
            'requirements' => $request->input('requirements'),
            'order' => $request->input('order'),
        ]);

        foreach (array_keys(config('app.locales')) as $locale) {
            $this->service->createTranslation(
                $component->id,
                $locale,
                $request->input("translations.{$locale}.name"),
                $request->input("translations.{$locale}.description", '')
            );
        }

        return response()->json([
            'message' => 'Component created successfully',
            'data' => $component
        ], 201);
    }

    public function updateWithNewTranslationSystem(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'translations' => 'array',
            'translations.*.name' => 'required|string',
            'translations.*.description' => 'nullable|string',
            'type' => 'string|in:module,feature,workflow,form,report,ui,integration',
            'is_core' => 'boolean',
            'is_active' => 'boolean',
            'settings' => 'nullable|json',
            'requirements' => 'nullable|json',
            'order' => 'integer',
        ]);

        $component = $this->service->update(
            $id,
            [
                'type' => $request->type,
                'is_core' => $request->boolean('is_core'),
                'is_active' => $request->boolean('is_active'),
                'settings' => $request->input('settings'),
                'requirements' => $request->input('requirements'),
                'order' => $request->input('order'),
            ]
        );

        foreach (array_keys(config('app.locales')) as $locale) {
            $this->service->updateTranslation(
                $component->id,
                $locale,
                $request->input("translations.{$locale}.name"),
                $request->input("translations.{$locale}.description", '')
            );
        }

        return response()->json([
            'message' => 'Component updated successfully',
            'data' => $component
        ]);
    }
}
