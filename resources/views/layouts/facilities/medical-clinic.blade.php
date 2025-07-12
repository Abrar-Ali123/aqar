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
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    
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
            font-family: 'Cairo', var(--body-font), sans-serif;
            background-color: #f8fafc;
        }
    </style>
    
    @stack('styles')
</head>
<body class="medical-clinic-template">
    <!-- القائمة العلوية -->
    @include('facilities.pages.partials.medical-clinic.navigation')
    
    <!-- المحتوى الرئيسي -->
    <main>
        {{ $slot }}
    </main>
    
    <!-- التذييل -->
    @include('facilities.pages.partials.medical-clinic.footer')
    
    <!-- زر الحجز السريع -->
    @if($facility->hasBooking())
        <div class="fixed bottom-6 left-6 z-50">
            <a href="#book" 
               class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-full shadow-lg transition-colors">
                <i class="fas fa-calendar-alt"></i>
                <span>احجز موعد</span>
            </a>
        </div>
    @endif
    
    <!-- السكربتات -->
    <script src="{{ mix('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
