@extends('layouts.app')

@section('content')
<div class="container py-4">
    {{-- Language Switcher --}}
    <div class="text-end mb-4">
        @foreach($languages as $lang)
            <a href="{{ route('home', ['locale' => $lang->code]) }}" 
               class="btn btn-custom-primary {{ $lang->code === app()->getLocale() ? '' : 'btn-outline-custom-primary' }} me-2">
                {{ $lang->name }}
            </a>
        @endforeach
    </div>

    {{-- Categories --}}
    <div class="row g-2 mb-4">
        <div class="col-12">
            <small class="text-muted">{{ __('messages.nav.categories') }}</small>
        </div>
        @foreach($categories as $category)
            <div class="col-3 col-md-1">
                <a href="{{ route('categories.show', ['locale' => app()->getLocale(), 'category' => $category->id]) }}" 
                   class="text-decoration-none">
                    <div class="card category-card border-0 h-100">
                        @if($category->image)
                            <div class="category-image-wrapper">
                                <img src="{{ $category->image }}" 
                                     class="card-img-top category-image" 
                                     alt="{{ $category->name }}">
                            </div>
                        @endif
                        <div class="card-body p-2">
                            <h6 class="card-title">{{ $category->name }}</h6>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    {{-- Products --}}
    <div class="products-section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="section-title">{{ __('messages.home.featured_products') }}</h2>
                </div>
                @foreach($products as $product)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="product-card">
                            <button class="add-to-cart">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                            <a href="{{ route('products.show', ['locale' => app()->getLocale(), 'product' => $product->id]) }}" class="product-link">
                                <div class="product-image-container">
                                    @if($product->image)
                                        <img src="{{ $product->image }}" alt="{{ $product->name }}" class="product-image">
                                    @endif
                                    @if($product->discount_percentage)
                                        <div class="discount-tag">-{{ $product->discount_percentage }}%</div>
                                    @endif
                                </div>
                                <div class="product-content">
                                    <h3 class="product-name">{{ $product->name }}</h3>
                                    <div class="price-section">
                                        @if($product->original_price && $product->original_price > $product->price)
                                            <span class="old-price">{{ number_format($product->original_price, 2) }} {{ __('messages.home.sar') }}</span>
                                        @endif
                                        <span class="current-price">{{ number_format($product->price, 2) }} {{ __('messages.home.sar') }}</span>
                                    </div>
                                    <div class="product-meta">
                                        <div class="rating">
                                            <div class="stars">
                                                <i class="fas fa-star"></i>
                                                <span>{{ number_format(($product->rating ?? 0), 1) }}</span>
                                            </div>
                                            <span class="reviews-count">({{ $product->reviews_count ?? 0 }})</span>
                                        </div>
                                        <div class="sales-count">
                                            <i class="fas fa-shopping-bag"></i>
                                            <span>{{ $product->purchases_count ?? 0 }}</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <button class="add-to-cart">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Facilities --}}
    <div class="row">
        <div class="col-12">
            <h2 class="mb-3">{{ __('messages.home.featured_facilities') }}</h2>
        </div>
        @foreach($facilities as $facility)
            <div class="col-md-3 mb-3">
                <div class="card h-100">
                    @if($facility->cover)
                        <img src="{{ $facility->cover }}" 
                             class="card-img-top" 
                             alt="{{ $facility->name }}">
                        <h3>{{ $facility->name }}</h3>
                    @endif
                    <div class="card-body text-center">
                        <h5 class="card-title">{{ $facility->name }}</h5>
                        <a href="{{ route('facilities.show', ['locale' => app()->getLocale(), 'facility' => $facility->id]) }}" 
                           class="btn btn-custom-primary">
                            {{ __('messages.home.visit_facility') }}
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@push('styles')
<style>


    .card-img-top {
        height: 200px;
        object-fit: cover;
    }

    /* تصميم كروت المنتجات */
    .product-card {
        position: relative;
        background: var(--white);
        border-radius: 12px;
        border: 1px solid var(--gray-200);
        transition: all 0.3s ease;
        height: 100%;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }

    .product-link {
        text-decoration: none;
        color: inherit;
        display: block;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-hover);
        border-color: var(--gray-900);
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-hover);
        border-color: var(--gray-900);
    }

    .product-card:hover .add-to-cart {
        transform: scale(1.1);
    }

    .product-card:focus {
        outline: none;
        box-shadow: 0 0 0 3px var(--gray-200);
    }

    .product-image-container {
        position: relative;
        padding-top: 100%;
        overflow: hidden;
        border-radius: 12px 12px 0 0;
    }

    .product-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .product-card:hover .product-image {
        transform: scale(1.05);
    }

    .discount-tag {
        position: absolute;
        top: 10px;
        left: 10px;
        background-color: var(--gray-900);
        color: var(--white);
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.85rem;
        font-weight: 500;
        box-shadow: var(--shadow);
    }

    .product-content {
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
        height: 100%;
        gap: 1rem;
    }

    .product-name {
        font-size: 1rem;
        font-weight: 500;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
        height: 2.4em;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .price-section {
        margin-bottom: 0.75rem;
    }

    .old-price {
        display: block;
        text-decoration: line-through;
        color: var(--gray);
        margin-right: 0.5rem;
        font-size: 0.9rem;
    }

    .current-price {
        color: var(--gray);
        font-weight: 600;
        font-size: 1.25rem;
    }

    .product-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
        color: var(--gray);
        font-size: 0.9rem;
    }

    .rating, .sales-count {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .stars {
        display: flex;
        align-items: center;
        gap: 4px;
        color: var(--gray-600);
    }

    .reviews-count {
        color: var(--text-light);
    }

    .add-to-cart {
        position: absolute;
        top: 15px;
        right: 15px;
        width: 35px;
        height: 35px;
        border: none;
        border-radius: 50%;
        background-color: var(--gray-900);
        color: var(--white);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: var(--shadow-sm);
        z-index: 2;
        opacity: 1;
    }

    .add-to-cart:hover {
        background-color: var(--black);
        transform: scale(1.05);
        box-shadow: var(--shadow-hover);
        opacity: 1;
    }

    .add-to-cart:active {
        transform: scale(0.95);
    }

    .add-to-cart i {
        font-size: 1.2rem;
    }
    
    .add-to-cart:active {
        transform: translateY(0);
    }

    .product-actions {
        display: flex;
        gap: 0.75rem;
        margin-top: auto;
    }

    .add-to-cart {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.875rem;
        border: none;
        border-radius: 8px;
        background-color: var(--gray-900);
        color: var(--white);
        font-weight: 500;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .add-to-cart:hover {
        background-color: var(--gray-700);
        transform: translateY(-1px);
        box-shadow: var(--shadow-hover);
    }

    .add-to-cart:active {
        transform: translateY(0);
    }

    .add-to-wishlist {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0.875rem;
        border: 1px solid var(--gray-200);
        border-radius: 8px;
        background-color: var(--white);
        color: var(--gray-600);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .add-to-wishlist:hover {
        border-color: var(--gray-900);
        color: var(--gray-900);
        transform: translateY(-1px);
    }

    .add-to-wishlist:active {
        transform: translateY(0);
    }

    /* تحسينات قسم المنتجات */
    .products-section {
        background: var(--gray-50);
        border-radius: 12px;
        padding: 2rem 0;
        margin: 2rem 0;
        box-shadow: var(--shadow);
    }

    .section-title {
        position: relative;
        color: var(--gray-900);
        font-size: 1.8rem;
        font-weight: 600;
        margin-bottom: 2rem;
        padding-bottom: 0.5rem;
    }

    .section-title:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 60px;
        height: 3px;
        background: var(--gray-900);
        border-radius: 2px;
    }
</style>
@endpush

@push('scripts')
<script>
function addToCart(productId) {
    // منع انتقال الرابط
    event.preventDefault();
    
    // إرسال طلب AJAX لإضافة المنتج للسلة
    fetch(`/cart/add/${productId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // تحديث عدد العناصر في السلة
            const cartCount = document.querySelector('.cart-count');
            if (cartCount) {
                cartCount.textContent = data.cartCount;
            }
            
            // عرض رسالة نجاح
            alert('تمت إضافة المنتج للسلة بنجاح');
        } else {
            alert(data.message || 'حدث خطأ أثناء إضافة المنتج للسلة');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ أثناء إضافة المنتج للسلة');
    });
}

function addToWishlist(productId) {
    // منع انتقال الرابط
    event.preventDefault();
    
    // إرسال طلب AJAX لإضافة المنتج للمفضلة
    fetch(`/wishlist/add/${productId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // تغيير شكل زر المفضلة
            const wishlistBtn = event.target.closest('.add-to-wishlist');
            if (wishlistBtn) {
                const icon = wishlistBtn.querySelector('i');
                icon.classList.remove('far');
                icon.classList.add('fas');
                icon.style.color = '#ff6b6b';
            }
            
            // عرض رسالة نجاح
            alert('تمت إضافة المنتج للمفضلة بنجاح');
        } else {
            alert(data.message || 'حدث خطأ أثناء إضافة المنتج للمفضلة');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ أثناء إضافة المنتج للمفضلة');
    });
}
</script>
@endpush
@endsection