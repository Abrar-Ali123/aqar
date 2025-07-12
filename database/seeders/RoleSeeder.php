<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            [
                'name' => [
                    'ar' => 'مدير النظام',
                    'en' => 'Admin'
                ],
                'level' => 1,
                'is_primary' => true,
                'is_paid' => false,
                'price' => null,
                'parent_id' => null,
                'facility_id' => null,
                'permission_id' => null
            ],
            [
                'name' => [
                    'ar' => 'مشرف',
                    'en' => 'Moderator'
                ],
                'level' => 2,
                'is_primary' => true,
                'is_paid' => false,
                'price' => null,
                'parent_id' => 1,
                'facility_id' => null,
                'permission_id' => null
            ],
            [
                'name' => [
                    'ar' => 'صاحب منشأة',
                    'en' => 'Facility Owner'
                ],
                'level' => 3,
                'is_primary' => true,
                'is_paid' => false,
                'price' => null,
                'parent_id' => 2,
                'facility_id' => null,
                'permission_id' => null
            ],
            [
                'name' => [
                    'ar' => 'مستخدم',
                    'en' => 'User'
                ],
                'level' => 4,
                'is_primary' => true,
                'is_paid' => false,
                'price' => null,
                'parent_id' => null,
                'facility_id' => null,
                'permission_id' => null
            ]
        ];

        foreach ($roles as $roleData) {
            $translations = $roleData['name'];
            unset($roleData['name']);
            
            $role = Role::create($roleData);
            
            foreach ($translations as $locale => $name) {
                $role->translateOrNew($locale)->name = $name;
            }
            $role->save();
        }
    }
}
