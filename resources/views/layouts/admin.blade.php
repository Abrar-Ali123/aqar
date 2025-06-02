<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Aqar')) - {{ __('admin.dashboard') }}</title>
    
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
    
    <!-- Admin Theme -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    @if(app()->getLocale() === 'ar')
        <link href="{{ asset('css/admin-rtl.css') }}" rel="stylesheet">
    @endif
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    @stack('styles')
    @livewireStyles
</head>
<body class="admin-panel">
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar" class="active">
            <div class="sidebar-header">
                <h3>{{ config('app.name', 'Aqar') }}</h3>
            </div>

            <ul class="list-unstyled components">
                <li>
                    <a href="{{ route('admin.dashboard', ['locale' => app()->getLocale()]) }}">
                        <i class="fas fa-home"></i>
                        {{ __('admin.dashboard') }}
                    </a>
                </li>
                @can('view users')
                <li>
                    <a href="{{ route('admin.users.index', ['locale' => app()->getLocale()]) }}">
                        <i class="fas fa-users"></i>
                        {{ __('admin.users.title') }}
                    </a>
                </li>
                @endcan
                @can('view languages')
                <li>
                    <a href="{{ route('admin.languages.index', ['locale' => app()->getLocale()]) }}">
                        <i class="fas fa-language"></i>
                        {{ __('admin.languages.title') }}
                    </a>
                </li>
                @endcan
                @can('view settings')
                <li>
                    <a href="{{ route('admin.settings.theme', ['locale' => app()->getLocale()]) }}">
                        <i class="fas fa-cog"></i>
                        {{ __('admin.settings.title') }}
                    </a>
                </li>
                @endcan
            </ul>
        </nav>

        <!-- Page Content -->
        <div id="content">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn">
                        <i class="fas fa-bars"></i>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarContent">
                        <ul class="navbar-nav ms-auto">
                            <!-- مكون تبديل اللغة -->
                            <li class="nav-item">
                                <x-language-switcher />
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('home', ['locale' => app()->getLocale()]) }}">
                                            <i class="fas fa-globe"></i>
                                            {{ __('admin.view_site') }}
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('logout', ['locale' => app()->getLocale()]) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-sign-out-alt"></i>
                                                {{ __('auth.logout') }}
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- رسائل النجاح والخطأ -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- المحتوى الرئيسي -->
            <main class="py-4">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/admin.js') }}" defer></script>
    @stack('scripts')
    @livewireScripts

    <script>
        $(document).ready(function () {
            // تفعيل/تعطيل الشريط الجانبي
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
            });

            // إخفاء رسائل التنبيه بعد 3 ثواني
            setTimeout(function() {
                $('.alert').alert('close');
            }, 3000);
        });
    </script>
</body>
</html>
