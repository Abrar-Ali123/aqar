@extends('components.layouts.app1')
@section('content')
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="header">بيانات المنتج الأساسية</div>
        <div class="content">


            <label for="is_active">الحالة:</label>
            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1">

            <label for="price">السعر:</label>
            <input type="number" class="form-control" id="price" name="price" required>

            </select>
        </div>


        <label for="category_id">التصنيف:</label>
        <select class="form-control" id="category_id" name="category_id" onchange="filterAttributes()">
            <option value="">اختر التصنيف</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>

        @php
            $fieldTypes = [
                'text' => 'text',
                'password' => 'password',
                'email' => 'email',
                'number' => 'number',
                'date' => 'date',
                'datetime-local' => 'datetime-local',
                'month' => 'month',
                'time' => 'time',
                'week' => 'week',
                'url' => 'url',
                'search' => 'search',
                'tel' => 'tel',
                'color' => 'color',
                'range' => 'range',
                'checkbox' => 'checkbox',
                'radio' => 'radio',
                'file' => 'file',
                'submit' => 'submit',
                'image' => 'image',
                'reset' => 'reset',
                'button' => 'button',
                // 'textarea' و 'select' ليست من نوع input لكن يمكن إدراجهما هنا للمساعدة في توليد النماذج
                'textarea' => 'textarea',
                'select' => 'select',
                // أنواع الإدخال الخاصة التي تتطلب تعاملًا خاصًا
                'hidden' => 'hidden',
            ];
        @endphp

        @foreach ($attributes as $attribute)
            <div class="form-group attribute" data-category="{{ $attribute->category_id }}" style="display: none;">
                <label for="attributes[{{ $attribute->id }}]">{{ $attribute->name }}:</label>

                @if ($attribute->type == 'textarea')
                    <textarea class="form-control" id="attributes[{{ $attribute->id }}]" name="attributes[{{ $attribute->id }}]">{{ old('attributes.' . $attribute->id) }}</textarea>
                @elseif ($attribute->type == 'select')
                    {{-- Select fields are special and need to enumerate options --}}
                @elseif (array_key_exists($attribute->type, $fieldTypes))
                    {{-- Use the field type from the $fieldTypes array --}}
                    <input type="{{ $fieldTypes[$attribute->type] }}" class="form-control"
                        id="attributes[{{ $attribute->id }}]" name="attributes[{{ $attribute->id }}]"
                        value="{{ old('attributes.' . $attribute->id) }}">
                @endif
            </div>
        @endforeach

        <label dir="rtl" for="product_type">نوع العقار:</label>
        <select class="form-control" id="product_type" name="product_type" required>
            <option value="ايجار">إيجار</option>
            <option value="بيع">بيع</option>
        </select>

        <!-- حقول الإدخال للإحداثيات وعنوان الخريطة -->
        <input type="text" id="latitude" name="latitude" placeholder="خط العرض">
        <input type="text" id="longitude" name="longitude" placeholder="خط الطول">
        <input type="text" id="google_maps_url" name="google_maps_url" placeholder="عنوان الخريطة">

        <input id="autocomplete" placeholder="🔍 ابحث عن العنوان على الخريطة" type="text" />
        <div id="map" style="height:400px;"></div>

        <!-- استبدل YOUR_API_KEY بمفتاح API الخاص بك -->
        <script
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCXV38aeBDnAiNzIsI97wtDRLapY4vc1Ds&libraries=places&callback=initAutocomplete"
            async defer></script>
        <script>
            var autocomplete, map, marker;

            function initAutocomplete() {
                // إنشاء خريطة Google
                map = new google.maps.Map(document.getElementById('map'), {
                    center: {
                        lat: -34.397,
                        lng: 150.644
                    }, // يمكنك تغيير هذه الإحداثيات
                    zoom: 13
                });

                // إنشاء كائن البحث التلقائي وربطه بحقل الإدخال
                autocomplete = new google.maps.places.Autocomplete(
                    document.getElementById('autocomplete'), {
                        types: ['geocode']
                    }
                );

                // ربط البحث التلقائي بالخريطة
                autocomplete.bindTo('bounds', map);

                // إنشاء ماركر على الخريطة
                marker = new google.maps.Marker({
                    map: map,
                    draggable: true, // جعل الماركر قابل للسحب
                    position: map.getCenter() // تعيين موقع الماركر ليكون في مركز الخريطة
                });

                // مستمع لحدث السحب للماركر
                marker.addListener('dragend', function(event) {
                    updateLocation(event.latLng.lat(), event.latLng.lng());
                });

                // مستمع لحدث النقر على الخريطة
                map.addListener('click', function(event) {
                    marker.setPosition(event.latLng);
                    updateLocation(event.latLng.lat(), event.latLng.lng());
                });

                // مستمع لحدث تغيير مكان البحث
                autocomplete.addListener('place_changed', function() {
                    var place = autocomplete.getPlace();
                    if (!place.geometry) {
                        window.alert("No details available for input: '" + place.name + "'");
                        return;
                    }

                    // تعيين موقع الماركر والخريطة بناءً على البحث
                    marker.setPosition(place.geometry.location);
                    marker.setVisible(true);
                    map.setCenter(place.geometry.location);
                    map.setZoom(17);

                    updateLocation(place.geometry.location.lat(), place.geometry.location.lng());
                });
            }

            // تحديث حقول الإحداثيات ورابط الخريطة بناءً على الموقع الجديد
            function updateLocation(lat, lng) {
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;
                document.getElementById('google_maps_url').value = 'https://www.google.com/maps/search/?api=1&query=' +
                    encodeURIComponent(lat + ',' + lng);
            }
        </script>

        <script>
            function filterAttributes() {
                var selectedCategory = document.getElementById('category_id').value;
                var attributes = document.querySelectorAll('.form-group[data-category]');

                attributes.forEach(function(attribute) {
                    if (attribute.getAttribute('data-category') === selectedCategory) {
                        attribute.style.display = 'block';
                    } else {
                        attribute.style.display = 'none';
                    }
                });
            }
        </script>

        @include('products.part2')
        @include('products.part')
        <button type="submit" class="btn btn-primary">حفظ</button>
        </div>
        </div>
    </form>
@endsection
