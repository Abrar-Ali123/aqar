<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Dashboard
            'dashboard_access' => ['dashboard'],

            // User Management
            'manage_users' => ['users.index', 'users.create', 'users.edit', 'users.show'],
            'view_users' => ['users.index', 'users.show'],
            'create_users' => ['users.create'],
            'edit_users' => ['users.edit'],
            'delete_users' => [], // Action, no page

            // Role & Permission Management
            'manage_roles' => ['roles.index', 'roles.create', 'roles.edit'],
            'manage_permissions' => ['permissions.index'],

            // Content Management
            'manage_content' => ['content.index', 'content.create', 'content.edit'],

            // Reports
            'view_reports' => ['reports.sales', 'reports.users'],
        ];

        foreach ($permissions as $permissionName => $pages) {
                        $permission = \App\Models\Permission::where('code', $permissionName)->first();

            if ($permission && !empty($pages)) {
                foreach ($pages as $page) {
                    \Illuminate\Support\Facades\DB::table('permission_pages')->updateOrInsert(
                        ['permission_id' => $permission->id, 'page' => $page],
                        ['created_at' => now(), 'updated_at' => now()]
                    );
                }
            }
        }
    }
}
