<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeTranslation;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    public function run()
    {
        $attributes = [
            [
                'name' => [
                    'ar' => 'المساحة',
                    'en' => 'Area'
                ],
                'type' => 'number',
                'required' => true,
                'category_id' => 1,
                'icon' => 'area-icon',
                'Symbol' => 'm²'
            ],
            [
                'name' => [
                    'ar' => 'عدد الغرف',
                    'en' => 'Rooms'
                ],
                'type' => 'number',
                'required' => true,
                'category_id' => 1,
                'icon' => 'room-icon',
                'Symbol' => null
            ],
            [
                'name' => [
                    'ar' => 'الموقع',
                    'en' => 'Location'
                ],
                'type' => 'text',
                'required' => true,
                'category_id' => 1,
                'icon' => 'location-icon',
                'Symbol' => null
            ]
        ];

        foreach ($attributes as $attributeData) {
            $attribute = Attribute::create([
                'type' => $attributeData['type'],
                'required' => $attributeData['required'],
                'category_id' => $attributeData['category_id'],
                'icon' => $attributeData['icon'],
                'Symbol' => $attributeData['Symbol']
            ]);

            foreach (['ar', 'en'] as $locale) {
                AttributeTranslation::create([
                    'attribute_id' => $attribute->id,
                    'locale' => $locale,
                    'name' => $attributeData['name'][$locale]
                ]);
            }
        }
    }
}
