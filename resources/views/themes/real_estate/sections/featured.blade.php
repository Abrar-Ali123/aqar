<section class="featured-section py-5">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="section-title">{{ __('pages.featured_properties') }}</h2>
            <p class="section-subtitle">{{ __('pages.featured_properties_subtitle') }}</p>
        </div>

        <div class="featured-properties mb-5">
            <div class="row g-4">
                @foreach($featuredProperties as $property)
                <div class="col-md-6 col-lg-4">
                    <div class="property-card">
                        <div class="property-image position-relative">
                            @if($property->images->count() > 0)
                                <img src="{{ $property->images->first()->url }}" 
                                     alt="{{ $property->title }}" 
                                     class="img-fluid w-100">
                            @endif
                            <div class="property-tags position-absolute top-0 start-0 p-3">
                                @if($property->is_featured)
                                    <span class="badge bg-warning">{{ __('pages.featured') }}</span>
                                @endif
                                @if($property->status === 'new')
                                    <span class="badge bg-success">{{ __('pages.new') }}</span>
                                @endif
                            </div>
                            <div class="property-price position-absolute bottom-0 end-0 p-3">
                                <h4 class="mb-0 text-white">{{ number_format($property->price) }}</h4>
                            </div>
                        </div>
                        <div class="property-info p-4">
                            <h3 class="property-title h5">{{ $property->title }}</h3>
                            <p class="property-location mb-3">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                {{ $property->location->name }}
                            </p>
                            <div class="property-features d-flex justify-content-between text-muted small mb-3">
                                <span><i class="fas fa-bed me-2"></i>{{ $property->bedrooms }} {{ __('pages.beds') }}</span>
                                <span><i class="fas fa-bath me-2"></i>{{ $property->bathrooms }} {{ __('pages.baths') }}</span>
                                <span><i class="fas fa-ruler-combined me-2"></i>{{ $property->area }}م²</span>
                            </div>
                            <div class="property-footer d-flex justify-content-between align-items-center">
                                <div class="agent d-flex align-items-center">
                                    <img src="{{ $property->agent->avatar }}" 
                                         alt="{{ $property->agent->name }}"
                                         class="rounded-circle me-2"
                                         width="30">
                                    <span class="small">{{ $property->agent->name }}</span>
                                </div>
                                <a href="{{ route('properties.show', ['locale' => app()->getLocale(), 'property' => $property->id]) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    {{ __('pages.view_details') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="featured-agencies mt-5">
            <h3 class="text-center mb-4">{{ __('pages.featured_agencies') }}</h3>
            <div class="row g-4">
                @foreach($featuredAgencies as $agency)
                <div class="col-6 col-md-3">
                    <div class="agency-card text-center">
                        <img src="{{ $agency->logo }}" 
                             alt="{{ $agency->name }}"
                             class="img-fluid mb-3"
                             style="max-height: 60px;">
                        <h4 class="agency-name h6 mb-2">{{ $agency->name }}</h4>
                        <p class="agency-stats small text-muted mb-0">
                            {{ $agency->properties_count }} {{ __('pages.properties') }}
                        </p>
                        <a href="{{ route('agencies.show', ['locale' => app()->getLocale(), 'agency' => $agency->id]) }}" 
                           class="stretched-link"></a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
