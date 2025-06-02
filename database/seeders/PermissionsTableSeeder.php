<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'name' => 'إدارة المواعيد',
                'code' => 'manage_appointments',
                'group' => 'appointments'
            ],
            [
                'name' => 'عرض المواعيد',
                'code' => 'view_appointments',
                'group' => 'appointments'
            ],
            [
                'name' => 'إنشاء المواعيد',
                'code' => 'create_appointments',
                'group' => 'appointments'
            ],
            [
                'name' => 'تعديل المواعيد',
                'code' => 'edit_appointments',
                'group' => 'appointments'
            ],
            [
                'name' => 'حذف المواعيد',
                'code' => 'delete_appointments',
                'group' => 'appointments'
            ]
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['code' => $permission['code']],
                [
                    'name' => $permission['name'],
                    'group' => $permission['group']
                ]
            );
        }
    }
}
