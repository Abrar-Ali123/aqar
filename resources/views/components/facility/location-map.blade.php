@props(['data', 'facility'])

<div class="location-map">
    @if(isset($data['title']))
        <h2 class="text-2xl font-bold mb-4">{{ $data['title'] }}</h2>
    @endif

    @if($facility->latitude && $facility->longitude)
        <div class="relative aspect-video rounded-lg overflow-hidden shadow-lg">
            <div id="map-{{ $facility->id }}" class="absolute inset-0"></div>
        </div>

        <div class="mt-4 flex justify-between items-center">
            <div class="text-gray-600">
                <i class="fas fa-map-marker-alt mr-2"></i>
                {{ $facility->address }}
            </div>
            <a href="https://www.google.com/maps/dir/?api=1&destination={{ $facility->latitude }},{{ $facility->longitude }}" 
               target="_blank"
               class="btn btn-primary btn-sm">
                <i class="fas fa-directions mr-2"></i>
                احصل على الاتجاهات
            </a>
        </div>

        @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const map = new google.maps.Map(document.getElementById('map-{{ $facility->id }}'), {
                    center: { 
                        lat: {{ $facility->latitude }}, 
                        lng: {{ $facility->longitude }} 
                    },
                    zoom: 15,
                    styles: [
                        {
                            "featureType": "poi",
                            "elementType": "labels",
                            "stylers": [{ "visibility": "off" }]
                        }
                    ]
                });

                const marker = new google.maps.Marker({
                    position: { 
                        lat: {{ $facility->latitude }}, 
                        lng: {{ $facility->longitude }} 
                    },
                    map: map,
                    title: '{{ $facility->name }}',
                    animation: google.maps.Animation.DROP
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
            });
        </script>
        @endpush

        @push('head')
        <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&libraries=places"></script>
        @endpush
    @else
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-lg">
            عذراً، موقع المنشأة غير متوفر حالياً.
        </div>
    @endif
</div>
