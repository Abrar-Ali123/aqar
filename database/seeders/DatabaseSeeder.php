<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Database\Seeders\LanguageSeeder;
use Database\Seeders\ProductTypeSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\FacilitySeeder;
use Database\Seeders\FeatureSeeder;
use Database\Seeders\BankSeeder;
use Database\Seeders\AttributeSeeder;
use Database\Seeders\PackageSeeder;
use Database\Seeders\BuildingSeeder;
use Database\Seeders\BookingSeeder;
use Database\Seeders\ContractSeeder;
use Database\Seeders\AppointmentSeeder;
use Database\Seeders\TenantSeeder;
use Database\Seeders\TaskSeeder;
use Database\Seeders\CommentSeeder;
use Database\Seeders\OwnerSeeder;
use Database\Seeders\TranslationSeeder;
use Database\Seeders\CitiesTableSeeder;
use Database\Seeders\TestUsersSeeder;
use Database\Seeders\FacilityPageSeeder;
use Database\Seeders\BusinessCategoryTemplatesSeeder;


class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. البيانات الأساسية
        $this->call([
            LanguageSeeder::class,      // اللغات
            TranslationSeeder::class,   // الترجمات
            BankSeeder::class,          // البنوك
            CitiesTableSeeder::class,   // المدن
        ]);

        // 2. الأدوار والصلاحيات
        $this->call([
            PermissionSeeder::class,    // الصلاحيات
            RoleSeeder::class,          // الأدوار
        ]);

        // 3. المستخدمين والملاك والمنشآت
        $this->call([
            UserSeeder::class,          // المستخدمين
            OwnerSeeder::class,         // الملاك
            FacilitySeeder::class,      // المنشآت
            TestUsersSeeder::class,
            FacilityPageSeeder::class,    // صفحات المنشآت
        ]);

        // 4. التصنيفات والميزات
        $this->call([
            CategorySeeder::class,      // التصنيفات
            AttributeSeeder::class,      // السمات/الخصائص
            FeatureSeeder::class,       // الميزات
            PackageSeeder::class,       // الباقات
            BusinessCategoryTemplatesSeeder::class, // قوالب الفئات
        ]);

        // 5. العقارات والمباني
        $this->call([
            BuildingSeeder::class,      // المباني
            ProductSeeder::class,       // العقارات
        ]);

        // 6. الحجوزات والعقود
        $this->call([
            // BookingSeeder::class, // Temporarily commented out due to dependency on ProductSeeder       // الحجوزات
            ContractSeeder::class,      // العقود
            AppointmentSeeder::class,   // المواعيد
        ]);

        // 7. المستأجرين والمهام
        $this->call([
            TenantSeeder::class,        // المستأجرين
            TaskSeeder::class,          // المهام
            // CommentSeeder::class, // Temporarily commented out due to dependency on ProductSeeder       // التعليقات
        ]);
    }
}
