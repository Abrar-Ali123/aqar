@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid">
        <form method="POST" action="{{ route('facilities.store') }}" enctype="multipart/form-data">
            @csrf
            <!-- Section for Facility Fields -->
            <h2>{{ __('facility.create_new_facility') }}</h2>

            <div class="form-group">
                <label for="is_active">{{ __('facility.is_active_now') }}: <span class="text-danger">*</span></label>
                <select class="form-control mb-3" id="is_active" name="is_active">
                    <option value="" disabled selected>is Active?</option>
                    <option value="1">yes</option>
                    <option value="0">No</option>

                </select>
            </div>
            <div class="mb-4">
                <label for="logo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">شعار
                    المنشأة</label>
                <input class="form-control mb-3" type="file" name="logo" id="logo" class="mt-1 block w-full">
                @error('logo')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="header" class="block text-sm font-medium text-gray-700 dark:text-gray-300">صورة الرأس</label>
                <input class="form-control mb-3" type="file" name="header" id="logo" class="mt-1 block w-full">
                @error('header')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            <script>
                function updateImagePreview(input, previewId) {
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();

                        reader.onload = function(e) {
                            var preview = document.getElementById(previewId);
                            preview.src = e.target.result;
                            preview.style.display = 'block';
                        };

                        reader.readAsDataURL(input.files[0]);
                    }
                }
            </script>






            <div class="form-group">
                <label for="License">{{ __('facility.license') }}:</label>
                <input class="form-control mb-3" type="text" class="form-control" id="License" name="License"
                    placeholder="{{ __('facility.enter_license') }}">


                <br>

                <input class="form-control mb-3" id="autocomplete" class="fas fa-search" placeholder="ادخل موقعك"
                    type="text"  />
                <br>
                <input class="form-control mb-3" type="text" id="latitude" name="latitude" placeholder="خط العرض">
                <input class="form-control mb-3" type="text" id="longitude" name="longitude" placeholder="خط الطول">
                <input class="form-control mb-3" type="text" id="google_maps_url" name="google_maps_url"
                    placeholder="عنوان الخريطة">

                <div id="map" style="height:400px;"></div>



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

                        autocomplete.bindTo('bounds', map);

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






                <!-- Section for Facility Translations -->
                @foreach (config('app.locales') as $locale)
                    <div class="form-group">
                        <label for="name_{{ $locale }}">{{ __('facility.name') }}: <span
                                class="text-danger">*</span></label>
                        <input class="form-control mb-3" type="text" class="form-control" id="name_{{ $locale }}"
                            name="translations[{{ $locale }}][name]"
                            placeholder="{{ __('facility.enter_name', ['locale' => strtoupper($locale)]) }}" required>
                    </div>





                    <div class="form-group">

                        <textarea name="" class="form-control mb-3" id="hidden_info_{{ $locale }}"
                            name="translations[{{ $locale }}][info]" cols="30" rows="10"></textarea>

                    </div>
                @endforeach

                <!-- Separator -->
                <br>

                <hr>

                <!-- Section for Admin Account Fields -->
                {{--        <h2>{{ __('facility.create_new_admin') }}</h2> --}}
                {{--        <div class="form-group"> --}}
                {{--            <label for="email">{{ __('facility.admin_email') }}: <span class="text-danger">*</span></label> --}}
                {{--            <input class="form-control mb-3" type="email" class="form-control" id="email" name="email" placeholder="{{ __('employee.enter_admin_email') }}" required> --}}
                {{--        </div> --}}
                {{--        <div class="form-group"> --}}
                {{--            <label for="phone_number">{{ __('facility.phone_number') }}:</label> --}}
                {{--            <input class="form-control mb-3" type="text" class="form-control" id="phone_number" name="phone_number" placeholder="{{ __('employee.enter_phone_number') }}"> --}}
                {{--        </div> --}}
                {{--        <div class="form-group"> --}}
                {{--            <label for="password">{{ __('facility.admin_password') }}: <span class="text-danger">*</span></label> --}}
                {{--            <input class="form-control mb-3" type="password" class="form-control" id="password" name="password" placeholder="{{ __('employee.enter_admin_password') }}" required> --}}
                {{--        </div> --}}
                <br>

                <!-- Section for Admin Account Fields -->
                <h2>{{ __('facility.create_new_admin') }}</h2>

                <div class="form-group">
                    <label for="email">{{ __('facility.admin_email') }}: <span class="text-danger">*</span></label>
                    <input class="form-control mb-3" type="email" class="form-control" id="email" name="email"
                        placeholder="{{ __('facility.enter_admin_email') }}" required>
                </div>

                <div class="form-group">
                    <label for="phone_number">{{ __('facility.phone_number') }}:</label>
                    <input class="form-control mb-3" type="text" class="form-control" id="phone_number"
                        name="phone_number" placeholder="{{ __('facility.enter_phone_number') }}">
                </div>

                <div class="form-group">
                    <label for="password">{{ __('facility.admin_password') }}: <span class="text-danger">*</span></label>
                    <input class="form-control mb-3" type="password" class="form-control" id="password" name="password"
                        placeholder="{{ __('facility.enter_admin_password') }}" required>
                </div>

                <!-- Section for Admin User Translations -->
                @foreach (config('app.locales') as $locale)
                    <div class="form-group">
                        <label for="user_name_{{ $locale }}">{{ __('facility.admin_name') }}
                            ({{ strtoupper($locale) }})
                            : <span class="text-danger">*</span></label>
                        <input class="form-control mb-3" type="text" class="form-control"
                            id="user_name_{{ $locale }}" name="user_translations[{{ $locale }}][name]"
                            placeholder="{{ __('facility.enter_admin_name', ['locale' => strtoupper($locale)]) }}"
                            required>
                    </div>
                @endforeach

                <!-- Control Buttons -->
                <div class="form-group">

                    <button type="submit" class="btn btn-primary mb-4">{{ __('facility.create_facility') }}</button>
                </div>

            </div>
        </form>
    </div>
@endsection
