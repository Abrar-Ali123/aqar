@extends('layouts.app')

@section('content')
<div class="events-page py-5">
    <div class="container">
        <!-- فلتر البحث -->
        <div class="search-filters bg-white rounded-3 shadow-sm p-4 mb-4">
            <form action="{{ route('products.index', ['locale' => app()->getLocale()]) }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <select name="category" class="form-select">
                            <option value="">{{ __('pages.category') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(request('category') == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="location" class="form-select">
                            <option value="">{{ __('pages.location') }}</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}" @selected(request('location') == $location->id)>
                                    {{ $location->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="date" class="form-select">
                            <option value="">{{ __('pages.date') }}</option>
                            <option value="today">{{ __('pages.today') }}</option>
                            <option value="tomorrow">{{ __('pages.tomorrow') }}</option>
                            <option value="this_week">{{ __('pages.this_week') }}</option>
                            <option value="this_month">{{ __('pages.this_month') }}</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i>{{ __('pages.search') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- نتائج البحث -->
        <div class="search-results">
            <div class="results-header d-flex justify-content-between align-items-center mb-4">
                <div class="results-count">
                    {{ $products->total() }} {{ __('pages.events_found') }}
                </div>
                <div class="view-options">
                    <button class="btn btn-outline-primary me-2" onclick="setViewMode('grid')">
                        <i class="fas fa-th-large"></i>
                    </button>
                    <button class="btn btn-outline-primary" onclick="setViewMode('calendar')">
                        <i class="fas fa-calendar-alt"></i>
                    </button>
                </div>
            </div>

            <div class="row g-4" id="eventsGrid">
                @foreach($products as $event)
                <div class="col-md-6 col-lg-4">
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
                            @if($event->is_featured)
                                <div class="event-featured position-absolute top-0 start-0 m-3">
                                    <span class="badge bg-warning">
                                        <i class="fas fa-star me-1"></i>{{ __('pages.featured') }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="event-info p-4">
                            <div class="event-meta d-flex justify-content-between text-muted small mb-2">
                                <span>
                                    <i class="fas fa-map-marker-alt me-1"></i>{{ $event->location->name }}
                                </span>
                                <span>
                                    <i class="fas fa-users me-1"></i>{{ $event->attendees_count }} {{ __('pages.attendees') }}
                                </span>
                            </div>
                            <h3 class="event-title h5 mb-3">{{ $event->title }}</h3>
                            <div class="event-details mb-3">
                                <div class="mb-2">
                                    <i class="far fa-clock text-primary me-2"></i>
                                    {{ $event->start_date->format('H:i') }} - {{ $event->end_date->format('H:i') }}
                                </div>
                                <div>
                                    <i class="fas fa-tag text-primary me-2"></i>
                                    {{ $event->category->name }}
                                </div>
                            </div>
                            <div class="event-organizer d-flex align-items-center">
                                <img src="{{ $event->organizer->avatar }}" 
                                     alt="{{ $event->organizer->name }}"
                                     class="rounded-circle me-2"
                                     width="30">
                                <span class="small">{{ $event->organizer->name }}</span>
                            </div>
                        </div>
                        <div class="event-footer border-top p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="event-price">
                                    @if($event->is_free)
                                        <span class="badge bg-success">{{ __('pages.free') }}</span>
                                    @else
                                        <span class="text-primary fw-bold">{{ number_format($event->price) }}</span>
                                    @endif
                                </div>
                                <a href="{{ route('products.show', ['locale' => app()->getLocale(), 'product' => $event->id]) }}" 
                                   class="btn btn-primary btn-sm">
                                    {{ __('pages.view_details') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="pagination-wrapper mt-5">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.event-card {
    background: white;
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
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

.date-box {
    min-width: 60px;
}

.search-filters {
    position: sticky;
    top: 1rem;
    z-index: 10;
}

/* Calendar View */
.calendar-view {
    display: none;
}

.calendar-view.active {
    display: block;
}

.calendar-day {
    min-height: 120px;
    border: 1px solid var(--bs-border-color);
}

.calendar-event {
    padding: 0.25rem 0.5rem;
    margin-bottom: 0.25rem;
    border-radius: 0.25rem;
    font-size: 0.875rem;
    background: var(--bs-primary-bg-subtle);
    color: var(--bs-primary);
}

/* RTL Support */
[dir="rtl"] .me-2 {
    margin-right: 0 !important;
    margin-left: 0.5rem !important;
}

[dir="rtl"] .ms-2 {
    margin-left: 0 !important;
    margin-right: 0.5rem !important;
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
function setViewMode(mode) {
    const container = document.getElementById('eventsGrid');
    const calendarView = document.querySelector('.calendar-view');
    
    if (mode === 'calendar') {
        container.style.display = 'none';
        calendarView.classList.add('active');
    } else {
        container.style.display = 'flex';
        calendarView.classList.remove('active');
    }
}
</script>
@endpush
