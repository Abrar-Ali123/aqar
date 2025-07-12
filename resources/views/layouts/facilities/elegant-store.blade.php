<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $facility->name }} - {{ config('app.name') }}</title>
    <meta name="description" content="{{ $facility->description }}">
    
    <!-- الخطوط -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    
    <!-- الأنماط -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <style>
        :root {
            --primary-color: {{ $themeConfig['primary_color'] }};
            --accent-color: {{ $themeConfig['accent_color'] }};
            --heading-font: {{ $themeConfig['heading_font'] }};
            --body-font: {{ $themeConfig['body_font'] }};
        }
        
        body {
            font-family: 'Tajawal', var(--body-font), sans-serif;
        }
    </style>
    
    @stack('styles')
</head>
<body class="elegant-store-template">
    <!-- القائمة العلوية -->
    @include('facilities.pages.partials.elegant-store.navigation')
    
    <!-- المحتوى الرئيسي -->
    <main>
        {{ $slot }}
    </main>
    
    <!-- التذييل -->
    @include('facilities.pages.partials.elegant-store.footer')
    
    <!-- السكربتات -->
    <script src="{{ mix('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
