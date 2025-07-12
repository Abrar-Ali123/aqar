@props(['product', 'design'])

<div class="product-card">
    <div class="product-image">
        @if($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->translations->first()?->name }}" class="img-fluid">
        @else
            <img src="{{ asset('images/placeholder.jpg') }}" alt="Product placeholder" class="img-fluid">
        @endif
        @if($product->is_featured)
            <span class="badge badge-featured">{{ __('Featured') }}</span>
        @endif
    </div>
    
    <div class="product-content">
        <h3 class="product-title">{{ $product->translations->first()?->name ?? __('Untitled Product') }}</h3>
        
        @if($product->translations->first()?->info)
            <p class="product-description">{{ Str::limit($product->translations->first()?->info, 100) }}</p>
        @endif
        
        <div class="product-price">
            @if($product->price)
                <span class="price">{{ number_format($product->price, 2) }} {{ __('SAR') }}</span>
            @endif
        </div>

        <div class="product-actions">
            <button class="btn btn-sm btn-primary btn-{{ $design['button_style'] ?? 'rounded' }} add-to-cart"
                    data-product-id="{{ $product->id }}"
                    onclick="addToCart({{ $product->id }})">
                <i class="fas fa-shopping-cart"></i> {{ __('Add to Cart') }}
            </button>
            
            <button class="btn btn-sm btn-outline-primary btn-{{ $design['button_style'] ?? 'rounded' }} add-to-wishlist"
                    data-product-id="{{ $product->id }}"
                    onclick="addToWishlist({{ $product->id }})">
                <i class="fas fa-heart"></i>
            </button>
        </div>
    </div>
</div>

@once
@push('styles')
<style>
    .product-card {
        border: 1px solid #eee;
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.3s ease;
        background: white;
    }

    .product-card:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }

    .product-image {
        position: relative;
        padding-top: 75%;
        overflow: hidden;
    }

    .product-image img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .badge-featured {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: var(--primary-color);
        color: white;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
    }

    .product-content {
        padding: 15px;
    }

    .product-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 10px;
        color: var(--primary-color);
    }

    .product-description {
        font-size: 14px;
        color: #666;
        margin-bottom: 10px;
    }

    .product-price {
        font-size: 18px;
        font-weight: 600;
        color: var(--secondary-color);
        margin-bottom: 15px;
    }

    .product-actions {
        display: flex;
        gap: 10px;
    }

    .add-to-wishlist {
        padding: 0.375rem;
        width: 38px;
    }
</style>
@endpush

@push('scripts')
<script>
    function addToCart(productId) {
        // التحقق من وجود السلة في localStorage
        let cart = JSON.parse(localStorage.getItem('cart') || '[]');
        
        // إضافة المنتج
        const exists = cart.find(item => item.id === productId);
        if (exists) {
            exists.quantity += 1;
        } else {
            cart.push({ id: productId, quantity: 1 });
        }
        
        // حفظ السلة
        localStorage.setItem('cart', JSON.stringify(cart));
        
        // تحديث عدد عناصر السلة في الواجهة
        updateCartCount();
        
        // إظهار رسالة نجاح
        showToast('{{ __("Product added to cart successfully") }}');
    }

    function addToWishlist(productId) {
        // التحقق من وجود المفضلة في localStorage
        let wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
        
        // إضافة/إزالة المنتج
        const index = wishlist.indexOf(productId);
        if (index > -1) {
            wishlist.splice(index, 1);
            showToast('{{ __("Product removed from wishlist") }}');
        } else {
            wishlist.push(productId);
            showToast('{{ __("Product added to wishlist") }}');
        }
        
        // حفظ المفضلة
        localStorage.setItem('wishlist', JSON.stringify(wishlist));
        
        // تحديث أيقونة المفضلة
        updateWishlistButton(productId);
    }

    function updateCartCount() {
        const cart = JSON.parse(localStorage.getItem('cart') || '[]');
        const count = cart.reduce((sum, item) => sum + item.quantity, 0);
        document.querySelector('.cart-count').textContent = count;
    }

    function updateWishlistButton(productId) {
        const wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
        const button = document.querySelector(`.add-to-wishlist[data-product-id="${productId}"] i`);
        if (wishlist.includes(productId)) {
            button.classList.remove('far');
            button.classList.add('fas');
            button.style.color = 'var(--secondary-color)';
        } else {
            button.classList.remove('fas');
            button.classList.add('far');
            button.style.color = '';
        }
    }

    // تحديث حالة الأزرار عند تحميل الصفحة
    document.addEventListener('DOMContentLoaded', function() {
        const wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
        wishlist.forEach(updateWishlistButton);
        updateCartCount();
    });
</script>
@endpush
@endonce
