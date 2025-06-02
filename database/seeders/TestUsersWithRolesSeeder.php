<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TestUsersWithRolesSeeder extends Seeder
{
    public function run()
    {
        // 1. مدير النظام (صلاحيات كاملة)
        $adminUser = User::create([
            'phone_number' => '550880798',
            'email' => 'admin@example.com',
            'password' => Hash::make('12345678'),
            'is_active' => true,
            'email_verified_at' => now(),
            'language_code' => 'ar',
            'is_multilanguage_enabled' => true
        ]);

        // إضافة ترجمات الاسم
        DB::table('user_translations')->insert([
            [
                'user_id' => $adminUser->id,
                'locale' => 'ar',
                'name' => 'مدير النظام',
            ],
            [
                'user_id' => $adminUser->id,
                'locale' => 'en',
                'name' => 'System Admin',
            ]
        ]);

        // 2. مستخدم مميز (صلاحيات محدودة)
        $premiumUser = User::create([
            'phone_number' => '550880798',
            'email' => 'premium@example.com',
            'password' => Hash::make('12345678'),
            'is_active' => true,
            'email_verified_at' => now(),
            'language_code' => 'ar',
            'is_multilanguage_enabled' => true
        ]);

        DB::table('user_translations')->insert([
            [
                'user_id' => $premiumUser->id,
                'locale' => 'ar',
                'name' => 'مستخدم مميز',
            ],
            [
                'user_id' => $premiumUser->id,
                'locale' => 'en',
                'name' => 'Premium User',
            ]
        ]);

        // 3. مالك منشأة
        $facilityOwner = User::create([
            'phone_number' => '550880798',
            'email' => 'owner@example.com',
            'password' => Hash::make('12345678'),
            'is_active' => true,
            'email_verified_at' => now(),
            'language_code' => 'ar',
            'is_multilanguage_enabled' => true
        ]);

        DB::table('user_translations')->insert([
            [
                'user_id' => $facilityOwner->id,
                'locale' => 'ar',
                'name' => 'مالك منشأة',
            ],
            [
                'user_id' => $facilityOwner->id,
                'locale' => 'en',
                'name' => 'Facility Owner',
            ]
        ]);

        // 4. مستخدم عادي
        $normalUser = User::create([
            'phone_number' => '550880798',
            'email' => 'user@example.com',
            'password' => Hash::make('12345678'),
            'is_active' => true,
            'email_verified_at' => now(),
            'language_code' => 'ar',
            'is_multilanguage_enabled' => false
        ]);

        DB::table('user_translations')->insert([
            [
                'user_id' => $normalUser->id,
                'locale' => 'ar',
                'name' => 'مستخدم عادي',
            ],
            [
                'user_id' => $normalUser->id,
                'locale' => 'en',
                'name' => 'Normal User',
            ]
        ]);

        // ربط المستخدمين بالأدوار والمنشآت
        $adminRoleId = DB::table('roles')->where('is_primary', true)->first()->id;
        $premiumRoleId = DB::table('roles')->where('is_paid', true)->first()->id;

        // إنشاء منشأة للاختبار
        $facilityId = DB::table('facilities')->insertGetId([
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('facility_translations')->insert([
            [
                'facility_id' => $facilityId,
                'locale' => 'ar',
                'name' => 'منشأة اختبار'
            ],
            [
                'facility_id' => $facilityId,
                'locale' => 'en',
                'name' => 'Test Facility'
            ]
        ]);

        // ربط المستخدمين بالأدوار والمنشآت
        DB::table('user_facility_role')->insert([
            [
                'user_id' => $adminUser->id,
                'facility_id' => $facilityId,
                'role_id' => $adminRoleId,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => $premiumUser->id,
                'facility_id' => $facilityId,
                'role_id' => $premiumRoleId,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => $facilityOwner->id,
                'facility_id' => $facilityId,
                'role_id' => $adminRoleId,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
