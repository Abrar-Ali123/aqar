@props(['settings', 'content', 'facility'])

<div class="map-component">
    <div id="facility-map" style="height: {{ $settings['height'] ?? 400 }}px;" class="rounded-lg"></div>
</div>

@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}"></script>
<script>
    function initMap() {
        const location = {
            lat: {{ $settings['location']['lat'] ?? $facility->latitude ?? 24.7136 }},
            lng: {{ $settings['location']['lng'] ?? $facility->longitude ?? 46.6753 }}
        };

        const map = new google.maps.Map(document.getElementById('facility-map'), {
            center: location,
            zoom: {{ $settings['zoom'] ?? 15 }},
            styles: [
                {
                    "featureType": "poi",
                    "elementType": "labels",
                    "stylers": [
                        { "visibility": "off" }
                    ]
                }
            ]
        });

        const marker = new google.maps.Marker({
            position: location,
            map: map,
            title: '{{ $facility->name }}'
        });

        const infoWindow = new google.maps.InfoWindow({
            content: `
                <div class="p-2">
                    <h3 class="font-bold">{{ $facility->name }}</h3>
                    <p class="text-gray-600">{{ $facility->address }}</p>
                </div>
            `
        });

        marker.addListener('click', () => {
            infoWindow.open(map, marker);
        });
    }

    // تأكد من تحميل الخريطة بعد تحميل الصفحة
    if (document.readyState === 'complete') {
        initMap();
    } else {
        window.addEventListener('load', initMap);
    }
</script>
@endpush
