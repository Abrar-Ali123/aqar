<div class="hero-section position-relative py-5 bg-gradient-primary">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 text-center text-lg-start text-white">
                <h1 class="display-4 fw-bold mb-4">{{ __('pages.real_estate_hero_title') }}</h1>
                <p class="lead mb-4">{{ __('pages.real_estate_hero_subtitle') }}</p>
                <form action="{{ route('products.index', ['locale' => app()->getLocale()]) }}" method="GET" 
                    class="search-form bg-white p-3 rounded-3 shadow mb-4">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <select name="category" class="form-select">
                                <option value="">{{ __('pages.property_type') }}</option>
                                @foreach($mainCategories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select name="location" class="form-select">
                                <option value="">{{ __('pages.location') }}</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary w-100" type="submit">
                                <i class="fas fa-search me-2"></i>{{ __('pages.search') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-6 d-none d-lg-block">
                <div class="hero-map rounded-3 shadow overflow-hidden">
                    <div id="propertyMap" style="height: 400px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&callback=initMap" async defer></script>
<script>
function initMap() {
    const map = new google.maps.Map(document.getElementById('propertyMap'), {
        zoom: 12,
        center: { lat: 24.7136, lng: 46.6753 }, // الرياض كمثال
    });
    
    // إضافة العقارات على الخريطة
    @foreach($featuredProperties as $property)
        new google.maps.Marker({
            position: { 
                lat: {{ $property->latitude }}, 
                lng: {{ $property->longitude }} 
            },
            map: map,
            title: "{{ $property->title }}"
        });
    @endforeach
}
</script>
@endpush
