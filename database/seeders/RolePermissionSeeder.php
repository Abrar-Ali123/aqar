<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // الأدوار والصلاحيات
        $rolePermissions = [
            'Admin' => ['Manage Facilities', 'View Facilities', 'Create Facilities', 'Edit Facilities', 'Delete Facilities', 'Manage Facility Pages', 'Manage Products'],
            'Moderator' => ['View Facilities', 'Edit Facilities', 'Manage Facility Pages'],
            'Facility Owner' => ['View Facilities', 'Edit Facilities', 'Manage Facility Pages', 'Manage Products'],
            'User' => ['View Facilities']
        ];

        foreach ($rolePermissions as $roleName => $permissionNames) {
            $role = Role::whereHas('translations', function($query) use ($roleName) {
                $query->where('name', $roleName);
            })->first();

            if ($role) {
                $permissionIds = Permission::whereHas('translations', function($query) use ($permissionNames) {
                    $query->whereIn('name', $permissionNames)->where('locale', 'en');
                })->pluck('id');

                $role->permissions()->sync($permissionIds);
            }
        }
    }
}
