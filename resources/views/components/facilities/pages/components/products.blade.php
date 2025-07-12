@props(['facility', 'settings'])

<section class="products-section py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-lg-8">
                <h2 class="section-title">{{ __('Our Products') }}</h2>
            </div>
            @can('create', [App\Models\Product::class, $facility])
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('facilities.products.create', ['locale' => app()->getLocale(), 'facility' => $facility->id]) }}" 
                       class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        {{ __('Add Product') }}
                    </a>
                </div>
            @endcan
        </div>

        <div class="row g-4">
            @forelse($products as $product)
                <div class="col-md-6 col-lg-4">
                    <div class="product-card">
                        @if($product->cover_image)
                            <img src="{{ $product->cover_image }}" 
                                 class="product-image" 
                                 alt="{{ $product->name }}">
                        @endif
                        <div class="product-content p-3">
                            <h3 class="product-title h5">{{ $product->name }}</h3>
                            @if($product->price)
                                <div class="product-price mb-3">
                                    {{ number_format($product->price, 2) }} {{ config('app.currency') }}
                                </div>
                            @endif
                            <p class="product-description mb-3">
                                {{ Str::limit($product->description, 100) }}
                            </p>
                            <a href="{{ route('facilities.products.show', ['locale' => app()->getLocale(), 'facility' => $facility->id, 'product' => $product->id]) }}" 
                               class="btn btn-outline-primary btn-sm">
                                {{ __('View Details') }}
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <p>{{ __('No products available.') }}</p>
                    </div>
                </div>
            @endforelse
        </div>

        @if($products->isNotEmpty())
            <div class="text-center mt-5">
                <a href="{{ route('facilities.products.index', ['locale' => app()->getLocale(), 'facility' => $facility->id]) }}" 
                   class="btn btn-outline-primary">
                    {{ __('View All Products') }}
                </a>
            </div>
        @endif
    </div>
</section>

<style>
.products-section {
    background-color: #f8f9fa;
}

.section-title {
    color: var(--facility-primary);
    margin-bottom: 1rem;
}

.product-card {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
    height: 100%;
}

.product-card:hover {
    transform: translateY(-5px);
}

.product-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 10px 10px 0 0;
}

.product-title {
    color: var(--facility-primary);
    margin-bottom: 0.5rem;
}

.product-price {
    color: var(--facility-secondary);
    font-weight: bold;
    font-size: 1.1rem;
}

.product-description {
    color: #6c757d;
    font-size: 0.9rem;
}

[dir="rtl"] .me-2 {
    margin-left: 0.5rem !important;
    margin-right: 0 !important;
}
</style>
