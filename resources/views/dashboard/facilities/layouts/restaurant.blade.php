{{-- قالب عرض المطعم --}}
<div class="facility-template restaurant-template">
    {{-- رأس الصفحة --}}
    <header class="facility-header position-relative">
        @if($facility->cover)
            <div class="cover-image" style="background-image: url('{{ asset($facility->cover) }}')"></div>
        @endif
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-md-2 mb-3 mb-md-0">
                    @if($facility->logo)
                        <img src="{{ asset($facility->logo) }}" alt="{{ $facility->translate()->name }}" class="img-fluid rounded-circle restaurant-logo">
                    @endif
                </div>
                <div class="col-md-10">
                    <h1 class="facility-name mb-2">{{ $facility->translate()->name }}</h1>
                    <p class="facility-description mb-3">{{ $facility->translate()->description }}</p>
                    <div class="facility-meta d-flex flex-wrap gap-3">
                        @if($facility->businessCategory)
                            <span class="meta-item">
                                <i class="fas fa-utensils"></i>
                                {{ $facility->businessCategory->translate()->name }}
                            </span>
                        @endif
                        @if($facility->address)
                            <span class="meta-item">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ $facility->address }}
                            </span>
                        @endif
                        @if($facility->phone)
                            <span class="meta-item">
                                <i class="fas fa-phone"></i>
                                {{ $facility->phone }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- عرض الأقسام المخصصة --}}
    @foreach($sections as $section)
        <section class="template-section py-5" id="section-{{ $section->id }}">
            <div class="container">
                @if($section->name)
                    <h2 class="section-title mb-4">{{ $section->name }}</h2>
                @endif
                
                <div class="section-content" style="{{ $section->styles ?? '' }}">
                    @foreach($section->components as $component)
                        @include("facilities.components.{$component->type}", [
                            'component' => $component,
                            'settings' => $component->settings ?? [],
                            'facility' => $facility
                        ])
                    @endforeach
                </div>
            </div>
        </section>
    @endforeach

    {{-- قائمة الطعام --}}
    @if($products->isNotEmpty())
        <section class="menu-section py-5 bg-light">
            <div class="container">
                <h2 class="section-title mb-4">{{ __('Our Menu') }}</h2>
                <div class="row g-4">
                    @foreach($products as $product)
                        <div class="col-md-6">
                            <div class="menu-item card h-100">
                                <div class="row g-0">
                                    @if($product->images->isNotEmpty())
                                        <div class="col-4">
                                            <img src="{{ asset($product->images->first()->path) }}" 
                                                 class="menu-item-image" 
                                                 alt="{{ $product->translate()->name }}">
                                        </div>
                                    @endif
                                    <div class="col">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <h5 class="card-title mb-1">{{ $product->translate()->name }}</h5>
                                                @if($product->price)
                                                    <span class="menu-item-price">
                                                        {{ number_format($product->price, 2) }}
                                                        <small>{{ $settings['currency'] ?? 'SAR' }}</small>
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="card-text">{{ Str::limit($product->translate()->description, 100) }}</p>
                                            
                                            @if($product->attributeValues->isNotEmpty())
                                                <div class="menu-item-attributes">
                                                    @foreach($product->attributeValues as $value)
                                                        <span class="badge bg-light text-dark me-1">
                                                            {{ $value->attribute->translate()->name }}: {{ $value->value }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- معرض الصور --}}
    @if($gallery->isNotEmpty())
        <section class="gallery-section py-5">
            <div class="container">
                <h2 class="section-title mb-4">{{ __('Photo Gallery') }}</h2>
                <div class="row g-4">
                    @foreach($gallery as $image)
                        <div class="col-6 col-md-4 col-lg-3">
                            <a href="{{ asset($image->path) }}" class="gallery-item" data-fancybox="gallery">
                                <img src="{{ asset($image->path) }}" 
                                     class="img-fluid rounded" 
                                     alt="{{ $image->alt_text ?? $facility->translate()->name }}">
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- خدمات الحجز --}}
    @if($facility->hasBooking() && $services->isNotEmpty())
        <section class="booking-section py-5 bg-light">
            <div class="container">
                <h2 class="section-title mb-4">{{ __('Book a Table') }}</h2>
                <div class="row">
                    <div class="col-md-6">
                        <div class="booking-info mb-4">
                            <h5 class="mb-3">{{ __('Reservation Information') }}</h5>
                            <ul class="list-unstyled">
                                @foreach($services as $service)
                                    <li class="mb-2">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        {{ $service->translate()->name }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        
                        @if($facility->working_hours)
                            <div class="working-hours">
                                <h5 class="mb-3">{{ __('Working Hours') }}</h5>
                                <ul class="list-unstyled">
                                    @foreach($facility->working_hours as $day => $hours)
                                        <li class="mb-2">
                                            <strong>{{ __($day) }}:</strong> {{ $hours }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <div class="booking-form card">
                            <div class="card-body">
                                <h5 class="card-title mb-4">{{ __('Make a Reservation') }}</h5>
                                <form action="{{ route('facilities.booking.store', ['facility' => $facility->id]) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('Date') }}</label>
                                        <input type="date" name="date" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('Time') }}</label>
                                        <input type="time" name="time" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('Number of Guests') }}</label>
                                        <input type="number" name="guests" class="form-control" min="1" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('Notes') }}</label>
                                        <textarea name="notes" class="form-control" rows="3"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">
                                        {{ __('Book Now') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- معلومات الاتصال --}}
    <section class="contact-section py-5">
        <div class="container">
            <h2 class="section-title mb-4">{{ __('Contact Us') }}</h2>
            <div class="row">
                <div class="col-md-6">
                    @if($facility->latitude && $facility->longitude)
                        <div class="map-container mb-4">
                            <div id="facility-map" style="height: 300px;"></div>
                        </div>
                    @endif
                </div>
                <div class="col-md-6">
                    <div class="contact-info">
                        @if($facility->address)
                            <div class="mb-3">
                                <h5><i class="fas fa-map-marker-alt me-2"></i>{{ __('Address') }}</h5>
                                <p>{{ $facility->address }}</p>
                            </div>
                        @endif
                        
                        @if($facility->phone)
                            <div class="mb-3">
                                <h5><i class="fas fa-phone me-2"></i>{{ __('Phone') }}</h5>
                                <p><a href="tel:{{ $facility->phone }}" class="text-decoration-none">{{ $facility->phone }}</a></p>
                            </div>
                        @endif
                        
                        @if($facility->email)
                            <div class="mb-3">
                                <h5><i class="fas fa-envelope me-2"></i>{{ __('Email') }}</h5>
                                <p><a href="mailto:{{ $facility->email }}" class="text-decoration-none">{{ $facility->email }}</a></p>
                            </div>
                        @endif
                        
                        @if($facility->social_media)
                            <div class="social-media">
                                <h5><i class="fas fa-share-alt me-2"></i>{{ __('Follow Us') }}</h5>
                                <div class="d-flex gap-2">
                                    @foreach($facility->social_media as $platform => $url)
                                        <a href="{{ $url }}" class="btn btn-outline-primary" target="_blank">
                                            <i class="fab fa-{{ $platform }}"></i>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@push('styles')
