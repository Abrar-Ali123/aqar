<?php

namespace App\Services;

use App\Models\PermissionCategory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PermissionCategoryService
{
    public function getTopLevelCategoriesWithChildren(): Collection
    {
        return PermissionCategory::with(['permissions', 'children'])
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();
    }

    public function getTopLevelCategoriesForDropdown(int $excludeId = null): Collection
    {
        return PermissionCategory::whereNull('parent_id')
            ->when($excludeId, function ($query) use ($excludeId) {
                $query->where('id', '!=', $excludeId);
            })
            ->orderBy('name')
            ->get();
    }

    public function createCategory(array $data): PermissionCategory
    {
        return PermissionCategory::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'description' => $data['description'],
            'parent_id' => $data['parent_id'],
            'order' => $data['order'] ?? 0,
            'translations' => $data['translations'],
        ]);
    }

    public function updateCategory(PermissionCategory $category, array $data): PermissionCategory
    {
        if (isset($data['parent_id'])) {
            if ($data['parent_id'] == $category->id) {
                throw new \Exception(__('Category cannot be its own parent'));
            }
            if ($category->children->pluck('id')->contains($data['parent_id'])) {
                throw new \Exception(__('Cannot set a child category as parent'));
            }
        }

        $category->update([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'description' => $data['description'],
            'parent_id' => $data['parent_id'],
            'order' => $data['order'] ?? $category->order,
            'translations' => $data['translations'],
        ]);

        return $category;
    }

    public function deleteCategory(PermissionCategory $category): void
    {
        DB::transaction(function () use ($category) {
            $category->children()->update(['parent_id' => $category->parent_id]);
            $category->permissions()->update(['permission_category_id' => $category->parent_id]);
            $category->delete();
        });
    }

    public function reorderCategories(array $categories): void
    {
        DB::transaction(function () use ($categories) {
            foreach ($categories as $item) {
                PermissionCategory::where('id', $item['id'])->update(['order' => $item['order']]);
            }
        });
    }
}
