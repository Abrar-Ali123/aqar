<section class="featured-section py-5 bg-light">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="section-title">{{ __('pages.featured_activities') }}</h2>
            <p class="section-subtitle">{{ __('pages.featured_activities_subtitle') }}</p>
        </div>

        <div class="featured-events mb-5">
            <h3 class="h4 mb-4">{{ __('pages.upcoming_events') }}</h3>
            <div class="row g-4">
                @foreach($featuredEvents as $event)
                <div class="col-md-6 col-lg-4">
                    <div class="event-card h-100">
                        <div class="event-image">
                            <img src="{{ $event->image_url }}" 
                                 alt="{{ $event->title }}"
                                 class="img-fluid">
                            @if($event->is_featured)
                                <div class="event-badge">
                                    <i class="fas fa-star"></i>
                                    {{ __('pages.featured') }}
                                </div>
                            @endif
                        </div>
                        <div class="event-info p-4">
                            <div class="event-meta d-flex justify-content-between text-muted small mb-2">
                                <span>
                                    <i class="far fa-calendar me-1"></i>
                                    {{ $event->start_date->format('d M Y') }}
                                </span>
                                <span>
                                    <i class="far fa-clock me-1"></i>
                                    {{ $event->start_time }}
                                </span>
                            </div>
                            <h3 class="event-title h5 mb-3">{{ $event->title }}</h3>
                            <p class="event-description text-muted small mb-3">
                                {{ Str::limit($event->description, 100) }}
                            </p>
                            <div class="event-footer d-flex justify-content-between align-items-center">
                                <div class="event-organizer d-flex align-items-center">
                                    <img src="{{ $event->organizer->avatar_url }}" 
                                         alt="{{ $event->organizer->name }}"
                                         class="rounded-circle me-2"
                                         width="30">
                                    <span class="small">{{ $event->organizer->name }}</span>
                                </div>
                                <div class="event-attendees small">
                                    <i class="fas fa-users me-1"></i>
                                    {{ $event->attendees_count }}
                                </div>
                            </div>
                            <a href="{{ route('events.show', ['locale' => app()->getLocale(), 'event' => $event->id]) }}" 
                               class="stretched-link"></a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="featured-services mt-5">
            <h3 class="h4 mb-4">{{ __('pages.popular_services') }}</h3>
            <div class="row g-4">
                @foreach($popularServices as $service)
                <div class="col-md-6">
                    <div class="service-card">
                        <div class="row g-0">
                            <div class="col-4">
                                <img src="{{ $service->image_url }}" 
                                     alt="{{ $service->title }}"
                                     class="img-fluid rounded-start service-image">
                            </div>
                            <div class="col-8">
                                <div class="service-info p-4">
                                    <div class="service-category small text-primary mb-2">
                                        <i class="fas fa-tag me-1"></i>
                                        {{ $service->category->name }}
                                    </div>
                                    <h4 class="service-title h5 mb-2">{{ $service->title }}</h4>
                                    <div class="service-rating mb-2">
                                        <div class="stars">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $service->rating ? 'text-warning' : 'text-muted' }}"></i>
                                            @endfor
                                            <span class="ms-1 small text-muted">({{ $service->reviews_count }})</span>
                                        </div>
                                    </div>
                                    <div class="service-provider d-flex align-items-center mb-3">
                                        <img src="{{ $service->provider->avatar_url }}" 
                                             alt="{{ $service->provider->name }}"
                                             class="rounded-circle me-2"
                                             width="30">
                                        <span class="small">{{ $service->provider->name }}</span>
                                    </div>
                                    <div class="service-stats d-flex justify-content-between small text-muted">
                                        <span>
                                            <i class="fas fa-user-check me-1"></i>
                                            {{ $service->completed_orders }} {{ __('pages.completed') }}
                                        </span>
                                        <span>
                                            <i class="fas fa-clock me-1"></i>
                                            {{ $service->response_time }} {{ __('pages.response') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('services.show', ['locale' => app()->getLocale(), 'service' => $service->id]) }}" 
                               class="stretched-link"></a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
.event-card {
    background: white;
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
    position: relative;
}

.event-card:hover {
    transform: translateY(-5px);
}

.event-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.event-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.event-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: rgba(255, 255, 255, 0.9);
    padding: 0.5rem 1rem;
    border-radius: 2rem;
    font-size: 0.875rem;
    color: var(--bs-warning);
}

.service-card {
    background: white;
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
    position: relative;
}

.service-card:hover {
    transform: translateY(-5px);
}

.service-image {
    height: 100%;
    object-fit: cover;
}

.stars {
    display: inline-flex;
    gap: 0.25rem;
}

/* RTL Support */
[dir="rtl"] .event-badge {
    right: auto;
    left: 1rem;
}

[dir="rtl"] .rounded-start {
    border-radius: 0 0.375rem 0.375rem 0 !important;
}

/* Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.event-card,
.service-card {
    animation: fadeInUp 0.5s ease backwards;
}
</style>
@endpush
