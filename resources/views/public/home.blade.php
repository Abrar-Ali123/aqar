{{-- الصفحة الرئيسية --}}
@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="hero-section bg-gradient-primary text-white py-5 mb-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="display-4 mb-4">{{ __('اكتشف عالماً من الخدمات والمنتجات') }}</h1>
                <p class="lead mb-4">{{ __('منصة متكاملة تجمع أفضل المنشآت والخدمات في مكان واحد') }}</p>
                <form action="{{ route('search', ['locale' => app()->getLocale()]) }}" method="GET" class="search-form">
                    <div class="input-group">
                        <input type="text" name="q" class="form-control form-control-lg" placeholder="{{ __('ابحث عن منتجات أو خدمات...') }}">
                        <button class="btn btn-light btn-lg" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
            <div class="col-md-6 text-center">
                <img src="{{ asset('images/hero-image.svg') }}" alt="{{ __('منصة متكاملة') }}" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<div class="container">
    <!-- Categories Section -->
    @if(count($categories) > 0)
    <section class="mb-5">
        <div class="section-header text-center mb-5">
            <h2 class="section-title">{{ __('تصفح الفئات') }}</h2>
            <p class="text-muted">{{ __('اختر من بين مجموعة متنوعة من الفئات') }}</p>
        </div>
        <div class="row g-4">
            @foreach($categories as $category)
            <div class="col-md-4">
                <div class="category-card card border-0 shadow-sm h-100 hover-lift">
                    <div class="card-body p-4">
                        <div class="category-icon mb-3">
                            <i class="fas fa-layer-group fa-2x text-primary"></i>
                        </div>
                        <h3 class="h5 mb-2">{{ $category['name'] }}</h3>
                        <p class="text-muted mb-3">{{ $category['products_count'] }} {{ __('منتج') }}</p>
                        <a href="{{ route('products.index', ['category' => $category['id']]) }}" class="btn btn-outline-primary stretched-link">
                            {{ __('عرض المنتجات') }} <i class="fas fa-arrow-left me-2"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    <!-- Latest Products Section -->
    @if(count($products) > 0)
    <section class="mb-5">
        <div class="section-header text-center mb-5">
            <h2 class="section-title">{{ __('أحدث المنتجات') }}</h2>
            <p class="text-muted">{{ __('اكتشف أحدث المنتجات والخدمات المضافة') }}</p>
        </div>
        <div class="row g-4">
            @foreach($products as $product)
            <div class="col-md-4">
                <div class="product-card card border-0 shadow-sm h-100 hover-lift">
                    <div class="card-img-top position-relative">
                        @if($product['image_url'])
                        <img src="{{ $product['image_url'] }}" class="img-fluid rounded-top" alt="{{ $product['name'] }}">
                        @endif
                        <div class="product-tag position-absolute top-0 end-0 m-3">
                            <span class="badge bg-primary">
                                {{ __($product['type']) }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <h3 class="h5 mb-2">{{ $product['name'] }}</h3>
                        <p class="text-muted mb-3">{{ Str::limit($product['description'], 100) }}</p>
                        <div class="product-details">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="h5 text-primary mb-0">{{ number_format($product['price']) }} {{ __('ريال') }}</span>
                                @if($product['facility'])
                                <span class="text-muted small">{{ $product['facility']['name'] }}</span>
                                @endif
                            </div>
                            <div class="product-meta d-flex justify-content-between">
                                <span class="text-muted small">{{ $product['category']['name'] }}</span>
                                <a href="{{ route('products.show', $product['id']) }}" class="btn btn-link text-primary p-0">
                                    {{ __('التفاصيل') }} <i class="fas fa-arrow-left ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    <!-- Featured Facilities Section -->
    @if(count($facilities) > 0)
    <section class="mb-5">
        <div class="section-header text-center mb-5">
            <h2 class="section-title">{{ __('المنشآت المميزة') }}</h2>
            <p class="text-muted">{{ __('تعرف على أفضل المنشآت لدينا') }}</p>
        </div>
        <div class="row g-4">
            @foreach($facilities as $facility)
            <div class="col-md-4">
                <div class="facility-card card border-0 shadow-sm h-100 hover-lift">
                    <div class="card-body p-4">
                        <div class="facility-logo mb-4">
                            @if($facility['logo'])
                            <img src="{{ asset('storage/' . $facility['logo']) }}" class="img-fluid rounded" alt="{{ $facility['name'] }}">
                            @else
                            <div class="placeholder-logo bg-light rounded d-flex align-items-center justify-content-center" style="height: 100px;">
                                <i class="fas fa-store fa-3x text-muted"></i>
                            </div>
                            @endif
                        </div>
                        <h3 class="h5 mb-3">{{ $facility['name'] }}</h3>
                        <p class="text-muted mb-4">{{ Str::limit($facility['info'], 100) }}</p>
                        <div class="facility-stats row text-center mb-4">
                            <div class="col">
                                <div class="stat-value h5 mb-1">{{ $facility['products_count'] }}</div>
                                <div class="stat-label small text-muted">{{ __('منتج') }}</div>
                            </div>
                            @if($facility['digital_products_count'] > 0)
                            <div class="col">
                                <div class="stat-value h5 mb-1">{{ $facility['digital_products_count'] }}</div>
                                <div class="stat-label small text-muted">{{ __('رقمي') }}</div>
                            </div>
                            @endif
                            @if($facility['services_count'] > 0)
                            <div class="col">
                                <div class="stat-value h5 mb-1">{{ $facility['services_count'] }}</div>
                                <div class="stat-label small text-muted">{{ __('خدمة') }}</div>
                            </div>
                            @endif
                        </div>
                        <a href="{{ route('facilities.show', $facility['id']) }}" class="btn btn-primary w-100">
                            {{ __('زيارة المنشأة') }} <i class="fas fa-arrow-left ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif
</div>

@push('styles')
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
    }
    
    .hero-section {
        min-height: 500px;
        display: flex;
        align-items: center;
    }

    .search-form .form-control {
        border-radius: 30px 0 0 30px;
        padding: 12px 25px;
    }

    .search-form .btn {
        border-radius: 0 30px 30px 0;
        padding: 12px 30px;
    }

    .hover-lift {
        transition: transform 0.2s ease;
    }

    .hover-lift:hover {
        transform: translateY(-5px);
    }

    .section-title {
        position: relative;
        display: inline-block;
        margin-bottom: 15px;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 50px;
        height: 3px;
        background-color: #3498db;
        border-radius: 3px;
    }

    .category-icon {
        width: 60px;
        height: 60px;
        background-color: rgba(52, 152, 219, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .product-card .card-img-top {
        height: 200px;
        object-fit: cover;
    }

    .facility-logo img {
        max-height: 100px;
        object-fit: contain;
    }

    .stat-value {
        color: #2c3e50;
        font-weight: 600;
    }
</style>
@endpush

@push('scripts')
<script>
    // يمكن إضافة أي سكربتات مطلوبة هنا
</script>
@endpush
@endsection
