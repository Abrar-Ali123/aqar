<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributeSeeder extends Seeder
{
    public function run(): void
    {
        // Get first category ID
        $categoryId = DB::table('categories')->first()->id;

        $attributes = [
            [
                'type' => 'select',
                'required' => true,
                'category_id' => $categoryId,
                'icon' => 'fas fa-home',
                'Symbol' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'select',
                'required' => true,
                'category_id' => $categoryId,
                'icon' => 'fas fa-compass',
                'Symbol' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'number',
                'required' => true,
                'category_id' => $categoryId,
                'icon' => 'fas fa-ruler-combined',
                'Symbol' => 'm²',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($attributes as $index => $attribute) {
            $attributeId = DB::table('attributes')->insertGetId($attribute);
            
            // Add translations
            DB::table('attribute_translations')->insert([
                [
                    'attribute_id' => $attributeId,
                    'locale' => 'ar',
                    'name' => match($index) {
                        0 => 'حالة العقار',
                        1 => 'الواجهة',
                        2 => 'المساحة',
                        default => 'خاصية ' . ($index + 1),
                    },
                    'symbol' => $attribute['Symbol'],
                ],
                [
                    'attribute_id' => $attributeId,
                    'locale' => 'en',
                    'name' => match($index) {
                        0 => 'Property Condition',
                        1 => 'Facing Direction',
                        2 => 'Area',
                        default => 'Attribute ' . ($index + 1),
                    },
                    'symbol' => $attribute['Symbol'],
                ]
            ]);
        }
    }
}
