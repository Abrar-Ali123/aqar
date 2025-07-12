<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CategoryService
{
    public function createCategory(Request $request): Category
    {
        return DB::transaction(function () use ($request) {
            $category = new Category();
            $this->fillCategoryData($category, $request);

            if ($request->hasFile('custom_icon')) {
                $category->custom_icon = $request->file('custom_icon')->store('categories/icons', 'public');
            }

            $category->save();

            $this->syncRelations($category, $request);

            return $category;
        });
    }

    public function updateCategory(Category $category, Request $request): Category
    {
        return DB::transaction(function () use ($category, $request) {
            $this->fillCategoryData($category, $request);

            if ($request->hasFile('custom_icon')) {
                if ($category->custom_icon) {
                    Storage::disk('public')->delete($category->custom_icon);
                }
                $category->custom_icon = $request->file('custom_icon')->store('categories/icons', 'public');
            }

            $category->save();

            $this->syncRelations($category, $request);

            return $category;
        });
    }

    public function deleteCategory(Category $category): void
    {
        if ($category->children()->exists()) {
            throw new \Exception(__('messages.category_has_children'));
        }

        if ($category->products()->exists()) {
            throw new \Exception(__('messages.category_has_products'));
        }

        DB::transaction(function () use ($category) {
            if ($category->custom_icon) {
                Storage::disk('public')->delete($category->custom_icon);
            }
            $category->delete();
        });
    }

    private function fillCategoryData(Category $category, Request $request): void
    {
        $category->parent_id = $request->parent_id;
        $category->icon = $request->icon;
        $category->icon_type = $request->icon_type;
        $category->show_in_menu = $request->boolean('show_in_menu');
        $category->show_in_home = $request->boolean('show_in_home');
        $category->is_featured = $request->boolean('is_featured');
        $category->status = $request->status ?? 'active';
        $category->order = $request->order ?? 0;
    }

    private function syncRelations(Category $category, Request $request): void
    {
        // Sync Translations
        foreach ($request->input('name', []) as $locale => $name) {
            if ($name || Language::where('code', $locale)->value('is_required')) {
                $category->translations()->updateOrCreate(
                    ['locale' => $locale],
                    [
                        'name' => $name,
                        'description' => $request->input("description.{$locale}"),
                        'meta_title' => $request->input("meta_title.{$locale}"),
                        'meta_description' => $request->input("meta_description.{$locale}"),
                        'slug' => $request->input("slug.{$locale}"),
                    ]
                );
            }
        }

        // Sync Attributes
        if ($request->has('attributes')) {
            $category->attributes()->sync($request->input('attributes', []));
        }
    }
}
