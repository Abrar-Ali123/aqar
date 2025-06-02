<?php

namespace App\Documentation;

/**
 * توثيق API النظام
 * 
 * يوفر هذا الملف توثيقاً شاملاً لجميع نقاط النهاية API في النظام
 */
class ApiDocumentation
{
    /**
     * الحصول على توثيق API كامل
     *
     * @return array
     */
    public static function getFullDocumentation(): array
    {
        return [
            'facilities' => [
                'endpoints' => [
                    'GET /api/facilities' => [
                        'description' => 'قائمة المنشآت',
                        'parameters' => [
                            'page' => 'رقم الصفحة',
                            'per_page' => 'عدد العناصر في الصفحة',
                            'search' => 'كلمة البحث',
                            'type' => 'نوع المنشأة',
                            'status' => 'حالة المنشأة'
                        ],
                        'response' => [
                            'data' => 'مصفوفة من المنشآت',
                            'meta' => 'معلومات الترقيم'
                        ]
                    ],
                    'POST /api/facilities' => [
                        'description' => 'إنشاء منشأة جديدة',
                        'parameters' => [
                            'name' => 'اسم المنشأة (مطلوب)',
                            'type_id' => 'معرف النوع (مطلوب)',
                            'description' => 'وصف المنشأة',
                            'location' => 'الموقع',
                            'contact_info' => 'معلومات الاتصال'
                        ]
                    ]
                ],
                'models' => [
                    'Facility' => [
                        'attributes' => [
                            'id' => 'معرف المنشأة',
                            'name' => 'اسم المنشأة',
                            'type_id' => 'معرف النوع',
                            'status' => 'الحالة',
                            'created_at' => 'تاريخ الإنشاء',
                            'updated_at' => 'تاريخ التحديث'
                        ]
                    ]
                ]
            ],
            'users' => [
                'endpoints' => [
                    'GET /api/users' => [
                        'description' => 'قائمة المستخدمين',
                        'parameters' => [
                            'page' => 'رقم الصفحة',
                            'role' => 'الدور',
                            'status' => 'الحالة'
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     * الحصول على توثيق نقطة نهاية محددة
     *
     * @param string $endpoint
     * @return array|null
     */
    public static function getEndpointDocumentation(string $endpoint): ?array
    {
        $docs = self::getFullDocumentation();
        foreach ($docs as $section) {
            if (isset($section['endpoints'][$endpoint])) {
                return $section['endpoints'][$endpoint];
            }
        }
        return null;
    }
}
