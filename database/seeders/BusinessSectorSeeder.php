<?php

namespace Database\Seeders;

use App\Models\BusinessSector;
use Illuminate\Database\Seeder;

class BusinessSectorSeeder extends Seeder
{
    public function run()
    {
        $sectors = [
            [
                'name' => [
                    'ar' => 'تجاري',
                    'en' => 'Commercial'
                ],
                'slug' => 'commercial',
                'is_active' => true
            ],
            [
                'name' => [
                    'ar' => 'صناعي',
                    'en' => 'Industrial'
                ],
                'slug' => 'industrial',
                'is_active' => true
            ],
            [
                'name' => [
                    'ar' => 'خدمي',
                    'en' => 'Services'
                ],
                'slug' => 'services',
                'is_active' => true
            ]
        ];

        foreach ($sectors as $sector) {
            $businessSector = BusinessSector::create([
                'slug' => $sector['slug'],
                'is_active' => $sector['is_active']
            ]);

            $businessSector->translateOrNew('ar')->name = $sector['name']['ar'];
            $businessSector->translateOrNew('en')->name = $sector['name']['en'];
            $businessSector->save();
        }
    }
}
