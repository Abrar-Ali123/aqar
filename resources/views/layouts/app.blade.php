<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Aqar') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    @if(session('direction') === 'rtl')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    @else
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @endif
    <link href="{{ asset('css/flags.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/hero.css') }}" rel="stylesheet">
    <link href="{{ asset('css/home-sections.css') }}" rel="stylesheet">
    <link href="{{ asset('css/facilities-list.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/search.css') }}">
    @if(app()->getLocale() === 'ar')
    <link href="{{ asset('css/rtl.css') }}" rel="stylesheet">
    @endif
    @stack('styles')

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="{{ asset('js/language.js') }}"></script>
    @livewireStyles
    @stack('scripts')

    @php
        $facilityStyles = null;
        if (isset($facility)) {
            if ($facility->styles) {
                $facilityStyles = json_decode($facility->styles, true);
            } elseif ($facility->template) {
                $facilityStyles = json_decode($facility->template->styles, true);
            }
        }
    @endphp

    <style>
        /* تأثيرات التحميل السلس */
        body {
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }

        body.page-loaded {
            opacity: 1;
        }

        .navbar {
            transition: all 0.3s ease-in-out;
        }

        .navbar-scrolled {
            background-color: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .facility-page {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.5s ease-out, transform 0.5s ease-out;
        }

        .page-loaded .facility-page {
            opacity: 1;
            transform: translateY(0);
        }

        /* تأثيرات التفاعل */
        .btn {
            transition: all 0.2s ease-in-out;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .product-card {
            transition: all 0.3s ease-in-out;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        :root {
            --primary-color: {{ $facilityStyles['colors']['primary'] ?? '#2563eb' }};
            --secondary-color: {{ $facilityStyles['colors']['secondary'] ?? '#475569' }};
            --accent-color: {{ $facilityStyles['colors']['accent'] ?? '#ed8936' }};
            --success-color: #22c55e;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --info-color: #3b82f6;
            --heading-font: {{ isset($facilityStyles['typography']['heading']) ? explode(',', $facilityStyles['typography']['heading'])[0] : '"Tajawal"' }}, sans-serif;
            --body-font: {{ isset($facilityStyles['typography']['body']) ? explode(',', $facilityStyles['typography']['body'])[0] : '"Tajawal"' }}, sans-serif;
            --heading-color: {{ isset($facilityStyles['typography']['heading']) ? explode(';', explode('color:', $facilityStyles['typography']['heading'])[1])[0] : '#2d3748' }};
            --body-color: {{ isset($facilityStyles['typography']['body']) ? explode(';', explode('color:', $facilityStyles['typography']['body'])[1])[0] : '#4a5568' }};
        }

        body {
            font-family: var(--body-font);
            color: var(--body-color);
            background-color: {{ $facilityStyles['layout']['background-color'] ?? '#ffffff' }};
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--heading-font);
            color: var(--heading-color);
        }

        [dir="rtl"] body {
            text-align: right;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--primary-color);
            filter: brightness(90%);
        }

        .btn-secondary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .btn-accent {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }

        /* Alert Styles */
        .alert {
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .alert-success {
            background-color: var(--success-color);
            border-color: var(--success-color);
            color: white;
        }

        .alert-error {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
            color: white;
        }

        /* Custom Facility Styles */
        .facility-content {
            max-width: {{ $facilityStyles['layout']['max-width'] ?? '1200px' }};
            margin: {{ $facilityStyles['layout']['margin'] ?? '0 auto' }};
        }

        @if(isset($facilityStyles['custom_css']))
            {!! $facilityStyles['custom_css'] !!}
        @endif
    </style>
</head>
<body>
    <div id="app">
        <!-- شريط التنقل الرئيسي -->
        @if(isset($facility) && $facility->pages->isNotEmpty())
            <nav class="navbar navbar-expand-lg shadow-sm" style="background-color: {{ $facilityStyles['colors']['primary'] ?? '#ffffff' }}">
        @else
            <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        @endif
            <div class="container">
                <a class="navbar-brand" href="{{ route('home', ['locale' => app()->getLocale()]) }}">
                    <x-application-logo class="h-8 w-auto" />
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- القائمة الرئيسية -->
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('facilities.index', ['locale' => app()->getLocale()]) }}">@lang('المنشآت')</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('products.index', ['locale' => app()->getLocale()]) }}">@lang('المنتجات')</a>
                        </li>
                    </ul>

                    <!-- محول اللغة وزر تسجيل الدخول -->
                    <div class="d-flex align-items-center gap-3">
                        <div class="dropdown me-3">
                            <button class="btn btn-link text-decoration-none dropdown-toggle" type="button" id="languageDropdown" data-bs-toggle="dropdown">
                                <i class="fas fa-globe me-1"></i>
                                {{ strtoupper(app()->getLocale()) }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('language.switch', 'ar') }}">العربية</a></li>
                                <li><a class="dropdown-item" href="{{ route('language.switch', 'en') }}">English</a></li>
                            </ul>
                        </div>
                        <a href="{{ route('account', ['locale' => app()->getLocale()]) }}" class="btn btn-primary">
                            <i class="fas fa-user me-1"></i> @lang('تسجيل الدخول')
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- رسائل النجاح والخطأ -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3" style="z-index: 1050;" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3" style="z-index: 1050;" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

   
        <!-- المحتوى الرئيسي -->
        <main class="py-4">
            @hasSection('content')
                @yield('content')
            @else
                {{ $slot ?? '' }}
            @endif
        </main>
    </div>

    @livewireScripts

    <script>
        // إخفاء رسائل التنبيه بعد 3 ثواني
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 3000);

            // تطبيق تأثيرات التحميل السلس
            document.body.classList.add('page-loaded');

            // تطبيق تأثيرات التمرير السلس
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // تحميل الصور بشكل تدريجي
            const lazyImages = document.querySelectorAll('img[loading="lazy"]');
            if ('loading' in HTMLImageElement.prototype) {
                lazyImages.forEach(img => {
                    img.src = img.dataset.src;
                });
            } else {
                // Fallback for browsers that don't support lazy loading
                const lazyLoadScript = document.createElement('script');
                lazyLoadScript.src = 'https://cdnjs.cloudflare.com/ajax/libs/lozad.js/1.16.0/lozad.min.js';
                lazyLoadScript.onload = function() {
                    const observer = lozad();
                    observer.observe();
                };
                document.body.appendChild(lazyLoadScript);
            }
        });
    </script>

    @if(isset($facility) && $facility->pages->isNotEmpty())
        <script>
            // تطبيق تأثيرات التمرير المخصصة
            window.addEventListener('scroll', function() {
                const navbar = document.querySelector('.navbar');
                if (window.scrollY > 50) {
                    navbar.classList.add('navbar-scrolled');
                } else {
                    navbar.classList.remove('navbar-scrolled');
                }
            });

            // تحميل الخطوط المخصصة
            if ('fonts' in document) {
                const headingFont = '{{ isset($facilityStyles["typography"]["heading"]) ? explode(",", $facilityStyles["typography"]["heading"])[0] : "Tajawal" }}';
                const bodyFont = '{{ isset($facilityStyles["typography"]["body"]) ? explode(",", $facilityStyles["typography"]["body"])[0] : "Tajawal" }}';

                Promise.all([
                    document.fonts.load(`1em ${headingFont}`),
                    document.fonts.load(`1em ${bodyFont}`)
                ]).then(() => {
                    document.documentElement.classList.add('fonts-loaded');
                });
            }
        </script>
    @endif
</body>
</html>
