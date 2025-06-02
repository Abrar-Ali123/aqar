<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            [
                'guard_name' => 'web',
                'pages' => json_encode([
                    'dashboard',
                    'users',
                    'roles',
                    'permissions',
                    'facilities',
                    'products',
                    'categories',
                    'features',
                    'settings'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'guard_name' => 'web',
                'pages' => json_encode([
                    'dashboard',
                    'products',
                    'profile'
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        foreach ($permissions as $index => $permission) {
            $permissionId = DB::table('permissions')->insertGetId($permission);
            
            // Add translations
            DB::table('permission_translations')->insert([
                [
                    'permission_id' => $permissionId,
                    'locale' => 'ar',
                    'name' => $index === 0 ? 'صلاحيات كاملة' : 'صلاحيات محدودة',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'permission_id' => $permissionId,
                    'locale' => 'en',
                    'name' => $index === 0 ? 'Full Permissions' : 'Limited Permissions',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
        }
    }
}
