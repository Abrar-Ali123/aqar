@extends('layouts.app')

@section('content')
<div class="hero-section py-5 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 mb-3">اكتشف أفضل المنتجات والخدمات</h1>
                <p class="lead text-muted mb-4">ابحث عن المنتجات والخدمات من أفضل المنشآت في مكان واحد</p>
                <!-- شريط البحث الرئيسي -->
                <form action="{{ route('products.index') }}" method="GET" class="search-form mb-4">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control form-control-lg" placeholder="ابحث عن منتجات أو خدمات...">
                        <button class="btn btn-primary btn-lg" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
                <!-- الفئات السريعة -->
                <div class="quick-categories">
                    @foreach($categories->take(5) as $category)
                        <a href="{{ route('products.index', ['category_id' => $category->id]) }}" class="btn btn-outline-primary m-1">
                            <i class="fas fa-tag me-1"></i>
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-6">
                <img src="{{ asset('images/hero-image.svg') }}" alt="Hero Image" class="img-fluid">
            </div>
        </div>
    </div>
    <!-- فيديو ترويجي في الهيرو -->
    <div class="video-promo my-3">
        <video class="w-100 rounded shadow" autoplay muted loop poster="{{ asset('images/hero-thumb.jpg') }}">
            <source src="{{ asset('videos/promo.mp4') }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>
</div>
@endsection
