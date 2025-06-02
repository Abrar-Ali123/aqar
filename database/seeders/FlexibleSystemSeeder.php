<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FlexibleSystemSeeder extends Seeder
{
    public function run(): void
    {
        // إنشاء مكونات النظام الأساسية
        $this->createSystemComponents();
        
        // إنشاء الحقول الديناميكية الأساسية
        $this->createDynamicFields();
        
        // إنشاء قواعد العمل الأساسية
        $this->createBusinessRules();
        
        // إنشاء قوالب واجهة المستخدم الأساسية
        $this->createUiTemplates();
    }

    private function createSystemComponents(): void
    {
        $components = [
            [
                'code' => 'user_management',
                'type' => 'module',
                'is_core' => true,
                'is_active' => true,
                'settings' => json_encode([
                    'permissions' => ['create', 'read', 'update', 'delete'],
                    'roles' => ['admin', 'manager', 'user']
                ]),
                'order' => 1
            ],
            [
                'code' => 'property_management',
                'type' => 'module',
                'is_core' => true,
                'is_active' => true,
                'settings' => json_encode([
                    'types' => ['apartment', 'villa', 'office', 'land'],
                    'features' => ['sale', 'rent']
                ]),
                'order' => 2
            ],
            [
                'code' => 'booking_management',
                'type' => 'module',
                'is_core' => true,
                'is_active' => true,
                'settings' => json_encode([
                    'booking_types' => ['daily', 'weekly', 'monthly', 'yearly'],
                    'calendar_settings' => [
                        'working_days' => ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday'],
                        'working_hours' => ['start' => '09:00', 'end' => '17:00'],
                        'time_slot_duration' => 30 // minutes
                    ],
                    'cancellation_rules' => [
                        'allowed_before_hours' => 24,
                        'refund_percentage' => 80
                    ]
                ]),
                'order' => 3
            ],
            [
                'code' => 'maintenance_management',
                'type' => 'module',
                'is_core' => true,
                'is_active' => true,
                'settings' => json_encode([
                    'maintenance_types' => [
                        'preventive' => [
                            'frequency' => 'monthly',
                            'priority' => 'medium'
                        ],
                        'corrective' => [
                            'response_time' => '24h',
                            'priority' => 'high'
                        ],
                        'emergency' => [
                            'response_time' => '2h',
                            'priority' => 'urgent'
                        ]
                    ],
                    'service_categories' => [
                        'electrical',
                        'plumbing',
                        'hvac',
                        'cleaning',
                        'security'
                    ]
                ]),
                'order' => 4
            ]
        ];

        foreach ($components as $component) {
            DB::table('system_components')->insertOrIgnore($component);

            // إضافة الترجمات
            $translations = $this->getComponentTranslations($component['code']);
            foreach ($translations as $translation) {
                DB::table('system_component_translations')->insertOrIgnore([
                    'system_component_id' => DB::table('system_components')->where('code', $component['code'])->value('id'),
                    'locale' => $translation['locale'],
                    'name' => $translation['name'],
                    'description' => $translation['description']
                ]);
            }
        }
    }

    private function getComponentTranslations(string $code): array
    {
        $translations = [
            'user_management' => [
                'ar' => [
                    'name' => 'إدارة المستخدمين',
                    'description' => 'نظام إدارة المستخدمين والصلاحيات'
                ],
                'en' => [
                    'name' => 'User Management',
                    'description' => 'User and permissions management system'
                ]
            ],
            'property_management' => [
                'ar' => [
                    'name' => 'إدارة العقارات',
                    'description' => 'نظام إدارة العقارات والممتلكات'
                ],
                'en' => [
                    'name' => 'Property Management',
                    'description' => 'Property and real estate management system'
                ]
            ],
            'booking_management' => [
                'ar' => [
                    'name' => 'إدارة الحجوزات',
                    'description' => 'نظام إدارة الحجوزات والجدولة'
                ],
                'en' => [
                    'name' => 'Booking Management',
                    'description' => 'Booking and scheduling management system'
                ]
            ],
            'maintenance_management' => [
                'ar' => [
                    'name' => 'إدارة الصيانة',
                    'description' => 'نظام إدارة الصيانة والخدمات'
                ],
                'en' => [
                    'name' => 'Maintenance Management',
                    'description' => 'Maintenance and services management system'
                ]
            ]
        ];

        return [
            ['locale' => 'ar', 'name' => $translations[$code]['ar']['name'], 'description' => $translations[$code]['ar']['description']],
            ['locale' => 'en', 'name' => $translations[$code]['en']['name'], 'description' => $translations[$code]['en']['description']]
        ];
    }

    private function createDynamicFields(): void
    {
        $fields = [
            [
                'code' => 'type',
                'field_type' => 'select',
                'validation_rules' => json_encode(['required']),
                'ui_settings' => json_encode([
                    'options' => [
                        'apartment' => 'شقة',
                        'villa' => 'فيلا',
                        'office' => 'مكتب',
                        'land' => 'أرض'
                    ]
                ]),
                'is_required' => true,
                'is_searchable' => true,
                'is_filterable' => true,
                'order' => 1
            ],
            [
                'code' => 'property_price',
                'field_type' => 'number',
                'validation_rules' => json_encode(['required', 'numeric', 'min:0']),
                'ui_settings' => json_encode([
                    'min' => 0,
                    'step' => 1000
                ]),
                'is_required' => true,
                'is_searchable' => true,
                'is_filterable' => true,
                'is_sortable' => true,
                'order' => 2
            ]
        ];

        foreach ($fields as $field) {
            DB::table('dynamic_fields')->insertOrIgnore($field);

            // إضافة الترجمات
            DB::table('dynamic_field_translations')->insertOrIgnore([
                [
                    'dynamic_field_id' => DB::table('dynamic_fields')->where('code', $field['code'])->value('id'),
                    'locale' => 'ar',
                    'name' => $field['code'] === 'type' ? 'النوع الرئيسي' : 'سعر المنتج',
                    'description' => $field['code'] === 'type' 
                        ? 'النوع الرئيسي للمنتج (شقة، فيلا، مكتب، أرض)'
                        : 'سعر المنتج بالريال السعودي',
                    'placeholder' => $field['code'] === 'type' ? 'اختر النوع الرئيسي' : 'أدخل السعر',
                    'help_text' => $field['code'] === 'type' 
                        ? 'يرجى اختيار النوع الرئيسي من القائمة'
                        : 'أدخل السعر بالريال السعودي'
                ],
                [
                    'dynamic_field_id' => DB::table('dynamic_fields')->where('code', $field['code'])->value('id'),
                    'locale' => 'en',
                    'name' => $field['code'] === 'type' ? 'Main Type' : 'Product Price',
                    'description' => $field['code'] === 'type'
                        ? 'Main type of product (Apartment, Villa, Office, Land)'
                        : 'Product price in SAR',
                    'placeholder' => $field['code'] === 'type' ? 'Select main type' : 'Enter price',
                    'help_text' => $field['code'] === 'type'
                        ? 'Please select the main type from the list'
                        : 'Enter the price in Saudi Riyals'
                ]
            ]);
        }
    }

    private function createBusinessRules(): void
    {
        $rules = [
            [
                'code' => 'price_validation',
                'conditions' => json_encode([
                    [
                        'field' => 'property_price',
                        'operator' => 'greater_than',
                        'value' => 0
                    ]
                ]),
                'actions' => json_encode([
                    [
                        'type' => 'validation',
                        'message' => 'يجب أن يكون سعر المنتج أكبر من صفر'
                    ]
                ]),
                'priority' => 1,
                'is_active' => true
            ]
        ];

        foreach ($rules as $rule) {
            DB::table('business_rules')->insertOrIgnore($rule);

            // إضافة الترجمات
            DB::table('business_rule_translations')->insertOrIgnore([
                [
                    'business_rule_id' => DB::table('business_rules')->where('code', $rule['code'])->value('id'),
                    'locale' => 'ar',
                    'name' => 'التحقق من السعر',
                    'description' => 'التحقق من صحة سعر المنتج',
                    'error_message' => 'يجب أن يكون سعر المنتج أكبر من صفر',
                    'success_message' => 'تم التحقق من السعر بنجاح'
                ],
                [
                    'business_rule_id' => DB::table('business_rules')->where('code', $rule['code'])->value('id'),
                    'locale' => 'en',
                    'name' => 'Price Validation',
                    'description' => 'Validate product price',
                    'error_message' => 'Product price must be greater than zero',
                    'success_message' => 'Price validation successful'
                ]
            ]);
        }
    }

    private function createUiTemplates(): void
    {
        $templates = [
            [
                'code' => 'property_form',
                'layout' => json_encode([
                    'type' => 'form',
                    'columns' => 2,
                    'sections' => [
                        [
                            'title' => 'معلومات المنتج',
                            'fields' => ['type', 'property_price']
                        ]
                    ]
                ]),
                'components' => json_encode([
                    'type' => [
                        'type' => 'select',
                        'required' => true
                    ],
                    'property_price' => [
                        'type' => 'number',
                        'required' => true
                    ]
                ]),
                'is_active' => true,
                'order' => 1
            ]
        ];

        foreach ($templates as $template) {
            DB::table('ui_templates')->insertOrIgnore($template);

            // إضافة الترجمات
            DB::table('ui_template_translations')->insertOrIgnore([
                [
                    'ui_template_id' => DB::table('ui_templates')->where('code', $template['code'])->value('id'),
                    'locale' => 'ar',
                    'name' => 'نموذج المنتج',
                    'description' => 'نموذج إدخال بيانات المنتج'
                ],
                [
                    'ui_template_id' => DB::table('ui_templates')->where('code', $template['code'])->value('id'),
                    'locale' => 'en',
                    'name' => 'Product Form',
                    'description' => 'Product data entry form'
                ]
            ]);
        }
    }
}
