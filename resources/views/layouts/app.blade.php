<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Tajawal', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style type="text/tailwindcss">
        @layer utilities {
            .rtl { direction: rtl; }
            .ltr { direction: ltr; }
        }
    </style>
    @livewireStyles

    <!-- Scripts -->
    @vite(['resources/js/app.js'])
    @stack('styles')
</head>
<body>
    <div id="app">
        @if(isset($slot))
                {{ $slot }}
            @endif
    </div>

    <!-- Scripts -->
    @stack('scripts')

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.body.classList.add('page-loaded');
        
        // إخفاء رسائل التنبيه بعد 3 ثواني
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                if (window.bootstrap) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            });
        }, 3000);
    });
    </script>
</body>
</html>
