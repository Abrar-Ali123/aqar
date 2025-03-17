<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use App\Models\FeatureTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeatureController extends Controller
{
    /**
     * Display a listing of the features.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $features = Feature::with('translations')->paginate(10);
        return view('dashboard.features.index', compact('features'));
    }

    /**
     * Show the form for creating a new feature.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.features.create');
    }

    /**
     * Store a newly created feature in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            // Create feature
            $feature = Feature::create([
                'icon' => $request->icon,
            ]);

            // Create translations
            FeatureTranslation::create([
                'feature_id' => $feature->id,
                'locale' => 'ar',
                'name' => $request->name_ar,
            ]);

            FeatureTranslation::create([
                'feature_id' => $feature->id,
                'locale' => 'en',
                'name' => $request->name_en,
            ]);

            DB::commit();

            return redirect()->route('admin.features.index')
                ->with('success', 'تم إضافة الميزة بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء إضافة الميزة: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified feature.
     *
     * @param  \App\Models\Feature  $feature
     * @return \Illuminate\Http\Response
     */
    public function show(Feature $feature)
    {
        $feature->load('translations');
        return view('dashboard.features.show', compact('feature'));
    }

    /**
     * Show the form for editing the specified feature.
     *
     * @param  \App\Models\Feature  $feature
     * @return \Illuminate\Http\Response
     */
    public function edit(Feature $feature)
    {
        return view('dashboard.features.edit', compact('feature'));
    }

    /**
     * Update the specified feature in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Feature  $feature
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Feature $feature)
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            // Update feature
            $feature->update([
                'icon' => $request->icon,
            ]);

            // Update translations
            $feature->translations()->where('locale', 'ar')->update([
                'name' => $request->name_ar,
            ]);

            $feature->translations()->where('locale', 'en')->update([
                'name' => $request->name_en,
            ]);

            DB::commit();

            return redirect()->route('admin.features.index')
                ->with('success', 'تم تحديث الميزة بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تحديث الميزة: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified feature from storage.
     *
     * @param  \App\Models\Feature  $feature
     * @return \Illuminate\Http\Response
     */
    public function destroy(Feature $feature)
    {
        // Check if feature has product features
        if ($feature->productFeatures()->count() > 0) {
            return redirect()->back()
                ->with('error', 'لا يمكن حذف الميزة لأنها مستخدمة في منتجات');
        }

        DB::beginTransaction();

        try {
            // Delete translations
            $feature->translations()->delete();

            // Delete feature
            $feature->delete();

            DB::commit();

            return redirect()->route('admin.features.index')
                ->with('success', 'تم حذف الميزة بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء حذف الميزة: ' . $e->getMessage());
        }
    }
}
