@extends('layouts.app')

@section('content')
<div class="property-details py-5">
    <div class="container">
        <!-- تفاصيل العقار الرئيسية -->
        <div class="property-header mb-5">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <nav aria-label="breadcrumb" class="mb-3">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home', ['locale' => app()->getLocale()]) }}">{{ __('pages.home') }}</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('products.index', ['locale' => app()->getLocale()]) }}">{{ __('pages.properties') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ $product->title }}</li>
                        </ol>
                    </nav>
                    <h1 class="mb-3">{{ $product->title }}</h1>
                    <p class="location mb-3">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        {{ $product->location->name }}
                    </p>
                    <div class="property-tags">
                        @if($product->is_featured)
                            <span class="badge bg-warning">{{ __('pages.featured') }}</span>
                        @endif
                        <span class="badge bg-primary">{{ $product->category->name }}</span>
                        <span class="badge bg-info">{{ $product->status }}</span>
                    </div>
                </div>
                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                    <div class="property-price mb-3">
                        <span class="h2 fw-bold text-primary">{{ number_format($product->price) }}</span>
                        @if($product->old_price)
                            <del class="text-muted ms-2">{{ number_format($product->old_price) }}</del>
                        @endif
                    </div>
                    <div class="property-actions d-flex gap-2 justify-content-lg-end">
                        <button class="btn btn-outline-primary" onclick="addToFavorites({{ $product->id }})">
                            <i class="far fa-heart me-2"></i>{{ __('pages.add_to_favorites') }}
                        </button>
                        <button class="btn btn-outline-primary" onclick="shareProperty()">
                            <i class="fas fa-share-alt me-2"></i>{{ __('pages.share') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- معرض الصور -->
        <div class="property-gallery mb-5">
            <div class="swiper gallery-slider">
                <div class="swiper-wrapper">
                    @foreach($product->images as $image)
                    <div class="swiper-slide">
                        <img src="{{ $image->url }}" 
                             alt="{{ $product->title }}"
                             class="img-fluid rounded-3">
                    </div>
                    @endforeach
                </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
            <div class="swiper gallery-thumbs mt-3">
                <div class="swiper-wrapper">
                    @foreach($product->images as $image)
                    <div class="swiper-slide">
                        <img src="{{ $image->url }}" 
                             alt="{{ $product->title }}"
                             class="img-fluid rounded-3">
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- تفاصيل العقار -->
                <div class="property-features bg-white rounded-3 shadow-sm p-4 mb-4">
                    <h3 class="h4 mb-4">{{ __('pages.property_features') }}</h3>
                    <div class="row g-4">
                        <div class="col-6 col-md-3">
                            <div class="feature-item text-center">
                                <i class="fas fa-bed mb-2"></i>
                                <h4 class="h6">{{ __('pages.bedrooms') }}</h4>
                                <p class="mb-0">{{ $product->bedrooms }}</p>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="feature-item text-center">
                                <i class="fas fa-bath mb-2"></i>
                                <h4 class="h6">{{ __('pages.bathrooms') }}</h4>
                                <p class="mb-0">{{ $product->bathrooms }}</p>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="feature-item text-center">
                                <i class="fas fa-ruler-combined mb-2"></i>
                                <h4 class="h6">{{ __('pages.area') }}</h4>
                                <p class="mb-0">{{ $product->area }}م²</p>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="feature-item text-center">
                                <i class="fas fa-calendar-alt mb-2"></i>
                                <h4 class="h6">{{ __('pages.year_built') }}</h4>
                                <p class="mb-0">{{ $product->year_built }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- وصف العقار -->
                <div class="property-description bg-white rounded-3 shadow-sm p-4 mb-4">
                    <h3 class="h4 mb-4">{{ __('pages.description') }}</h3>
                    {!! $product->description !!}
                </div>

                <!-- المميزات -->
                <div class="property-amenities bg-white rounded-3 shadow-sm p-4 mb-4">
                    <h3 class="h4 mb-4">{{ __('pages.amenities') }}</h3>
                    <div class="row g-3">
                        @foreach($product->amenities as $amenity)
                        <div class="col-md-4">
                            <div class="amenity-item">
                                <i class="{{ $amenity->icon }} me-2"></i>
                                {{ $amenity->name }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- الموقع -->
                <div class="property-location bg-white rounded-3 shadow-sm p-4 mb-4">
                    <h3 class="h4 mb-4">{{ __('pages.location') }}</h3>
                    <div id="propertyMap" style="height: 400px;" class="rounded-3"></div>
                </div>

                <!-- المراجعات -->
                <div class="property-reviews bg-white rounded-3 shadow-sm p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="h4 mb-0">{{ __('pages.reviews') }}</h3>
                        <button class="btn btn-primary" onclick="showReviewForm()">
                            {{ __('pages.write_review') }}
                        </button>
                    </div>

                    @foreach($product->reviews as $review)
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
                </div>
            </div>

            <div class="col-lg-4">
                <!-- معلومات الوكيل -->
                <div class="agent-card bg-white rounded-3 shadow-sm p-4 mb-4">
                    <div class="agent-info text-center mb-4">
                        <img src="{{ $product->agent->avatar }}" 
                             alt="{{ $product->agent->name }}"
                             class="rounded-circle mb-3"
                             width="100">
                        <h4 class="h5 mb-2">{{ $product->agent->name }}</h4>
                        <p class="text-muted mb-3">{{ $product->agent->title }}</p>
                        <div class="agent-rating mb-3">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= $product->agent->rating ? 'text-warning' : 'text-muted' }}"></i>
                            @endfor
                            <span class="ms-2">({{ $product->agent->reviews_count }})</span>
                        </div>
                        <div class="agent-stats d-flex justify-content-center gap-3 text-muted small">
                            <span>
                                <i class="fas fa-home me-1"></i>
                                {{ $product->agent->properties_count }} {{ __('pages.properties') }}
                            </span>
                            <span>
                                <i class="fas fa-star me-1"></i>
                                {{ number_format($product->agent->rating, 1) }}
                            </span>
                        </div>
                    </div>

                    <!-- نموذج الاتصال -->
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

                <!-- عقارات مشابهة -->
                <div class="similar-properties bg-white rounded-3 shadow-sm p-4">
                    <h3 class="h5 mb-4">{{ __('pages.similar_properties') }}</h3>
                    @foreach($similarProperties as $similar)
                    <div class="similar-property-item mb-3">
                        <div class="row g-0">
                            <div class="col-4">
                                <img src="{{ $similar->images->first()->url }}" 
                                     alt="{{ $similar->title }}"
                                     class="img-fluid rounded-start">
                            </div>
                            <div class="col-8">
                                <div class="p-3">
                                    <h4 class="h6 mb-2">{{ $similar->title }}</h4>
                                    <p class="price text-primary mb-0">
                                        {{ number_format($similar->price) }}
                                    </p>
                                </div>
                            </div>
                            <a href="{{ route('products.show', ['locale' => app()->getLocale(), 'product' => $similar->id]) }}" 
                               class="stretched-link"></a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.gallery-slider {
    height: 500px;
    border-radius: 1rem;
    overflow: hidden;
}

.gallery-slider img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.gallery-thumbs {
    height: 100px;
}

.gallery-thumbs .swiper-slide {
    opacity: 0.5;
    cursor: pointer;
    transition: all 0.3s ease;
}

.gallery-thumbs .swiper-slide-thumb-active {
    opacity: 1;
}

.feature-item i {
    font-size: 2rem;
    color: var(--bs-primary);
}

.amenity-item {
    padding: 0.5rem;
    background: var(--bs-light);
    border-radius: 0.5rem;
}

.agent-card {
    position: sticky;
    top: 1rem;
}

/* RTL Support */
[dir="rtl"] .gallery-slider .swiper-button-next {
    right: auto;
    left: 10px;
}

[dir="rtl"] .gallery-slider .swiper-button-prev {
    left: auto;
    right: 10px;
}
</style>
@endpush

@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&callback=initMap" async defer></script>
<script>
function initMap() {
    const map = new google.maps.Map(document.getElementById('propertyMap'), {
        zoom: 15,
        center: { 
            lat: {{ $product->latitude }}, 
            lng: {{ $product->longitude }} 
        }
    });

    new google.maps.Marker({
        position: { 
            lat: {{ $product->latitude }}, 
            lng: {{ $product->longitude }} 
        },
        map: map,
        title: "{{ $product->title }}"
    });
}

new Swiper('.gallery-slider', {
    spaceBetween: 10,
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
    pagination: {
        el: '.swiper-pagination',
        clickable: true
    },
    thumbs: {
        swiper: new Swiper('.gallery-thumbs', {
            spaceBetween: 10,
            slidesPerView: 4,
            freeMode: true,
            watchSlidesVisibility: true,
            watchSlidesProgress: true,
        })
    }
});

function addToFavorites(productId) {
    // Implementation
}

function shareProperty() {
    // Implementation
}

function showReviewForm() {
    // Implementation
}
</script>
@endpush
