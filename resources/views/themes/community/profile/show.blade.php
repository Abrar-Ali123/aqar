@extends('layouts.app')

@section('content')
<div class="member-profile py-5">
    <div class="container">
        <!-- معلومات العضو الرئيسية -->
        <div class="profile-header bg-white rounded-3 shadow-sm p-4 mb-4">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center">
                        <img src="{{ $member->avatar }}" 
                             alt="{{ $member->name }}"
                             class="rounded-circle me-4"
                             width="120">
                        <div>
                            <h1 class="h3 mb-2">{{ $member->name }}</h1>
                            <p class="text-muted mb-3">{{ __('pages.member_since') }}: {{ $member->created_at->format('M Y') }}</p>
                            <div class="member-badges mb-3">
                                @if($member->is_verified)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>{{ __('pages.verified_member') }}
                                    </span>
                                @endif
                                @if($member->role === 'organizer')
                                    <span class="badge bg-primary">
                                        <i class="fas fa-star me-1"></i>{{ __('pages.event_organizer') }}
                                    </span>
                                @endif
                                @if($member->role === 'volunteer')
                                    <span class="badge bg-info">
                                        <i class="fas fa-hands-helping me-1"></i>{{ __('pages.volunteer') }}
                                    </span>
                                @endif
                            </div>
                            <div class="member-actions">
                                <button class="btn btn-primary me-2" onclick="followMember({{ $member->id }})">
                                    <i class="fas fa-user-plus me-2"></i>{{ __('pages.follow') }}
                                </button>
                                <button class="btn btn-outline-primary" onclick="contactMember()">
                                    <i class="fas fa-envelope me-2"></i>{{ __('pages.contact') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mt-4 mt-lg-0">
                    <div class="row text-center g-3">
                        <div class="col-4">
                            <div class="stat-item">
                                <div class="h3 mb-1">{{ $member->events_count }}</div>
                                <div class="text-muted">{{ __('pages.events') }}</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-item">
                                <div class="h3 mb-1">{{ $member->followers_count }}</div>
                                <div class="text-muted">{{ __('pages.followers') }}</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-item">
                                <div class="h3 mb-1">{{ $member->following_count }}</div>
                                <div class="text-muted">{{ __('pages.following') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- الأحداث القادمة -->
                <div class="upcoming-events bg-white rounded-3 shadow-sm p-4 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="h4 mb-0">{{ __('pages.upcoming_events') }}</h2>
                        <div class="events-filter">
                            <select class="form-select" onchange="filterEvents(this.value)">
                                <option value="all">{{ __('pages.all_events') }}</option>
                                <option value="organizing">{{ __('pages.organizing') }}</option>
                                <option value="attending">{{ __('pages.attending') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-4">
                        @foreach($member->upcomingEvents as $event)
                        <div class="col-md-6">
                            <div class="event-card">
                                <div class="event-image position-relative">
                                    @if($event->images->count() > 0)
                                        <img src="{{ $event->images->first()->url }}" 
                                             alt="{{ $event->title }}"
                                             class="img-fluid w-100">
                                    @endif
                                    <div class="event-date position-absolute top-0 end-0 m-3 text-center">
                                        <div class="date-box bg-white rounded-3 shadow-sm p-2">
                                            <div class="month text-primary small">{{ $event->start_date->format('M') }}</div>
                                            <div class="day h4 mb-0">{{ $event->start_date->format('d') }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="event-info p-4">
                                    <div class="event-meta d-flex justify-content-between text-muted small mb-2">
                                        <span>
                                            <i class="fas fa-map-marker-alt me-1"></i>{{ $event->location->name }}
                                        </span>
                                        <span>
                                            <i class="fas fa-users me-1"></i>{{ $event->attendees_count }}
                                        </span>
                                    </div>
                                    <h3 class="event-title h5 mb-3">{{ $event->title }}</h3>
                                    <div class="event-details mb-3">
                                        <div class="mb-2">
                                            <i class="far fa-clock text-primary me-2"></i>
                                            {{ $event->start_date->format('H:i') }}
                                        </div>
                                        <div>
                                            <i class="fas fa-tag text-primary me-2"></i>
                                            {{ $event->category->name }}
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ route('products.show', ['locale' => app()->getLocale(), 'product' => $event->id]) }}" 
                                   class="stretched-link"></a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- الأنشطة -->
                <div class="member-activities bg-white rounded-3 shadow-sm p-4 mb-4">
                    <h2 class="h4 mb-4">{{ __('pages.activities') }}</h2>
                    
                    @foreach($member->activities as $activity)
                    <div class="activity-item border-bottom pb-4 mb-4">
                        <div class="d-flex">
                            <div class="activity-icon me-3">
                                @switch($activity->type)
                                    @case('event_created')
                                        <div class="icon-circle bg-primary text-white">
                                            <i class="fas fa-calendar-plus"></i>
                                        </div>
                                        @break
                                    @case('event_attended')
                                        <div class="icon-circle bg-success text-white">
                                            <i class="fas fa-calendar-check"></i>
                                        </div>
                                        @break
                                    @case('review_posted')
                                        <div class="icon-circle bg-info text-white">
                                            <i class="fas fa-star"></i>
                                        </div>
                                        @break
                                    @default
                                        <div class="icon-circle bg-secondary text-white">
                                            <i class="fas fa-circle"></i>
                                        </div>
                                @endswitch
                            </div>
                            <div class="activity-content">
                                <div class="activity-text mb-1">
                                    {!! $activity->description !!}
                                </div>
                                <div class="activity-date text-muted small">
                                    {{ $activity->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- المراجعات -->
                <div class="member-reviews bg-white rounded-3 shadow-sm p-4">
                    <h2 class="h4 mb-4">{{ __('pages.reviews') }}</h2>
                    
                    @foreach($member->reviews as $review)
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
                <!-- نموذج الاتصال -->
                <div class="contact-card bg-white rounded-3 shadow-sm p-4 mb-4">
                    <h3 class="h5 mb-4">{{ __('pages.contact_member') }}</h3>
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
                            <textarea class="form-control" 
                                      rows="4"
                                      placeholder="{{ __('pages.your_message') }}"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            {{ __('pages.send_message') }}
                        </button>
                    </form>
                </div>

                <!-- المهارات والاهتمامات -->
                <div class="member-interests bg-white rounded-3 shadow-sm p-4 mb-4">
                    <h3 class="h5 mb-4">{{ __('pages.interests') }}</h3>
                    <div class="interests-list">
                        @foreach($member->interests as $interest)
                        <span class="badge bg-light text-dark me-2 mb-2">{{ $interest->name }}</span>
                        @endforeach
                    </div>
                </div>

                <!-- الشارات والإنجازات -->
                <div class="member-badges bg-white rounded-3 shadow-sm p-4">
                    <h3 class="h5 mb-4">{{ __('pages.achievements') }}</h3>
                    <div class="row g-3">
                        @foreach($member->achievements as $achievement)
                        <div class="col-4">
                            <div class="achievement-item text-center">
                                <div class="achievement-icon mb-2">
                                    <img src="{{ $achievement->icon }}" 
                                         alt="{{ $achievement->name }}"
                                         class="img-fluid"
                                         width="40">
                                </div>
                                <div class="achievement-name small">{{ $achievement->name }}</div>
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
    height: 200px;
    overflow: hidden;
}

.event-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.icon-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.achievement-item {
    padding: 1rem;
    background: var(--bs-light);
    border-radius: 0.5rem;
}

/* RTL Support */
[dir="rtl"] .me-4 {
    margin-right: 0 !important;
    margin-left: 1.5rem !important;
}

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
        height: 160px;
    }
}
</style>
@endpush

@push('scripts')
<script>
function followMember(memberId) {
    // Implementation
}

function contactMember() {
    // Implementation
}

function filterEvents(type) {
    // Implementation
}
</script>
@endpush
