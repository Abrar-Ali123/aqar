<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => [
                    'ar' => 'إلكترونيات',
                    'en' => 'Electronics'
                ],
                'image' => 'https://images.unsplash.com/photo-1498049794561-7780e7231661?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3wxMjA3fDB8MXxzZWFyY2h8NHx8ZWxlY3Ryb25pY3N8fDB8fHx8MTcwNjAyNDI5NHww&ixlib=rb-4.0.3&q=80&w=800',
                'is_active' => true,
                'order' => 1
            ],
            [
                'name' => [
                    'ar' => 'أزياء',
                    'en' => 'Fashion'
                ],
                'image' => 'https://images.unsplash.com/photo-1445205170230-053b83016050?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3wxMjA3fDB8MXxzZWFyY2h8MTB8fGZhc2hpb258fDB8fHx8MTcwNjAyNDI5NHww&ixlib=rb-4.0.3&q=80&w=800',
                'is_active' => true,
                'order' => 2
            ],
            [
                'name' => [
                    'ar' => 'المنزل والحديقة',
                    'en' => 'Home & Garden'
                ],
                'image' => 'https://images.unsplash.com/photo-1484154218962-a197022b5858?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3wxMjA3fDB8MXxzZWFyY2h8MTB8fGhvbWUlMjBkZWNvcnx8MHx8fHwxNzA2MDI0Mjk0fDA&ixlib=rb-4.0.3&q=80&w=800',
                'is_active' => true,
                'order' => 3
            ]
        ];

        foreach ($categories as $category) {
            $categoryModel = Category::create([
                'image' => $category['image'],
                'is_active' => $category['is_active'],
                'order' => $category['order']
            ]);

            foreach (['ar', 'en'] as $locale) {
                $categoryModel->translations()->create([
                    'locale' => $locale,
                    'name' => $category['name'][$locale]
                ]);
            }
        }
    }
}
