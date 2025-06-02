@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- القائمة الجانبية -->
        <div class="col-lg-3">
            @include('profile.sidebar')
        </div>

        <!-- المحتوى الرئيسي -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="h4 mb-0">{{ __('المفضلة') }}</h3>
                
                <!-- تصفية المنتجات -->
                <div class="btn-group">
                    <button type="button" 
                            class="btn btn-outline-primary dropdown-toggle" 
                            data-bs-toggle="dropdown">
                        {{ __('تصفية حسب') }}
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item {{ !request('type') ? 'active' : '' }}" 
                               href="{{ route('profile.favorites') }}">
                                {{ __('الكل') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request('type') === 'products' ? 'active' : '' }}" 
                               href="{{ route('profile.favorites', ['type' => 'products']) }}">
                                {{ __('المنتجات') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request('type') === 'facilities' ? 'active' : '' }}" 
                               href="{{ route('profile.favorites', ['type' => 'facilities']) }}">
                                {{ __('المنشآت') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- قائمة المفضلة -->
            @if($favorites->isNotEmpty())
                <div class="row g-4">
                    @foreach($favorites as $favorite)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border-0 shadow-sm favorite-item">
                                <!-- صورة المنتج/المنشأة -->
                                <div class="position-relative">
                                    @if($favorite->favorable->getFirstMediaUrl('images'))
                                        <img src="{{ $favorite->favorable->getFirstMediaUrl('images') }}" 
                                             class="card-img-top" 
                                             alt="{{ $favorite->favorable->name }}"
                                             style="height: 200px; object-fit: cover;">
                                    @else
                                        <div class="placeholder-image bg-light d-flex align-items-center justify-content-center"
                                             style="height: 200px;">
                                            <i class="fas fa-image fa-2x text-muted"></i>
                                        </div>
                                    @endif
                                    
                                    <!-- زر إزالة من المفضلة -->
                                    <button class="btn btn-light btn-sm position-absolute top-0 end-0 m-2 remove-favorite"
                                            data-favorite-id="{{ $favorite->id }}">
                                        <i class="fas fa-heart text-danger"></i>
                                    </button>

                                    @if($favorite->favorable_type === 'App\Models\Product' && $favorite->favorable->discount_percentage > 0)
                                        <div class="position-absolute top-0 start-0 m-2">
                                            <span class="badge bg-danger">
                                                {{ $favorite->favorable->discount_percentage }}% {{ __('خصم') }}
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <div class="card-body">
                                    <!-- نوع العنصر -->
                                    <div class="mb-2">
                                        <span class="badge bg-{{ $favorite->favorable_type === 'App\Models\Product' ? 'primary' : 'success' }}">
                                            {{ $favorite->favorable_type === 'App\Models\Product' ? __('منتج') : __('منشأة') }}
                                        </span>
                                    </div>

                                    <!-- اسم المنتج/المنشأة -->
                                    <h5 class="card-title mb-2">
                                        <a href="{{ $favorite->favorable->url }}" class="text-decoration-none text-dark">
                                            {{ $favorite->favorable->name }}
                                        </a>
                                    </h5>

                                    @if($favorite->favorable_type === 'App\Models\Product')
                                        <!-- معلومات المنتج -->
                                        <div class="mb-2">
                                            @if($favorite->favorable->discount_price)
                                                <span class="text-danger fw-bold">
                                                    {{ number_format($favorite->favorable->discount_price) }}
                                                </span>
                                                <small class="text-decoration-line-through text-muted ms-2">
                                                    {{ number_format($favorite->favorable->price) }}
                                                </small>
                                            @else
                                                <span class="fw-bold">
                                                    {{ number_format($favorite->favorable->price) }}
                                                </span>
                                            @endif
                                            <span class="text-muted">{{ __('ريال') }}</span>
                                        </div>

                                        <!-- المنشأة -->
                                        <p class="card-text text-muted small mb-3">
                                            <i class="fas fa-store me-1"></i>
                                            {{ $favorite->favorable->facility->name }}
                                        </p>

                                        <!-- زر إضافة للسلة -->
                                        <button class="btn btn-primary w-100 add-to-cart"
                                                data-product-id="{{ $favorite->favorable->id }}"
                                                {{ !$favorite->favorable->in_stock ? 'disabled' : '' }}>
                                            @if($favorite->favorable->in_stock)
                                                <i class="fas fa-shopping-cart me-1"></i>
                                                {{ __('إضافة للسلة') }}
                                            @else
                                                <i class="fas fa-clock me-1"></i>
                                                {{ __('نفذت الكمية') }}
                                            @endif
                                        </button>
                                    @else
                                        <!-- معلومات المنشأة -->
                                        <div class="mb-3">
                                            <p class="card-text text-muted small mb-2">
                                                <i class="fas fa-map-marker-alt me-1"></i>
                                                {{ $favorite->favorable->address }}
                                            </p>
                                            <div class="rating">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= $favorite->favorable->rating ? 'text-warning' : 'text-muted' }}"></i>
                                                @endfor
                                                <span class="text-muted ms-1">
                                                    ({{ $favorite->favorable->reviews_count }})
                                                </span>
                                            </div>
                                        </div>

                                        <!-- زر عرض المنشأة -->
                                        <a href="{{ $favorite->favorable->url }}" 
                                           class="btn btn-outline-primary w-100">
                                            <i class="fas fa-external-link-alt me-1"></i>
                                            {{ __('عرض المنشأة') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- ترقيم الصفحات -->
                <div class="mt-4">
                    {{ $favorites->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-heart fa-3x text-muted mb-3"></i>
                    <h3 class="h5">{{ __('لا توجد عناصر في المفضلة') }}</h3>
                    <p class="text-muted">{{ __('لم تقم بإضافة أي منتجات أو منشآت للمفضلة') }}</p>
                    <div class="mt-3">
                        <a href="{{ route('products.index') }}" class="btn btn-primary me-2">
                            {{ __('تصفح المنتجات') }}
                        </a>
                        <a href="{{ route('facilities.index') }}" class="btn btn-outline-primary">
                            {{ __('تصفح المنشآت') }}
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // إزالة من المفضلة
    document.querySelectorAll('.remove-favorite').forEach(button => {
        button.addEventListener('click', function() {
            const favoriteId = this.dataset.favoriteId;
            
            fetch(`/favorites/${favoriteId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // إزالة العنصر بتأثير متلاشي
                    const item = this.closest('.favorite-item').parentElement;
                    item.style.transition = 'opacity 0.3s ease';
                    item.style.opacity = '0';
                    setTimeout(() => {
                        item.remove();
                        
                        // إذا لم تعد هناك عناصر، أعد تحميل الصفحة لعرض رسالة "لا توجد عناصر"
                        if (document.querySelectorAll('.favorite-item').length === 0) {
                            location.reload();
                        }
                    }, 300);
                }
            });
        });
    });

    // إضافة للسلة
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            
            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: 1
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // تحديث عدد العناصر في السلة في القائمة العلوية
                    const cartCount = document.querySelector('.cart-count');
                    if (cartCount) {
                        cartCount.textContent = data.cart_count;
                    }

                    // إظهار رسالة نجاح
                    const toast = document.createElement('div');
                    toast.className = 'toast position-fixed bottom-0 end-0 m-3';
                    toast.innerHTML = `
                        <div class="toast-body bg-success text-white">
                            ${data.message}
                        </div>
                    `;
                    document.body.appendChild(toast);
                    new bootstrap.Toast(toast).show();
                }
            });
        });
    });
});
</script>
@endpush
