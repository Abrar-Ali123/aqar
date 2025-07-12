<?php

namespace Database\Seeders;

use App\Models\FacilityTemplate;
use Illuminate\Database\Seeder;

class FacilityTemplateSeeder extends Seeder
{
    public function run()
    {
        $templates = [
            [
                'name' => 'Modern Store',
                'slug' => 'modern-store',
                'thumbnail' => 'templates/modern-store.jpg',
                'preview_url' => 'templates/modern-store/preview',
                'supported_components' => json_encode([
                    'hero-slider',
                    'featured-products',
                    'categories-grid',
                    'testimonials',
                    'instagram-feed',
                    'contact-form'
                ]),
                'default_settings' => json_encode([
                    'layout' => 'modern',
                    'rtl_support' => true,
                    'show_cart' => true,
                    'show_wishlist' => true
                ]),
                'style_settings' => json_encode([
                    'primary_color' => '#2C3E50',
                    'secondary_color' => '#E74C3C',
                    'font_family' => 'Cairo',
                    'header_style' => 'transparent'
                ]),
                'layout_settings' => json_encode([
                    'header' => [
                        'style' => 'transparent',
                        'show_search' => true,
                        'show_cart' => true
                    ],
                    'footer' => [
                        'style' => 'dark',
                        'columns' => 4
                    ],
                    'sidebar' => [
                        'position' => 'right',
                        'widgets' => ['categories', 'tags', 'featured']
                    ]
                ])
            ],
            // يمكن إضافة المزيد من القوالب هنا
        ];

        foreach ($templates as $template) {
            $newTemplate = FacilityTemplate::create([
                'name' => $template['name'],
                'slug' => $template['slug'],
                'thumbnail' => $template['thumbnail'],
                'preview_url' => $template['preview_url'],
                'supported_components' => $template['supported_components'],
                'default_settings' => $template['default_settings'],
                'style_settings' => $template['style_settings'],
                'layout_settings' => $template['layout_settings'],
                'is_active' => true
            ]);

            // إضافة الترجمات
            $newTemplate->translateOrNew('ar')->name = 'متجر عصري';
            $newTemplate->translateOrNew('ar')->description = 'قالب عصري للمتاجر الإلكترونية';
            
            $newTemplate->translateOrNew('en')->name = 'Modern Store';
            $newTemplate->translateOrNew('en')->description = 'Modern template for e-commerce stores';
            
            $newTemplate->save();
        }
    }
}
