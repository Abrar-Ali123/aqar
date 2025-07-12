<?php

namespace Database\Seeders;

use App\Models\FacilityPage;
use Illuminate\Database\Seeder;

class FacilityPageSeeder extends Seeder
{
    public function run()
    {
        $pages = [
            [
                'facility_id' => 1,
                'title' => 'متجر الأناقة',
                'slug' => 'elegance-store-page',
                'is_active' => true,
                'translations' => json_encode([
                    'ar' => [
                        'title' => 'متجر الأناقة',
                        'description' => 'متجر متخصص في الملابس الراقية',
                        'keywords' => 'ملابس,أزياء,أناقة'
                    ],
                    'en' => [
                        'title' => 'Elegance Store',
                        'description' => 'Specialized store in elegant clothing',
                        'keywords' => 'clothing,fashion,elegance'
                    ]
                ]),
                'settings' => json_encode([
                    'layout' => 'modern',
                    'theme' => 'light',
                    'rtl' => true,
                    'show_logo' => true,
                    'show_search' => true,
                    'show_cart' => true,
                    'show_wishlist' => true
                ]),
                'template_settings' => json_encode([
                    'header_style' => 'transparent',
                    'footer_style' => 'dark',
                    'sidebar_position' => 'right',
                    'product_view' => 'grid'
                ]),
                'design_settings' => json_encode([
                    'primary_color' => '#2C3E50',
                    'secondary_color' => '#E74C3C',
                    'font_family' => 'Cairo',
                    'button_style' => 'rounded',
                    'custom_css' => ''
                ]),
                'analytics_settings' => json_encode([
                    'track_views' => true,
                    'track_clicks' => true,
                    'track_sales' => true,
                    'google_analytics' => false
                ]),
                'contact_info' => json_encode([
                    'phone' => '+966500000000',
                    'email' => 'contact@elegance.com',
                    'address' => 'الرياض - شارع العليا',
                    'working_hours' => '9:00 AM - 11:00 PM',
                    'social_media' => [
                        'facebook' => 'elegance.store',
                        'instagram' => 'elegance.store',
                        'twitter' => 'elegance_store'
                    ]
                ]),
                'reviews_settings' => json_encode([
                    'enabled' => true,
                    'require_purchase' => true,
                    'moderate_reviews' => true,
                    'allow_photos' => true
                ]),
                'schedule_settings' => json_encode([
                    'timezone' => 'Asia/Riyadh',
                    'business_hours' => [
                        'sunday' => ['09:00-22:00'],
                        'monday' => ['09:00-22:00'],
                        'tuesday' => ['09:00-22:00'],
                        'wednesday' => ['09:00-22:00'],
                        'thursday' => ['09:00-22:00'],
                        'friday' => ['16:00-22:00'],
                        'saturday' => ['09:00-22:00']
                    ]
                ]),
                'seo_settings' => json_encode([
                    'meta_title' => 'متجر الأناقة | أفضل الملابس الراقية',
                    'meta_description' => 'متجر متخصص في الملابس الراقية للرجال والنساء',
                    'meta_keywords' => 'ملابس,أزياء,أناقة,متجر,ملابس رجالية,ملابس نسائية',
                    'og_image' => 'store-banner.jpg'
                ]),
                'content' => json_encode([
                    'sections' => [
                        [
                            'type' => 'hero',
                            'title' => [
                                'ar' => 'متجر الأناقة',
                                'en' => 'Elegance Store'
                            ],
                            'subtitle' => [
                                'ar' => 'أناقة تليق بك',
                                'en' => 'Elegance that suits you'
                            ],
                            'background' => 'hero-bg.jpg'
                        ],
                        [
                            'type' => 'products',
                            'title' => [
                                'ar' => 'منتجاتنا',
                                'en' => 'Our Products'
                            ],
                            'limit' => 8
                        ],
                        [
                            'type' => 'contact',
                            'title' => [
                                'ar' => 'تواصل معنا',
                                'en' => 'Contact Us'
                            ]
                        ]
                    ]
                ]),
                'meta_data' => json_encode([
                    'views' => 0,
                    'likes' => 0,
                    'shares' => 0
                ])
            ],
            [
                'facility_id' => 2,
                'title' => 'مطعم الذواقة',
                'slug' => 'gourmet-restaurant-page',
                'is_active' => true,
                'content' => json_encode([
                    'sections' => [
                        [
                            'type' => 'hero',
                            'title' => [
                                'ar' => 'مطعم الذواقة',
                                'en' => 'Gourmet Restaurant'
                            ],
                            'subtitle' => [
                                'ar' => 'مذاق لا يُنسى',
                                'en' => 'Unforgettable taste'
                            ],
                            'background' => 'restaurant-bg.jpg'
                        ],
                        [
                            'type' => 'menu',
                            'title' => [
                                'ar' => 'قائمة الطعام',
                                'en' => 'Menu'
                            ]
                        ],
                        [
                            'type' => 'contact',
                            'title' => [
                                'ar' => 'احجز طاولتك',
                                'en' => 'Book Your Table'
                            ]
                        ]
                    ]
                ]),
                'meta_data' => json_encode([
                    'views' => 0,
                    'likes' => 0,
                    'shares' => 0
                ])
            ]
        ];

        foreach ($pages as $page) {
            FacilityPage::create($page);
        }
    }
}
