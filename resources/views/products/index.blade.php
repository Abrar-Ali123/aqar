@extends('layouts.app')
@section('content')

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-lg-8 mx-auto text-center">
            <h2 class="section-title">
                @if($type == 'physical')
                    منتجات
                @elseif($type == 'digital')
                    منتجات رقمية
                @elseif($type == 'service')
                    خدمات
                @else
                    جميع المنتجات
                @endif
            </h2>
        </div>
    </div>

    <!-- فلاتر البحث -->
    <div class="filters mb-4">
        <form action="{{ route('products.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-0">
                        <i class="fas fa-search text-primary"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-0 shadow-none" placeholder="ابحث عن منتج..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="category_id" class="form-select border-0 shadow-none">
                    <option value="">كل الفئات</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="type" class="form-select border-0 shadow-none">
                    <option value="">كل الأنواع</option>
                    <option value="physical" {{ request('type') == 'physical' ? 'selected' : '' }}>منتجات</option>
                    <option value="digital" {{ request('type') == 'digital' ? 'selected' : '' }}>منتجات رقمية</option>
                    <option value="service" {{ request('type') == 'service' ? 'selected' : '' }}>خدمات</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter me-1"></i>
                    تصفية
                </button>
            </div>
        </form>
    </div>

    <!-- عرض المنتجات -->
    <div class="row g-4">
        @forelse($products as $product)
            <div class="col-md-4">
                <div class="card h-100">
                    @if($product->thumbnail)
                        <img src="{{ Storage::url($product->thumbnail) }}" class="card-img-top" alt="{{ $product->translations->first()->name }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="fas fa-box fa-3x text-secondary opacity-25"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title mb-0">{{ $product->translations->first()->name }}</h5>
                            <span class="badge bg-{{ $product->type == 'physical' ? 'primary' : ($product->type == 'digital' ? 'info' : 'success') }}">
                                {{ $product->type == 'physical' ? 'منتج' : ($product->type == 'digital' ? 'رقمي' : 'خدمة') }}
                            </span>
                        </div>
                        <p class="card-text text-muted mb-3">{{ Str::limit($product->translations->first()->description, 100) }}</p>
                        @if($product->facility)
                            <div class="facility-info mb-3 py-2">
                                <a href="{{ route('facilities.show', $product->facility->id) }}" class="text-decoration-none text-muted">
                                    <i class="fas fa-store me-1"></i>
                                    {{ $product->facility->name }}
                                </a>
                            </div>
                        @endif
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 mb-0 text-primary">{{ number_format($product->price, 2) }} ر.س</span>
                            <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary">
                                <i class="fas fa-eye me-1"></i>
                                عرض التفاصيل
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                <h3 class="text-muted">لا توجد منتجات متاحة</h3>
            </div>
        @endforelse
    </div>

    <!-- الترقيم -->
    <div class="d-flex justify-content-center mt-4">
        {{ $products->withQueryString()->links() }}
    </div>
</div>

<style>
.filters {
    background-color: #f8f9fa;
    padding: 1.5rem;
    border-radius: 10px;
    margin-bottom: 2rem;
}

.filters .form-control,
.filters .form-select {
    background-color: white;
    border-radius: 5px;
}

.facility-info {
    border-top: 1px solid #eee;
    border-bottom: 1px solid #eee;
}

.badge {
    padding: 0.5rem 1rem;
    font-weight: 500;
}

.section-title {
    position: relative;
    margin-bottom: 2rem;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 50px;
    height: 3px;
    background-color: var(--bs-primary);
}
</style>
@endsection
