@component('mail::message')
# تنبيه: خطأ حرج في التطبيق

حدث خطأ حرج في التطبيق يتطلب انتباهك الفوري.

## تفاصيل الخطأ:
- **الرسالة**: {{ $message }}
- **المستخدم**: {{ $user }}
- **الرابط**: {{ $url }}
- **الملف**: {{ $file }}
- **السطر**: {{ $line }}

## التتبع الكامل:
```
{{ $trace }}
```

@component('mail::button', ['url' => config('app.url').'/admin/logs'])
عرض سجلات الأخطاء
@endcomponent

تم إرسال هذا البريد تلقائياً من نظام مراقبة الأخطاء.

مع التحية,<br>
{{ config('app.name') }}
@endcomponent
