<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $mainCategories = [
            [
                'parent_id' => null,
                'image' => 'categories/residential.png',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'parent_id' => null,
                'image' => 'categories/commercial.png',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        foreach ($mainCategories as $index => $category) {
            $categoryId = DB::table('categories')->insertGetId($category);
            
            // Add translations
            DB::table('category_translations')->insert([
                [
                    'category_id' => $categoryId,
                    'locale' => 'ar',
                    'name' => $index === 0 ? 'عقارات سكنية' : 'عقارات تجارية',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'category_id' => $categoryId,
                    'locale' => 'en',
                    'name' => $index === 0 ? 'Residential Properties' : 'Commercial Properties',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);

            // Add subcategories
            if ($index === 0) { // Residential subcategories
                $subCategories = ['Apartments', 'Villas', 'Land'];
                $subCategoriesAr = ['شقق', 'فلل', 'أراضي'];
            } else { // Commercial subcategories
                $subCategories = ['Offices', 'Shops', 'Warehouses'];
                $subCategoriesAr = ['مكاتب', 'محلات', 'مستودعات'];
            }

            foreach ($subCategories as $subIndex => $subCategory) {
                $subCategoryData = [
                    'parent_id' => $categoryId,
                    'image' => 'categories/' . strtolower($subCategory) . '.png',
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $subCategoryId = DB::table('categories')->insertGetId($subCategoryData);

                DB::table('category_translations')->insert([
                    [
                        'category_id' => $subCategoryId,
                        'locale' => 'ar',
                        'name' => $subCategoriesAr[$subIndex],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'category_id' => $subCategoryId,
                        'locale' => 'en',
                        'name' => $subCategory,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                ]);
            }
        }
    }
}
