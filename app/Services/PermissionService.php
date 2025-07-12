<?php

namespace App\Services;

use App\Models\Permission;
use App\Models\PermissionCategory;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PermissionService
{
    /**
     * Get categories with their permissions for the index page.
     *
     * @return Collection
     */
    public function getCategoriesForIndex(): Collection
    {
        return PermissionCategory::with(['permissions', 'children.permissions'])
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();
    }

    /**
     * Get all categories for dropdowns.
     *
     * @return Collection
     */
    public function getAllCategories(): Collection
    {
        return PermissionCategory::orderBy('name')->get();
    }

    /**
     * Create a new permission.
     *
     * @param array $data
     * @return Permission
     */
    public function createPermission(array $data): Permission
    {
        return Permission::create([
            'name' => Str::slug($data['name']), // The name is used as the slug/code
            'category_id' => $data['category_id'],
            'description' => $data['description'],
            'translations' => $data['translations'],
            'guard_name' => 'web'
        ]);
    }

    /**
     * Update an existing permission.
     *
     * @param Permission $permission
     * @param array $data
     * @return Permission
     */
    public function updatePermission(Permission $permission, array $data): Permission
    {
        // Note: The permission 'name' (slug) is not updatable to maintain integrity.
        $permission->update([
            'category_id' => $data['category_id'],
            'description' => $data['description'],
            'translations' => $data['translations']
        ]);
        return $permission;
    }

    /**
     * Delete a permission.
     *
     * @param Permission $permission
     * @return void
     */
    public function deletePermission(Permission $permission): void
    {
        $permission->delete();
    }
}
