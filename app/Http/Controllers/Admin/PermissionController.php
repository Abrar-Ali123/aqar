<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\PermissionCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PermissionController extends Controller
{
    public function index()
    {
        $categories = PermissionCategory::with(['permissions', 'children.permissions'])
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();

        return view('dashboard.permissions.index', compact('categories'));
    }

    public function create()
    {
        $categories = PermissionCategory::orderBy('name')->get();
        return view('dashboard.permissions.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:permission_categories,id',
            'description' => 'nullable|string',
            'translations' => 'required|array',
            'translations.ar' => 'required|string',
            'translations.en' => 'required|string',
        ]);

        $permission = Permission::create([
            'name' => Str::slug($validated['name']),
            'category_id' => $validated['category_id'],
            'description' => $validated['description'],
            'translations' => $validated['translations'],
            'guard_name' => 'web'
        ]);

        return redirect()
            ->route('admin.permissions.index')
            ->with('success', __('Permission created successfully'));
    }

    public function edit(Permission $permission)
    {
        $categories = PermissionCategory::orderBy('name')->get();
        return view('dashboard.permissions.edit', compact('permission', 'categories'));
    }

    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:permission_categories,id',
            'description' => 'nullable|string',
            'translations' => 'required|array',
            'translations.ar' => 'required|string',
            'translations.en' => 'required|string',
        ]);

        $permission->update([
            'category_id' => $validated['category_id'],
            'description' => $validated['description'],
            'translations' => $validated['translations']
        ]);

        return redirect()
            ->route('admin.permissions.index')
            ->with('success', __('Permission updated successfully'));
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();

        return redirect()
            ->route('admin.permissions.index')
            ->with('success', __('Permission deleted successfully'));
    }

    public function audit(Permission $permission)
    {
        $logs = $permission->auditLogs()
            ->with('user')
            ->latest()
            ->paginate(20);

        return view('dashboard.permissions.audit', compact('permission', 'logs'));
    }
}
