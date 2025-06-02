<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Facility;
use App\Models\PageTemplate;
use App\Models\FacilityPage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class FacilityPageSeeder extends Seeder
{
    public function run()
    {
        // تنظيف التخزين المؤقت
        Cache::flush();

        // حذف البيانات القديمة
        FacilityPage::query()->delete();
        PageTemplate::query()->delete();

        // إنشاء قالب صفحة جديد
        $template = PageTemplate::create([
            'name' => 'قالب عصري للمنشآت',
            'slug' => 'modern-facility-template',
            'description' => 'قالب احترافي يتضمن كافة العناصر الأساسية للمنشأة',
            'ui_components' => json_encode([
                'hero' => [
                    'type' => 'slider',
                    'settings' => [
                        'autoplay' => true,
                        'interval' => 5,
                        'images' => [
                            '/images/facilities/hero1.jpg',
                            '/images/facilities/hero2.jpg',
                            '/images/facilities/hero3.jpg'
                        ]
                    ]
                ],
                'about' => [
                    'type' => 'text',
                    'settings' => [
                        'content' => '<h2 class="text-3xl font-bold mb-4">مرحباً بكم في منشأتنا</h2>
                                    <p class="text-gray-600 leading-relaxed">نحن نقدم أفضل الخدمات والمنتجات لعملائنا الكرام. نسعى دائماً للتميز والابتكار في كل ما نقدمه.</p>',
                        'alignment' => 'center',
                        'style' => 'heading'
                    ]
                ],
                'products' => [
                    'type' => 'products',
                    'settings' => [
                        'layout' => 'grid',
                        'filters' => ['category', 'price'],
                        'limit' => 6
                    ]
                ],
                'contact' => [
                    'type' => 'contact',
                    'settings' => [
                        'fields' => [
                            ['name' => 'name', 'type' => 'text', 'label' => 'الاسم'],
                            ['name' => 'email', 'type' => 'email', 'label' => 'البريد الإلكتروني'],
                            ['name' => 'message', 'type' => 'textarea', 'label' => 'الرسالة']
                        ],
                        'success_message' => 'تم إرسال رسالتك بنجاح، سنتواصل معك قريباً'
                    ]
                ],
                'map' => [
                    'type' => 'map',
                    'settings' => [
                        'location' => ['lat' => 24.7136, 'lng' => 46.6753], // الرياض
                        'zoom' => 15,
                        'height' => 400
                    ]
                ],
                'reviews' => [
                    'type' => 'reviews',
                    'settings' => [
                        'layout' => 'slider',
                        'limit' => 5,
                        'show_rating' => true
                    ]
                ]
            ]),
            'styles' => json_encode([
                'layout' => 'background-color: #ffffff; max-width: 1200px; margin: 0 auto;',
                'typography' => [
                    'heading' => 'font-family: "Cairo", sans-serif; color: #2d3748;',
                    'body' => 'font-family: "Cairo", sans-serif; color: #4a5568;'
                ],
                'colors' => [
                    'primary' => '#3182ce',
                    'secondary' => '#718096',
                    'accent' => '#ed8936'
                ]
            ]),
            'layout' => json_encode([
                'sections' => [
                    [
                        'type' => 'slider',
                        'settings' => ['component' => 'hero'],
                        'style' => 'margin-bottom: 2rem;'
                    ],
                    [
                        'type' => 'text',
                        'settings' => ['component' => 'about'],
                        'style' => 'padding: 3rem 0; background-color: #f7fafc;'
                    ],
                    [
                        'type' => 'products',
                        'settings' => ['component' => 'products'],
                        'style' => 'padding: 3rem 0;'
                    ],
                    [
                        'type' => 'grid',
                        'settings' => [
                            'columns' => 2,
                            'gap' => 30,
                            'components' => ['contact', 'map']
                        ],
                        'style' => 'padding: 3rem 0; background-color: #f7fafc;'
                    ],
                    [
                        'type' => 'reviews',
                        'settings' => ['component' => 'reviews'],
                        'style' => 'padding: 3rem 0;'
                    ]
                ]
            ])
        ]);

        // إضافة الصفحة للمنشأة رقم 1
        $facility = Facility::find(1);
        if ($facility) {
            FacilityPage::create([
                'facility_id' => $facility->id,
                'template_id' => $template->id,
                'name' => 'الصفحة الرئيسية',
                'slug' => 'home',
                'is_active' => true,
                'order' => 1,
                'meta_title' => $facility->name,
                'meta_description' => 'الصفحة الرئيسية ل' . $facility->name,
                'content' => json_encode([
                    'sections' => [
                        'hero' => [
                            'title' => $facility->name,
                            'subtitle' => 'نرحب بكم في موقعنا'
                        ],
                        'about' => [
                            'title' => 'من نحن',
                            'content' => 'نحن ' . $facility->name . ' نقدم أفضل الخدمات والمنتجات لعملائنا الكرام.'
                        ]
                    ]
                ])
            ]);
        }
    }
}
