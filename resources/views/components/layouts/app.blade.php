 <!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    @livewireStyles
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .sidebar {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: #343a40;
            padding: 15px;
            color: white;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
        }
        .sidebar a:hover {
            text-decoration: underline;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
        }
    </style>
    @livewireAssets

</head>
<body>

    <div class="sidebar">
        <h2>Dashboard</h2>
        <ul class="list-unstyled">
        <li><a href="{{ route('categories.index') }}">تصنيفات</a></li>
<li><a href="{{ route('upload.upload') }}">صور</a></li>
<li><a href="{{ route('icons.index') }}">الأيقونات</a></li>
<li><a href="{{ route('products.index') }}">المنتجات</a></li>

<li><a href="{{ route('permissions.index') }}">الصلاحيات</a></li>
<li><a href="{{ route('roles.index') }}">الأدوار</a></li>
<li><a href="{{ route('attributes.index') }}">الخصائص</a></li>

        </ul>
    </div>

    <div class="content">

        <h1>Welcome to Your Dashboard</h1>
        {{ $slot ?? '' }} <!-- هذا لـ Livewire المكونات -->

        @yield('content') <!-- هذا المكان حيث سيظهر محتوى Livewire -->
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    @livewireScripts

</body>
</html>

