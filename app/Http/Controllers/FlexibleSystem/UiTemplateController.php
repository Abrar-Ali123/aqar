<?php

namespace App\Http\Controllers\FlexibleSystem;

use App\Http\Controllers\Controller;
use App\Services\FlexibleSystem\UiTemplateService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UiTemplateController extends Controller
{
    protected $service;

    public function __construct(UiTemplateService $service)
    {
        $this->service = $service;
    }

    public function index(): JsonResponse
    {
        $templates = $this->service->all();
        return response()->json(['data' => $templates]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:ui_templates',
            'layout' => 'required|json',
            'components' => 'nullable|json',
            'styles' => 'nullable|json',
            'behaviors' => 'nullable|json',
            'responsive_settings' => 'nullable|json',
            'is_active' => 'boolean',
            'order' => 'integer',
            'translations' => 'required|array',
            'translations.*.locale' => 'required|string|size:2',
            'translations.*.name' => 'required|string',
            'translations.*.description' => 'nullable|string'
        ]);

        $template = $this->service->createWithTranslations(
            $request->except('translations'),
            $request->input('translations')
        );

        return response()->json([
            'message' => 'UI template created successfully',
            'data' => $template
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $template = $this->service->findById($id);
        return response()->json(['data' => $template]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'code' => 'string|max:50|unique:ui_templates,code,' . $id,
            'layout' => 'json',
            'components' => 'nullable|json',
            'styles' => 'nullable|json',
            'behaviors' => 'nullable|json',
            'responsive_settings' => 'nullable|json',
            'is_active' => 'boolean',
            'order' => 'integer',
            'translations' => 'array',
            'translations.*.locale' => 'required|string|size:2',
            'translations.*.name' => 'required|string',
            'translations.*.description' => 'nullable|string'
        ]);

        $template = $this->service->updateWithTranslations(
            $id,
            $request->except('translations'),
            $request->input('translations', [])
        );

        return response()->json([
            'message' => 'UI template updated successfully',
            'data' => $template
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);
        return response()->json(['message' => 'UI template deleted successfully']);
    }

    public function getActiveTemplates(): JsonResponse
    {
        $templates = $this->service->getActiveTemplates();
        return response()->json(['data' => $templates]);
    }

    public function findByComponents(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'components' => 'required|array'
        ]);

        $templates = $this->service->findByComponents($request->input('components'));
        return response()->json(['data' => $templates]);
    }

    public function getResponsiveTemplates(): JsonResponse
    {
        $templates = $this->service->getResponsiveTemplates();
        return response()->json(['data' => $templates]);
    }

    public function renderTemplate(Request $request, int $id): JsonResponse
    {
        $template = $this->service->findById($id);
        $data = $request->input('data', []);

        $html = $this->service->renderTemplate($template, $data);

        return response()->json([
            'html' => $html
        ]);
    }

    public function validateTemplate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'template' => 'required|array'
        ]);

        $result = $this->service->validateTemplate($request->input('template'));

        return response()->json($result);
    }

    public function storeWithNewTranslationSystem(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'translations' => 'required|array',
            'translations.*.name' => 'required|string',
            'translations.*.description' => 'nullable|string',
            'component_type' => 'required|string|in:form,list,detail',
            'template_data' => 'required|json',
            'is_active' => 'boolean',
        ]);

        try {
            $template = $this->service->create([
                'component_type' => $request->component_type,
                'template_data' => json_decode($request->template_data, true),
                'is_active' => $request->boolean('is_active'),
            ]);

            foreach (array_keys(config('app.locales')) as $locale) {
                $translation = $template->translations()->where('locale', $locale)->firstOrCreate([]);
                $translation->name = $request->input("translations.{$locale}.name");
                $translation->description = $request->input("translations.{$locale}.description", '');
                $translation->save();
            }

            return response()->json([
                'message' => 'UI template created successfully',
                'data' => $template
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating UI template',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateWithNewTranslationSystem(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'translations' => 'required|array',
            'translations.*.name' => 'required|string',
            'translations.*.description' => 'nullable|string',
            'component_type' => 'required|string|in:form,list,detail',
            'template_data' => 'required|json',
            'is_active' => 'boolean',
        ]);

        try {
            $template = $this->service->update(
                $id,
                [
                    'component_type' => $request->component_type,
                    'template_data' => json_decode($request->template_data, true),
                    'is_active' => $request->boolean('is_active'),
                ]
            );

            foreach (array_keys(config('app.locales')) as $locale) {
                $translation = $template->translations()->where('locale', $locale)->firstOrCreate([]);
                $translation->name = $request->input("translations.{$locale}.name");
                $translation->description = $request->input("translations.{$locale}.description", '');
                $translation->save();
            }

            return response()->json([
                'message' => 'UI template updated successfully',
                'data' => $template
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating UI template',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
