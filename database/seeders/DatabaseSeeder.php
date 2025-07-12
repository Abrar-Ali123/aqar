<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. الجداول الأساسية المستقلة
        $this->call([
            UserSeeder::class, // إضافة المستخدمين أولاً
            RoleSeeder::class,
            PermissionSeeder::class,
            LanguageSeeder::class,
            CategorySeeder::class,
            BusinessSectorSeeder::class,
            BusinessCategorySeeder::class,
            AttributeSeeder::class,
        ]);

        // 2. جداول العلاقات الأساسية
        $this->call([
            RolePermissionSeeder::class,
            PermissionPageSeeder::class,
            PageTemplateSeeder::class,
        ]);

        // 3. جداول المنشآت
        $this->call([
            FacilitySeeder::class,
            FacilityPageSeeder::class,
            // FacilityImageSeeder::class, // غير موجود حالياً
        ]);

        // 4. البيانات المرتبطة بالمنشآت
        $this->call([
            ProductSeeder::class,
            // ServiceSeeder::class, // غير موجود حالياً
            // EventSeeder::class, // غير موجود حالياً
        ]);

        // 5. البيانات الإضافية
        $this->call([
            // FacilityPageComponentSeeder::class, // غير موجود حالياً
            // AttributeValueSeeder::class, // غير موجود حالياً
        ]);
    }
}