<style>
    .restaurant-template {
        --restaurant-primary: {{ $styles['colors']['primary'] ?? '#dc3545' }};
        --restaurant-secondary: {{ $styles['colors']['secondary'] ?? '#6c757d' }};
    }

    .facility-header {
        background-color: var(--restaurant-primary);
        color: white;
        padding: 2rem 0;
        margin-bottom: 2rem;
    }

    .cover-image {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-size: cover;
        background-position: center;
        opacity: 0.3;
    }

    .restaurant-logo {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border: 4px solid white;
    }

    .facility-meta .meta-item {
        background: rgba(255, 255, 255, 0.1);
        padding: 0.5rem 1rem;
        border-radius: 2rem;
    }

    .menu-item {
        transition: transform 0.3s ease;
    }

    .menu-item:hover {
        transform: translateY(-5px);
    }

    .menu-item-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .menu-item-price {
        color: var(--restaurant-primary);
        font-weight: bold;
        font-size: 1.1rem;
    }

    .menu-item-attributes {
        margin-top: 0.5rem;
    }

    .gallery-item {
        display: block;
        position: relative;
        overflow: hidden;
    }

    .gallery-item img {
        transition: transform 0.3s ease;
    }

    .gallery-item:hover img {
        transform: scale(1.05);
    }

    .booking-form {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }

    .contact-info h5 {
        color: var(--restaurant-primary);
    }

    .social-media .btn {
        width: 40px;
        height: 40px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }
</style>
@endpush

@push('scripts')
@if($facility->latitude && $facility->longitude)
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}"></script>
<script>
    function initMap() {
        const location = {
            lat: {{ $facility->latitude }},
            lng: {{ $facility->longitude }}
        };
        
        const map = new google.maps.Map(document.getElementById('facility-map'), {
            zoom: 15,
            center: location,
        });
        
        new google.maps.Marker({
            position: location,
            map: map,
            title: '{{ $facility->translate()->name }}'
        });
    }
    
    document.addEventListener('DOMContentLoaded', initMap);
</script>
@endif
@endpush
