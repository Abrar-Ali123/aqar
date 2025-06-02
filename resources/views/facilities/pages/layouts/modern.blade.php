<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $page->meta_title ?? $page->title)</title>
    @if($page->meta_description)
        <meta name="description" content="{{ $page->meta_description }}">
    @endif
    @if($page->meta_image)
        <meta property="og:image" content="{{ $page->meta_image }}">
    @endif
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
    <style>
        body { background: #f8f9fa; font-family: 'Cairo', sans-serif; }
        .facility-navbar { background: {{$page->design_settings['primary_color'] ?? '#007bff'}}; }
        .facility-navbar .nav-link, .facility-navbar .navbar-brand { color: #fff !important; }
        .facility-footer { background: {{$page->design_settings['secondary_color'] ?? '#222'}}; color: #fff; }
        {!! $page->design_settings['custom_css'] ?? '' !!}
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    @stack('head')
</head>
<body>
<nav class="navbar facility-navbar navbar-expand-lg mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">{{ $facility->name }}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#facilityNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="facilityNav">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                @foreach($facility->pages()->where('is_active', true)->orderBy('order')->get() as $navPage)
                    <li class="nav-item">
                        <a class="nav-link{{ $navPage->id == $page->id ? ' active' : '' }}" href="{{ route('facilities.pages.show', [$facility->id, $navPage->id]) }}">
                            {{ $navPage->title }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</nav>
<div class="container mb-5">
    @yield('content')
</div>
<footer class="facility-footer py-3 mt-auto">
    <div class="container text-center small">
        &copy; {{ date('Y') }} {{ $facility->name }} - جميع الحقوق محفوظة
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
