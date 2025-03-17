<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('translations', 'parent.translations')->paginate(10);
        return view('dashboard.categories.index', compact('categories'));
    }

    public function create()
    {
        $categories = Category::with('translations')->get();
        return view('dashboard.categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        DB::beginTransaction();

        try {
            // Upload image if provided
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('categories', 'public');
            }

            // Create category
            $category = Category::create([
                'parent_id' => $request->parent_id,
                'image' => $imagePath,
            ]);

            // Create translations
            CategoryTranslation::create([
                'category_id' => $category->id,
                'locale' => 'ar',
                'name' => $request->name_ar,
            ]);

            CategoryTranslation::create([
                'category_id' => $category->id,
                'locale' => 'en',
                'name' => $request->name_en,
            ]);

            DB::commit();

            return redirect()->route('admin.categories.index')
                ->with('success', 'تم إضافة الفئة بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء إضافة الفئة: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Category $category)
    {
        $category->load('translations', 'parent.translations', 'children.translations');
        return view('dashboard.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        $categories = Category::with('translations')
            ->where('id', '!=', $category->id)
            ->whereNotIn('id', $category->getAllChildren()->pluck('id')->toArray())
            ->get();

        return view('dashboard.categories.edit', compact('category', 'categories'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Prevent category from being its own parent or child
        if ($request->parent_id && (
            $request->parent_id == $category->id ||
            $category->getAllChildren()->pluck('id')->contains($request->parent_id)
        )) {
            return redirect()->back()
                ->with('error', 'لا يمكن جعل الفئة أباً لنفسها أو لأحد أبنائها')
                ->withInput();
        }

        DB::beginTransaction();

        try {
            // Update image if provided
            $imagePath = $category->image;
            if ($request->hasFile('image')) {
                // Delete old image
                if ($category->image) {
                    Storage::disk('public')->delete($category->image);
                }
                $imagePath = $request->file('image')->store('categories', 'public');
            } elseif ($request->has('delete_image') && $request->delete_image) {
                // Delete image if requested
                if ($category->image) {
                    Storage::disk('public')->delete($category->image);
                }
                $imagePath = null;
            }

            // Update category
            $category->update([
                'parent_id' => $request->parent_id,
                'image' => $imagePath,
            ]);

            // Update translations
            $category->translations()->where('locale', 'ar')->update([
                'name' => $request->name_ar,
            ]);

            $category->translations()->where('locale', 'en')->update([
                'name' => $request->name_en,
            ]);

            DB::commit();

            return redirect()->route('admin.categories.index')
                ->with('success', 'تم تحديث الفئة بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تحديث الفئة: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Category $category)
    {
        // Check if category has products
        if ($category->products()->count() > 0) {
            return redirect()->back()
                ->with('error', 'لا يمكن حذف الفئة لأنها تحتوي على منتجات');
        }

        DB::beginTransaction();

        try {
            // Delete image
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            // Update children to parent's parent
            foreach ($category->children as $child) {
                $child->update(['parent_id' => $category->parent_id]);
            }

            // Delete translations
            $category->translations()->delete();

            // Delete category
            $category->delete();

            DB::commit();

            return redirect()->route('admin.categories.index')
                ->with('success', 'تم حذف الفئة بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء حذف الفئة: ' . $e->getMessage());
        }
    }
}
