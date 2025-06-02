<div class="hero-section position-relative py-5">
    <div class="hero-slider swiper">
        <div class="swiper-wrapper">
            @foreach($featuredProducts->take(5) as $product)
            <div class="swiper-slide">
                <div class="hero-slide position-relative">
                    <img src="{{ $product->image_url }}" 
                         alt="{{ $product->title }}"
                         class="slide-bg">
                    <div class="slide-content position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="text-white">
                                        <h1 class="display-4 fw-bold mb-4">{{ $product->title }}</h1>
                                        <p class="lead mb-4">{{ $product->short_description }}</p>
                                        <div class="d-flex gap-3">
                                            <div class="product-price">
                                                <span class="h3 text-warning mb-0">
                                                    {{ number_format($product->price) }}
                                                </span>
                                                @if($product->old_price)
                                                    <del class="ms-2">{{ number_format($product->old_price) }}</del>
                                                @endif
                                            </div>
                                            <a href="{{ route('products.show', ['locale' => app()->getLocale(), 'product' => $product->id]) }}" 
                                               class="btn btn-light btn-lg">
                                                {{ __('pages.shop_now') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="swiper-pagination"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    </div>

    <div class="hero-search position-relative container">
        <div class="search-bar bg-white rounded-pill shadow-lg p-2 mt-n5">
            <form action="{{ route('products.index', ['locale' => app()->getLocale()]) }}" 
                  method="GET" 
                  class="d-flex align-items-center">
                <div class="dropdown flex-grow-1">
                    <select name="category" class="form-select border-0">
                        <option value="">{{ __('pages.all_categories') }}</option>
                        @foreach($mainCategories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-grow-2 px-3">
                    <input type="text" 
                           name="search" 
                           class="form-control border-0" 
                           placeholder="{{ __('pages.search_products') }}">
                </div>
                <button type="submit" class="btn btn-primary rounded-pill px-4">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
.hero-slider {
    height: 600px;
}

.hero-slide {
    height: 600px;
}

.slide-bg {
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: brightness(0.7);
}

.slide-content {
    background: linear-gradient(90deg, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.4) 50%, rgba(0,0,0,0) 100%);
}

[dir="rtl"] .slide-content {
    background: linear-gradient(-90deg, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.4) 50%, rgba(0,0,0,0) 100%);
}
</style>
@endpush

@push('scripts')
<script>
new Swiper('.hero-slider', {
    loop: true,
    effect: 'fade',
    autoplay: {
        delay: 5000,
    },
    pagination: {
        el: '.swiper-pagination',
        clickable: true
    },
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    }
});
</script>
@endpush
