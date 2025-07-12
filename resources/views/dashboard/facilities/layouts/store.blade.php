{{-- قالب عرض المتجر --}}
<div class="facility-template store-template">
    {{-- رأس الصفحة --}}
    <header class="facility-header position-relative">
        @if($facility->cover)
            <div class="cover-image" style="background-image: url('{{ asset($facility->cover) }}')"></div>
        @endif
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-md-2 mb-3 mb-md-0">
                    @if($facility->logo)
                        <img src="{{ asset($facility->logo) }}" alt="{{ $facility->translate()->name }}" class="img-fluid rounded-circle store-logo">
                    @endif
                </div>
                <div class="col-md-10">
                    <h1 class="facility-name mb-2">{{ $facility->translate()->name }}</h1>
                    <p class="facility-description mb-3">{{ $facility->translate()->description }}</p>
                    <div class="facility-meta d-flex flex-wrap gap-3">
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
                        @if($facility->email)
                            <span class="meta-item">
                                <i class="fas fa-envelope"></i>
                                {{ $facility->email }}
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

    {{-- قسم المنتجات --}}
    @if($products->isNotEmpty())
        <section class="products-section py-5 bg-light">
            <div class="container">
                <h2 class="section-title mb-4">{{ __('Our Products') }}</h2>
                <div class="row g-4">
                    @foreach($products as $product)
                        <div class="col-md-4">
                            <div class="product-card card h-100">
                                @if($product->images->isNotEmpty())
                                    <img src="{{ asset($product->images->first()->path) }}" 
                                         class="card-img-top" 
                                         alt="{{ $product->translate()->name }}">
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title">{{ $product->translate()->name }}</h5>
                                    <p class="card-text">{{ Str::limit($product->translate()->description, 100) }}</p>
                                    
                                    @if($product->price)
                                        <div class="product-price mb-3">
                                            <span class="price">{{ number_format($product->price, 2) }}</span>
                                            <span class="currency">{{ $settings['currency'] ?? 'SAR' }}</span>
                                        </div>
                                    @endif
                                    
                                    @if($product->attributeValues->isNotEmpty())
                                        <div class="product-attributes">
                                            @foreach($product->attributeValues as $value)
                                                <span class="badge bg-secondary me-1">
                                                    {{ $value->attribute->translate()->name }}: {{ $value->value }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
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

    {{-- معلومات الاتصال --}}
    <section class="contact-section py-5 bg-light">
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
                        
                        @if($facility->working_hours)
                            <div class="mb-3">
                                <h5><i class="fas fa-clock me-2"></i>{{ __('Working Hours') }}</h5>
                                <ul class="list-unstyled">
                                    @foreach($facility->working_hours as $day => $hours)
                                        <li>{{ __($day) }}: {{ $hours }}</li>
                                    @endforeach
                                </ul>
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
    .store-template {
        --store-primary: {{ $styles['colors']['primary'] ?? '#0d6efd' }};
        --store-secondary: {{ $styles['colors']['secondary'] ?? '#6c757d' }};
    }

    .facility-header {
        background-color: var(--store-primary);
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

    .store-logo {
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

    .product-card {
        transition: transform 0.3s ease;
    }

    .product-card:hover {
        transform: translateY(-5px);
    }

    .product-card .card-img-top {
        height: 200px;
        object-fit: cover;
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

    .contact-info h5 {
        color: var(--store-primary);
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
