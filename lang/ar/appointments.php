<?php

return [
    // العناوين
    'appointments' => 'المواعيد',
    'create_appointment' => 'إنشاء موعد جديد',
    'edit_appointment' => 'تعديل الموعد',
    
    // أنواع المواعيد
    'types' => [
        'attendance' => 'حضور وانصراف',
        'leave' => 'إجازة',
        'training' => 'تدريب',
        'interview' => 'مقابلة',
        'evaluation' => 'تقييم'
    ],

    // حالات المواعيد
    'status' => [
        'scheduled' => 'مجدول',
        'approved' => 'تمت الموافقة',
        'rejected' => 'مرفوض',
        'completed' => 'مكتمل'
    ],

    // الحقول
    'fields' => [
        'type' => 'النوع',
        'appointment_time' => 'الوقت',
        'description' => 'الوصف',
        'status' => 'الحالة',
        'facility' => 'المنشأة',
        'user' => 'المستخدم'
    ],

    // الأزرار
    'actions' => [
        'create' => 'إنشاء',
        'edit' => 'تعديل',
        'delete' => 'حذف',
        'approve' => 'موافقة',
        'reject' => 'رفض',
        'complete' => 'إكمال'
    ],

    // الرسائل
    'messages' => [
        'created' => 'تم إنشاء الموعد بنجاح',
        'updated' => 'تم تحديث الموعد بنجاح',
        'deleted' => 'تم حذف الموعد بنجاح',
        'status_updated' => 'تم تحديث حالة الموعد بنجاح',
        'overlap' => 'يوجد تعارض في المواعيد'
    ],

    // التصفية
    'filters' => [
        'all_types' => 'جميع الأنواع',
        'upcoming' => 'المواعيد القادمة',
        'past' => 'المواعيد السابقة',
        'today' => 'مواعيد اليوم'
    ],

    // أخرى
    'no_appointments' => 'لا توجد مواعيد',
    'select_type' => 'اختر النوع',
    'notification' => 'إشعار'
];
