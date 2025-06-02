<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FlexibleSystem\ProductTemplate;
use App\Models\FlexibleSystem\TemplateField;

class RealEstateTemplateSeeder extends Seeder
{
    public function run()
    {
        // إنشاء قالب العقارات
        $template = ProductTemplate::create([
            'code' => 'real_estate',
            'name' => 'قالب العقارات',
            'category' => 'real_estate',
            'description' => 'قالب شامل للعقارات يدعم جميع أنواع العقارات',
            'fields' => [
                'basic_info' => [
                    'group_name' => 'معلومات أساسية',
                    'fields' => [
                        'type',
                        'property_status',
                        'purpose',
                        'title',
                        'description',
                        'price',
                        'price_type',
                        'negotiable'
                    ]
                ],
                'location' => [
                    'group_name' => 'الموقع',
                    'fields' => [
                        'city',
                        'district',
                        'street',
                        'building_number',
                        'postal_code',
                        'coordinates',
                        'address_details'
                    ]
                ],
                'details' => [
                    'group_name' => 'تفاصيل العقار',
                    'fields' => [
                        'land_area',
                        'building_area',
                        'property_age',
                        'bedrooms',
                        'bathrooms',
                        'living_rooms',
                        'kitchens',
                        'parking_spots',
                        'floor_number',
                        'furnished',
                        'direction'
                    ]
                ],
                'features' => [
                    'group_name' => 'المميزات',
                    'fields' => [
                        'features_list',
                        'nearby_places',
                        'additional_info'
                    ]
                ],
                'media' => [
                    'group_name' => 'الوسائط',
                    'fields' => [
                        'main_image',
                        'gallery',
                        'video_url',
                        'virtual_tour_url',
                        'floor_plan'
                    ]
                ]
            ],
            'validation_rules' => [
                'type' => 'required',
                'purpose' => 'required',
                'price' => 'required|numeric',
                'city' => 'required',
                'district' => 'required'
            ],
            'search_config' => [
                'searchable_fields' => [
                    'title' => 10,
                    'description' => 5,
                    'district' => 3,
                    'city' => 3
                ],
                'filterable_fields' => [
                    'type',
                    'purpose',
                    'price',
                    'bedrooms',
                    'bathrooms',
                    'furnished'
                ]
            ],
            'filter_config' => [
                'price' => [
                    'type' => 'range',
                    'min' => 0,
                    'max' => 10000000
                ],
                'bedrooms' => [
                    'type' => 'select',
                    'options' => [1,2,3,4,5,'5+']
                ],
                'type' => [
                    'type' => 'select',
                    'options' => [
                        'apartment' => 'شقة',
                        'villa' => 'فيلا',
                        'land' => 'أرض',
                        'building' => 'عمارة',
                        'office' => 'مكتب',
                        'shop' => 'محل تجاري'
                    ]
                ]
            ],
            'ui_components' => [
                'show' => [
                    'header' => [
                        'type' => 'property_header',
                        'config' => [
                            'show_price' => true,
                            'show_purpose' => true,
                            'show_type' => true
                        ]
                    ],
                    'gallery' => [
                        'type' => 'media_gallery',
                        'config' => [
                            'layout' => 'grid',
                            'show_main_image' => true,
                            'thumbnails' => true
                        ]
                    ],
                    'details' => [
                        'type' => 'property_details',
                        'config' => [
                            'layout' => 'two_columns',
                            'show_icons' => true
                        ]
                    ],
                    'location' => [
                        'type' => 'location_map',
                        'config' => [
                            'zoom_level' => 15,
                            'show_nearby' => true
                        ]
                    ],
                    'features' => [
                        'type' => 'features_list',
                        'config' => [
                            'layout' => 'grid',
                            'show_icons' => true
                        ]
                    ]
                ]
            ],
            'media_config' => [
                'main_image' => [
                    'required' => true,
                    'max_size' => 2048,
                    'dimensions' => [
                        'min_width' => 800,
                        'min_height' => 600
                    ]
                ],
                'gallery' => [
                    'max_files' => 20,
                    'max_size' => 2048,
                    'allowed_types' => ['image/jpeg', 'image/png']
                ],
                'floor_plan' => [
                    'max_files' => 5,
                    'max_size' => 5120,
                    'allowed_types' => ['image/jpeg', 'image/png', 'application/pdf']
                ]
            ],
            'booking_config' => [
                'enabled' => true,
                'type' => 'viewing',
                'duration_unit' => 'hour',
                'duration' => 1,
                'available_days' => ['sun', 'mon', 'tue', 'wed', 'thu'],
                'working_hours' => [
                    'start' => '09:00',
                    'end' => '17:00',
                    'break' => ['13:00', '14:00']
                ],
                'required_fields' => [
                    'name',
                    'phone',
                    'email',
                    'preferred_time'
                ]
            ]
        ]);

        // إنشاء الحقول
        $fields = [
            [
                'code' => 'type',
                'name' => 'نوع العقار',
                'type' => 'select',
                'options' => [
                    'apartment' => 'شقة',
                    'villa' => 'فيلا',
                    'land' => 'أرض',
                    'building' => 'عمارة',
                    'office' => 'مكتب',
                    'shop' => 'محل تجاري'
                ],
                'is_required' => true,
                'is_searchable' => true,
                'is_filterable' => true,
                'display_order' => 1,
                'group' => 'basic_info'
            ],
            [
                'code' => 'property_status',
                'name' => 'حالة العقار',
                'type' => 'select',
                'options' => [
                    'available' => 'متاح',
                    'sold' => 'تم البيع',
                    'rented' => 'تم التأجير'
                ],
                'is_required' => true,
                'is_filterable' => true,
                'display_order' => 2,
                'group' => 'basic_info'
            ],
            [
                'code' => 'purpose',
                'name' => 'الغرض',
                'type' => 'select',
                'options' => [
                    'sale' => 'للبيع',
                    'rent' => 'للإيجار'
                ],
                'is_required' => true,
                'is_searchable' => true,
                'is_filterable' => true,
                'display_order' => 3,
                'group' => 'basic_info'
            ],
            [
                'code' => 'price',
                'name' => 'السعر',
                'type' => 'price',
                'is_required' => true,
                'is_searchable' => true,
                'is_filterable' => true,
                'is_sortable' => true,
                'display_order' => 4,
                'group' => 'basic_info',
                'validation_rules' => ['numeric', 'min:0']
            ],
            [
                'code' => 'land_area',
                'name' => 'مساحة الأرض',
                'type' => 'number',
                'is_required' => true,
                'is_searchable' => true,
                'is_filterable' => true,
                'is_sortable' => true,
                'display_order' => 1,
                'group' => 'details',
                'ui_settings' => [
                    'suffix' => 'متر مربع',
                    'min' => 0
                ]
            ],
            [
                'code' => 'building_area',
                'name' => 'مساحة البناء',
                'type' => 'number',
                'is_filterable' => true,
                'is_sortable' => true,
                'display_order' => 2,
                'group' => 'details',
                'ui_settings' => [
                    'suffix' => 'متر مربع',
                    'min' => 0
                ]
            ],
            [
                'code' => 'bedrooms',
                'name' => 'غرف النوم',
                'type' => 'select',
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '5+' => '5+'
                ],
                'is_filterable' => true,
                'display_order' => 3,
                'group' => 'details'
            ],
            [
                'code' => 'coordinates',
                'name' => 'الموقع على الخريطة',
                'type' => 'location',
                'is_required' => true,
                'display_order' => 1,
                'group' => 'location'
            ],
            [
                'code' => 'features_list',
                'name' => 'المميزات',
                'type' => 'multiselect',
                'options' => [
                    'elevator' => 'مصعد',
                    'parking' => 'موقف سيارات',
                    'security' => 'أمن',
                    'pool' => 'مسبح',
                    'gym' => 'صالة رياضية',
                    'central_ac' => 'تكييف مركزي',
                    'garden' => 'حديقة'
                ],
                'is_searchable' => true,
                'is_filterable' => true,
                'display_order' => 1,
                'group' => 'features'
            ],
            [
                'code' => 'main_image',
                'name' => 'الصورة الرئيسية',
                'type' => 'image',
                'is_required' => true,
                'display_order' => 1,
                'group' => 'media',
                'validation_rules' => [
                    'image',
                    'mimes:jpeg,png',
                    'max:2048',
                    'dimensions:min_width=800,min_height=600'
                ]
            ]
        ];

        foreach ($fields as $field) {
            $template->fields()->create($field);
        }
    }
}
