<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'is_primary' => true,
                'is_paid' => false,
                'price' => 0.00,
                'facility_id' => null,
                'permission_id' => DB::table('permissions')->where('guard_name', 'web')->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'is_primary' => false,
                'is_paid' => true,
                'price' => 99.99,
                'facility_id' => null,
                'permission_id' => DB::table('permissions')->where('guard_name', 'web')->skip(1)->first()->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        foreach ($roles as $index => $role) {
            $roleId = DB::table('roles')->insertGetId($role);
            
            // Add translations
            DB::table('role_translations')->insert([
                [
                    'role_id' => $roleId,
                    'locale' => 'ar',
                    'name' => $index === 0 ? 'مدير النظام' : 'مستخدم مميز',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'role_id' => $roleId,
                    'locale' => 'en',
                    'name' => $index === 0 ? 'System Admin' : 'Premium User',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
        }
    }
}
