{{-- في ملف Blade الخاص بك --}}
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اختبار الجلسة</title>
</head>
<body>
    @auth
        <h1>مرحبًا بك، {{ Auth::user()->name }}!</h1>
        <p>أنت مسجل للدخول.</p>
    @endauth

    @guest
        <h1>مرحبًا!</h1>
        <p>يرجى <a href="{{ route('login') }}">تسجيل الدخول</a> للوصول إلى المحتوى.</p>
    @endguest
</body>
</html>
