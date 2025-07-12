<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            // منتجات متجر الأناقة
            [
                'facility_id' => 1,
                'category_id' => 1, // فئة الملابس
                'is_featured' => true,
                'name' => [
                    'ar' => 'قميص كلاسيكي',
                    'en' => 'Classic Shirt'
                ],
                'description' => [
                    'ar' => 'قميص كلاسيكي أنيق مناسب لجميع المناسبات',
                    'en' => 'Elegant classic shirt suitable for all occasions'
                ],
                'price' => 299.99,
                'image' => 'https://images.unsplash.com/photo-1598033129183-c4f50c736f10?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3wxMjA3fDB8MXxzZWFyY2h8NHx8Y2xhc3NpYyUyMHNoaXJ0fHwwfHx8fDE3MDYwMjQyOTR8MA&ixlib=rb-4.0.3&q=80&w=800',
                'is_active' => true
            ],
            [
                'facility_id' => 1,
                'category_id' => 1, // فئة الملابس
                'is_featured' => false,
                'name' => [
                    'ar' => 'بنطلون جينز',
                    'en' => 'Jeans'
                ],
                'description' => [
                    'ar' => 'بنطلون جينز عصري بقصة مريحة',
                    'en' => 'Modern jeans with comfortable fit'
                ],
                'price' => 399.99,
                'image' => 'https://images.unsplash.com/photo-1542272604-787c3835535d?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3wxMjA3fDB8MXxzZWFyY2h8Mnx8amVhbnN8fDB8fHx8MTcwNjAyNDI5NHww&ixlib=rb-4.0.3&q=80&w=800',
                'is_active' => true
            ],
            // منتجات مطعم الذواقة
            [
                'facility_id' => 2,
                'category_id' => 2, // فئة المأكولات
                'is_featured' => true,
                'name' => [
                    'ar' => 'برجر لحم واجيو',
                    'en' => 'Wagyu Burger'
                ],
                'description' => [
                    'ar' => 'برجر محضر من أجود أنواع لحم واجيو مع صلصة خاصة',
                    'en' => 'Burger made from premium Wagyu beef with special sauce'
                ],
                'price' => 89.99,
                'image' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3wxMjA3fDB8MXxzZWFyY2h8Mnx8YnVyZ2VyfHwwfHx8fDE3MDYwMjQyOTR8MA&ixlib=rb-4.0.3&q=80&w=800',
                'is_active' => true
            ],
            [
                'facility_id' => 2,
                'category_id' => 2, // فئة المأكولات
                'is_featured' => false,
                'name' => [
                    'ar' => 'باستا الروبيان',
                    'en' => 'Shrimp Pasta'
                ],
                'description' => [
                    'ar' => 'باستا محضرة مع روبيان طازج وصلصة كريمة',
                    'en' => 'Pasta with fresh shrimp and cream sauce'
                ],
                'price' => 79.99,
                'image' => 'https://images.unsplash.com/photo-1563379926898-05f4575a45d8?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3wxMjA3fDB8MXxzZWFyY2h8NHx8c2hyaW1wJTIwcGFzdGF8fDB8fHx8MTcwNjAyNDI5NHww&ixlib=rb-4.0.3&q=80&w=800',
                'is_active' => true
            ]
        ];

        foreach ($products as $product) {
            $name = $product['name'];
            $description = $product['description'];
            
            unset($product['name'], $product['description'], $product['is_featured']);
            $product['slug'] = Str::slug($name['en']);
            
            $newProduct = Product::create($product);
            
            foreach (['ar', 'en'] as $locale) {
                $newProduct->translateOrNew($locale)->name = $name[$locale];
                $newProduct->translateOrNew($locale)->description = $description[$locale];
            }
            
            $newProduct->save();
        }
    }
}
