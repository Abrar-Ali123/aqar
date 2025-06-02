<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FlexibleSystem\EntityType;

class EntityTypesSeeder extends Seeder
{
    public function run()
    {
        // إنشاء نوع المنتج
        $productType = EntityType::create([
            'code' => 'product',
            'name' => 'منتج',
            'description' => 'النوع الأساسي للمنتجات',
            'attributes' => [
                'name' => [
                    'name' => 'الاسم',
                    'type' => 'string',
                    'required' => true
                ],
                'description' => [
                    'name' => 'الوصف',
                    'type' => 'text',
                    'required' => true
                ],
                'price' => [
                    'name' => 'السعر',
                    'type' => 'decimal',
                    'required' => true
                ],
                'status' => [
                    'name' => 'الحالة',
                    'type' => 'enum',
                    'options' => ['active', 'inactive', 'draft'],
                    'default' => 'active'
                ]
            ],
            'behaviors' => [
                'searchable' => [
                    'fields' => ['name', 'description', 'attributes']
                ],
                'reviewable' => [
                    'enabled' => true,
                    'moderation' => 'required'
                ],
                'comparable' => [
                    'enabled' => true
                ]
            ],
            'relationships' => [
                'allowed_types' => ['facility', 'category'],
                'rules' => [
                    'facility' => [
                        'type' => 'belongs_to',
                        'required' => true
                    ],
                    'category' => [
                        'type' => 'belongs_to_many',
                        'required' => true
                    ]
                ]
            ],
            'validation_rules' => [
                'name' => 'required|min:3|max:255',
                'price' => 'required|numeric|min:0',
                'status' => 'required|in:active,inactive,draft'
            ],
            'permissions' => [
                'create' => ['admin', 'vendor'],
                'update' => ['admin', 'vendor', 'owner'],
                'delete' => ['admin', 'owner'],
                'view' => ['*']
            ]
        ]);

        // إنشاء نوع المنشأة
        $facilityType = EntityType::create([
            'code' => 'facility',
            'name' => 'منشأة',
            'description' => 'النوع الأساسي للمنشآت',
            'attributes' => [
                'name' => [
                    'name' => 'الاسم',
                    'type' => 'string',
                    'required' => true
                ],
                'description' => [
                    'name' => 'الوصف',
                    'type' => 'text',
                    'required' => true
                ],
                'address' => [
                    'name' => 'العنوان',
                    'type' => 'object',
                    'required' => true,
                    'fields' => [
                        'street' => 'string',
                        'city' => 'string',
                        'state' => 'string',
                        'postal_code' => 'string',
                        'coordinates' => 'point'
                    ]
                ],
                'contact' => [
                    'name' => 'معلومات التواصل',
                    'type' => 'object',
                    'required' => true,
                    'fields' => [
                        'phone' => 'string',
                        'email' => 'email',
                        'website' => 'url'
                    ]
                ],
                'working_hours' => [
                    'name' => 'ساعات العمل',
                    'type' => 'object',
                    'fields' => [
                        'sunday' => 'timerange',
                        'monday' => 'timerange',
                        'tuesday' => 'timerange',
                        'wednesday' => 'timerange',
                        'thursday' => 'timerange',
                        'friday' => 'timerange',
                        'saturday' => 'timerange'
                    ]
                ],
                'status' => [
                    'name' => 'الحالة',
                    'type' => 'enum',
                    'options' => ['active', 'inactive', 'pending'],
                    'default' => 'pending'
                ]
            ],
            'behaviors' => [
                'searchable' => [
                    'fields' => ['name', 'description', 'address']
                ],
                'reviewable' => [
                    'enabled' => true,
                    'moderation' => 'required'
                ],
                'bookable' => [
                    'enabled' => true,
                    'settings' => [
                        'duration_unit' => 'hour',
                        'min_duration' => 1,
                        'max_duration' => 8
                    ]
                ]
            ],
            'relationships' => [
                'allowed_types' => ['product', 'service', 'category'],
                'rules' => [
                    'product' => [
                        'type' => 'has_many',
                        'required' => false
                    ],
                    'service' => [
                        'type' => 'has_many',
                        'required' => false
                    ],
                    'category' => [
                        'type' => 'belongs_to_many',
                        'required' => true
                    ]
                ]
            ],
            'validation_rules' => [
                'name' => 'required|min:3|max:255',
                'address.city' => 'required',
                'contact.phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
                'contact.email' => 'required|email'
            ],
            'permissions' => [
                'create' => ['admin'],
                'update' => ['admin', 'owner'],
                'delete' => ['admin'],
                'view' => ['*']
            ],
            'settings' => [
                'requires_verification' => true,
                'auto_approve_products' => false,
                'max_products' => 1000,
                'max_services' => 100,
                'notification_channels' => ['email', 'sms', 'system']
            ]
        ]);

        // إنشاء نوع الخدمة
        $serviceType = EntityType::create([
            'code' => 'service',
            'name' => 'خدمة',
            'description' => 'النوع الأساسي للخدمات',
            'attributes' => [
                'name' => [
                    'name' => 'الاسم',
                    'type' => 'string',
                    'required' => true
                ],
                'description' => [
                    'name' => 'الوصف',
                    'type' => 'text',
                    'required' => true
                ],
                'price' => [
                    'name' => 'السعر',
                    'type' => 'decimal',
                    'required' => true
                ],
                'duration' => [
                    'name' => 'المدة',
                    'type' => 'object',
                    'required' => true,
                    'fields' => [
                        'value' => 'integer',
                        'unit' => 'enum:minute,hour,day'
                    ]
                ],
                'availability' => [
                    'name' => 'الأوقات المتاحة',
                    'type' => 'object',
                    'fields' => [
                        'schedule' => 'json',
                        'exceptions' => 'json'
                    ]
                ],
                'status' => [
                    'name' => 'الحالة',
                    'type' => 'enum',
                    'options' => ['active', 'inactive', 'draft'],
                    'default' => 'active'
                ]
            ],
            'behaviors' => [
                'searchable' => [
                    'fields' => ['name', 'description']
                ],
                'bookable' => [
                    'enabled' => true,
                    'settings' => [
                        'requires_approval' => true,
                        'cancellation_policy' => '24h'
                    ]
                ],
                'reviewable' => [
                    'enabled' => true
                ]
            ],
            'relationships' => [
                'allowed_types' => ['facility', 'category'],
                'rules' => [
                    'facility' => [
                        'type' => 'belongs_to',
                        'required' => true
                    ],
                    'category' => [
                        'type' => 'belongs_to_many',
                        'required' => true
                    ]
                ]
            ],
            'validation_rules' => [
                'name' => 'required|min:3|max:255',
                'price' => 'required|numeric|min:0',
                'duration.value' => 'required|integer|min:1',
                'duration.unit' => 'required|in:minute,hour,day'
            ],
            'permissions' => [
                'create' => ['admin', 'facility_owner'],
                'update' => ['admin', 'facility_owner', 'service_provider'],
                'delete' => ['admin', 'facility_owner'],
                'view' => ['*']
            ]
        ]);
    }
}
