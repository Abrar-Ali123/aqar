<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FeatureController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('view features')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $features = Feature::with(['translations', 'category'])
            ->orderBy('order')
            ->paginate(10);

        return view('admin.features.index', compact('features'));
    }

    public function create()
    {
        if (!auth()->user()->can('create features')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $languages = Language::active()->orderBy('order')->get();
        return view('admin.features.create', compact('languages'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('create features')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $rules = $this->getValidationRules();
        $validated = $request->validate($rules);

        try {
            DB::transaction(function () use ($request, $validated) {
                // إنشاء الميزة
                $feature = Feature::create([
                    'category_id' => $request->category_id,
                    'icon' => $request->icon,
                    'icon_type' => $request->icon_type,
                    'is_required' => $request->boolean('is_required'),
                    'is_filterable' => $request->boolean('is_filterable'),
                    'show_in_list' => $request->boolean('show_in_list'),
                    'show_in_details' => $request->boolean('show_in_details'),
                    'status' => $request->status ?? 'active',
                    'order' => $request->order ?? 0,
                ]);

                // معالجة الصورة المخصصة
                if ($request->hasFile('custom_icon')) {
                    $path = $request->file('custom_icon')->store('features/icons', 'public');
                    $feature->update(['custom_icon' => $path]);
                }

                // حفظ الترجمات
                foreach ($validated['name'] as $locale => $name) {
                    if ($name || Language::where('code', $locale)->value('is_required')) {
                        $feature->translations()->create([
                            'locale' => $locale,
                            'name' => $name,
                            'description' => $validated['description'][$locale] ?? null,
                            'help_text' => $validated['help_text'][$locale] ?? null,
                        ]);
                    }
                }

                // إضافة الخيارات
                if ($request->has('options')) {
                    foreach ($request->options as $option) {
                        $featureOption = $feature->options()->create([
                            'value' => $option['value'],
                            'price' => $option['price'] ?? 0,
                            'is_default' => $option['is_default'] ?? false,
                            'order' => $option['order'] ?? 0,
                        ]);

                        // حفظ ترجمات الخيارات
                        foreach ($option['label'] as $locale => $label) {
                            if ($label || Language::where('code', $locale)->value('is_required')) {
                                $featureOption->translations()->create([
                                    'locale' => $locale,
                                    'label' => $label,
                                ]);
                            }
                        }
                    }
                }
            });

            return redirect()->route('admin.features.index')
                ->with('success', __('messages.feature_created_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.feature_create_error'))
                ->withInput();
        }
    }

    public function edit(Feature $feature)
    {
        if (!auth()->user()->can('edit features')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $languages = Language::active()->orderBy('order')->get();
        $translations = $feature->translations->keyBy('locale');
        $optionTranslations = $feature->options->load('translations')
            ->map(function ($option) {
                return [
                    'id' => $option->id,
                    'translations' => $option->translations->keyBy('locale'),
                ];
            })
            ->keyBy('id');

        return view('admin.features.edit', compact('feature', 'languages', 'translations', 'optionTranslations'));
    }

    public function update(Request $request, Feature $feature)
    {
        if (!auth()->user()->can('edit features')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $rules = $this->getValidationRules($feature->id);
        $validated = $request->validate($rules);

        try {
            DB::transaction(function () use ($request, $feature, $validated) {
                // تحديث الميزة
                $feature->update([
                    'category_id' => $request->category_id,
                    'icon' => $request->icon,
                    'icon_type' => $request->icon_type,
                    'is_required' => $request->boolean('is_required'),
                    'is_filterable' => $request->boolean('is_filterable'),
                    'show_in_list' => $request->boolean('show_in_list'),
                    'show_in_details' => $request->boolean('show_in_details'),
                    'status' => $request->status ?? $feature->status,
                    'order' => $request->order ?? $feature->order,
                ]);

                // معالجة الصورة المخصصة
                if ($request->hasFile('custom_icon')) {
                    // حذف الصورة القديمة
                    if ($feature->custom_icon) {
                        Storage::disk('public')->delete($feature->custom_icon);
                    }
                    
                    $path = $request->file('custom_icon')->store('features/icons', 'public');
                    $feature->update(['custom_icon' => $path]);
                }

                // تحديث الترجمات
                foreach ($validated['name'] as $locale => $name) {
                    $feature->translations()->updateOrCreate(
                        ['locale' => $locale],
                        [
                            'name' => $name,
                            'description' => $validated['description'][$locale] ?? null,
                            'help_text' => $validated['help_text'][$locale] ?? null,
                        ]
                    );
                }

                // تحديث الخيارات
                if ($request->has('options')) {
                    // حذف الخيارات غير الموجودة في الطلب
                    $feature->options()
                        ->whereNotIn('id', collect($request->options)->pluck('id')->filter())
                        ->delete();

                    foreach ($request->options as $option) {
                        $featureOption = $feature->options()->updateOrCreate(
                            ['id' => $option['id'] ?? null],
                            [
                                'value' => $option['value'],
                                'price' => $option['price'] ?? 0,
                                'is_default' => $option['is_default'] ?? false,
                                'order' => $option['order'] ?? 0,
                            ]
                        );

                        // تحديث ترجمات الخيارات
                        foreach ($option['label'] as $locale => $label) {
                            $featureOption->translations()->updateOrCreate(
                                ['locale' => $locale],
                                ['label' => $label]
                            );
                        }
                    }
                } else {
                    // حذف جميع الخيارات إذا لم يتم تحديد أي خيارات
                    $feature->options()->delete();
                }
            });

            return redirect()->route('admin.features.index')
                ->with('success', __('messages.feature_updated_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.feature_update_error'))
                ->withInput();
        }
    }

    public function destroy(Feature $feature)
    {
        if (!auth()->user()->can('delete features')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        // التحقق من استخدام الميزة
        if ($feature->products()->exists()) {
            return redirect()->back()
                ->with('error', __('messages.feature_in_use'));
        }

        try {
            DB::transaction(function () use ($feature) {
                // حذف الصورة المخصصة
                if ($feature->custom_icon) {
                    Storage::disk('public')->delete($feature->custom_icon);
                }

                // حذف الميزة (سيتم حذف الترجمات والخيارات تلقائياً بسبب onDelete('cascade'))
                $feature->delete();
            });

            return redirect()->route('admin.features.index')
                ->with('success', __('messages.feature_deleted_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.feature_delete_error'));
        }
    }

    private function getValidationRules($featureId = null): array
    {
        $rules = [
            'category_id' => 'required|exists:categories,id',
            'icon' => 'nullable|string|max:50',
            'icon_type' => 'required|in:font,custom',
            'custom_icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_required' => 'boolean',
            'is_filterable' => 'boolean',
            'show_in_list' => 'boolean',
            'show_in_details' => 'boolean',
            'status' => 'nullable|in:active,inactive',
            'order' => 'nullable|integer|min:0',
            'options' => 'nullable|array',
            'options.*.id' => 'nullable|exists:feature_options,id',
            'options.*.value' => 'required|string|max:50',
            'options.*.price' => 'nullable|numeric|min:0',
            'options.*.is_default' => 'boolean',
            'options.*.order' => 'nullable|integer|min:0',
        ];

        // إضافة قواعد التحقق للحقول المترجمة
        foreach (Language::active()->get() as $language) {
            $required = $language->is_required ? 'required' : 'nullable';
            $rules["name.{$language->code}"] = "{$required}|string|max:255";
            $rules["description.{$language->code}"] = "nullable|string";
            $rules["help_text.{$language->code}"] = "nullable|string";

            // قواعد التحقق لترجمات الخيارات
            $rules["options.*.label.{$language->code}"] = "{$required}|string|max:255";
        }

        return $rules;
    }
}
