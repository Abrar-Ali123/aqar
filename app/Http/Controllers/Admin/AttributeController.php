<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttributeController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('view attributes')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $attributes = Attribute::with(['translations', 'category'])
            ->orderBy('order')
            ->paginate(10);

        return view('admin.attributes.index', compact('attributes'));
    }

    public function create()
    {
        if (!auth()->user()->can('create attributes')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $languages = Language::active()->orderBy('order')->get();
        return view('admin.attributes.create', compact('languages'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('create attributes')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $rules = $this->getValidationRules();
        $validated = $request->validate($rules);

        try {
            DB::transaction(function () use ($request, $validated) {
                // إنشاء السمة
                $attribute = Attribute::create([
                    'category_id' => $request->category_id,
                    'type' => $request->type,
                    'validation_rules' => $request->validation_rules,
                    'is_required' => $request->boolean('is_required'),
                    'is_filterable' => $request->boolean('is_filterable'),
                    'is_searchable' => $request->boolean('is_searchable'),
                    'show_in_list' => $request->boolean('show_in_list'),
                    'show_in_details' => $request->boolean('show_in_details'),
                    'status' => $request->status ?? 'active',
                    'order' => $request->order ?? 0,
                ]);

                // حفظ الترجمات
                foreach ($validated['name'] as $locale => $name) {
                    if ($name || Language::where('code', $locale)->value('is_required')) {
                        $attribute->translations()->create([
                            'locale' => $locale,
                            'name' => $name,
                            'description' => $validated['description'][$locale] ?? null,
                            'placeholder' => $validated['placeholder'][$locale] ?? null,
                            'help_text' => $validated['help_text'][$locale] ?? null,
                        ]);
                    }
                }

                // إضافة الخيارات
                if ($request->has('options') && in_array($request->type, ['select', 'multiselect', 'radio', 'checkbox'])) {
                    foreach ($request->options as $option) {
                        $attributeOption = $attribute->options()->create([
                            'value' => $option['value'],
                            'is_default' => $option['is_default'] ?? false,
                            'order' => $option['order'] ?? 0,
                        ]);

                        // حفظ ترجمات الخيارات
                        foreach ($option['label'] as $locale => $label) {
                            if ($label || Language::where('code', $locale)->value('is_required')) {
                                $attributeOption->translations()->create([
                                    'locale' => $locale,
                                    'label' => $label,
                                ]);
                            }
                        }
                    }
                }
            });

            return redirect()->route('admin.attributes.index')
                ->with('success', __('messages.attribute_created_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.attribute_create_error'))
                ->withInput();
        }
    }

    public function edit(Attribute $attribute)
    {
        if (!auth()->user()->can('edit attributes')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $languages = Language::active()->orderBy('order')->get();
        $translations = $attribute->translations->keyBy('locale');
        $optionTranslations = $attribute->options->load('translations')
            ->map(function ($option) {
                return [
                    'id' => $option->id,
                    'translations' => $option->translations->keyBy('locale'),
                ];
            })
            ->keyBy('id');

        return view('admin.attributes.edit', compact('attribute', 'languages', 'translations', 'optionTranslations'));
    }

    public function update(Request $request, Attribute $attribute)
    {
        if (!auth()->user()->can('edit attributes')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $rules = $this->getValidationRules($attribute->id);
        $validated = $request->validate($rules);

        try {
            DB::transaction(function () use ($request, $attribute, $validated) {
                // تحديث السمة
                $attribute->update([
                    'category_id' => $request->category_id,
                    'type' => $request->type,
                    'validation_rules' => $request->validation_rules,
                    'is_required' => $request->boolean('is_required'),
                    'is_filterable' => $request->boolean('is_filterable'),
                    'is_searchable' => $request->boolean('is_searchable'),
                    'show_in_list' => $request->boolean('show_in_list'),
                    'show_in_details' => $request->boolean('show_in_details'),
                    'status' => $request->status ?? $attribute->status,
                    'order' => $request->order ?? $attribute->order,
                ]);

                // تحديث الترجمات
                foreach ($validated['name'] as $locale => $name) {
                    $attribute->translations()->updateOrCreate(
                        ['locale' => $locale],
                        [
                            'name' => $name,
                            'description' => $validated['description'][$locale] ?? null,
                            'placeholder' => $validated['placeholder'][$locale] ?? null,
                            'help_text' => $validated['help_text'][$locale] ?? null,
                        ]
                    );
                }

                // تحديث الخيارات
                if ($request->has('options') && in_array($request->type, ['select', 'multiselect', 'radio', 'checkbox'])) {
                    // حذف الخيارات غير الموجودة في الطلب
                    $attribute->options()
                        ->whereNotIn('id', collect($request->options)->pluck('id')->filter())
                        ->delete();

                    foreach ($request->options as $option) {
                        $attributeOption = $attribute->options()->updateOrCreate(
                            ['id' => $option['id'] ?? null],
                            [
                                'value' => $option['value'],
                                'is_default' => $option['is_default'] ?? false,
                                'order' => $option['order'] ?? 0,
                            ]
                        );

                        // تحديث ترجمات الخيارات
                        foreach ($option['label'] as $locale => $label) {
                            $attributeOption->translations()->updateOrCreate(
                                ['locale' => $locale],
                                ['label' => $label]
                            );
                        }
                    }
                } else {
                    // حذف جميع الخيارات إذا تم تغيير نوع السمة
                    $attribute->options()->delete();
                }
            });

            return redirect()->route('admin.attributes.index')
                ->with('success', __('messages.attribute_updated_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.attribute_update_error'))
                ->withInput();
        }
    }

    public function destroy(Attribute $attribute)
    {
        if (!auth()->user()->can('delete attributes')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        // التحقق من استخدام السمة
        if ($attribute->products()->exists() || $attribute->categories()->exists()) {
            return redirect()->back()
                ->with('error', __('messages.attribute_in_use'));
        }

        try {
            DB::transaction(function () use ($attribute) {
                // حذف السمة (سيتم حذف الترجمات والخيارات تلقائياً بسبب onDelete('cascade'))
                $attribute->delete();
            });

            return redirect()->route('admin.attributes.index')
                ->with('success', __('messages.attribute_deleted_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.attribute_delete_error'));
        }
    }

    private function getValidationRules($attributeId = null): array
    {
        $rules = [
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|in:text,textarea,number,date,select,multiselect,radio,checkbox,file,color',
            'validation_rules' => 'nullable|string',
            'is_required' => 'boolean',
            'is_filterable' => 'boolean',
            'is_searchable' => 'boolean',
            'show_in_list' => 'boolean',
            'show_in_details' => 'boolean',
            'status' => 'nullable|in:active,inactive',
            'order' => 'nullable|integer|min:0',
            'options' => 'required_if:type,select,multiselect,radio,checkbox|array',
            'options.*.id' => 'nullable|exists:attribute_options,id',
            'options.*.value' => 'required|string|max:50',
            'options.*.is_default' => 'boolean',
            'options.*.order' => 'nullable|integer|min:0',
        ];

        // إضافة قواعد التحقق للحقول المترجمة
        foreach (Language::active()->get() as $language) {
            $required = $language->is_required ? 'required' : 'nullable';
            $rules["name.{$language->code}"] = "{$required}|string|max:255";
            $rules["description.{$language->code}"] = "nullable|string";
            $rules["placeholder.{$language->code}"] = "nullable|string|max:255";
            $rules["help_text.{$language->code}"] = "nullable|string";

            // قواعد التحقق لترجمات الخيارات
            $rules["options.*.label.{$language->code}"] = "{$required}|string|max:255";
        }

        return $rules;
    }
}
