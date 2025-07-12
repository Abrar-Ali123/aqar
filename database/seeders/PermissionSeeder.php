<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            'manage_facilities' => ['en' => 'Manage Facilities', 'ar' => 'إدارة المنشآت'],
            'view_facilities' => ['en' => 'View Facilities', 'ar' => 'عرض المنشآت'],
            'create_facilities' => ['en' => 'Create Facilities', 'ar' => 'إنشاء المنشآت'],
            'edit_facilities' => ['en' => 'Edit Facilities', 'ar' => 'تعديل المنشآت'],
            'delete_facilities' => ['en' => 'Delete Facilities', 'ar' => 'حذف المنشآت'],
            'manage_facility_pages' => ['en' => 'Manage Facility Pages', 'ar' => 'إدارة صفحات المنشأة'],
            'manage_products' => ['en' => 'Manage Products', 'ar' => 'إدارة المنتجات'],
        ];

        foreach ($permissions as $permissionName => $translations) {
                        $permission = Permission::create(['code' => $permissionName, 'guard_name' => 'web']);
            
            foreach ($translations as $locale => $name) {
                $permission->translateOrNew($locale)->name = $name;
            }
            $permission->save();
        }
    }
}
