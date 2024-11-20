@extends('components.layouts.app2')

@section('content')
<div class="container">
    <h1>إضافة مشروع جديد</h1>

    <form action="{{ route('projects.store', ['facility' => $facility->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="header">بيانات المشروع الأساسية</div>
        <div class="content">

            <!-- اختيار نوع المشروع -->
            <label for="project_type">النوع:</label>
            <select class="form-control" id="project_type" name="project_type" required>
                <option value="apartment_complex">مجمع سكني</option>
                <option value="villa_group">مجموعة فلل</option>
            </select>

            <!-- الحقول للإحداثيات وعنوان الخريطة -->
            <label for="latitude">الإحداثيات (خط العرض)</label>
            <input type="text" class="form-control" id="latitude" name="latitude" placeholder="أدخل خط العرض" value="{{ old('latitude') }}" required>

            <label for="longitude">الإحداثيات (خط الطول)</label>
            <input type="text" class="form-control" id="longitude" name="longitude" placeholder="أدخل خط الطول" value="{{ old('longitude') }}" required>

            <label for="google_maps_url">رابط Google Maps</label>
            <input type="text" class="form-control" id="google_maps_url" name="google_maps_url" placeholder="أدخل رابط Google Maps" value="{{ old('google_maps_url') }}">

            <!-- صورة المشروع -->
            <label for="image">صورة المشروع</label>
            <input type="file" class="form-control" id="image" name="image">
            <img id="image-preview" style="display:none; width: 100px; height: 100px;" />

            <!-- خريطة Google التفاعلية -->
            <label>🔍 ابحث عن العنوان على الخريطة</label>
            <input type="text" id="autocomplete" class="form-control mb-3" placeholder="ابحث عن العنوان">
            <div id="map" style="height: 400px;"></div>

            @foreach (config('app.locales') as $locale)
    <div class="mb-6">
        <label for="name_{{ $locale }}" class="block text-sm font-medium text-gray-700">اسم المشروع:</label>
        <input type="text" class="form-control mt-1 block w-full p-2 border border-gray-300 rounded-md" id="name_{{ $locale }}" name="translations[{{ $locale }}][name]" required>
    </div>

    <div class="mb-6">
        <label for="description_{{ $locale }}" class="block text-sm font-medium text-gray-700">الوصف:</label>

        <!-- Rich Text Editor Container -->
        <div class="form-group">
            <!-- Rich Text Tools -->
            <div id="editor-toolbar" class="editor-toolbar mb-2 flex space-x-2">
                <button type="button" onclick="execCmd('bold', '{{ $locale }}')" class="px-2 py-1 border rounded"><i class="fas fa-bold"></i></button>
                <button type="button" onclick="execCmd('italic', '{{ $locale }}')" class="px-2 py-1 border rounded"><i class="fas fa-italic"></i></button>
                <button type="button" onclick="execCmd('underline', '{{ $locale }}')" class="px-2 py-1 border rounded"><i class="fas fa-underline"></i></button>
                <button type="button" onclick="execCmd('justifyLeft', '{{ $locale }}')" class="px-2 py-1 border rounded"><i class="fas fa-align-left"></i></button>
                <button type="button" onclick="execCmd('justifyCenter', '{{ $locale }}')" class="px-2 py-1 border rounded"><i class="fas fa-align-center"></i></button>
                <button type="button" onclick="execCmd('justifyRight', '{{ $locale }}')" class="px-2 py-1 border rounded"><i class="fas fa-align-right"></i></button>
                <button type="button" onclick="execCmd('justifyFull', '{{ $locale }}')" class="px-2 py-1 border rounded"><i class="fas fa-align-justify"></i></button>
                <button type="button" onclick="execCmd('insertUnorderedList', '{{ $locale }}')" class="px-2 py-1 border rounded"><i class="fas fa-list-ul"></i></button>
                <button type="button" onclick="execCmd('insertOrderedList', '{{ $locale }}')" class="px-2 py-1 border rounded"><i class="fas fa-list-ol"></i></button>
                <button type="button" onclick="execCmd('cut', '{{ $locale }}')" class="px-2 py-1 border rounded"><i class="fas fa-cut"></i></button>
                <button type="button" onclick="execCmd('copy', '{{ $locale }}')" class="px-2 py-1 border rounded"><i class="fas fa-copy"></i></button>
                <button type="button" onclick="execCmd('paste', '{{ $locale }}')" class="px-2 py-1 border rounded"><i class="fas fa-paste"></i></button>
                <button type="button" onclick="execCmd('undo', '{{ $locale }}')" class="px-2 py-1 border rounded"><i class="fas fa-undo-alt"></i></button>
                <button type="button" onclick="execCmd('redo', '{{ $locale }}')" class="px-2 py-1 border rounded"><i class="fas fa-redo-alt"></i></button>
            </div>

            <!-- Content Editable Div -->
            <div class="rich-text-editor form-control mt-1 block w-full p-2 border border-gray-300 rounded-md" contenteditable="true" id="description_{{ $locale }}" style="min-height: 100px;" placeholder="{{ __('facility.enter_facility_description', ['locale' => strtoupper($locale)]) }}"></div>

            <!-- Hidden Input Field to Store Data -->
            <input type="hidden" id="hidden_description_{{ $locale }}" name="translations[{{ $locale }}][description]">
        </div>
    </div>

    <!-- JavaScript to handle the Rich Text Editor Commands and Save Action -->
    <script>
        // Function to execute command from the tools
        function execCmd(command, locale) {
            document.execCommand(command, false, null);
            // Update hidden input with contenteditable div's content after change
            document.getElementById('hidden_description_' + locale).value = document.getElementById('description_' + locale).innerHTML;
        }

        // Function to save content into the hidden input field
        function saveContent(locale) {
            var content = document.getElementById('description_' + locale).innerHTML;
            document.getElementById('hidden_description_' + locale).value = content;
        }

        // Event listener to update hidden input whenever the content changes
        document.getElementById('description_{{ $locale }}').addEventListener('input', function() {
            document.getElementById('hidden_description_{{ $locale }}').value = this.innerHTML;
        });
    </script>

