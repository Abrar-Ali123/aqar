@extends('layouts.app')

@section('content')
<div class="event-details py-5">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('home', ['locale' => app()->getLocale()]) }}">{{ __('pages.home') }}</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('products.index', ['locale' => app()->getLocale()]) }}">{{ __('pages.events') }}</a>
                </li>
                <li class="breadcrumb-item active">{{ $product->title }}</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-lg-8">
                <!-- تفاصيل الحدث -->
                <div class="event-header bg-white rounded-3 shadow-sm p-4 mb-4">
                    <div class="event-image position-relative mb-4">
                        @if($product->images->count() > 0)
                            <img src="{{ $product->images->first()->url }}" 
                                 alt="{{ $product->title }}"
                                 class="img-fluid rounded-3">
                        @endif
                        @if($product->is_featured)
                            <div class="event-featured position-absolute top-0 start-0 m-3">
                                <span class="badge bg-warning">
                                    <i class="fas fa-star me-1"></i>{{ __('pages.featured') }}
                                </span>
                            </div>
                        @endif
                    </div>
                    <h1 class="mb-4">{{ $product->title }}</h1>
                    <div class="event-meta row g-4 mb-4">
                        <div class="col-md-3">
                            <div class="meta-item d-flex align-items-center">
                                <i class="far fa-calendar text-primary me-2"></i>
                                <div>
                                    <div class="small text-muted">{{ __('pages.date') }}</div>
                                    <div>{{ $product->start_date->format('d M Y') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="meta-item d-flex align-items-center">
                                <i class="far fa-clock text-primary me-2"></i>
                                <div>
                                    <div class="small text-muted">{{ __('pages.time') }}</div>
                                    <div>{{ $product->start_date->format('H:i') }} - {{ $product->end_date->format('H:i') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="meta-item d-flex align-items-center">
                                <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                <div>
                                    <div class="small text-muted">{{ __('pages.location') }}</div>
                                    <div>{{ $product->location->name }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="meta-item d-flex align-items-center">
                                <i class="fas fa-users text-primary me-2"></i>
                                <div>
                                    <div class="small text-muted">{{ __('pages.attendees') }}</div>
                                    <div>{{ $product->attendees_count }} / {{ $product->capacity ?: '∞' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="event-description">
                        {!! $product->description !!}
                    </div>
                </div>

                <!-- الموقع -->
                <div class="event-location bg-white rounded-3 shadow-sm p-4 mb-4">
                    <h3 class="h4 mb-4">{{ __('pages.location_details') }}</h3>
                    <div id="eventMap" style="height: 400px;" class="rounded-3 mb-4"></div>
                    <div class="location-details">
                        <p class="mb-2">
                            <i class="fas fa-map-marker-alt text-primary me-2"></i>
                            {{ $product->location->address }}
                        </p>
                        <p class="mb-0">
                            <i class="fas fa-directions text-primary me-2"></i>
                            <a href="https://maps.google.com/?q={{ $product->latitude }},{{ $product->longitude }}" 
                               target="_blank"
                               class="text-decoration-none">
                                {{ __('pages.get_directions') }}
                            </a>
                        </p>
                    </div>
                </div>

                <!-- المنظم -->
                <div class="event-organizer bg-white rounded-3 shadow-sm p-4 mb-4">
                    <h3 class="h4 mb-4">{{ __('pages.organizer') }}</h3>
                    <div class="organizer-info d-flex align-items-center">
                        <img src="{{ $product->organizer->avatar }}" 
                             alt="{{ $product->organizer->name }}"
                             class="rounded-circle me-3"
                             width="60">
                        <div>
                            <h4 class="h5 mb-2">{{ $product->organizer->name }}</h4>
                            <p class="text-muted mb-2">{{ $product->organizer->bio }}</p>
                            <div class="organizer-contact">
                                <a href="mailto:{{ $product->organizer->email }}" class="btn btn-outline-primary btn-sm me-2">
                                    <i class="fas fa-envelope me-2"></i>{{ __('pages.contact') }}
                                </a>
                                <a href="{{ route('profile.show', ['locale' => app()->getLocale(), 'user' => $product->organizer->id]) }}" 
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-user me-2"></i>{{ __('pages.view_profile') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- المراجعات -->
                <div class="event-reviews bg-white rounded-3 shadow-sm p-4">
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
                <!-- تذاكر الحدث -->
                <div class="event-tickets bg-white rounded-3 shadow-sm p-4 mb-4">
                    <h3 class="h5 mb-4">{{ __('pages.tickets') }}</h3>
                    @if($product->is_free)
                        <div class="ticket-free text-center mb-4">
                            <div class="h3 text-success mb-3">{{ __('pages.free_event') }}</div>
                            <p class="text-muted mb-4">{{ __('pages.free_event_description') }}</p>
                            <button class="btn btn-success w-100" onclick="registerForEvent()">
                                {{ __('pages.register_now') }}
                            </button>
                        </div>
                    @else
                        @foreach($product->tickets as $ticket)
                        <div class="ticket-item mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="h6 mb-0">{{ $ticket->name }}</h4>
                                <span class="ticket-price h5 mb-0">{{ number_format($ticket->price) }}</span>
                            </div>
                            <p class="text-muted small mb-3">{{ $ticket->description }}</p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="ticket-availability small">
                                    <i class="fas fa-ticket-alt me-1"></i>
                                    {{ $ticket->available_count }} {{ __('pages.available') }}
                                </div>
                                <div class="ticket-quantity">
                                    <select class="form-select form-select-sm" style="width: 80px;">
                                        @for($i = 1; $i <= min(5, $ticket->available_count); $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <button class="btn btn-primary w-100" onclick="purchaseTicket({{ $ticket->id }})">
                                {{ __('pages.buy_ticket') }}
                            </button>
                        </div>
                        @endforeach
                    @endif
                </div>

                <!-- معلومات إضافية -->
                <div class="event-info bg-white rounded-3 shadow-sm p-4 mb-4">
                    <h3 class="h5 mb-4">{{ __('pages.additional_info') }}</h3>
                    <div class="info-item d-flex align-items-center mb-3">
                        <i class="fas fa-tag text-primary me-3"></i>
                        <div>
                            <div class="small text-muted">{{ __('pages.category') }}</div>
                            <div>{{ $product->category->name }}</div>
                        </div>
                    </div>
                    <div class="info-item d-flex align-items-center mb-3">
                        <i class="fas fa-language text-primary me-3"></i>
                        <div>
                            <div class="small text-muted">{{ __('pages.language') }}</div>
                            <div>{{ $product->language }}</div>
                        </div>
                    </div>
                    @if($product->age_restriction)
                    <div class="info-item d-flex align-items-center">
                        <i class="fas fa-user-clock text-primary me-3"></i>
                        <div>
                            <div class="small text-muted">{{ __('pages.age_restriction') }}</div>
                            <div>{{ $product->age_restriction }}+</div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- مشاركة الحدث -->
                <div class="event-share bg-white rounded-3 shadow-sm p-4">
                    <h3 class="h5 mb-4">{{ __('pages.share_event') }}</h3>
                    <div class="share-buttons d-flex gap-2">
                        <button class="btn btn-outline-primary flex-grow-1" onclick="shareOnFacebook()">
                            <i class="fab fa-facebook-f"></i>
                        </button>
                        <button class="btn btn-outline-info flex-grow-1" onclick="shareOnTwitter()">
                            <i class="fab fa-twitter"></i>
                        </button>
                        <button class="btn btn-outline-success flex-grow-1" onclick="shareOnWhatsApp()">
                            <i class="fab fa-whatsapp"></i>
                        </button>
                        <button class="btn btn-outline-secondary flex-grow-1" onclick="copyEventLink()">
                            <i class="fas fa-link"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.event-image {
    height: 400px;
    overflow: hidden;
}

.event-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.meta-item {
    padding: 1rem;
    background: var(--bs-light);
    border-radius: 0.5rem;
}

.meta-item i {
    font-size: 1.5rem;
}

.ticket-item {
    padding: 1rem;
    background: var(--bs-light);
    border-radius: 0.5rem;
}

/* RTL Support */
[dir="rtl"] .me-3 {
    margin-right: 0 !important;
    margin-left: 1rem !important;
}

[dir="rtl"] .me-2 {
    margin-right: 0 !important;
    margin-left: 0.5rem !important;
}

@media (max-width: 768px) {
    .event-image {
        height: 250px;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&callback=initMap" async defer></script>
<script>
function initMap() {
    const map = new google.maps.Map(document.getElementById('eventMap'), {
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

function showReviewForm() {
    // Implementation
}

function registerForEvent() {
    // Implementation
}

function purchaseTicket(ticketId) {
    // Implementation
}

function shareOnFacebook() {
    // Implementation
}

function shareOnTwitter() {
    // Implementation
}

function shareOnWhatsApp() {
    // Implementation
}

function copyEventLink() {
    // Implementation
}
</script>
@endpush
