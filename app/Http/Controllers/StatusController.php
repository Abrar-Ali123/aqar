<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatusController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('view statuses')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $statuses = Status::with(['translations'])
            ->orderBy('order')
            ->paginate(10);

        return view('admin.statuses.index', compact('statuses'));
    }

    public function create()
    {
        if (!auth()->user()->can('create statuses')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $languages = Language::active()->orderBy('order')->get();
        return view('admin.statuses.create', compact('languages'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('create statuses')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $rules = $this->getValidationRules();
        $validated = $request->validate($rules);

        try {
            DB::transaction(function () use ($request, $validated) {
                // إنشاء الحالة
                $status = Status::create([
                    'color' => $request->color,
                    'icon' => $request->icon,
                    'type' => $request->type,
                    'is_active' => $request->boolean('is_active'),
                    'is_default' => $request->boolean('is_default'),
                    'order' => $request->order ?? 0,
                ]);

                // حفظ الترجمات
                foreach ($validated['name'] as $locale => $name) {
                    if ($name || Language::where('code', $locale)->value('is_required')) {
                        $status->translations()->create([
                            'locale' => $locale,
                            'name' => $name,
                            'description' => $validated['description'][$locale] ?? null,
                        ]);
                    }
                }

                // إذا كانت الحالة افتراضية، نلغي الافتراضية من الحالات الأخرى
                if ($status->is_default) {
                    Status::where('id', '!=', $status->id)
                        ->where('type', $status->type)
                        ->update(['is_default' => false]);
                }
            });

            return redirect()->route('admin.statuses.index')
                ->with('success', __('messages.status_created_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.status_create_error'))
                ->withInput();
        }
    }

    public function edit(Status $status)
    {
        if (!auth()->user()->can('edit statuses')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $languages = Language::active()->orderBy('order')->get();
        $translations = $status->translations->keyBy('locale');

        return view('admin.statuses.edit', compact('status', 'languages', 'translations'));
    }

    public function update(Request $request, Status $status)
    {
        if (!auth()->user()->can('edit statuses')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $rules = $this->getValidationRules($status->id);
        $validated = $request->validate($rules);

        try {
            DB::transaction(function () use ($request, $status, $validated) {
                // تحديث الحالة
                $status->update([
                    'color' => $request->color,
                    'icon' => $request->icon,
                    'type' => $request->type,
                    'is_active' => $request->boolean('is_active'),
                    'is_default' => $request->boolean('is_default'),
                    'order' => $request->order ?? $status->order,
                ]);

                // تحديث الترجمات
                foreach ($validated['name'] as $locale => $name) {
                    $status->translations()->updateOrCreate(
                        ['locale' => $locale],
                        [
                            'name' => $name,
                            'description' => $validated['description'][$locale] ?? null,
                        ]
                    );
                }

                // إذا كانت الحالة افتراضية، نلغي الافتراضية من الحالات الأخرى
                if ($status->is_default) {
                    Status::where('id', '!=', $status->id)
                        ->where('type', $status->type)
                        ->update(['is_default' => false]);
                }
            });

            return redirect()->route('admin.statuses.index')
                ->with('success', __('messages.status_updated_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.status_update_error'))
                ->withInput();
        }
    }

    public function destroy(Status $status)
    {
        if (!auth()->user()->can('delete statuses')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        // لا يمكن حذف الحالة الافتراضية
        if ($status->is_default) {
            return redirect()->back()
                ->with('error', __('messages.cannot_delete_default_status'));
        }

        // التحقق من وجود عناصر مرتبطة
        if ($status->items()->exists()) {
            return redirect()->back()
                ->with('error', __('messages.status_has_items'));
        }

        try {
            // حذف الحالة (سيتم حذف الترجمات تلقائياً بسبب onDelete('cascade'))
            $status->delete();

            return redirect()->route('admin.statuses.index')
                ->with('success', __('messages.status_deleted_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.status_delete_error'));
        }
    }

    private function getValidationRules($statusId = null): array
    {
        $rules = [
            'color' => 'required|string|max:7',
            'icon' => 'nullable|string|max:50',
            'type' => 'required|string|in:project,task,user,order,payment',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
            'order' => 'nullable|integer|min:0',
        ];

        // إضافة قواعد التحقق للحقول المترجمة
        foreach (Language::active()->get() as $language) {
            $required = $language->is_required ? 'required' : 'nullable';
            $rules["name.{$language->code}"] = "{$required}|string|max:255";
            $rules["description.{$language->code}"] = "nullable|string";
        }

        return $rules;
    }
}
