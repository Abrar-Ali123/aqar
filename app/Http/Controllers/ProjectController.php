<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Language;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('view projects')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $projects = Project::with(['translations', 'category', 'features', 'facilities'])
            ->orderBy('order')
            ->paginate(10);

        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        if (!auth()->user()->can('create projects')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $languages = Language::active()->orderBy('order')->get();
        $categories = Category::with('translations')->get();
        return view('admin.projects.create', compact('languages', 'categories'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('create projects')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $rules = $this->getValidationRules();
        $validated = $request->validate($rules);

        try {
            DB::transaction(function () use ($request, $validated) {
                // إنشاء المشروع
                $project = Project::create([
                    'category_id' => $request->category_id,
                    'location' => $request->location,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'price' => $request->price,
                    'sale_price' => $request->sale_price,
                    'units_count' => $request->units_count,
                    'is_active' => $request->boolean('is_active'),
                    'is_featured' => $request->boolean('is_featured'),
                    'show_in_home' => $request->boolean('show_in_home'),
                    'status' => $request->status ?? 'active',
                    'order' => $request->order ?? 0,
                ]);

                // حفظ الترجمات
                foreach ($validated['name'] as $locale => $name) {
                    if ($name || Language::where('code', $locale)->value('is_required')) {
                        $project->translations()->create([
                            'locale' => $locale,
                            'name' => $name,
                            'description' => $validated['description'][$locale] ?? null,
                            'short_description' => $validated['short_description'][$locale] ?? null,
                            'address' => $validated['address'][$locale] ?? null,
                            'meta_title' => $validated['meta_title'][$locale] ?? null,
                            'meta_description' => $validated['meta_description'][$locale] ?? null,
                            'slug' => $validated['slug'][$locale] ?? null,
                        ]);
                    }
                }

                // معالجة الصور
                if ($request->hasFile('images')) {
                    foreach ($request->file('images') as $image) {
                        $path = $image->store('projects/' . $project->id, 'public');
                        $project->images()->create([
                            'path' => $path,
                            'order' => $project->images()->count(),
                        ]);
                    }
                }

                // إضافة المرافق
                if ($request->has('facilities')) {
                    foreach ($request->facilities as $facilityId => $value) {
                        $project->facilities()->attach($facilityId, ['value' => $value]);
                    }
                }

                // إضافة المميزات
                if ($request->has('features')) {
                    foreach ($request->features as $featureId => $options) {
                        $project->features()->attach($featureId, [
                            'options' => json_encode($options),
                            'price' => $request->feature_prices[$featureId] ?? 0,
                        ]);
                    }
                }
            });

            return redirect()->route('admin.projects.index')
                ->with('success', __('messages.project_created_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.project_create_error'))
                ->withInput();
        }
    }

    public function edit(Project $project)
    {
        if (!auth()->user()->can('edit projects')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $languages = Language::active()->orderBy('order')->get();
        $translations = $project->translations->keyBy('locale');
        $categories = Category::with('translations')->get();
        
        // تحضير المرافق والمميزات المحددة
        $selectedFacilities = $project->facilities->pluck('pivot.value', 'id');
        $selectedFeatures = $project->features->map(function ($feature) {
            return [
                'id' => $feature->id,
                'options' => json_decode($feature->pivot->options),
                'price' => $feature->pivot->price,
            ];
        })->keyBy('id');

        return view('admin.projects.edit', compact(
            'project',
            'languages',
            'translations',
            'categories',
            'selectedFacilities',
            'selectedFeatures'
        ));
    }

    public function update(Request $request, Project $project)
    {
        if (!auth()->user()->can('edit projects')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $rules = $this->getValidationRules($project->id);
        $validated = $request->validate($rules);

        try {
            DB::transaction(function () use ($request, $project, $validated) {
                // تحديث المشروع
                $project->update([
                    'category_id' => $request->category_id,
                    'location' => $request->location,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'price' => $request->price,
                    'sale_price' => $request->sale_price,
                    'units_count' => $request->units_count,
                    'is_active' => $request->boolean('is_active'),
                    'is_featured' => $request->boolean('is_featured'),
                    'show_in_home' => $request->boolean('show_in_home'),
                    'status' => $request->status ?? $project->status,
                    'order' => $request->order ?? $project->order,
                ]);

                // تحديث الترجمات
                foreach ($validated['name'] as $locale => $name) {
                    $project->translations()->updateOrCreate(
                        ['locale' => $locale],
                        [
                            'name' => $name,
                            'description' => $validated['description'][$locale] ?? null,
                            'short_description' => $validated['short_description'][$locale] ?? null,
                            'address' => $validated['address'][$locale] ?? null,
                            'meta_title' => $validated['meta_title'][$locale] ?? null,
                            'meta_description' => $validated['meta_description'][$locale] ?? null,
                            'slug' => $validated['slug'][$locale] ?? null,
                        ]
                    );
                }

                // معالجة الصور الجديدة
                if ($request->hasFile('images')) {
                    foreach ($request->file('images') as $image) {
                        $path = $image->store('projects/' . $project->id, 'public');
                        $project->images()->create([
                            'path' => $path,
                            'order' => $project->images()->count(),
                        ]);
                    }
                }

                // حذف الصور المحددة
                if ($request->has('delete_images')) {
                    foreach ($request->delete_images as $imageId) {
                        $image = $project->images()->find($imageId);
                        if ($image) {
                            Storage::disk('public')->delete($image->path);
                            $image->delete();
                        }
                    }
                }

                // تحديث ترتيب الصور
                if ($request->has('image_order')) {
                    foreach ($request->image_order as $id => $order) {
                        $project->images()->where('id', $id)->update(['order' => $order]);
                    }
                }

                // تحديث المرافق
                $project->facilities()->detach();
                if ($request->has('facilities')) {
                    foreach ($request->facilities as $facilityId => $value) {
                        $project->facilities()->attach($facilityId, ['value' => $value]);
                    }
                }

                // تحديث المميزات
                $project->features()->detach();
                if ($request->has('features')) {
                    foreach ($request->features as $featureId => $options) {
                        $project->features()->attach($featureId, [
                            'options' => json_encode($options),
                            'price' => $request->feature_prices[$featureId] ?? 0,
                        ]);
                    }
                }
            });

            return redirect()->route('admin.projects.index')
                ->with('success', __('messages.project_updated_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.project_update_error'))
                ->withInput();
        }
    }

    public function destroy(Project $project)
    {
        if (!auth()->user()->can('delete projects')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        try {
            DB::transaction(function () use ($project) {
                // حذف الصور
                foreach ($project->images as $image) {
                    Storage::disk('public')->delete($image->path);
                }

                // حذف المشروع (سيتم حذف الترجمات والصور تلقائياً بسبب onDelete('cascade'))
                $project->delete();
            });

            return redirect()->route('admin.projects.index')
                ->with('success', __('messages.project_deleted_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.project_delete_error'));
        }
    }

    private function getValidationRules($projectId = null): array
    {
        $rules = [
            'category_id' => 'required|exists:categories,id',
            'location' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'units_count' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'show_in_home' => 'boolean',
            'status' => 'nullable|in:active,inactive,draft',
            'order' => 'nullable|integer|min:0',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'exists:project_images,id',
            'image_order' => 'nullable|array',
            'image_order.*' => 'integer|min:0',
            'facilities' => 'nullable|array',
            'facilities.*' => 'nullable|string',
            'features' => 'nullable|array',
            'features.*' => 'array',
            'feature_prices' => 'nullable|array',
            'feature_prices.*' => 'nullable|numeric|min:0',
        ];

        // إضافة قواعد التحقق للحقول المترجمة
        foreach (Language::active()->get() as $language) {
            $required = $language->is_required ? 'required' : 'nullable';
            $rules["name.{$language->code}"] = "{$required}|string|max:255";
            $rules["description.{$language->code}"] = "nullable|string";
            $rules["short_description.{$language->code}"] = "nullable|string|max:500";
            $rules["address.{$language->code}"] = "{$required}|string|max:255";
            $rules["meta_title.{$language->code}"] = "nullable|string|max:255";
            $rules["meta_description.{$language->code}"] = "nullable|string";
            $rules["slug.{$language->code}"] = "{$required}|string|max:255|unique:project_translations,slug" . 
                ($projectId ? ",{$projectId},project_id" : '');
        }

        return $rules;
    }
}
