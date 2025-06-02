<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'phone_number' => '0500000000',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'is_multilanguage_enabled' => true,
                'role_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'phone_number' => '0500000001',
                'email' => 'user@example.com',
                'password' => Hash::make('password'),
                'is_multilanguage_enabled' => true,
                'role_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];
        foreach ($users as $index => $user) {
            $userId = DB::table('users')->insertGetId($user);
            DB::table('user_translations')->insert([
                [
                    'user_id' => $userId,
                    'name' => $index === 0 ? 'Admin User' : 'Normal User',
                    'info' => $index === 0 ? 'System administrator' : 'Regular user account',
                    'locale' => 'en',
                ],
                [
                    'user_id' => $userId,
                    'name' => $index === 0 ? 'مدير النظام' : 'مستخدم عادي',
                    'info' => $index === 0 ? 'مدير النظام الكامل' : 'حساب مستخدم عادي',
                    'locale' => 'ar',
                ]
            ]);
        }
    }
}