@endforeach

<!-- Rich Text Editor Style -->
<style>
    .rich-text-editor {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }
</style>


            <button type="submit" class="btn btn-primary mt-4">إضافة المشروع</button>
        </div>
    </form>
</div>

<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=initAutocomplete" async defer></script>
<script>
    // إعداد خريطة Google ومربعات البحث
    let autocomplete, map, marker;

    function initAutocomplete() {
        // إنشاء الخريطة
        map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: -34.397, lng: 150.644},
            zoom: 13
        });

        // إعداد البحث التلقائي
        autocomplete = new google.maps.places.Autocomplete(document.getElementById('autocomplete'), {types: ['geocode']});
        autocomplete.bindTo('bounds', map);

        // إعداد الماركر
        marker = new google.maps.Marker({map: map, draggable: true, position: map.getCenter()});
        marker.addListener('dragend', (event) => updateLocation(event.latLng.lat(), event.latLng.lng()));

        map.addListener('click', (event) => {
            marker.setPosition(event.latLng);
            updateLocation(event.latLng.lat(), event.latLng.lng());
        });

        // عند تغيير الموقع باستخدام البحث
        autocomplete.addListener('place_changed', () => {
            const place = autocomplete.getPlace();
            if (!place.geometry) {
                alert("لم يتم العثور على تفاصيل للعنوان المحدد.");
                return;
            }

            map.setCenter(place.geometry.location);
            map.setZoom(17);
            marker.setPosition(place.geometry.location);
            updateLocation(place.geometry.location.lat(), place.geometry.location.lng());
        });
    }

    // تحديث الإحداثيات والرابط عند تغيير الموقع
    function updateLocation(lat, lng) {
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
        document.getElementById('google_maps_url').value = `https://www.google.com/maps/search/?api=1&query=${lat},${lng}`;
    }

    // معاينة الصورة
    document.getElementById('image').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                const img = document.getElementById('image-preview');
                img.src = e.target.result;
                img.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
