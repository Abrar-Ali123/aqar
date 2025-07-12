<?php

namespace App\Services;

use App\Models\Role;
use App\Models\Language;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class RoleService
{
    /**
     * Get a paginated list of roles.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getRoles(): LengthAwarePaginator
    {
        return Role::with(['translations', 'permissions'])
            ->orderBy('order')
            ->paginate(10);
    }

    /**
     * Create a new role with translations and permissions.
     *
     * @param array $data
     * @return Role
     */
    public function createRole(array $data): Role
    {
        return DB::transaction(function () use ($data) {
            $role = Role::create([
                'guard_name' => 'web',
                'is_admin' => $data['is_admin'] ?? false,
                'is_default' => $data['is_default'] ?? false,
                'status' => $data['status'] ?? 'active',
                'order' => $data['order'] ?? 0,
            ]);

            $this->syncTranslations($role, $data);
            $this->syncPermissions($role, $data);

            if ($role->is_default) {
                Role::where('id', '!=', $role->id)->update(['is_default' => false]);
            }

            return $role;
        });
    }

    /**
     * Update an existing role with translations and permissions.
     *
     * @param Role $role
     * @param array $data
     * @return Role
     */
    public function updateRole(Role $role, array $data): Role
    {
        return DB::transaction(function () use ($role, $data) {
            $role->update([
                'is_admin' => $data['is_admin'] ?? false,
                'is_default' => $data['is_default'] ?? false,
                'status' => $data['status'] ?? $role->status,
                'order' => $data['order'] ?? $role->order,
            ]);

            $this->syncTranslations($role, $data);
            $this->syncPermissions($role, $data);

            if ($role->is_default) {
                Role::where('id', '!=', $role->id)->update(['is_default' => false]);
            } elseif (Role::count() === 1) {
                $role->update(['is_default' => true]);
            }

            return $role;
        });
    }

    /**
     * Delete a role.
     *
     * @param Role $role
     * @return void
     * @throws \Exception
     */
    public function deleteRole(Role $role): void
    {
        if ($role->is_default) {
            throw new \Exception(__('messages.cannot_delete_default_role'));
        }

        if ($role->users()->exists()) {
            throw new \Exception(__('messages.role_has_users'));
        }

        DB::transaction(function () use ($role) {
            $role->delete();

            if (Role::count() === 1) {
                Role::first()->update(['is_default' => true]);
            }
        });
    }

    /**
     * Sync translations for a role.
     *
     * @param Role $role
     * @param array $data
     */
    private function syncTranslations(Role $role, array $data): void
    {
        foreach ($data['name'] as $locale => $name) {
            $role->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'name' => $name,
                    'description' => $data['description'][$locale] ?? null,
                ]
            );
        }
    }

    /**
     * Sync permissions for a role.
     *
     * @param Role $role
     * @param array $data
     */
    private function syncPermissions(Role $role, array $data): void
    {
        if (isset($data['permissions'])) {
            $role->permissions()->sync($data['permissions']);
        } else {
            $role->permissions()->detach();
        }
    }
}
