@extends('layouts.app')

@section('content')
<!-- قسم البحث -->
<div class="hero-section py-5 bg-gradient-primary text-white">
    <div class="container">
        <h1 class="display-4 fw-bold mb-4 text-center">{{ __('pages.marketplace_title') }}</h1>
        <p class="lead text-center mb-5">{{ __('pages.marketplace_subtitle') }}</p>
        <form action="{{ route('products.index', ['locale' => app()->getLocale()]) }}" method="GET" class="d-flex justify-content-center mb-4">
            <input type="text" name="search" class="form-control form-control-lg w-50 me-2" placeholder="ابحث عن منتجات أو خدمات...">
            <button class="btn btn-light btn-lg px-4" type="submit">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>
</div>

<!-- قسم التصنيفات -->
<section class="categories-section py-5">
    <div class="container">
        <h2 class="section-title text-center mb-4">{{ __('pages.main_categories') }}</h2>
        <div class="row g-4">
            @foreach($categories as $category)
            <div class="col-md-3">
                <a href="{{ route('products.index', ['locale' => app()->getLocale(), 'category' => $category->id]) }}" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm hover-shadow">
                        <div class="card-body text-center">
                            <i class="{{ $category->icon ?? 'fas fa-folder' }} fa-3x mb-3 text-primary"></i>
                            <h5 class="card-title mb-2">{{ $category->name }}</h5>
                            <p class="text-muted mb-0">{{ $category->products_count }} {{ __('pages.items') }}</p>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- قسم المنتجات المميزة -->
<section class="featured-products py-5 bg-light">
    <div class="container">
        <h2 class="section-title text-center mb-4">{{ __('pages.latest_products') }}</h2>
        <div class="row g-4">
            @foreach($featuredProducts as $product)
            <div class="col-md-3">
                <div class="card h-100 border-0 shadow-sm hover-shadow">
                    @if($product->getFirstMediaUrl('images'))
                    <img src="{{ $product->getFirstMediaUrl('images') }}" class="card-img-top" alt="{{ $product->name }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title mb-2">{{ $product->name }}</h5>
                        <p class="text-muted mb-2">{{ Str::limit($product->description, 100) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-primary fw-bold">{{ number_format($product->price, 2) }} ريال</span>
                            <a href="{{ route('products.show', ['locale' => app()->getLocale(), 'product' => $product->id]) }}" class="btn btn-outline-primary btn-sm">
                                {{ __('pages.learn_more') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- قسم المنشآت المميزة -->
<section class="featured-facilities py-5">
    <div class="container">
        <h2 class="section-title text-center mb-4">{{ __('pages.featured_facilities') }}</h2>
        <div class="row g-4">
            @foreach($featuredFacilities as $facility)
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm hover-shadow">
                    @if($facility->logo)
                    <img src="{{ asset('storage/' . $facility->logo) }}" class="card-img-top p-3" alt="{{ $facility->name }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title mb-2">{{ $facility->name }}</h5>
                        <p class="text-muted mb-3">{{ Str::limit($facility->description, 100) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge bg-primary me-2">{{ $facility->products_count }} {{ __('pages.products') }}</span>
                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-star text-warning me-1"></i>
                                    {{ number_format($facility->reviews_avg_rating, 1) }}
                                </span>
                            </div>
                            <a href="{{ route('facilities.show', ['locale' => app()->getLocale(), 'facility' => $facility->id]) }}" class="btn btn-outline-primary btn-sm">
                                {{ __('pages.view_profile') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
