<section class="featured-section py-5 bg-light">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="section-title">{{ __('pages.trending_products') }}</h2>
            <p class="section-subtitle">{{ __('pages.trending_products_subtitle') }}</p>
        </div>

        <div class="featured-products mb-5">
            <div class="row g-4">
                @foreach($featuredProducts as $product)
                <div class="col-md-6 col-lg-3">
                    <div class="product-card">
                        <div class="product-image">
                            <img src="{{ $product->image_url }}" 
                                 alt="{{ $product->title }}"
                                 class="img-fluid">
                            @if($product->discount_percentage > 0)
                                <div class="product-badge bg-danger">
                                    -{{ $product->discount_percentage }}%
                                </div>
                            @endif
                            <div class="product-actions">
                                <button type="button" 
                                        class="btn-action"
                                        data-bs-toggle="tooltip"
                                        title="{{ __('pages.add_to_wishlist') }}"
                                        onclick="addToWishlist({{ $product->id }})">
                                    <i class="far fa-heart"></i>
                                </button>
                                <button type="button" 
                                        class="btn-action"
                                        data-bs-toggle="tooltip"
                                        title="{{ __('pages.quick_view') }}"
                                        onclick="quickView({{ $product->id }})">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="product-info p-3">
                            <div class="product-category small text-muted mb-2">
                                {{ $product->category->name }}
                            </div>
                            <h3 class="product-title h6 mb-2">
                                <a href="{{ route('products.show', ['locale' => app()->getLocale(), 'product' => $product->id]) }}">
                                    {{ $product->title }}
                                </a>
                            </h3>
                            <div class="product-rating mb-2">
                                <div class="stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $product->rating ? 'text-warning' : 'text-muted' }}"></i>
                                    @endfor
                                </div>
                                <span class="rating-count text-muted">({{ $product->reviews_count }})</span>
                            </div>
                            <div class="product-price d-flex align-items-center gap-2 mb-3">
                                <span class="current-price">{{ number_format($product->price) }}</span>
                                @if($product->old_price)
                                    <span class="old-price text-muted text-decoration-line-through">
                                        {{ number_format($product->old_price) }}
                                    </span>
                                @endif
                            </div>
                            <button type="button" 
                                    class="btn btn-primary w-100"
                                    onclick="addToCart({{ $product->id }})">
                                <i class="fas fa-shopping-cart me-2"></i>
                                {{ __('pages.add_to_cart') }}
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="featured-shops mt-5">
            <h3 class="text-center mb-4">{{ __('pages.top_rated_shops') }}</h3>
            <div class="row g-4">
                @foreach($topShops as $shop)
                <div class="col-md-3">
                    <div class="shop-card text-center">
                        <img src="{{ $shop->logo }}" 
                             alt="{{ $shop->name }}"
                             class="shop-logo mb-3">
                        <h4 class="shop-name h6 mb-2">{{ $shop->name }}</h4>
                        <div class="shop-rating mb-2">
                            <div class="stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $shop->rating ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                            </div>
                        </div>
                        <p class="shop-stats small text-muted mb-3">
                            {{ $shop->products_count }} {{ __('pages.products') }} &bull;
                            {{ $shop->reviews_count }} {{ __('pages.reviews') }}
                        </p>
                        <a href="{{ route('shops.show', ['locale' => app()->getLocale(), 'shop' => $shop->id]) }}" 
                           class="btn btn-outline-primary btn-sm">
                            {{ __('pages.visit_shop') }}
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
.product-card {
    background: white;
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.product-card:hover {
    transform: translateY(-5px);
}

.product-image {
    position: relative;
    padding-top: 100%;
}

.product-image img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-badge {
    position: absolute;
    top: 1rem;
    left: 1rem;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    color: white;
}

.product-actions {
    position: absolute;
    top: 1rem;
    right: 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    opacity: 0;
    transform: translateX(1rem);
    transition: all 0.3s ease;
}

.product-card:hover .product-actions {
    opacity: 1;
    transform: translateX(0);
}

.btn-action {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 50%;
    background: white;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.btn-action:hover {
    background: var(--bs-primary);
    color: white;
}

.shop-logo {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 50%;
    padding: 0.5rem;
    border: 2px solid var(--bs-primary);
}

.stars {
    display: inline-flex;
    gap: 0.25rem;
}

/* RTL Support */
[dir="rtl"] .product-badge {
    left: auto;
    right: 1rem;
}

[dir="rtl"] .product-actions {
    right: auto;
    left: 1rem;
    transform: translateX(-1rem);
}

[dir="rtl"] .product-card:hover .product-actions {
    transform: translateX(0);
}
</style>
@endpush

@push('scripts')
<script>
function addToWishlist(productId) {
    // Implementation
}

function quickView(productId) {
    // Implementation
}

function addToCart(productId) {
    // Implementation
}
</script>
@endpush
