<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        if (!auth()->user()->can('view categories')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $categories = Category::with(['translations', 'parent', 'children'])
            ->orderBy('order')
            ->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        if (!auth()->user()->can('create categories')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $languages = Language::active()->orderBy('order')->get();
        $categories = Category::with('translations')->get();
        return view('admin.categories.create', compact('languages', 'categories'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('create categories')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $rules = $this->getValidationRules();
        $validated = $request->validate($rules);

        try {
            DB::transaction(function () use ($request, $validated) {
                // إنشاء الفئة
                $category = Category::create([
                    'parent_id' => $request->parent_id,
                    'icon' => $request->icon,
                    'icon_type' => $request->icon_type,
                    'show_in_menu' => $request->boolean('show_in_menu'),
                    'show_in_home' => $request->boolean('show_in_home'),
                    'is_featured' => $request->boolean('is_featured'),
                    'status' => $request->status ?? 'active',
                    'order' => $request->order ?? 0,
                ]);

                // معالجة الصورة المخصصة
                if ($request->hasFile('custom_icon')) {
                    $path = $request->file('custom_icon')->store('categories/icons', 'public');
                    $category->update(['custom_icon' => $path]);
                }

                // حفظ الترجمات
                foreach ($validated['name'] as $locale => $name) {
                    if ($name || Language::where('code', $locale)->value('is_required')) {
                        $category->translations()->create([
                            'locale' => $locale,
                            'name' => $name,
                            'description' => $validated['description'][$locale] ?? null,
                            'meta_title' => $validated['meta_title'][$locale] ?? null,
                            'meta_description' => $validated['meta_description'][$locale] ?? null,
                            'slug' => $validated['slug'][$locale] ?? null,
                        ]);
                    }
                }

                // ربط السمات
                if ($request->has('attributes')) {
                    $category->attributes()->sync($request->attributes);
                }
            });

            return redirect()->route('admin.categories.index')
                ->with('success', __('messages.category_created_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.category_create_error'))
                ->withInput();
        }
    }

    public function edit(Category $category)
    {
        if (!auth()->user()->can('edit categories')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        $languages = Language::active()->orderBy('order')->get();
        $translations = $category->translations->keyBy('locale');
        $categories = Category::where('id', '!=', $category->id)
            ->with('translations')
            ->get();

        return view('admin.categories.edit', compact('category', 'languages', 'translations', 'categories'));
    }

    public function update(Request $request, Category $category)
    {
        if (!auth()->user()->can('edit categories')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        // التحقق من عدم اختيار الفئة كأب لنفسها
        if ($request->parent_id == $category->id) {
            return redirect()->back()
                ->with('error', __('messages.category_cannot_be_parent_of_itself'))
                ->withInput();
        }

        $rules = $this->getValidationRules($category->id);
        $validated = $request->validate($rules);

        try {
            DB::transaction(function () use ($request, $category, $validated) {
                // تحديث الفئة
                $category->update([
                    'parent_id' => $request->parent_id,
                    'icon' => $request->icon,
                    'icon_type' => $request->icon_type,
                    'show_in_menu' => $request->boolean('show_in_menu'),
                    'show_in_home' => $request->boolean('show_in_home'),
                    'is_featured' => $request->boolean('is_featured'),
                    'status' => $request->status ?? $category->status,
                    'order' => $request->order ?? $category->order,
                ]);

                // معالجة الصورة المخصصة
                if ($request->hasFile('custom_icon')) {
                    // حذف الصورة القديمة
                    if ($category->custom_icon) {
                        Storage::disk('public')->delete($category->custom_icon);
                    }
                    
                    $path = $request->file('custom_icon')->store('categories/icons', 'public');
                    $category->update(['custom_icon' => $path]);
                }

                // تحديث الترجمات
                foreach ($validated['name'] as $locale => $name) {
                    $category->translations()->updateOrCreate(
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

                // تحديث السمات
                if ($request->has('attributes')) {
                    $category->attributes()->sync($request->attributes);
                }
            });

            return redirect()->route('admin.categories.index')
                ->with('success', __('messages.category_updated_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.category_update_error'))
                ->withInput();
        }
    }

    public function destroy(Category $category)
    {
        if (!auth()->user()->can('delete categories')) {
            return redirect()->back()->with('error', __('messages.unauthorized_action'));
        }

        // التحقق من عدم وجود فئات فرعية
        if ($category->children()->exists()) {
            return redirect()->back()
                ->with('error', __('messages.category_has_children'));
        }

        // التحقق من عدم وجود منتجات
        if ($category->products()->exists()) {
            return redirect()->back()
                ->with('error', __('messages.category_has_products'));
        }

        try {
            DB::transaction(function () use ($category) {
                // حذف الصورة المخصصة
                if ($category->custom_icon) {
                    Storage::disk('public')->delete($category->custom_icon);
                }

                // حذف الفئة (سيتم حذف الترجمات تلقائياً بسبب onDelete('cascade'))
                $category->delete();
            });

            return redirect()->route('admin.categories.index')
                ->with('success', __('messages.category_deleted_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.category_delete_error'));
        }
    }

    private function getValidationRules($categoryId = null): array
    {
        $rules = [
            'parent_id' => 'nullable|exists:categories,id',
            'icon' => 'nullable|string|max:50',
            'icon_type' => 'required|in:font,custom',
            'custom_icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'show_in_menu' => 'boolean',
            'show_in_home' => 'boolean',
            'is_featured' => 'boolean',
            'status' => 'nullable|in:active,inactive',
            'order' => 'nullable|integer|min:0',
            'attributes' => 'nullable|array',
            'attributes.*' => 'exists:attributes,id',
        ];

        // إضافة قواعد التحقق للحقول المترجمة
        foreach (Language::active()->get() as $language) {
            $required = $language->is_required ? 'required' : 'nullable';
            $rules["name.{$language->code}"] = "{$required}|string|max:255";
            $rules["description.{$language->code}"] = "nullable|string";
            $rules["meta_title.{$language->code}"] = "nullable|string|max:255";
            $rules["meta_description.{$language->code}"] = "nullable|string";
            $rules["slug.{$language->code}"] = "{$required}|string|max:255|unique:category_translations,slug" . 
                ($categoryId ? ",{$categoryId},category_id" : '');
        }

        return $rules;
    }
}
