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
    <link href="https://fonts.googleapis.com/css2?family=Aref+Ruqaa:wght@400;700&family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    
    <!-- الأنماط -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <style>
        :root {
            --primary-color: {{ $themeConfig['primary_color'] }};
            --accent-color: {{ $themeConfig['accent_color'] }};
            --heading-font: 'Aref Ruqaa', {{ $themeConfig['heading_font'] }}, serif;
            --body-font: {{ $themeConfig['body_font'] }};
        }
        
        body {
            font-family: 'Tajawal', var(--body-font), sans-serif;
            background-color: #0a0a0a;
            color: #ffffff;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: var(--heading-font);
        }
    </style>
    
    @stack('styles')
</head>
<body class="modern-restaurant-template">
    <!-- القائمة العلوية -->
    @include('facilities.pages.partials.modern-restaurant.navigation')
    
    <!-- المحتوى الرئيسي -->
    <main>
        {{ $slot }}
    </main>
    
    <!-- التذييل -->
    @include('facilities.pages.partials.modern-restaurant.footer')
    
    <!-- زر الحجز -->
    @if($facility->hasBooking())
        <div class="fixed bottom-6 left-6 z-50">
            <a href="#book" 
               class="flex items-center gap-2 bg-accent-color hover:opacity-90 text-white px-8 py-4 rounded-none shadow-lg transition-opacity">
                <i class="fas fa-utensils"></i>
                <span>احجز طاولة</span>
            </a>
        </div>
    @endif
    
    <!-- السكربتات -->
    <script src="{{ mix('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
