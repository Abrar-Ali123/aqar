@props(['page' => null])

@php
    $template = json_decode($page->template_settings ?? '{}', true);
    $design = json_decode($page->design_settings ?? '{}', true);
    $components = json_decode($page->content ?? '{}', true)['components'] ?? [];
@endphp

<div class="facility-page {{ $template['layout'] ?? 'modern' }}" 
    style="{{ $design['custom_css'] ?? '' }}">
    
    {{-- Hero Section --}}
    @if(isset($components['hero']))
    <section class="hero-section {{ $design['header_style'] ?? 'transparent' }}">
        <div class="container">
            <div class="hero-content text-{{ $template['rtl'] ? 'right' : 'left' }}">
                <h1 class="hero-title">{{ $page->translations->first()?->title }}</h1>
                @if($components['hero']['subtitle'])
                    <p class="hero-subtitle">{{ $components['hero']['subtitle'] }}</p>
                @endif
                @if($components['hero']['cta'])
                    <a href="{{ $components['hero']['cta']['url'] }}" class="btn btn-primary btn-{{ $design['button_style'] ?? 'rounded' }}">
                        {{ $components['hero']['cta']['text'] }}
                    </a>
                @endif
            </div>
        </div>
        @if($components['hero']['background'])
            <div class="hero-background" style="background-image: url('{{ $components['hero']['background'] }}')"></div>
        @endif
    </section>
    @endif

    {{-- Products Section --}}
    @if(isset($components['products']))
    <section class="products-section py-5">
        <div class="container">
            @if($components['products']['title'])
                <h2 class="section-title mb-4">{{ $components['products']['title'] }}</h2>
            @endif
            <div class="products-grid grid-{{ $template['product_view'] ?? 'grid' }}">
                @foreach($facility->products->take($components['products']['limit'] ?? 8) as $product)
                    <x-facility.product-card :product="$product" :design="$design" />
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Features Section --}}
    @if(isset($components['features']))
    <section class="features-section py-5 bg-{{ $components['features']['background'] ?? 'light' }}">
        <div class="container">
            @if($components['features']['title'])
                <h2 class="section-title mb-4">{{ $components['features']['title'] }}</h2>
            @endif
            <div class="features-grid">
                @foreach($components['features']['items'] as $feature)
                    <div class="feature-item text-center">
                        <i class="{{ $feature['icon'] }} feature-icon"></i>
                        <h3 class="feature-title">{{ $feature['title'] }}</h3>
                        <p class="feature-description">{{ $feature['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Contact Section --}}
    @if(isset($components['contact']))
    <section class="contact-section py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h2 class="section-title">{{ $components['contact']['title'] }}</h2>
                    <div class="contact-info">
                        @if($facility->business_details['phone'])
                            <p><i class="fas fa-phone"></i> {{ $facility->business_details['phone'] }}</p>
                        @endif
                        @if($facility->business_details['email'])
                            <p><i class="fas fa-envelope"></i> {{ $facility->business_details['email'] }}</p>
                        @endif
                        @if($facility->business_details['address'])
                            <p><i class="fas fa-map-marker-alt"></i> {{ $facility->business_details['address'] }}</p>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <form class="contact-form" action="{{ route('facilities.contact', $facility->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <input type="text" name="name" class="form-control" placeholder="{{ __('Your Name') }}" required>
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" class="form-control" placeholder="{{ __('Your Email') }}" required>
                        </div>
                        <div class="form-group">
                            <textarea name="message" class="form-control" rows="4" placeholder="{{ __('Your Message') }}" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-{{ $design['button_style'] ?? 'rounded' }}">
                            {{ __('Send Message') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Reviews Section --}}
    @if(isset($components['reviews']) && $page->reviews_settings['enabled'])
    <section class="reviews-section py-5 bg-light">
        <div class="container">
            <h2 class="section-title mb-4">{{ $components['reviews']['title'] }}</h2>
            <div class="reviews-slider">
                @foreach($facility->reviews->take(5) as $review)
                    <div class="review-card">
                        <div class="review-rating">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                            @endfor
                        </div>
                        <p class="review-text">{{ $review->content }}</p>
                        <p class="review-author">{{ $review->author_name }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
</div>

@push('styles')
<style>
    :root {
        --primary-color: {{ $design['primary_color'] ?? '#2C3E50' }};
        --secondary-color: {{ $design['secondary_color'] ?? '#E74C3C' }};
        --font-family: {{ $design['font_family'] ?? 'Cairo' }}, sans-serif;
    }

    .facility-page {
        font-family: var(--font-family);
    }

    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .section-title {
        color: var(--primary-color);
    }

    {!! $design['custom_css'] ?? '' !!}
</style>
@endpush

@push('scripts')
<script>
    // تهيئة سلايدر المراجعات
    if (document.querySelector('.reviews-slider')) {
        new Swiper('.reviews-slider', {
            slidesPerView: 3,
            spaceBetween: 30,
            pagination: {
                el: '.swiper-pagination',
                clickable: true
            },
            breakpoints: {
                640: { slidesPerView: 1 },
                768: { slidesPerView: 2 },
                1024: { slidesPerView: 3 }
            }
        });
    }
</script>
@endpush
