<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Language;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('view roles')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $roles = Role::with(['translations', 'permissions'])
            ->orderBy('order')
            ->paginate(10);

        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        if (!auth()->user()->can('create roles')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $languages = Language::active()->orderBy('order')->get();
        $permissions = Permission::all();
        return view('admin.roles.create', compact('languages', 'permissions'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('create roles')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $rules = $this->getValidationRules();
        $validated = $request->validate($rules);

        try {
            DB::transaction(function () use ($request, $validated) {
                // إنشاء الدور
                $role = Role::create([
                    'guard_name' => 'web',
                    'is_admin' => $request->boolean('is_admin'),
                    'is_default' => $request->boolean('is_default'),
                    'status' => $request->status ?? 'active',
                    'order' => $request->order ?? 0,
                ]);

                // حفظ الترجمات
                foreach ($validated['name'] as $locale => $name) {
                    if ($name || Language::where('code', $locale)->value('is_required')) {
                        $role->translations()->create([
                            'locale' => $locale,
                            'name' => $name,
                            'description' => $validated['description'][$locale] ?? null,
                        ]);
                    }
                }

                // إضافة الصلاحيات
                if ($request->has('permissions')) {
                    $role->permissions()->sync($request->permissions);
                }

                // إذا كان الدور افتراضياً، نجعل باقي الأدوار غير افتراضية
                if ($role->is_default) {
                    Role::where('id', '!=', $role->id)
                        ->update(['is_default' => false]);
                }
            });

            return redirect()->route('admin.roles.index')
                ->with('success', __('messages.role_created_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.role_create_error'))
                ->withInput();
        }
    }

    public function edit(Role $role)
    {
        if (!auth()->user()->can('edit roles')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $languages = Language::active()->orderBy('order')->get();
        $translations = $role->translations->keyBy('locale');
        $permissions = Permission::all();
        $selectedPermissions = $role->permissions->pluck('id')->toArray();

        return view('admin.roles.edit', compact('role', 'languages', 'translations', 'permissions', 'selectedPermissions'));
    }

    public function update(Request $request, Role $role)
    {
        if (!auth()->user()->can('edit roles')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $rules = $this->getValidationRules($role->id);
        $validated = $request->validate($rules);

        try {
            DB::transaction(function () use ($request, $role, $validated) {
                // تحديث الدور
                $role->update([
                    'is_admin' => $request->boolean('is_admin'),
                    'is_default' => $request->boolean('is_default'),
                    'status' => $request->status ?? $role->status,
                    'order' => $request->order ?? $role->order,
                ]);

                // تحديث الترجمات
                foreach ($validated['name'] as $locale => $name) {
                    $role->translations()->updateOrCreate(
                        ['locale' => $locale],
                        [
                            'name' => $name,
                            'description' => $validated['description'][$locale] ?? null,
                        ]
                    );
                }

                // تحديث الصلاحيات
                if ($request->has('permissions')) {
                    $role->permissions()->sync($request->permissions);
                } else {
                    $role->permissions()->detach();
                }

                // إذا كان الدور افتراضياً، نجعل باقي الأدوار غير افتراضية
                if ($role->is_default) {
                    Role::where('id', '!=', $role->id)
                        ->update(['is_default' => false]);
                }
                // إذا كان الدور الوحيد، نجعله افتراضياً
                elseif (Role::count() === 1) {
                    $role->update(['is_default' => true]);
                }
            });

            return redirect()->route('admin.roles.index')
                ->with('success', __('messages.role_updated_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.role_update_error'))
                ->withInput();
        }
    }

    public function destroy(Role $role)
    {
        if (!auth()->user()->can('delete roles')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        // لا يمكن حذف الدور الافتراضي
        if ($role->is_default) {
            return redirect()->back()
                ->with('error', __('messages.cannot_delete_default_role'));
        }

        // التحقق من عدم وجود مستخدمين
        if ($role->users()->exists()) {
            return redirect()->back()
                ->with('error', __('messages.role_has_users'));
        }

        try {
            DB::transaction(function () use ($role) {
                // حذف الدور (سيتم حذف الترجمات تلقائياً بسبب onDelete('cascade'))
                $role->delete();

                // إذا كان هذا آخر دور، نجعل أول دور متبقي هو الافتراضي
                if (Role::count() === 1) {
                    Role::first()->update(['is_default' => true]);
                }
            });

            return redirect()->route('admin.roles.index')
                ->with('success', __('messages.role_deleted_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.role_delete_error'));
        }
    }

    private function getValidationRules($roleId = null): array
    {
        $rules = [
            'is_admin' => 'boolean',
            'is_default' => 'boolean',
            'status' => 'nullable|in:active,inactive',
            'order' => 'nullable|integer|min:0',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
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
