@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

<style>
.search-filters {
    position: sticky;
    top: 20px;
}

.property-card {
    transition: transform 0.3s ease;
}

.property-card:hover {
    transform: translateY(-5px);
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

[dir="rtl"] .start-0 {
    left: auto !important;
    right: 0 !important;
}

[dir="rtl"] .end-0 {
    right: auto !important;
    left: 0 !important;
}
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
// تهيئة الخريطة
let map = L.map('map').setView([24.7136, 46.6753], 13);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

let marker;

// حدث تغيير الموقع
map.on('click', function(e) {
    if (marker) {
        map.removeLayer(marker);
    }
    marker = L.marker(e.latlng).addTo(map);
    
    document.getElementById('lat').value = e.latlng.lat;
    document.getElementById('lng').value = e.latlng.lng;
});

// تحديث البحث عند تغيير الفلاتر
document.querySelectorAll('.filter-input').forEach(input => {
    input.addEventListener('change', function() {
        document.getElementById('searchForm').submit();
    });
});

// حذف فلتر
function removeFilter(key) {
    let form = document.getElementById('searchForm');
    let input = form.querySelector(`[name="${key}"]`);
    if (input) {
        input.value = '';
    }
    form.submit();
}

// البحث في الموقع
let searchTimeout;
document.getElementById('locationSearch').addEventListener('input', function(e) {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(function() {
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${e.target.value}`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    let location = data[0];
                    map.setView([location.lat, location.lon], 13);
                    if (marker) {
                        map.removeLayer(marker);
                    }
                    marker = L.marker([location.lat, location.lon]).addTo(map);
                    
                    document.getElementById('lat').value = location.lat;
                    document.getElementById('lng').value = location.lon;
                }
            });
    }, 500);
});
</script>
@endpush
