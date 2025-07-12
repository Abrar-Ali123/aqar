@props(['events'])

@php
    use Carbon\Carbon;
    use Illuminate\Support\Str;

    // Helper function to get translation data
    function getTranslation($event) {
        if (is_object($event) && method_exists($event, 'translate')) {
            return $event->translate();
        }
        
        // If it's an array or object with translations
        if (isset($event->translations) || isset($event['translations'])) {
            $translations = is_array($event) ? $event['translations'] : $event->translations;
            $locale = app()->getLocale();
            
            // Find translation for current locale
            $translation = collect($translations)->first(function($trans) use ($locale) {
                return (is_array($trans) ? $trans['locale'] : $trans->locale) === $locale;
            });
            
            return $translation ?: (is_array($translations) ? reset($translations) : $translations->first());
        }
        
        // Fallback to the event object itself
        return $event;
    }

    // Helper function to format date
    function formatDate($date) {
        if ($date instanceof Carbon) {
            return $date->format('d M Y');
        }
        return Carbon::parse($date)->format('d M Y');
    }
@endphp

<div class="events-list">
    @forelse($events as $event)
        @php
            $translation = getTranslation($event);
            $title = is_array($translation) ? $translation['title'] : $translation->title;
            $description = is_array($translation) ? ($translation['description'] ?? null) : ($translation->description ?? null);
            $image = is_array($event) ? ($event['image'] ?? null) : ($event->image ?? null);
            $location = is_array($event) ? ($event['location'] ?? null) : ($event->location ?? null);
            $startDate = is_array($event) ? ($event['start_date'] ?? null) : ($event->start_date ?? null);
            $endDate = is_array($event) ? ($event['end_date'] ?? null) : ($event->end_date ?? null);
            $time = is_array($event) ? ($event['time'] ?? null) : ($event->time ?? null);
        @endphp

        <div class="event-card mb-4 bg-white rounded shadow-sm overflow-hidden">
            <div class="row g-0">
                @if($image)
                    <div class="col-md-4">
                        <img src="{{ asset($image) }}" 
                             alt="{{ $title }}"
                             class="img-fluid h-100 object-fit-cover">
                    </div>
                @endif
                
                <div class="col-md-{{ $image ? '8' : '12' }}">
                    <div class="p-4">
                        <h5 class="event-title mb-2">{{ $title }}</h5>
                        
                        <div class="event-meta text-muted mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="far fa-calendar me-2"></i>
                                <span>{{ formatDate($startDate) }}</span>
                                
                                @if($endDate)
                                    <span class="mx-2">-</span>
                                    <span>{{ formatDate($endDate) }}</span>
                                @endif
                            </div>
                            
                            @if($time)
                                <div class="d-flex align-items-center">
                                    <i class="far fa-clock me-2"></i>
                                    <span>{{ $time }}</span>
                                </div>
                            @endif
                        </div>
                        
                        @if($description)
                            <p class="event-description mb-3">
                                {{ Str::limit($description, 150) }}
                            </p>
                        @endif
                        
                        @if($location)
                            <div class="event-location d-flex align-items-center text-muted">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                <span>{{ $location }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center text-muted py-4">
            {{ __('No upcoming events at the moment') }}
        </div>
    @endforelse
</div>

<style>
    .event-card {
        transition: transform 0.2s ease-in-out;
    }
    
    .event-card:hover {
        transform: translateY(-5px);
    }
    
    .event-meta i {
        width: 16px;
        text-align: center;
    }
    
    .object-fit-cover {
        object-fit: cover;
    }
</style>
