<?php

namespace Database\Seeders;

use App\Models\BusinessCategory;
use Illuminate\Database\Seeder;

class BusinessCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            // تجاري
            [
                'sector_id' => 1,
                'name' => [
                    'ar' => 'متاجر',
                    'en' => 'Stores'
                ],
                'slug' => 'stores',
                'is_active' => true
            ],
            [
                'sector_id' => 1,
                'name' => [
                    'ar' => 'مطاعم',
                    'en' => 'Restaurants'
                ],
                'slug' => 'restaurants',
                'is_active' => true
            ],
            // صناعي
            [
                'sector_id' => 2,
                'name' => [
                    'ar' => 'مصانع',
                    'en' => 'Factories'
                ],
                'slug' => 'factories',
                'is_active' => true
            ],
            // خدمي
            [
                'sector_id' => 3,
                'name' => [
                    'ar' => 'عيادات',
                    'en' => 'Clinics'
                ],
                'slug' => 'clinics',
                'is_active' => true
            ]
        ];

        foreach ($categories as $category) {
            $businessCategory = BusinessCategory::create([
                'sector_id' => $category['sector_id'],
                'slug' => $category['slug'],
                'is_active' => $category['is_active']
            ]);

            $businessCategory->translateOrNew('ar')->name = $category['name']['ar'];
            $businessCategory->translateOrNew('en')->name = $category['name']['en'];
            $businessCategory->save();
        }
    }
}
