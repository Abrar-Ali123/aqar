<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Models\Category;
use App\Models\Language;
use App\Services\CategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function __construct(protected CategoryService $categoryService)
    {
    }

    public function index(): View
    {
        $categories = Category::with(['translations', 'parent', 'children'])
            ->orderBy('order')
            ->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        $languages = Language::active()->orderBy('order')->get();
        $categories = Category::with('translations')->get();
        return view('admin.categories.create', compact('languages', 'categories'));
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $category = $this->categoryService->createCategory($request);

        return redirect()
            ->route('admin.categories.show', ['category' => $category->id, 'locale' => app()->getLocale()])
            ->with('success', 'تم إنشاء الفئة بنجاح');
    }

    public function show(Category $category): View
    {
        $category->load(['translations', 'parent', 'children', 'attributes']);
        return view('admin.categories.show', compact('category'));
    }

    public function edit(Category $category): View
    {
        $languages = Language::active()->orderBy('order')->get();
        $translations = $category->translations->keyBy('locale');
        $categories = Category::where('id', '!=', $category->id)
            ->with('translations')
            ->get();
        $attributes = $category->attributes->pluck('id')->toArray();

        return view('admin.categories.edit', compact('category', 'languages', 'translations', 'categories', 'attributes'));
    }

    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $category = $this->categoryService->updateCategory($category, $request);

        return redirect()
            ->route('admin.categories.show', ['category' => $category->id, 'locale' => app()->getLocale()])
            ->with('success', 'تم تحديث الفئة بنجاح');
    }

    public function destroy(Category $category): RedirectResponse
    {
        try {
            $this->categoryService->deleteCategory($category);
            return redirect()->route('admin.categories.index')
                ->with('success', 'تم حذف الفئة بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }
}
