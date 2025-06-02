<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PermissionCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PermissionCategoryController extends Controller
{
    public function index()
    {
        $categories = PermissionCategory::with(['permissions', 'children'])
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();

        return view('dashboard.permission-categories.index', compact('categories'));
    }

    public function create()
    {
        $categories = PermissionCategory::whereNull('parent_id')->orderBy('name')->get();
        return view('dashboard.permission-categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:permission_categories,id',
            'order' => 'nullable|integer',
            'translations' => 'required|array',
            'translations.ar' => 'required|string',
            'translations.en' => 'required|string',
        ]);

        PermissionCategory::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'],
            'parent_id' => $validated['parent_id'],
            'order' => $validated['order'] ?? 0,
            'translations' => $validated['translations'],
        ]);

        return redirect()
            ->route('admin.permission-categories.index')
            ->with('success', __('Category created successfully'));
    }

    public function edit(PermissionCategory $category)
    {
        $categories = PermissionCategory::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->orderBy('name')
            ->get();

        return view('dashboard.permission-categories.edit', compact('category', 'categories'));
    }

    public function update(Request $request, PermissionCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:permission_categories,id',
            'order' => 'nullable|integer',
            'translations' => 'required|array',
            'translations.ar' => 'required|string',
            'translations.en' => 'required|string',
        ]);

        // Prevent category from being its own parent
        if ($validated['parent_id'] == $category->id) {
            return back()->withErrors(['parent_id' => __('Category cannot be its own parent')]);
        }

        // Check if the new parent is not a descendant of this category
        if ($validated['parent_id'] && $category->children->pluck('id')->contains($validated['parent_id'])) {
            return back()->withErrors(['parent_id' => __('Cannot set a child category as parent')]);
        }

        $category->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'],
            'parent_id' => $validated['parent_id'],
            'order' => $validated['order'] ?? 0,
            'translations' => $validated['translations'],
        ]);

        return redirect()
            ->route('admin.permission-categories.index')
            ->with('success', __('Category updated successfully'));
    }

    public function destroy(PermissionCategory $category)
    {
        // Move child categories to parent
        if ($category->children->count() > 0) {
            $category->children()->update(['parent_id' => $category->parent_id]);
        }

        // Move permissions to parent category if exists
        if ($category->permissions->count() > 0) {
            $category->permissions()->update(['category_id' => $category->parent_id]);
        }

        $category->delete();

        return redirect()
            ->route('admin.permission-categories.index')
            ->with('success', __('Category deleted successfully'));
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'categories' => 'required|array',
            'categories.*.id' => 'required|exists:permission_categories,id',
            'categories.*.order' => 'required|integer'
        ]);

        foreach ($request->categories as $item) {
            PermissionCategory::where('id', $item['id'])->update(['order' => $item['order']]);
        }

        return response()->json(['message' => __('Categories reordered successfully')]);
    }

    public function audit(PermissionCategory $category)
    {
        $logs = $category->auditLogs()
            ->with('user')
            ->latest()
            ->paginate(20);

        return view('dashboard.permission-categories.audit', compact('category', 'logs'));
    }
}
