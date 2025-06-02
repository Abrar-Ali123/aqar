<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PageTemplate;

class FacilityTemplateSeeder extends Seeder
{
    public function run()
    {
        $templates = [
            [
                'name' => 'القالب الأساسي',
                'slug' => 'basic',
                'description' => 'قالب بسيط وأنيق يناسب جميع أنواع المنشآت',
                'thumbnail' => 'templates/basic.jpg',
                'sections' => [
                    'header' => [
                        'order' => 1,
                        'settings' => [
                            'show_search' => true,
                            'show_cta' => true,
                            'style' => 'modern'
                        ]
                    ],
                    'about' => [
                        'order' => 2,
                        'settings' => [
                            'layout' => 'side-by-side',
                            'show_stats' => true
                        ]
                    ],
                    'services' => [
                        'order' => 3,
                        'settings' => [
                            'columns' => 3,
                            'show_icons' => true
                        ]
                    ],
                    'gallery' => [
                        'order' => 4,
                        'settings' => [
                            'layout' => 'grid',
                            'columns' => 4
                        ]
                    ],
                    'testimonials' => [
                        'order' => 5,
                        'settings' => [
                            'style' => 'carousel',
                            'show_rating' => true
                        ]
                    ],
                    'contact' => [
                        'order' => 6,
                        'settings' => [
                            'show_map' => true,
                            'show_form' => true
                        ]
                    ]
                ],
                'styles' => [
                    'colors' => [
                        'primary' => '#2563eb',
                        'secondary' => '#475569',
                        'accent' => '#f59e0b'
                    ],
                    'fonts' => [
                        'heading' => 'Cairo',
                        'body' => 'Tajawal'
                    ]
                ]
            ],
            [
                'name' => 'قالب الفنادق والمنتجعات',
                'slug' => 'hotel',
                'description' => 'قالب متخصص للفنادق والمنتجعات مع نظام حجز متكامل',
                'thumbnail' => 'templates/hotel.jpg',
                'sections' => [
                    'header' => [
                        'order' => 1,
                        'settings' => [
                            'show_booking' => true,
                            'show_video' => true,
                            'style' => 'fullscreen'
                        ]
                    ],
                    'rooms' => [
                        'order' => 2,
                        'settings' => [
                            'layout' => 'grid',
                            'show_prices' => true,
                            'show_availability' => true
                        ]
                    ],
                    'amenities' => [
                        'order' => 3,
                        'settings' => [
                            'layout' => 'icons-grid',
                            'columns' => 4
                        ]
                    ],
                    'dining' => [
                        'order' => 4,
                        'settings' => [
                            'layout' => 'carousel',
                            'show_menu' => true
                        ]
                    ],
                    'spa' => [
                        'order' => 5,
                        'settings' => [
                            'layout' => 'side-by-side',
                            'show_services' => true
                        ]
                    ],
                    'gallery' => [
                        'order' => 6,
                        'settings' => [
                            'layout' => 'masonry',
                            'columns' => 3
                        ]
                    ],
                    'reviews' => [
                        'order' => 7,
                        'settings' => [
                            'style' => 'cards',
                            'show_rating' => true
                        ]
                    ],
                    'location' => [
                        'order' => 8,
                        'settings' => [
                            'show_map' => true,
                            'show_directions' => true
                        ]
                    ]
                ],
                'styles' => [
                    'colors' => [
                        'primary' => '#1e40af',
                        'secondary' => '#334155',
                        'accent' => '#c2410c'
                    ],
                    'fonts' => [
                        'heading' => 'Aref Ruqaa',
                        'body' => 'Almarai'
                    ]
                ]
            ],
            [
                'name' => 'قالب المطاعم والمقاهي',
                'slug' => 'restaurant',
                'description' => 'قالب مخصص للمطاعم والمقاهي مع عرض القوائم والحجوزات',
                'thumbnail' => 'templates/restaurant.jpg',
                'sections' => [
                    'header' => [
                        'order' => 1,
                        'settings' => [
                            'show_reservation' => true,
                            'show_hours' => true,
                            'style' => 'parallax'
                        ]
                    ],
                    'menu' => [
                        'order' => 2,
                        'settings' => [
                            'layout' => 'tabs',
                            'show_prices' => true,
                            'show_images' => true
                        ]
                    ],
                    'specials' => [
                        'order' => 3,
                        'settings' => [
                            'layout' => 'carousel',
                            'show_timer' => true
                        ]
                    ],
                    'gallery' => [
                        'order' => 4,
                        'settings' => [
                            'layout' => 'grid',
                            'columns' => 3
                        ]
                    ],
                    'chefs' => [
                        'order' => 5,
                        'settings' => [
                            'layout' => 'cards',
                            'show_bio' => true
                        ]
                    ],
                    'testimonials' => [
                        'order' => 6,
                        'settings' => [
                            'style' => 'quotes',
                            'show_rating' => true
                        ]
                    ],
                    'reservation' => [
                        'order' => 7,
                        'settings' => [
                            'show_calendar' => true,
                            'show_time_slots' => true
                        ]
                    ],
                    'contact' => [
                        'order' => 8,
                        'settings' => [
                            'show_map' => true,
                            'show_hours' => true
                        ]
                    ]
                ],
                'styles' => [
                    'colors' => [
                        'primary' => '#b91c1c',
                        'secondary' => '#44403c',
                        'accent' => '#ca8a04'
                    ],
                    'fonts' => [
                        'heading' => 'Lateef',
                        'body' => 'IBM Plex Sans Arabic'
                    ]
                ]
            ]
        ];

        foreach ($templates as $template) {
            PageTemplate::create($template);
        }
    }
}
