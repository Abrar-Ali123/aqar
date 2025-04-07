<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RoleTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('translations')->paginate(10);
        return view('dashboard.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::with('translations')->get();
        return view('dashboard.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_ar' => 'nullable|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'is_primary' => 'nullable',
            'is_paid' => 'nullable',
            'price' => 'nullable|numeric|min:0',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        try {
            DB::beginTransaction();

            $role = Role::create([
                'is_primary' => $request->boolean('is_primary'),
                'is_paid' => $request->boolean('is_paid'),
                'price' => $request->is_paid ? $request->price : null,
            ]);

            RoleTranslation::create([
                'role_id' => $role->id,
                'locale' => 'ar',
                'name' => $request->name_ar,
            ]);

            RoleTranslation::create([
                'role_id' => $role->id,
                'locale' => 'en',
                'name' => $request->name_en,
            ]);

            $role->permissions()->attach($request->permissions);

            DB::commit();

            return redirect()->route('admin.roles.index')
                ->with('success', 'تم إنشاء الدور بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء إنشاء الدور');
        }
    }

    public function show(Role $role)
    {
        return view('dashboard.roles.show', compact('role'));
    }

    public function edit(Role $role)
    {
        $permissions = Permission::with('translations')->get();
        return view('dashboard.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name_ar' => 'nullable|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'is_primary' => 'nullable',
            'is_paid' => 'nullable',
            'price' => 'nullable|numeric|min:0',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        try {
            DB::beginTransaction();

            $role->update([
                'is_primary' => $request->boolean('is_primary'),
                'is_paid' => $request->boolean('is_paid'),
                'price' => $request->is_paid ? $request->price : null,
            ]);

            $role->translations()->where('locale', 'ar')->update([
                'name' => $request->name_ar,
            ]);

            $role->translations()->where('locale', 'en')->update([
                'name' => $request->name_en,
            ]);

            $role->permissions()->sync($request->permissions);

            DB::commit();

            return redirect()->route('admin.roles.index')
                ->with('success', 'تم تحديث الدور بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تحديث الدور');
        }
    }

    public function destroy(Role $role)
    {
        try {
            $role->delete();
            return redirect()->route('admin.roles.index')
                ->with('success', 'تم حذف الدور بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء حذف الدور');
        }
    }
}
