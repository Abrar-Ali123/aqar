@extends('layouts.app')

@section('content')
<!-- قسم الترحيب -->
<div class="hero-section position-relative bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="d-flex align-items-center mb-4">
                    <img src="{{ Storage::url($mainFacility->logo) }}" alt="{{ $mainFacility->getTranslation('name', $locale) }}" class="facility-logo me-3">
                    <div>
                        <h1 class="h2 mb-0">{{ $mainFacility->getTranslation('name', $locale) }}</h1>
                        <div class="d-flex align-items-center mt-2">
                            @if($mainFacility->facilityType)
                            <span class="badge bg-primary me-2">
                                {{ $mainFacility->facilityType->getTranslation('name', $locale) }}
                            </span>
                            @endif
                            @if($mainFacility->businessCategory)
                            <span class="badge bg-secondary me-2">
                                {{ $mainFacility->businessCategory->getTranslation('name', $locale) }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                <p class="lead mb-4">{{ $mainFacility->getTranslation('description', $locale) }}</p>
                
                <!-- معلومات التواصل -->
                <div class="contact-info mb-4">
                    @if($mainFacility->phone)
                        <div class="mb-2">
                            <i class="fas fa-phone me-2"></i>
                            <a href="tel:{{ $mainFacility->phone }}" class="text-white">{{ $mainFacility->phone }}</a>
                        </div>
                    @endif
                    @if($mainFacility->email)
                        <div class="mb-2">
                            <i class="fas fa-envelope me-2"></i>
                            <a href="mailto:{{ $mainFacility->email }}" class="text-white">{{ $mainFacility->email }}</a>
                        </div>
                    @endif
                    @if($mainFacility->website)
                        <div class="mb-2">
                            <i class="fas fa-globe me-2"></i>
                            <a href="{{ $mainFacility->website }}" target="_blank" class="text-white">{{ $mainFacility->website }}</a>
                        </div>
                    @endif
                </div>

                <!-- روابط التواصل الاجتماعي -->
                <div class="social-links mb-4">
                    @if($mainFacility->facebook)
                        <a href="{{ $mainFacility->facebook }}" target="_blank" class="btn btn-light btn-sm me-2"><i class="fab fa-facebook"></i></a>
                    @endif
                    @if($mainFacility->twitter)
                        <a href="{{ $mainFacility->twitter }}" target="_blank" class="btn btn-light btn-sm me-2"><i class="fab fa-twitter"></i></a>
                    @endif
                    @if($mainFacility->instagram)
                        <a href="{{ $mainFacility->instagram }}" target="_blank" class="btn btn-light btn-sm me-2"><i class="fab fa-instagram"></i></a>
                    @endif
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block">
                @if($mainFacility->cover_url)
                    <img src="{{ $mainFacility->cover_url }}" alt="Facility Cover" class="img-fluid rounded-3">
                @endif
            </div>
        </div>
    </div>
    <div class="shape-bottom">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <path fill="#ffffff" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
        </svg>
    </div>
</div>

<!-- قسم معرض الصور -->
<div class="gallery-section py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">{{ __('pages.gallery') }}</h2>
        <div class="row g-4">
            @foreach($mainFacility->images as $image)
            <div class="col-md-4">
                <a href="{{ Storage::url($image->path) }}" class="gallery-item" data-gallery="facility-gallery">
                    <img src="{{ Storage::url($image->path) }}" alt="{{ $mainFacility->getTranslation('name', $locale) }}" class="img-fluid rounded-3">
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- قسم الموقع وساعات العمل -->
<div class="location-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2 class="mb-4">{{ __('pages.location_and_hours') }}</h2>
                <div class="mb-4">
                    <h5>{{ __('pages.address') }}</h5>
                    <p>{{ $mainFacility->address }}</p>
                </div>
                <div class="mb-4">
                    <h5>{{ __('pages.working_hours') }}</h5>
                    <ul class="list-unstyled">
                        @foreach(json_decode($mainFacility->working_hours ?? '[]') as $day => $hours)
                        <li class="mb-2">
                            <strong>{{ __('days.' . $day) }}:</strong> {{ $hours->open }} - {{ $hours->close }}
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div id="map" class="rounded-3" style="height: 400px;"></div>
            </div>
        </div>
    </div>
</div>

<!-- قسم التقييمات -->
<div class="reviews-section py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">{{ __('pages.reviews') }}</h2>
        <div class="row">
            <div class="col-md-4 text-center">
                <div class="overall-rating mb-4">
                    <h3 class="display-1">{{ number_format($mainFacility->average_rating, 1) }}</h3>
                    <div class="stars mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= $mainFacility->average_rating ? 'text-warning' : 'text-muted' }}"></i>
                        @endfor
                    </div>
                    <p>{{ $mainFacility->reviews_count }} {{ __('pages.reviews') }}</p>
                </div>
            </div>
            <div class="col-md-8">
                @foreach($mainFacility->reviews()->latest()->take(5)->get() as $review)
                <div class="review-item mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <img src="{{ $review->user->avatar_url }}" alt="{{ $review->user->name }}" class="rounded-circle me-2" width="40">
                        <div>
                            <h6 class="mb-0">{{ $review->user->name }}</h6>
                            <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    <div class="stars mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                        @endfor
                    </div>
                    <p class="mb-0">{{ $review->comment }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- قسم المنتجات -->
<div class="products-section py-5">
    <div class="container">
        <h2 class="text-center mb-5">{{ __('pages.our_products') }}</h2>
        
        <!-- فلتر المنتجات -->
        <div class="filters mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <select class="form-select" name="category">
                        <option value="">{{ __('search.all_categories') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <select class="form-select" name="type">
                        <option value="">{{ __('search.all_types') }}</option>
                        <option value="physical">{{ __('search.physical_products') }}</option>
                        <option value="digital">{{ __('search.digital_products') }}</option>
                        <option value="service">{{ __('search.services') }}</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="{{ __('search.search_placeholder') }}">
                        <button class="btn btn-primary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- عرض المنتجات -->
        <div class="row">
            @foreach($products as $product)
            <div class="col-md-4 mb-4">
                <div class="card h-100 product-card">
                    @if($product->image_url)
                    <img src="{{ $product->image_url }}" class="card-img-top" alt="{{ $product->getTranslation('name', $locale) }}">
                    @endif
                    <div class="card-body">
                        @if($product->category)
                        <div class="category-badge badge bg-secondary mb-2">{{ $product->category->getTranslation('name', $locale) }}</div>
                        @endif
                        <div class="product-type badge bg-primary mb-2">
                            {{ __('products.type_' . $product->type) }}
                        </div>
                        <h5 class="card-title">{{ $product->getTranslation('name', $locale) }}</h5>
                        <p class="card-text">{{ Str::limit($product->getTranslation('description', $locale), 100) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="price">{{ number_format($product->price, 2) }} {{ __('general.currency') }}</span>
                            <a href="{{ route('products.show', ['locale' => $locale, 'product' => $product->id]) }}" class="btn btn-outline-primary">
                                {{ __('pages.view_product') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- ترقيم الصفحات -->
        <div class="d-flex justify-content-center mt-4">
            {{ $products->appends(['view' => 'single'])->links() }}
        </div>
    </div>
</div>

@push('styles')
<style>
/* Hero Section Styles */
.hero-section {
    background: linear-gradient(135deg, var(--bs-primary) 0%, #2c3e50 100%);
    padding: 6rem 0 8rem;
    position: relative;
    overflow: hidden;
}

.facility-logo {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 1rem;
    border: 3px solid white;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.facility-logo:hover {
    transform: scale(1.05);
}

.shape-bottom {
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 100%;
    overflow: hidden;
    line-height: 0;
}

/* Gallery Section Styles */
.gallery-section {
    background-color: #f8f9fa;
    position: relative;
}

.gallery-item {
    display: block;
    position: relative;
    overflow: hidden;
    border-radius: 1rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    margin-bottom: 1.5rem;
}

.gallery-item img {
    transition: transform 0.5s ease;
    width: 100%;
    height: 250px;
    object-fit: cover;
}

.gallery-item:hover img {
    transform: scale(1.08);
}

.gallery-item::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to bottom, rgba(0,0,0,0) 0%, rgba(0,0,0,0.5) 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.gallery-item:hover::after {
    opacity: 1;
}

/* Product Card Styles */
.product-card {
    border: none;
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    height: 100%;
}

.product-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.product-card img {
    height: 250px;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.product-card:hover img {
    transform: scale(1.05);
}

.product-type {
    position: absolute;
    top: 1rem;
    right: 1rem;
    z-index: 1;
    padding: 0.5rem 1rem;
    border-radius: 2rem;
    font-weight: 500;
    backdrop-filter: blur(10px);
    background: rgba(255,255,255,0.9);
}

[dir="rtl"] .product-type {
    right: auto;
    left: 1rem;
}

/* Review Section Styles */
.review-item {
    background: white;
    padding: 1.5rem;
    border-radius: 1rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    margin-bottom: 1.5rem;
    transition: transform 0.3s ease;
}

.review-item:hover {
    transform: translateY(-5px);
}

.review-item .avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    margin-right: 1rem;
}

/* Location Section Styles */
.location-section {
    background-color: white;
    position: relative;
}

#map {
    border-radius: 1rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    overflow: hidden;
}

/* Social Links Styles */
.social-links .btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    margin: 0 0.5rem;
    background: rgba(255,255,255,0.9);
    color: var(--bs-primary);
}

.social-links .btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
}

/* Contact Info Styles */
.contact-info a {
    text-decoration: none;
    transition: opacity 0.3s ease;
}

.contact-info a:hover {
    opacity: 0.8;
}

/* Badges Styles */
.badge {
    padding: 0.5rem 1rem;
    border-radius: 2rem;
    font-weight: 500;
    margin-right: 0.5rem;
}

/* Animation Classes */
.fade-in {
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
@endpush

@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&callback=initMap" async defer></script>
<script>
function initMap() {
    const location = { lat: {{ $mainFacility->latitude }}, lng: {{ $mainFacility->longitude }} };
    const map = new google.maps.Map(document.getElementById('map'), {
        zoom: 15,
        center: location,
    });
    const marker = new google.maps.Marker({
        position: location,
        map: map,
        title: '{{ $mainFacility->getTranslation('name', $locale) }}'
    });
}
</script>
@endpush

@endsection
