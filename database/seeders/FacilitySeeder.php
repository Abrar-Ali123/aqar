<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\PageTemplate;
use App\Models\FacilityPage;
use App\Models\BusinessCategory;
use App\Models\BusinessSector;

class FacilitySeeder extends Seeder
{
    public function run(): void
    {
        // إنشاء القوالب
        $templates = [
            // القالب العصري
            [
                'name' => 'القالب العصري',
                'slug' => 'modern-template',
                'description' => 'قالب عصري بتصميم حديث',
                'is_active' => true,
                'is_default' => true,
                'category' => 'general',
                'styles' => json_encode([
                    'colors' => [
                        'primary' => '#2563eb',
                        'secondary' => '#475569',
                        'accent' => '#f59e0b'
                    ],
                    'typography' => [
                        'heading' => 'Tajawal, sans-serif',
                        'body' => 'Cairo, sans-serif'
                    ],
                    'layout' => 'max-width: 1200px; margin: 0 auto;'
                ]),
                'layout' => json_encode([
                    'sections' => [
                        [
                            'type' => 'slider',
                            'settings' => [
                                'component' => 'hero-slider',
                                'autoplay' => true,
                                'interval' => 5000,
                                'slides' => [
                                    [
                                        'image' => '/dashboard/images/companies/img-1.png',
                                        'title' => 'المقر الرئيسي',
                                        'description' => 'تصميم عصري وحديث'
                                    ],
                                    [
                                        'image' => '/dashboard/images/companies/img-2.png',
                                        'title' => 'منتجاتنا',
                                        'description' => 'أفضل المنتجات بأعلى جودة'
                                    ],
                                    [
                                        'image' => '/dashboard/images/companies/img-3.png',
                                        'title' => 'خدماتنا',
                                        'description' => 'خدمات متكاملة لعملائنا'
                                    ]
                                ]
                            ]
                        ],
                        [
                            'type' => 'products',
                            'settings' => [
                                'component' => 'featured-products',
                                'layout' => 'grid',
                                'columns' => 3,
                                'products' => [
                                    [
                                        'image' => '/dashboard/images/products/img-1.png',
                                        'title' => 'منتج 1',
                                        'price' => '199'
                                    ],
                                    [
                                        'image' => '/dashboard/images/products/img-2.png',
                                        'title' => 'منتج 2',
                                        'price' => '299'
                                    ],
                                    [
                                        'image' => '/dashboard/images/products/img-3.png',
                                        'title' => 'منتج 3',
                                        'price' => '399'
                                    ]
                                ]
                            ]
                        ],
                        [
                            'type' => 'map',
                            'settings' => [
                                'component' => 'google-map',
                                'zoom' => 15
                            ]
                        ]
                    ]
                ])
            ],

            // القالب الكلاسيكي
            [
                'name' => 'القالب الكلاسيكي',
                'slug' => 'classic-template',
                'description' => 'قالب كلاسيكي بتصميم أنيق',
                'is_active' => true,
                'is_default' => false,
                'category' => 'general',
                'styles' => json_encode([
                    'colors' => [
                        'primary' => '#8B4513',
                        'secondary' => '#654321',
                        'accent' => '#D2691E'
                    ],
                    'typography' => [
                        'heading' => 'Amiri, serif',
                        'body' => 'Lateef, serif'
                    ],
                    'layout' => 'max-width: 1000px; margin: 0 auto; padding: 2rem;'
                ]),
                'layout' => json_encode([
                    'sections' => [
                        [
                            'type' => 'text',
                            'settings' => [
                                'component' => 'welcome-text',
                                'content' => '<h1>مرحباً بكم</h1>'
                            ]
                        ],
                        [
                            'type' => 'products',
                            'settings' => [
                                'component' => 'product-list',
                                'layout' => 'list',
                                'products' => [
                                    [
                                        'image' => '/dashboard/images/products/img-4.png',
                                        'title' => 'منتج كلاسيكي 1',
                                        'price' => '599'
                                    ],
                                    [
                                        'image' => '/dashboard/images/products/img-5.png',
                                        'title' => 'منتج كلاسيكي 2',
                                        'price' => '699'
                                    ],
                                    [
                                        'image' => '/dashboard/images/products/img-8.png',
                                        'title' => 'منتج كلاسيكي 3',
                                        'price' => '799'
                                    ]
                                ]
                            ]
                        ],
                        [
                            'type' => 'contact',
                            'settings' => [
                                'component' => 'contact-form',
                                'style' => 'classic'
                            ]
                        ]
                    ]
                ])
            ],

            // القالب المستقبلي
            [
                'name' => 'القالب المستقبلي',
                'slug' => 'futuristic-template',
                'description' => 'قالب مستقبلي بتصميم عصري',
                'is_active' => true,
                'is_default' => false,
                'category' => 'general',
                'styles' => json_encode([
                    'colors' => [
                        'primary' => '#6d28d9',
                        'secondary' => '#4c1d95',
                        'accent' => '#7c3aed'
                    ],
                    'typography' => [
                        'heading' => 'IBM Plex Arabic, sans-serif',
                        'body' => 'Dubai, sans-serif'
                    ],
                    'layout' => 'max-width: 1400px; margin: 0 auto; padding: 1rem;'
                ]),
                'layout' => json_encode([
                    'sections' => [
                        [
                            'type' => 'slider',
                            'settings' => [
                                'component' => 'dynamic-slider',
                                'effect' => '3d'
                            ]
                        ],
                        [
                            'type' => 'products',
                            'settings' => [
                                'component' => 'product-cards',
                                'layout' => 'masonry',
                                'products' => [
                                    [
                                        'image' => '/dashboard/images/products/img-10.png',
                                        'title' => 'منتج مستقبلي 1',
                                        'price' => '899'
                                    ],
                                    [
                                        'image' => '/dashboard/images/products/img-11.png',
                                        'title' => 'منتج مستقبلي 2',
                                        'price' => '999'
                                    ]
                                ]
                            ]
                        ],
                        [
                            'type' => 'reviews',
                            'settings' => [
                                'component' => 'testimonials',
                                'style' => 'modern'
                            ]
                        ]
                    ]
                ])
            ],

            // القالب الطبيعي
            [
                'name' => 'القالب الطبيعي',
                'slug' => 'natural-template',
                'description' => 'قالب بتصميم طبيعي وألوان هادئة',
                'is_active' => true,
                'is_default' => false,
                'category' => 'general',
                'styles' => json_encode([
                    'colors' => [
                        'primary' => '#059669',
                        'secondary' => '#065f46',
                        'accent' => '#10b981'
                    ],
                    'typography' => [
                        'heading' => 'Almarai, sans-serif',
                        'body' => 'Harmattan, sans-serif'
                    ],
                    'layout' => 'max-width: 1200px; margin: 0 auto; padding: 1.5rem;'
                ]),
                'layout' => json_encode([
                    'sections' => [
                        [
                            'type' => 'slider',
                            'settings' => [
                                'component' => 'nature-slider',
                                'effect' => 'fade'
                            ]
                        ],
                        [
                            'type' => 'products',
                            'settings' => [
                                'component' => 'eco-products',
                                'layout' => 'organic',
                                'products' => [
                                    [
                                        'image' => '/dashboard/images/products/img-1.png',
                                        'title' => 'منتج طبيعي 1',
                                        'price' => '199'
                                    ],
                                    [
                                        'image' => '/dashboard/images/products/img-2.png',
                                        'title' => 'منتج طبيعي 2',
                                        'price' => '299'
                                    ]
                                ]
                            ]
                        ],
                        [
                            'type' => 'map',
                            'settings' => [
                                'component' => 'leaflet-map',
                                'style' => 'nature'
                            ]
                        ]
                    ]
                ])
            ],

            // القالب الإبداعي
            [
                'name' => 'القالب الإبداعي',
                'slug' => 'creative-template',
                'description' => 'قالب إبداعي بتصميم مميز',
                'is_active' => true,
                'is_default' => false,
                'category' => 'general',
                'styles' => json_encode([
                    'colors' => [
                        'primary' => '#9333ea',
                        'secondary' => '#6b21a8',
                        'accent' => '#a855f7'
                    ],
                    'typography' => [
                        'heading' => 'Noto Kufi Arabic, sans-serif',
                        'body' => 'Noto Sans Arabic, sans-serif'
                    ],
                    'layout' => 'max-width: 1300px; margin: 0 auto; padding: 2rem;'
                ]),
                'layout' => json_encode([
                    'sections' => [
                        [
                            'type' => 'slider',
                            'settings' => [
                                'component' => 'creative-slider',
                                'effect' => 'parallax'
                            ]
                        ],
                        [
                            'type' => 'products',
                            'settings' => [
                                'component' => 'art-gallery',
                                'layout' => 'mosaic',
                                'products' => [
                                    [
                                        'image' => '/dashboard/images/products/img-3.png',
                                        'title' => 'منتج إبداعي 1',
                                        'price' => '399'
                                    ],
                                    [
                                        'image' => '/dashboard/images/products/img-4.png',
                                        'title' => 'منتج إبداعي 2',
                                        'price' => '499'
                                    ]
                                ]
                            ]
                        ],
                        [
                            'type' => 'reviews',
                            'settings' => [
                                'component' => 'creative-testimonials',
                                'style' => 'artistic'
                            ]
                        ]
                    ]
                ])
            ]
        ];

        foreach ($templates as $template) {
            $layout = $template['layout'];
            unset($template['layout']);
            
            // تحقق من وجود القالب
            $existingTemplate = PageTemplate::where('slug', $template['slug'])->first();
            
            if (!$existingTemplate) {
                $template = PageTemplate::create($template);
                $template->layout = json_encode($layout);
                $template->save();
            }
        }

        // إنشاء المنشآت
        $facilities = [
            [
                'is_active' => true,
                'is_featured' => true,
                'is_primary' => true,
                'logo' => 'public/facilities/img-1.png',
                'header' => 'public/facilities/img-2.png',
                'License' => 'ABC123',
                'latitude' => 24.7136,
                'longitude' => 46.6753,
                'google_maps_url' => 'https://goo.gl/maps/example1',
                'default_locale' => 'ar',
                'supported_locales' => json_encode(['ar', 'en']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'is_active' => true,
                'is_featured' => true,
                'is_primary' => false,
                'logo' => 'public/facilities/img-3.png',
                'header' => 'public/facilities/img-4.png',
                'License' => 'XYZ789',
                'latitude' => 21.4858,
                'longitude' => 39.1925,
                'google_maps_url' => 'https://goo.gl/maps/example2',
                'default_locale' => 'ar',
                'supported_locales' => json_encode(['ar', 'en']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'is_active' => true,
                'is_featured' => true,
                'is_primary' => false,
                'logo' => 'public/facilities/img-5.png',
                'header' => 'public/facilities/img-6.png',
                'License' => 'DEF456',
                'latitude' => 26.2172,
                'longitude' => 50.1971,
                'google_maps_url' => 'https://goo.gl/maps/example3',
                'default_locale' => 'ar',
                'supported_locales' => json_encode(['ar', 'en']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'is_active' => true,
                'is_featured' => false,
                'is_primary' => false,
                'logo' => 'public/facilities/img-7.png',
                'header' => 'public/facilities/img-1.png',
                'License' => 'GHI789',
                'latitude' => 24.4539,
                'longitude' => 39.6142,
                'google_maps_url' => 'https://goo.gl/maps/example4',
                'default_locale' => 'ar',
                'supported_locales' => json_encode(['ar', 'en']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'is_active' => true,
                'is_featured' => true,
                'is_primary' => false,
                'logo' => 'public/facilities/img-2.png',
                'header' => 'public/facilities/img-3.png',
                'License' => 'JKL012',
                'latitude' => 28.3835,
                'longitude' => 36.5662,
                'google_maps_url' => 'https://goo.gl/maps/example5',
                'default_locale' => 'ar',
                'supported_locales' => json_encode(['ar', 'en']),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        foreach ($facilities as $index => $facility) {
            $facilityId = DB::table('facilities')->insertGetId($facility);
            
            $styles = [
                // النمط العصري
                [
                    'layout' => 'display: grid; gap: 2rem; background: linear-gradient(135deg, #f6f8fc 0%, #ffffff 100%);',
                    'colors' => [
                        'primary' => '#4F46E5',
                        'secondary' => '#10B981',
                        'accent' => '#F59E0B'
                    ],
                    'fonts' => [
                        'heading' => 'font-family: "Cairo", sans-serif; font-weight: 700;',
                        'body' => 'font-family: "Tajawal", sans-serif;'
                    ],
                    'components' => [
                        'card' => 'background: white; border-radius: 1rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1); transition: transform 0.2s;',
                        'button' => 'background: #4F46E5; color: white; padding: 0.75rem 1.5rem; border-radius: 0.5rem; font-weight: 600;',
                        'input' => 'border: 2px solid #E5E7EB; border-radius: 0.5rem; padding: 0.75rem;'
                    ]
                ],
                // النمط الكلاسيكي
                [
                    'layout' => 'display: grid; gap: 1.5rem; background-color: #FDF6E3;',
                    'colors' => [
                        'primary' => '#8B4513',
                        'secondary' => '#A0522D',
                        'accent' => '#CD853F'
                    ],
                    'fonts' => [
                        'heading' => 'font-family: "Amiri", serif; font-weight: 700;',
                        'body' => 'font-family: "Noto Naskh Arabic", serif;'
                    ],
                    'layout' => 'classic'
                ],
                [
                    'colors' => [
                        'primary' => '#6d28d9',
                        'secondary' => '#4c1d95',
                        'accent' => '#7c3aed'
                    ],
                    'typography' => [
                        'heading' => 'IBM Plex Arabic, sans-serif',
                        'body' => 'Dubai, sans-serif'
                    ],
                    'layout' => 'futuristic'
                ],
                [
                    'colors' => [
                        'primary' => '#059669',
                        'secondary' => '#065f46',
                        'accent' => '#10b981'
                    ],
                    'typography' => [
                        'heading' => 'Almarai, sans-serif',
                        'body' => 'Harmattan, sans-serif'
                    ],
                    'layout' => 'natural'
                ],
                [
                    'colors' => [
                        'primary' => '#9333ea',
                        'secondary' => '#6b21a8',
                        'accent' => '#a855f7'
                    ],
                    'typography' => [
                        'heading' => 'Noto Kufi Arabic, sans-serif',
                        'body' => 'Noto Sans Arabic, sans-serif'
                    ],
                    'layout' => 'creative'
                ]
        ];

            // تحديث المنشأة مع النمط المخصص
            $templateId = PageTemplate::where('slug', match($index) {
                0 => 'modern-template',
                1 => 'classic-template',
                2 => 'futuristic-template',
                3 => 'natural-template',
                4 => 'creative-template',
                default => 'modern-template'
            })->first()->id;

            DB::table('facilities')->where('id', $facilityId)->update([
                'template_id' => $templateId,
                'styles' => json_encode($styles[$index % count($styles)])
            ]);

            DB::table('facility_translations')->insert([
                [
                    'facility_id' => $facilityId,
                    'locale' => 'ar',
                    'name' => match($index) {
                        0 => 'المقر الرئيسي - التصميم العصري',
                        1 => 'فرع جدة - التصميم الكلاسيكي',
                        2 => 'فرع الدمام - التصميم المستقبلي',
                        3 => 'فرع المدينة - التصميم الطبيعي',
                        4 => 'فرع تبوك - التصميم الإبداعي',
                        default => 'فرع ' . $index . ' - تصميم مخصص'
                    },
                    'info' => match($index) {
                        0 => 'المقر الرئيسي بتصميم عصري وألوان حيوية',
                        1 => 'فرع جدة بتصميم كلاسيكي وألوان دافئة',
                        2 => 'فرع الدمام بتصميم مستقبلي وألوان جريئة',
                        3 => 'فرع المدينة بتصميم طبيعي وألوان هادئة',
                        4 => 'فرع تبوك بتصميم إبداعي وألوان مميزة',
                        default => 'فرع مخصص بتصميم فريد'
                    },
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'facility_id' => $facilityId,
                    'locale' => 'en',
                    'name' => match($index) {
                        0 => 'HQ - Modern Design',
                        1 => 'Jeddah - Classic Design',
                        2 => 'Dammam - Futuristic Design',
                        3 => 'Madinah - Natural Design',
                        4 => 'Tabuk - Creative Design',
                        default => 'Branch ' . $index . ' - Custom Design'
                    },
                    'info' => match($index) {
                        0 => 'Main HQ with modern design and vibrant colors',
                        1 => 'Jeddah branch with classic design and warm colors',
                        2 => 'Dammam branch with futuristic design and bold colors',
                        3 => 'Madinah branch with natural design and calm colors',
                        4 => 'Tabuk branch with creative design and unique colors',
                        default => 'Custom branch with unique design'
                    },
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);

            // إنشاء صفحات المنشأة
            $defaultTemplate = PageTemplate::where('is_default', true)->first();
            if ($defaultTemplate) {
                $slug = Str::slug(DB::table('facility_translations')
                    ->where('facility_id', $facilityId)
                    ->where('locale', 'en')
                    ->value('name'));

                FacilityPage::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'facility_id' => $facilityId,
                        'template_id' => $defaultTemplate->id,
                        'title' => DB::table('facility_translations')
                            ->where('facility_id', $facilityId)
                            ->where('locale', 'ar')
                            ->value('name'),
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                );
            }
        }
    }
}
