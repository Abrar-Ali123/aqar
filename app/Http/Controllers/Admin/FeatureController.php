<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreFeatureRequest;
use App\Http\Requests\Admin\UpdateFeatureRequest;
use App\Models\Feature;
use App\Services\FeatureService;

class FeatureController extends Controller
{
    protected $featureService;

    public function __construct(FeatureService $featureService)
    {
        $this->featureService = $featureService;
    }

    public function index()
    {
        $this->authorize('manage features');
        $features = $this->featureService->getPaginatedFeatures();
        return view('admin.features.index', compact('features'));
    }

    public function create()
    {
        $this->authorize('manage features');
        $languages = $this->featureService->getActiveLanguages();
        return view('admin.features.create', compact('languages'));
    }

    public function store(StoreFeatureRequest $request)
    {
        try {
            $feature = $this->featureService->createFeature($request->validated());
            return redirect()->route('admin.features.show', $feature->id)
                ->with('success', 'تم إنشاء الميزة بنجاح.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء إنشاء الميزة: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Feature $feature)
    {
        $this->authorize('manage features');
        $feature = $this->featureService->getFeatureDetails($feature);
        return view('admin.features.show', compact('feature'));
    }

    public function edit(Feature $feature)
    {
        $this->authorize('manage features');
        $languages = $this->featureService->getActiveLanguages();
        $feature = $this->featureService->getFeatureDetails($feature);
        return view('admin.features.edit', compact('feature', 'languages'));
    }

    public function update(UpdateFeatureRequest $request, Feature $feature)
    {
        try {
            $this->featureService->updateFeature($feature, $request->validated());
            return redirect()->route('admin.features.show', $feature->id)
                ->with('success', 'تم تحديث الميزة بنجاح.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تحديث الميزة: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Feature $feature)
    {
        $this->authorize('manage features');
        try {
            $this->featureService->deleteFeature($feature);
            return redirect()->route('admin.features.index')
                ->with('success', 'تم حذف الميزة بنجاح.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء حذف الميزة: ' . $e->getMessage());
        }
    }
}
