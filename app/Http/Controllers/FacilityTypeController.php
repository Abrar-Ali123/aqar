<?php

namespace App\Http\Controllers;

use App\Models\FacilityType;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FacilityTypeController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('view facility_types')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $types = FacilityType::with(['translations', 'facilities'])
            ->orderBy('order')
            ->paginate(10);

        return view('admin.facility-types.index', compact('types'));
    }

    public function create()
    {
        if (!auth()->user()->can('create facility_types')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $languages = Language::active()->orderBy('order')->get();
        return view('admin.facility-types.create', compact('languages'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('create facility_types')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $rules = $this->getValidationRules();
        $validated = $request->validate($rules);

        try {
            DB::transaction(function () use ($request, $validated) {
                // إنشاء نوع المرفق
                $type = FacilityType::create([
                    'icon_id' => $request->icon_id,
                    'is_active' => $request->boolean('is_active'),
                    'status' => $request->status ?? 'active',
                    'order' => $request->order ?? 0,
                ]);

                // حفظ الترجمات
                foreach ($validated['name'] as $locale => $name) {
                    if ($name || Language::where('code', $locale)->value('is_required')) {
                        $type->translations()->create([
                            'locale' => $locale,
                            'name' => $name,
                            'description' => $validated['description'][$locale] ?? null,
                            'meta_title' => $validated['meta_title'][$locale] ?? null,
                            'meta_description' => $validated['meta_description'][$locale] ?? null,
                            'slug' => $validated['slug'][$locale] ?? null,
                        ]);
                    }
                }
            });

            return redirect()->route('admin.facility-types.index')
                ->with('success', __('messages.facility_type_created_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.facility_type_create_error'))
                ->withInput();
        }
    }

    public function edit(FacilityType $type)
    {
        if (!auth()->user()->can('edit facility_types')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $languages = Language::active()->orderBy('order')->get();
        $translations = $type->translations->keyBy('locale');

        return view('admin.facility-types.edit', compact('type', 'languages', 'translations'));
    }

    public function update(Request $request, FacilityType $type)
    {
        if (!auth()->user()->can('edit facility_types')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $rules = $this->getValidationRules($type->id);
        $validated = $request->validate($rules);

        try {
            DB::transaction(function () use ($request, $type, $validated) {
                // تحديث نوع المرفق
                $type->update([
                    'icon_id' => $request->icon_id,
                    'is_active' => $request->boolean('is_active'),
                    'status' => $request->status ?? $type->status,
                    'order' => $request->order ?? $type->order,
                ]);

                // تحديث الترجمات
                foreach ($validated['name'] as $locale => $name) {
                    $type->translations()->updateOrCreate(
                        ['locale' => $locale],
                        [
                            'name' => $name,
                            'description' => $validated['description'][$locale] ?? null,
                            'meta_title' => $validated['meta_title'][$locale] ?? null,
                            'meta_description' => $validated['meta_description'][$locale] ?? null,
                            'slug' => $validated['slug'][$locale] ?? null,
                        ]
                    );
                }
            });

            return redirect()->route('admin.facility-types.index')
                ->with('success', __('messages.facility_type_updated_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.facility_type_update_error'))
                ->withInput();
        }
    }

    public function destroy(FacilityType $type)
    {
        if (!auth()->user()->can('delete facility_types')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        // التحقق من وجود مرافق مرتبطة
        if ($type->facilities()->exists()) {
            return redirect()->back()
                ->with('error', __('messages.facility_type_has_facilities'));
        }

        try {
            // حذف نوع المرفق (سيتم حذف الترجمات تلقائياً بسبب onDelete('cascade'))
            $type->delete();

            return redirect()->route('admin.facility-types.index')
                ->with('success', __('messages.facility_type_deleted_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.facility_type_delete_error'));
        }
    }

    private function getValidationRules($typeId = null): array
    {
        $rules = [
            'icon_id' => 'nullable|exists:icons,id',
            'is_active' => 'boolean',
            'status' => 'nullable|in:active,inactive,draft',
            'order' => 'nullable|integer|min:0',
        ];

        // إضافة قواعد التحقق للحقول المترجمة
        foreach (Language::active()->get() as $language) {
            $required = $language->is_required ? 'required' : 'nullable';
            $rules["name.{$language->code}"] = "{$required}|string|max:255";
            $rules["description.{$language->code}"] = "nullable|string";
            $rules["meta_title.{$language->code}"] = "nullable|string|max:255";
            $rules["meta_description.{$language->code}"] = "nullable|string";
            $rules["slug.{$language->code}"] = "{$required}|string|max:255|unique:facility_type_translations,slug" . 
                ($typeId ? ",{$typeId},facility_type_id" : '');
        }

        return $rules;
    }
}
