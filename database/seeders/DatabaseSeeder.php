<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $allRoutes = Route::getRoutes()->getRoutesByName();
        $allRoutesNames = array_keys($allRoutes);

        // Create the main facility
        $mainFacilityId = DB::table('facilities')->insertGetId([
            'is_active' => true,
            'is_primary' => true,
            'logo' => null,
            'header' => null,
            'license' => null,
            'latitude' => null,
            'longitude' => null,
            'google_maps_url' => null,
        ]);

        DB::table('facility_translations')->insert([
            ['facility_id' => $mainFacilityId, 'name' => 'Main Facility', 'info' => 'Main facility information in English', 'locale' => 'en'],
            ['facility_id' => $mainFacilityId, 'name' => 'المنشأة الرئيسية', 'info' => 'معلومات المنشأة الرئيسية باللغة العربية', 'locale' => 'ar'],
        ]);

        // Create permissions
        $permissionId = DB::table('permissions')->insertGetId([
            'pages' => json_encode($allRoutesNames),
        ]);

        DB::table('permission_translations')->insert([
            ['permission_id' => $permissionId, 'name' => 'Full Access', 'locale' => 'en'],
            ['permission_id' => $permissionId, 'name' => 'وصول كامل', 'locale' => 'ar'],
        ]);

        // Create the admin role if it doesn't exist
        $adminRoleId = DB::table('roles')->updateOrInsert(
            ['permission_id' => $permissionId],
            ['is_primary' => true]
        );

        DB::table('role_translations')->insertOrIgnore([
            ['role_id' => $adminRoleId, 'name' => 'Administrator', 'locale' => 'en'],
            ['role_id' => $adminRoleId, 'name' => 'مدير', 'locale' => 'ar'],
        ]);

        // Create the admin user and associate it with the main facility and admin role
        $adminUserId = DB::table('users')->insertGetId([
            'phone_number' => '+966550880798',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'avatar' => 'default.jpg',
            'bank_account' => '000123456',
            'facility_id' => $mainFacilityId,
            'primary_role' => 'Administrator',
        ]);

        DB::table('user_translations')->insert([
            ['user_id' => $adminUserId, 'name' => 'مدير النظام', 'locale' => 'ar'],
            ['user_id' => $adminUserId, 'name' => 'System Administrator', 'locale' => 'en'],
        ]);

        // Create the normal user role if it doesn't exist
        $userRoleId = DB::table('roles')->updateOrInsert(
            ['permission_id' => $permissionId],
            ['is_primary' => false]
        );

        DB::table('role_translations')->insertOrIgnore([
            ['role_id' => $userRoleId, 'name' => 'User', 'locale' => 'en'],
            ['role_id' => $userRoleId, 'name' => 'مستخدم', 'locale' => 'ar'],
        ]);

        // Create a normal user and associate it with the main facility and normal user role
        $normalUserId = DB::table('users')->insertGetId([
            'phone_number' => '987654321',
            'email' => 'user@example.com',
            'password' => Hash::make('user123'),
            'avatar' => 'default.jpg',
            'bank_account' => '000987654',
            'facility_id' => $mainFacilityId,
            'primary_role' => 'User',
        ]);

        DB::table('user_translations')->insert([
            ['user_id' => $normalUserId, 'name' => 'مستخدم عادي', 'locale' => 'ar'],
            ['user_id' => $normalUserId, 'name' => 'Normal User', 'locale' => 'en'],
        ]);

        // Attach roles to users
        DB::table('user_role')->insert([
            ['user_id' => $adminUserId, 'role_id' => $adminRoleId],
            ['user_id' => $normalUserId, 'role_id' => $userRoleId],
        ]);

        DB::table('user_facility_role')->insert([
            'user_id' => $adminUserId,
            'facility_id' => $mainFacilityId,
            'role_id' => $adminRoleId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('user_facility_role')->insert([
            'user_id' => $normalUserId,
            'facility_id' => $mainFacilityId,
            'role_id' => $userRoleId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
