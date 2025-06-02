@extends('layouts.app')

@section('content')
<div class="agent-profile py-5">
    <div class="container">
        <!-- معلومات الوكيل الرئيسية -->
        <div class="profile-header bg-white rounded-3 shadow-sm p-4 mb-4">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center">
                        <img src="{{ $agent->avatar }}" 
                             alt="{{ $agent->name }}"
                             class="rounded-circle me-4"
                             width="120">
                        <div>
                            <h1 class="h3 mb-2">{{ $agent->name }}</h1>
                            <p class="text-muted mb-3">{{ $agent->title }}</p>
                            <div class="agent-rating mb-3">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $agent->rating ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                                <span class="ms-2">({{ $agent->reviews_count }} {{ __('pages.reviews') }})</span>
                            </div>
                            <div class="agent-contact">
                                @if($agent->phone)
                                    <a href="tel:{{ $agent->phone }}" class="btn btn-outline-primary me-2">
                                        <i class="fas fa-phone me-2"></i>{{ $agent->phone }}
                                    </a>
                                @endif
                                @if($agent->email)
                                    <a href="mailto:{{ $agent->email }}" class="btn btn-outline-primary">
                                        <i class="fas fa-envelope me-2"></i>{{ $agent->email }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mt-4 mt-lg-0">
                    <div class="row text-center g-3">
                        <div class="col-4">
                            <div class="stat-item">
                                <div class="h3 mb-1">{{ $agent->properties_count }}</div>
                                <div class="text-muted">{{ __('pages.properties') }}</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-item">
                                <div class="h3 mb-1">{{ $agent->sales_count }}</div>
                                <div class="text-muted">{{ __('pages.sales') }}</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-item">
                                <div class="h3 mb-1">{{ number_format($agent->rating, 1) }}</div>
                                <div class="text-muted">{{ __('pages.rating') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- نبذة عن الوكيل -->
                <div class="profile-about bg-white rounded-3 shadow-sm p-4 mb-4">
                    <h2 class="h4 mb-4">{{ __('pages.about_agent') }}</h2>
                    {!! $agent->bio !!}
                </div>

                <!-- العقارات -->
                <div class="profile-properties bg-white rounded-3 shadow-sm p-4 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="h4 mb-0">{{ __('pages.agent_properties') }}</h2>
                        <div class="properties-filter">
                            <select class="form-select" onchange="filterProperties(this.value)">
                                <option value="all">{{ __('pages.all_properties') }}</option>
                                <option value="sale">{{ __('pages.for_sale') }}</option>
                                <option value="rent">{{ __('pages.for_rent') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-4">
                        @foreach($agent->properties as $property)
                        <div class="col-md-6">
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
                                        <span class="badge bg-info">{{ $property->type }}</span>
                                    </div>
                                    <div class="property-price position-absolute bottom-0 end-0 p-3">
                                        <h4 class="mb-0 text-white">{{ number_format($property->price) }}</h4>
                                    </div>
                                </div>
                                <div class="property-info p-4">
                                    <h3 class="property-title h5 mb-2">{{ $property->title }}</h3>
                                    <p class="property-location mb-3">
                                        <i class="fas fa-map-marker-alt me-2"></i>
                                        {{ $property->location->name }}
                                    </p>
                                    <div class="property-features d-flex justify-content-between text-muted small">
                                        <span><i class="fas fa-bed me-2"></i>{{ $property->bedrooms }}</span>
                                        <span><i class="fas fa-bath me-2"></i>{{ $property->bathrooms }}</span>
                                        <span><i class="fas fa-ruler-combined me-2"></i>{{ $property->area }}م²</span>
                                    </div>
                                </div>
                                <a href="{{ route('products.show', ['locale' => app()->getLocale(), 'product' => $property->id]) }}" 
                                   class="stretched-link"></a>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="pagination-wrapper mt-4">
                        {{ $agent->properties->links() }}
                    </div>
                </div>

                <!-- المراجعات -->
                <div class="profile-reviews bg-white rounded-3 shadow-sm p-4">
                    <h2 class="h4 mb-4">{{ __('pages.agent_reviews') }}</h2>
                    
                    @foreach($agent->reviews as $review)
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
                        {{ $agent->reviews->links() }}
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- نموذج الاتصال -->
                <div class="contact-card bg-white rounded-3 shadow-sm p-4 mb-4">
                    <h3 class="h5 mb-4">{{ __('pages.contact_agent') }}</h3>
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

                <!-- ساعات العمل -->
                <div class="working-hours bg-white rounded-3 shadow-sm p-4 mb-4">
                    <h3 class="h5 mb-4">{{ __('pages.working_hours') }}</h3>
                    <ul class="list-unstyled mb-0">
                        @foreach($agent->working_hours as $day => $hours)
                        <li class="d-flex justify-content-between mb-2">
                            <span>{{ __("pages.$day") }}</span>
                            <span>{{ $hours }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <!-- الشهادات -->
                <div class="certifications bg-white rounded-3 shadow-sm p-4">
                    <h3 class="h5 mb-4">{{ __('pages.certifications') }}</h3>
                    <div class="row g-3">
                        @foreach($agent->certifications as $cert)
                        <div class="col-6">
                            <div class="certification-item text-center">
                                <img src="{{ $cert->image }}" 
                                     alt="{{ $cert->name }}"
                                     class="img-fluid mb-2">
                                <div class="small">{{ $cert->name }}</div>
                            </div>
                        </div>
                        @endforeach
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

.property-card {
    background: white;
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
    position: relative;
}

.property-card:hover {
    transform: translateY(-5px);
}

.property-image {
    height: 200px;
    overflow: hidden;
}

.property-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.certification-item img {
    max-height: 80px;
    object-fit: contain;
}

/* RTL Support */
[dir="rtl"] .me-4 {
    margin-right: 0 !important;
    margin-left: 1.5rem !important;
}

[dir="rtl"] .me-2 {
    margin-right: 0 !important;
    margin-left: 0.5rem !important;
}
</style>
@endpush

@push('scripts')
<script>
function filterProperties(type) {
    // Implementation
}
</script>
@endpush
