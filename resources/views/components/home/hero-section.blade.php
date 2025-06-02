@props(['latestProducts'])

<section class="hero-section position-relative">
    <div class="hero-slider swiper">
        <div class="swiper-wrapper">
            @foreach($latestProducts->take(5) as $product)
            <div class="swiper-slide">
                <div class="hero-slide position-relative">
                    <img src="{{ $product->image_url }}" alt="{{ $product->title }}" class="slide-bg">
                    <div class="slide-content position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="text-white">
                                        <span class="badge bg-primary mb-2">{{ $product->category->name }}</span>
                                        <h1 class="display-4 fw-bold mb-3">{{ $product->title }}</h1>
                                        <p class="lead mb-4">{{ $product->short_description }}</p>
                                        <div class="d-flex gap-3">
                                            <a href="{{ route('products.show', ['locale' => app()->getLocale(), 'product' => $product->id]) }}" 
                                               class="btn btn-light btn-lg">
                                                {{ __('pages.view_details') }}
                                            </a>
                                            <a href="{{ route('contact', ['locale' => app()->getLocale()]) }}" 
                                               class="btn btn-outline-light btn-lg">
                                                {{ __('pages.contact_us') }}
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
    <div class="hero-search">
        <form action="{{ route('search.results', ['locale' => app()->getLocale()]) }}" method="GET" class="search-form shadow-lg">
            <div class="input-group">
                <input type="text" 
                       class="form-control form-control-lg" 
                       name="q" 
                       placeholder="{{ __('pages.search_placeholder') }}"
                       aria-label="{{ __('pages.search_placeholder') }}">
                <button class="btn btn-primary btn-lg" type="submit">
                    <i class="fas fa-search me-2"></i>
                    {{ __('pages.search') }}
                </button>
            </div>
        </form>
    </div>
</section>

@push('styles')
<style>
.hero-section {
    height: 80vh;
    min-height: 600px;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--info-color) 100%);
}

.hero-slider {
    height: 100%;
}

.hero-slide {
    height: 100%;
}

.slide-bg {
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: brightness(0.5);
}

.slide-content {
    background: linear-gradient(90deg, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.4) 50%, rgba(0,0,0,0) 100%);
}

[dir="rtl"] .slide-content {
    background: linear-gradient(-90deg, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.4) 50%, rgba(0,0,0,0) 100%);
}

.hero-search {
    position: absolute;
    bottom: -30px;
    left: 50%;
    transform: translateX(-50%);
    width: 90%;
    max-width: 800px;
    z-index: 10;
}

.search-form {
    background: white;
    padding: 20px;
    border-radius: 15px;
}

.swiper-button-prev,
.swiper-button-next {
    color: white;
}

.swiper-pagination-bullet {
    background: white;
}

.swiper-pagination-bullet-active {
    background: var(--primary-color);
}

@media (max-width: 768px) {
    .hero-section {
        height: 60vh;
    }
    
    .display-4 {
        font-size: 2rem;
    }
    
    .lead {
        font-size: 1rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    new Swiper('.hero-slider', {
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });
});
</script>
@endpush
