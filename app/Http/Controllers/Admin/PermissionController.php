<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\PermissionTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::with('translations')->paginate(10);
        return view('dashboard.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('dashboard.permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_ar' => 'nullable|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'pages' => 'nullable|array',
            'pages.*' => 'string',
        ]);

        try {
            DB::beginTransaction();

            $permission = Permission::create([
                'pages' => json_encode($request->pages),
            ]);

            PermissionTranslation::create([
                'permission_id' => $permission->id,
                'locale' => 'ar',
                'name' => $request->name_ar,
            ]);

            PermissionTranslation::create([
                'permission_id' => $permission->id,
                'locale' => 'en',
                'name' => $request->name_en,
            ]);

            DB::commit();

            return redirect()->route('admin.permissions.index')
                ->with('success', 'تم إنشاء الصلاحية بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء إنشاء الصلاحية');
        }
    }

    public function show(Permission $permission)
    {
        return view('dashboard.permissions.show', compact('permission'));
    }

    public function edit(Permission $permission)
    {
        return view('dashboard.permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name_ar' => 'nullable|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'pages' => 'nullable|array',
            'pages.*' => 'string',
        ]);

        try {
            DB::beginTransaction();

            $permission->update([
                'pages' => json_encode($request->pages),
            ]);

            $permission->translations()->where('locale', 'ar')->update([
                'name' => $request->name_ar,
            ]);

            $permission->translations()->where('locale', 'en')->update([
                'name' => $request->name_en,
            ]);

            DB::commit();

            return redirect()->route('admin.permissions.index')
                ->with('success', 'تم تحديث الصلاحية بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تحديث الصلاحية');
        }
    }

    public function destroy(Permission $permission)
    {
        try {
            $permission->delete();
            return redirect()->route('admin.permissions.index')
                ->with('success', 'تم حذف الصلاحية بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء حذف الصلاحية');
        }
    }
}
