<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePermissionCategoryRequest;
use App\Http\Requests\Admin\UpdatePermissionCategoryRequest;
use App\Models\PermissionCategory;
use App\Services\PermissionCategoryService;
use Illuminate\Http\Request;

class PermissionCategoryController extends Controller
{
    protected $categoryService;

    public function __construct(PermissionCategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $categories = $this->categoryService->getTopLevelCategoriesWithChildren();
        return view('dashboard.permission-categories.index', compact('categories'));
    }

    public function create()
    {
        $categories = $this->categoryService->getTopLevelCategoriesForDropdown();
        return view('dashboard.permission-categories.create', compact('categories'));
    }

    public function store(StorePermissionCategoryRequest $request)
    {
        $this->categoryService->createCategory($request->validated());

        return redirect()
            ->route('admin.permission-categories.index')
            ->with('success', __('Category created successfully'));
    }

    public function edit(PermissionCategory $category)
    {
        $categories = $this->categoryService->getTopLevelCategoriesForDropdown($category->id);
        return view('dashboard.permission-categories.edit', compact('category', 'categories'));
    }

    public function update(UpdatePermissionCategoryRequest $request, PermissionCategory $category)
    {
        try {
            $this->categoryService->updateCategory($category, $request->validated());
            return redirect()
                ->route('admin.permission-categories.index')
                ->with('success', __('Category updated successfully'));
        } catch (\Exception $e) {
            return back()->withErrors(['parent_id' => $e->getMessage()]);
        }
    }

    public function destroy(PermissionCategory $category)
    {
        $this->categoryService->deleteCategory($category);

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

        $this->categoryService->reorderCategories($request->categories);

        return response()->json(['message' => __('Categories reordered successfully')]);
    }

    public function audit(PermissionCategory $category)
    {
        // This can be moved to the service later if needed.
        $logs = $category->auditLogs()
            ->with('user')
            ->latest()
            ->paginate(20);

        return view('dashboard.permission-categories.audit', compact('category', 'logs'));
    }
}
