<?php

namespace Database\Seeders;

use App\Models\BusinessCategory;
use App\Models\BusinessSector;
use App\Models\PageTemplate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BusinessCategoryTemplatesSeeder extends Seeder
{
    public function run(): void
    {
        // إنشاء قطاع الأعمال إذا لم يكن موجوداً
        $sector = BusinessSector::updateOrCreate(
            ['slug' => 'services'],
            [
                'name' => 'خدمات',
                'name_en' => 'Services',
                'icon' => 'fas fa-concierge-bell',
                'is_active' => true
            ]
        );

        $categories = [
            [
                'name' => 'مطاعم وكافيهات',
                'slug' => 'restaurants-cafes',
                'features' => [
                    'قائمة طعام رقمية',
                    'حجز طاولات',
                    'طلب طعام',
                    'تقييمات وآراء'
                ],
                'recommended_components' => [
                    [
                        'type' => 'hero',
                        'name' => 'صورة رئيسية مع قائمة',
                        'settings' => [
                            'show_menu_button' => true,
                            'show_reservation_button' => true
                        ]
                    ],
                    [
                        'type' => 'menu',
                        'name' => 'قائمة الطعام',
                        'settings' => [
                            'categories_style' => 'grid',
                            'show_prices' => true,
                            'show_images' => true
                        ]
                    ],
                    [
                        'type' => 'gallery',
                        'name' => 'معرض الصور',
                        'settings' => [
                            'layout' => 'masonry',
                            'show_filters' => true
                        ]
                    ],
                    [
                        'type' => 'reviews',
                        'name' => 'التقييمات',
                        'settings' => [
                            'show_rating' => true,
                            'allow_photos' => true
                        ]
                    ]
                ]
            ],
            [
                'name' => 'عقارات',
                'slug' => 'real-estate',
                'features' => [
                    'عرض العقارات',
                    'البحث المتقدم',
                    'حجز معاينة',
                    'حاسبة التمويل'
                ],
                'recommended_components' => [
                    [
                        'type' => 'hero',
                        'name' => 'بحث العقارات',
                        'settings' => [
                            'show_search' => true,
                            'show_filters' => true
                        ]
                    ],
                    [
                        'type' => 'properties',
                        'name' => 'قائمة العقارات',
                        'settings' => [
                            'view_mode' => 'grid',
                            'show_map' => true
                        ]
                    ],
                    [
                        'type' => 'calculator',
                        'name' => 'حاسبة التمويل',
                        'settings' => [
                            'show_banks' => true,
                            'show_comparison' => true
                        ]
                    ]
                ]
            ],
            [
                'name' => 'متاجر',
                'slug' => 'shops',
                'features' => [
                    'عرض المنتجات',
                    'سلة المشتريات',
                    'الدفع الإلكتروني',
                    'تتبع الطلبات'
                ],
                'recommended_components' => [
                    [
                        'type' => 'hero',
                        'name' => 'عروض المتجر',
                        'settings' => [
                            'show_offers' => true,
                            'autoplay' => true
                        ]
                    ],
                    [
                        'type' => 'products',
                        'name' => 'المنتجات',
                        'settings' => [
                            'view_mode' => 'grid',
                            'show_filters' => true
                        ]
                    ],
                    [
                        'type' => 'categories',
                        'name' => 'التصنيفات',
                        'settings' => [
                            'style' => 'cards',
                            'show_count' => true
                        ]
                    ]
                ]
            ]
        ];

        // إنشاء قطاع الأعمال إذا لم يكن موجوداً
        $sector = BusinessSector::firstOrCreate(
            ['name' => 'خدمات'],
            [
                'name_en' => 'Services',
                'slug' => 'services',
                'icon' => 'fas fa-concierge-bell',
                'is_active' => true
            ]
        );

        foreach ($categories as $category) {
            $category['sector_id'] = $sector->id;
            $businessCategory = BusinessCategory::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
            
            // إنشاء قالب افتراضي لكل فئة
            $name = "قالب {$category['name']} الأساسي";
            $description = "قالب مخصص لـ {$category['name']} مع كافة المميزات الأساسية";
            $slug = Str::slug($name);

            PageTemplate::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'description' => $description,
                    'is_active' => true,
                    'components' => $category['recommended_components'],
                    'settings' => [],
                    'features' => $category['features'],
                    'category_id' => $businessCategory->id
                ]
            );
        }
    }
}
