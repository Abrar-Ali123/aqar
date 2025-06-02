@extends('layouts.app')

@section('content')
<div class="products-page py-5">
    <div class="container">
        <!-- فلتر البحث -->
        <div class="search-filters bg-white rounded-3 shadow-sm p-4 mb-4">
            <form action="{{ route('products.index', ['locale' => app()->getLocale()]) }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <select name="category" class="form-select">
                            <option value="">{{ __('pages.category') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(request('category') == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="price_range" class="form-select">
                            <option value="">{{ __('pages.price_range') }}</option>
                            <option value="0-100">0 - 100</option>
                            <option value="100-500">100 - 500</option>
                            <option value="500-1000">500 - 1,000</option>
                            <option value="1000+">1,000+</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="sort" class="form-select">
                            <option value="newest">{{ __('pages.sort_newest') }}</option>
                            <option value="price_low">{{ __('pages.sort_price_low') }}</option>
                            <option value="price_high">{{ __('pages.sort_price_high') }}</option>
                            <option value="popular">{{ __('pages.sort_popular') }}</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i>{{ __('pages.search') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- نتائج البحث -->
        <div class="search-results">
            <div class="results-header d-flex justify-content-between align-items-center mb-4">
                <div class="results-count">
                    {{ $products->total() }} {{ __('pages.products_found') }}
                </div>
                <div class="view-options">
                    <button class="btn btn-outline-primary me-2" onclick="setViewMode('grid')">
                        <i class="fas fa-th-large"></i>
                    </button>
                    <button class="btn btn-outline-primary" onclick="setViewMode('list')">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
            </div>

            <div class="row g-4" id="productsGrid">
                @foreach($products as $product)
                <div class="col-md-6 col-lg-3">
                    <div class="product-card">
                        <div class="product-image position-relative">
                            @if($product->images->count() > 0)
                                <img src="{{ $product->images->first()->url }}" 
                                     alt="{{ $product->title }}"
                                     class="img-fluid w-100">
                            @endif
                            <div class="product-tags position-absolute top-0 start-0 p-3">
                                @if($product->is_featured)
                                    <span class="badge bg-warning">{{ __('pages.featured') }}</span>
                                @endif
                                @if($product->discount)
                                    <span class="badge bg-danger">-{{ $product->discount }}%</span>
                                @endif
                            </div>
                            <div class="product-actions position-absolute bottom-0 start-0 end-0 p-3">
                                <div class="d-flex gap-2">
                                    <button class="btn btn-light btn-sm flex-grow-1" onclick="addToCart({{ $product->id }})">
                                        <i class="fas fa-shopping-cart me-2"></i>{{ __('pages.add_to_cart') }}
                                    </button>
                                    <button class="btn btn-light btn-sm" onclick="addToWishlist({{ $product->id }})">
                                        <i class="far fa-heart"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="product-info p-4">
                            <div class="product-category small text-muted mb-2">
                                {{ $product->category->name }}
                            </div>
                            <h3 class="product-title h6 mb-2">{{ $product->title }}</h3>
                            <div class="product-rating mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $product->rating ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                                <span class="ms-2 text-muted small">({{ $product->reviews_count }})</span>
                            </div>
                            <div class="product-price">
                                @if($product->old_price)
                                    <del class="text-muted me-2">{{ number_format($product->old_price) }}</del>
                                @endif
                                <span class="text-primary fw-bold">{{ number_format($product->price) }}</span>
                            </div>
                        </div>
                        <a href="{{ route('products.show', ['locale' => app()->getLocale(), 'product' => $product->id]) }}" 
                           class="stretched-link"></a>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="pagination-wrapper mt-5">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.product-card {
    background: white;
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
    position: relative;
}

.product-card:hover {
    transform: translateY(-5px);
}

.product-image {
    height: 240px;
    overflow: hidden;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-actions {
    background: linear-gradient(to top, rgba(0,0,0,0.8), rgba(0,0,0,0));
    opacity: 0;
    transition: opacity 0.3s ease;
}

.product-card:hover .product-actions {
    opacity: 1;
}

.search-filters {
    position: sticky;
    top: 1rem;
    z-index: 10;
}

/* RTL Support */
[dir="rtl"] .me-2 {
    margin-right: 0 !important;
    margin-left: 0.5rem !important;
}

[dir="rtl"] .ms-2 {
    margin-left: 0 !important;
    margin-right: 0.5rem !important;
}

/* List View */
.list-view .product-card {
    display: flex;
    align-items: stretch;
}

.list-view .product-image {
    width: 300px;
    height: auto;
}

.list-view .product-info {
    flex: 1;
}

@media (max-width: 768px) {
    .list-view .product-card {
        flex-direction: column;
    }

    .list-view .product-image {
        width: 100%;
        height: 240px;
    }
}
</style>
@endpush

@push('scripts')
<script>
function setViewMode(mode) {
    const container = document.getElementById('productsGrid');
    if (mode === 'list') {
        container.classList.add('list-view');
    } else {
        container.classList.remove('list-view');
    }
}

function addToCart(productId) {
    // Implementation
}

function addToWishlist(productId) {
    // Implementation
}
</script>
@endpush
