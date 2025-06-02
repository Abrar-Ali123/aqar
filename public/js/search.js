// تهيئة المتغيرات
let map = null;
let marker = null;
let searchTimeout = null;

// تهيئة الخريطة
function initMap() {
    map = L.map('map').setView([24.7136, 46.6753], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

    // استعادة الموقع المحفوظ
    const lat = document.getElementById('lat').value;
    const lng = document.getElementById('lng').value;
    if (lat && lng) {
        setMapMarker([lat, lng]);
    }

    // حدث النقر على الخريطة
    map.on('click', function(e) {
        setMapMarker([e.latlng.lat, e.latlng.lng]);
    });
}

// تعيين علامة على الخريطة
function setMapMarker(latlng) {
    if (marker) {
        map.removeLayer(marker);
    }
    marker = L.marker(latlng).addTo(map);
    map.setView(latlng, 13);
    
    document.getElementById('lat').value = latlng[0];
    document.getElementById('lng').value = latlng[1];
}

// البحث في الموقع
function searchLocation(query) {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(function() {
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    const location = data[0];
                    setMapMarker([location.lat, location.lon]);
                }
            });
    }, 500);
}

// تحديث البحث
function updateSearch() {
    const form = document.getElementById('searchForm');
    const resultsContainer = document.querySelector('.search-results');
    
    resultsContainer.classList.add('search-loading');
    
    const formData = new FormData(form);
    const queryString = new URLSearchParams(formData).toString();
    const url = `${form.action}?${queryString}`;
    
    history.pushState(null, '', url);
    
    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        resultsContainer.innerHTML = html;
        resultsContainer.classList.remove('search-loading');
    })
    .catch(() => {
        resultsContainer.classList.remove('search-loading');
    });
}

// حذف فلتر
function removeFilter(key) {
    const form = document.getElementById('searchForm');
    const input = form.querySelector(`[name="${key}"]`);
    if (input) {
        if (input.type === 'checkbox') {
            input.checked = false;
        } else {
            input.value = '';
        }
    }
    updateSearch();
}

// إظهار/إخفاء الفلاتر في الشاشات الصغيرة
function toggleFilters() {
    const filters = document.querySelector('.search-filters');
    const backdrop = document.querySelector('.filters-backdrop');
    
    filters.classList.toggle('show');
    backdrop.classList.toggle('show');
}

// تهيئة الأحداث
document.addEventListener('DOMContentLoaded', function() {
    // تهيئة الخريطة
    initMap();
    
    // البحث في الموقع
    const locationSearch = document.getElementById('locationSearch');
    if (locationSearch) {
        locationSearch.addEventListener('input', (e) => searchLocation(e.target.value));
    }
    
    // تحديث البحث عند تغيير الفلاتر
    document.querySelectorAll('.filter-input').forEach(input => {
        input.addEventListener('change', updateSearch);
    });
    
    // زر إظهار/إخفاء الفلاتر
    const filterToggle = document.getElementById('filterToggle');
    if (filterToggle) {
        filterToggle.addEventListener('click', toggleFilters);
    }
    
    // إخفاء الفلاتر عند النقر على الخلفية
    const backdrop = document.querySelector('.filters-backdrop');
    if (backdrop) {
        backdrop.addEventListener('click', toggleFilters);
    }
    
    // تحديث عرض الخريطة عند تغيير حجم النافذة
    window.addEventListener('resize', () => {
        if (map) {
            map.invalidateSize();
        }
    });
});
