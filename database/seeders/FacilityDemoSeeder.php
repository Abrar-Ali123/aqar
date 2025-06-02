<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Facility;
use App\Models\User;
use App\Models\Category;
use App\Models\Image;

class FacilityDemoSeeder extends Seeder
{
    public function run()
    {
        // إنشاء منشآت تجريبية متنوعة
        $facilities = [
            [
                'name' => [
                    'ar' => 'برج السلام الفندقي',
                    'en' => 'Al Salam Hotel Tower'
                ],
                'description' => [
                    'ar' => 'برج فندقي فاخر في قلب المدينة مع إطلالات بانورامية خلابة',
                    'en' => 'Luxury hotel tower in the heart of the city with panoramic views'
                ],
                'category' => 'hotels',
                'rating' => 4.8,
                'features' => ['wifi', 'parking', 'pool', 'spa', 'restaurant'],
                'images' => [
                    'hotel-tower-1.jpg',
                    'hotel-tower-2.jpg',
                    'hotel-tower-3.jpg'
                ]
            ],
            [
                'name' => [
                    'ar' => 'مجمع الواحة التجاري',
                    'en' => 'Al Waha Commercial Complex'
                ],
                'description' => [
                    'ar' => 'مجمع تجاري عصري يضم أفضل العلامات التجارية العالمية',
                    'en' => 'Modern commercial complex featuring top global brands'
                ],
                'category' => 'malls',
                'rating' => 4.5,
                'features' => ['parking', 'security', 'food_court', 'cinema'],
                'images' => [
                    'mall-complex-1.jpg',
                    'mall-complex-2.jpg'
                ]
            ],
            [
                'name' => [
                    'ar' => 'مركز الشفاء الطبي',
                    'en' => 'Al Shifa Medical Center'
                ],
                'description' => [
                    'ar' => 'مركز طبي متكامل يقدم خدمات طبية متميزة بأحدث التقنيات',
                    'en' => 'Integrated medical center providing excellent healthcare services'
                ],
                'category' => 'medical',
                'rating' => 4.9,
                'features' => ['parking', 'emergency', 'pharmacy', 'lab'],
                'images' => [
                    'medical-center-1.jpg',
                    'medical-center-2.jpg'
                ]
            ]
        ];

        foreach ($facilities as $facilityData) {
            $facility = Facility::create([
                'name' => $facilityData['name'],
                'description' => $facilityData['description'],
                'rating' => $facilityData['rating'],
                'status' => 'active',
                'owner_id' => User::where('email', 'owner@example.com')->first()->id,
                'category_id' => Category::where('slug', $facilityData['category'])->first()->id
            ]);

            // إضافة الميزات
            foreach ($facilityData['features'] as $feature) {
                $facility->features()->attach(
                    \App\Models\Feature::where('code', $feature)->first()->id
                );
            }

            // إضافة الصور
            foreach ($facilityData['images'] as $image) {
                $facility->images()->create([
                    'path' => 'facilities/' . $image,
                    'type' => 'main'
                ]);
            }
        }
    }
}
