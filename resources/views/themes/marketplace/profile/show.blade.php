@extends('layouts.app')

@section('content')
<div class="seller-profile py-5">
    <div class="container">
        <!-- معلومات البائع الرئيسية -->
        <div class="profile-header bg-white rounded-3 shadow-sm p-4 mb-4">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center">
                        <img src="{{ $seller->avatar }}" 
                             alt="{{ $seller->name }}"
                             class="rounded-circle me-4"
                             width="120">
                        <div>
                            <h1 class="h3 mb-2">{{ $seller->name }}</h1>
                            <p class="text-muted mb-3">{{ __('pages.member_since') }}: {{ $seller->created_at->format('M Y') }}</p>
                            <div class="seller-rating mb-3">
                                <div class="stars mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $seller->rating ? 'text-warning' : 'text-muted' }}"></i>
                                    @endfor
                                    <span class="ms-2">({{ $seller->reviews_count }} {{ __('pages.reviews') }})</span>
                                </div>
                                <div class="badges">
                                    @if($seller->is_verified)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i>{{ __('pages.verified_seller') }}
                                        </span>
                                    @endif
                                    @if($seller->is_premium)
                                        <span class="badge bg-primary">
                                            <i class="fas fa-crown me-1"></i>{{ __('pages.premium_seller') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="seller-contact">
                                <button class="btn btn-primary me-2" onclick="followSeller({{ $seller->id }})">
                                    <i class="fas fa-user-plus me-2"></i>{{ __('pages.follow') }}
                                </button>
                                <button class="btn btn-outline-primary" onclick="contactSeller()">
                                    <i class="fas fa-envelope me-2"></i>{{ __('pages.contact') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mt-4 mt-lg-0">
                    <div class="row text-center g-3">
                        <div class="col-4">
                            <div class="stat-item">
                                <div class="h3 mb-1">{{ $seller->products_count }}</div>
                                <div class="text-muted">{{ __('pages.products') }}</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-item">
                                <div class="h3 mb-1">{{ $seller->followers_count }}</div>
                                <div class="text-muted">{{ __('pages.followers') }}</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-item">
                                <div class="h3 mb-1">{{ $seller->sales_count }}</div>
                                <div class="text-muted">{{ __('pages.sales') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- المنتجات -->
                <div class="seller-products bg-white rounded-3 shadow-sm p-4 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="h4 mb-0">{{ __('pages.seller_products') }}</h2>
                        <div class="products-filter">
                            <select class="form-select" onchange="filterProducts(this.value)">
                                <option value="all">{{ __('pages.all_products') }}</option>
                                <option value="available">{{ __('pages.available') }}</option>
                                <option value="sold">{{ __('pages.sold') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-4">
                        @foreach($seller->products as $product)
                        <div class="col-md-6 col-lg-4">
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

                    <div class="pagination-wrapper mt-4">
                        {{ $seller->products->links() }}
                    </div>
                </div>

                <!-- المراجعات -->
                <div class="seller-reviews bg-white rounded-3 shadow-sm p-4">
                    <h2 class="h4 mb-4">{{ __('pages.seller_reviews') }}</h2>
                    
                    @foreach($seller->reviews as $review)
                    <div class="review-item border-bottom pb-4 mb-4">
                        <div class="d-flex justify-content-between mb-3">
                            <div class="reviewer d-flex align-items-center">
                                <img src="{{ $review->user->avatar }}" 
                                     alt="{{ $review->user->name }}"
                                     class="rounded-circle me-3"
                                     width="40">
                                <div>
                                    <h4 class="h6 mb-1">{{ $review->user->name }}</h4>
                                    <div class="review-date text-muted small">
                                        {{ $review->created_at->format('d M Y') }}
                                    </div>
                                </div>
                            </div>
                            <div class="review-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                            </div>
                        </div>
                        <p class="review-content mb-0">{{ $review->content }}</p>
                    </div>
                    @endforeach

                    <div class="pagination-wrapper">
                        {{ $seller->reviews->links() }}
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- نموذج الاتصال -->
                <div class="contact-card bg-white rounded-3 shadow-sm p-4 mb-4">
                    <h3 class="h5 mb-4">{{ __('pages.contact_seller') }}</h3>
                    <form class="contact-form">
                        <div class="mb-3">
                            <input type="text" 
                                   class="form-control" 
                                   placeholder="{{ __('pages.your_name') }}">
                        </div>
                        <div class="mb-3">
                            <input type="email" 
                                   class="form-control" 
                                   placeholder="{{ __('pages.your_email') }}">
                        </div>
                        <div class="mb-3">
                            <input type="tel" 
                                   class="form-control" 
                                   placeholder="{{ __('pages.your_phone') }}">
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control" 
                                      rows="4"
                                      placeholder="{{ __('pages.your_message') }}"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            {{ __('pages.send_message') }}
                        </button>
                    </form>
                </div>

                <!-- سياسات البائع -->
                <div class="seller-policies bg-white rounded-3 shadow-sm p-4 mb-4">
                    <h3 class="h5 mb-4">{{ __('pages.seller_policies') }}</h3>
                    <div class="policy-item mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-truck text-primary me-3"></i>
                            <h4 class="h6 mb-0">{{ __('pages.shipping_policy') }}</h4>
                        </div>
                        <p class="text-muted small mb-0">{{ $seller->shipping_policy }}</p>
                    </div>
                    <div class="policy-item mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-undo text-primary me-3"></i>
                            <h4 class="h6 mb-0">{{ __('pages.return_policy') }}</h4>
                        </div>
                        <p class="text-muted small mb-0">{{ $seller->return_policy }}</p>
                    </div>
                    <div class="policy-item">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-shield-alt text-primary me-3"></i>
                            <h4 class="h6 mb-0">{{ __('pages.warranty_policy') }}</h4>
                        </div>
                        <p class="text-muted small mb-0">{{ $seller->warranty_policy }}</p>
                    </div>
                </div>

                <!-- التقييمات التفصيلية -->
                <div class="seller-ratings bg-white rounded-3 shadow-sm p-4">
                    <h3 class="h5 mb-4">{{ __('pages.detailed_ratings') }}</h3>
                    <div class="rating-item mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ __('pages.product_quality') }}</span>
                            <span class="text-primary">{{ number_format($seller->quality_rating, 1) }}/5</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-primary" 
                                 style="width: {{ ($seller->quality_rating/5)*100 }}%"></div>
                        </div>
                    </div>
                    <div class="rating-item mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ __('pages.communication') }}</span>
                            <span class="text-primary">{{ number_format($seller->communication_rating, 1) }}/5</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-primary" 
                                 style="width: {{ ($seller->communication_rating/5)*100 }}%"></div>
                        </div>
                    </div>
                    <div class="rating-item">
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ __('pages.shipping_speed') }}</span>
                            <span class="text-primary">{{ number_format($seller->shipping_rating, 1) }}/5</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-primary" 
                                 style="width: {{ ($seller->shipping_rating/5)*100 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.profile-header {
    background: linear-gradient(to right, var(--bs-white), var(--bs-light));
}

.stat-item {
    padding: 1rem;
    background: var(--bs-light);
    border-radius: 0.5rem;
}

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
    height: 200px;
    overflow: hidden;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.policy-item {
    padding: 1rem;
    background: var(--bs-light);
    border-radius: 0.5rem;
    margin-bottom: 1rem;
}

.policy-item:last-child {
    margin-bottom: 0;
}

/* RTL Support */
[dir="rtl"] .me-4 {
    margin-right: 0 !important;
    margin-left: 1.5rem !important;
}

[dir="rtl"] .me-3 {
    margin-right: 0 !important;
    margin-left: 1rem !important;
}

[dir="rtl"] .me-2 {
    margin-right: 0 !important;
    margin-left: 0.5rem !important;
}
</style>
@endpush

@push('scripts')
<script>
function followSeller(sellerId) {
    // Implementation
}

function contactSeller() {
    // Implementation
}

function filterProducts(type) {
    // Implementation
}
</script>
@endpush
