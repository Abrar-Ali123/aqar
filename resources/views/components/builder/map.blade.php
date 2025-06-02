<div class="map-component" x-data="{
    lat: @entangle('content.lat'),
    lng: @entangle('content.lng'),
    zoom: @entangle('content.zoom'),
    markerTitle: @entangle('content.markerTitle'),
    editing: false,
    map: null,
    marker: null,

    initMap() {
        // تهيئة الخريطة
        this.map = L.map($refs.mapContainer).setView([this.lat || 24.7136, this.lng || 46.6753], this.zoom || 13);
        
        // إضافة طبقة الخريطة
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(this.map);

        // إضافة العلامة إذا كانت الإحداثيات موجودة
        if (this.lat && this.lng) {
            this.marker = L.marker([this.lat, this.lng], {
                draggable: this.editing
            }).addTo(this.map);

            if (this.markerTitle) {
                this.marker.bindPopup(this.markerTitle).openPopup();
            }
        }

        // تحديث الإحداثيات عند النقر على الخريطة في وضع التحرير
        this.map.on('click', (e) => {
            if (!this.editing) return;
            
            this.lat = e.latlng.lat;
            this.lng = e.latlng.lng;
            
            if (this.marker) {
                this.marker.setLatLng(e.latlng);
            } else {
                this.marker = L.marker(e.latlng, {
                    draggable: true
                }).addTo(this.map);
            }

            // تحديث البيانات
            this.updateMapData();
        });
    },

    updateMapData() {
        $wire.emit('componentSettingsUpdated', '{{ $componentId }}', {
            content: {
                lat: this.lat,
                lng: this.lng,
                zoom: this.map.getZoom(),
                markerTitle: this.markerTitle
            }
        });
    },

    toggleEditing() {
        this.editing = !this.editing;
        if (this.marker) {
            this.marker.setDraggable(this.editing);
        }
    }
}" x-init="initMap"
    class="relative">
    
    <!-- الخريطة -->
    <div x-ref="mapContainer" 
         class="w-full rounded-lg overflow-hidden"
         style="height: 400px;">
    </div>

    <!-- شريط التحكم -->
    <div class="absolute top-2 right-2 flex gap-2">
        <!-- زر التحرير -->
        <button 
            class="p-2 bg-white rounded-lg shadow-md hover:bg-gray-100"
            x-on:click="toggleEditing">
            <i class="fas" x-bind:class="editing ? 'fa-check text-green-500' : 'fa-edit text-blue-500'"></i>
        </button>

        <!-- زر البحث -->
        <button 
            x-show="editing"
            class="p-2 bg-white rounded-lg shadow-md hover:bg-gray-100"
            x-on:click="$dispatch('open-map-search')">
            <i class="fas fa-search text-blue-500"></i>
        </button>

        <!-- زر تحديد الموقع الحالي -->
        <button 
            x-show="editing"
            class="p-2 bg-white rounded-lg shadow-md hover:bg-gray-100"
            x-on:click="
                navigator.geolocation.getCurrentPosition(position => {
                    lat = position.coords.latitude;
                    lng = position.coords.longitude;
                    map.setView([lat, lng], 15);
                    
                    if (marker) {
                        marker.setLatLng([lat, lng]);
                    } else {
                        marker = L.marker([lat, lng], {
                            draggable: true
                        }).addTo(map);
                    }
                    
                    updateMapData();
                })">
            <i class="fas fa-location-arrow text-blue-500"></i>
        </button>
    </div>

    <!-- نموذج البحث -->
    <div x-show="editing" 
         x-on:open-map-search.window="$refs.searchInput.focus()"
         class="absolute top-12 right-2 w-64">
        <div class="bg-white rounded-lg shadow-md p-2">
            <input 
                x-ref="searchInput"
                type="text" 
                class="w-full px-2 py-1 border rounded"
                placeholder="ابحث عن موقع..."
                x-on:keyup.enter="
                    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${$event.target.value}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.length > 0) {
                                lat = parseFloat(data[0].lat);
                                lng = parseFloat(data[0].lon);
                                map.setView([lat, lng], 15);
                                
                                if (marker) {
                                    marker.setLatLng([lat, lng]);
                                } else {
                                    marker = L.marker([lat, lng], {
                                        draggable: true
                                    }).addTo(map);
                                }
                                
                                updateMapData();
                            }
                        })">
        </div>
    </div>

    <!-- نموذج تحرير عنوان العلامة -->
    <div x-show="editing && marker" 
         class="absolute bottom-2 left-2 right-2">
        <div class="bg-white rounded-lg shadow-md p-2">
            <input 
                type="text" 
                x-model="markerTitle"
                class="w-full px-2 py-1 border rounded"
                placeholder="عنوان العلامة..."
                x-on:change="
                    if (marker) {
                        marker.bindPopup(markerTitle).openPopup();
                    }
                    updateMapData();">
        </div>
    </div>
</div>
