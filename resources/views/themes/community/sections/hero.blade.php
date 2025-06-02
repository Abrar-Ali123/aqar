<div class="hero-section py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 text-center text-lg-start mb-5 mb-lg-0">
                <h1 class="display-4 fw-bold mb-4">{{ __('pages.community_hero_title') }}</h1>
                <p class="lead mb-4">{{ __('pages.community_hero_subtitle') }}</p>
                <div class="d-flex gap-3 justify-content-center justify-content-lg-start">
                    <a href="{{ route('events.index', ['locale' => app()->getLocale()]) }}" 
                       class="btn btn-primary btn-lg">
                        <i class="fas fa-calendar-alt me-2"></i>
                        {{ __('pages.browse_events') }}
                    </a>
                    <a href="{{ route('services.index', ['locale' => app()->getLocale()]) }}" 
                       class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-hands-helping me-2"></i>
                        {{ __('pages.find_services') }}
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="upcoming-events">
                    <h3 class="h4 mb-4">{{ __('pages.upcoming_events') }}</h3>
                    @foreach($upcomingEvents as $event)
                    <div class="event-card mb-3">
                        <div class="row g-0">
                            <div class="col-4">
                                <img src="{{ $event->image_url }}" 
                                     alt="{{ $event->title }}"
                                     class="img-fluid rounded-start event-image">
                            </div>
                            <div class="col-8">
                                <div class="card-body">
                                    <div class="event-date text-primary mb-2">
                                        <i class="far fa-calendar me-2"></i>
                                        {{ $event->start_date->format('d M Y') }}
                                    </div>
                                    <h4 class="event-title h6 mb-2">{{ $event->title }}</h4>
                                    <div class="event-location small text-muted">
                                        <i class="fas fa-map-marker-alt me-2"></i>
                                        {{ $event->location }}
                                    </div>
                                    <div class="event-stats d-flex gap-3 mt-2 small">
                                        <span>
                                            <i class="fas fa-user me-1"></i>
                                            {{ $event->attendees_count }}
                                        </span>
                                        <span>
                                            <i class="fas fa-comment me-1"></i>
                                            {{ $event->comments_count }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('events.show', ['locale' => app()->getLocale(), 'event' => $event->id]) }}" 
                               class="stretched-link"></a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="community-stats py-5 mt-5 bg-light">
        <div class="container">
            <div class="row g-4 text-center">
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-icon mb-3">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-value h3 mb-2">{{ number_format($stats->members_count) }}</div>
                        <div class="stat-label">{{ __('pages.community_members') }}</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-icon mb-3">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="stat-value h3 mb-2">{{ number_format($stats->events_count) }}</div>
                        <div class="stat-label">{{ __('pages.events_organized') }}</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-icon mb-3">
                            <i class="fas fa-hands-helping"></i>
                        </div>
                        <div class="stat-value h3 mb-2">{{ number_format($stats->services_count) }}</div>
                        <div class="stat-label">{{ __('pages.services_offered') }}</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-icon mb-3">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="stat-value h3 mb-2">{{ number_format($stats->average_rating, 1) }}</div>
                        <div class="stat-label">{{ __('pages.satisfaction_rate') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.hero-section {
    background: linear-gradient(135deg, var(--bs-primary-bg-subtle) 0%, var(--bs-light) 100%);
}

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
    height: 100%;
    object-fit: cover;
}

.stat-icon {
    width: 60px;
    height: 60px;
    margin: 0 auto;
    background: var(--bs-primary-bg-subtle);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.stat-icon i {
    font-size: 1.5rem;
    color: var(--bs-primary);
}

/* RTL Support */
[dir="rtl"] .text-lg-start {
    text-align: right !important;
}

[dir="rtl"] .justify-content-lg-start {
    justify-content: flex-end !important;
}

[dir="rtl"] .rounded-start {
    border-radius: 0 0.375rem 0.375rem 0 !important;
}
</style>
@endpush
