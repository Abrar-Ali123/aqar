@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">تحديث المنشأة</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="#">المنشآت</a></li>
                            <li class="breadcrumb-item active">تحديث المنشأة</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">تفاصيل المنشأة</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('facilities.update', $facility->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row gy-4">
                                <div class="col-md-6">
                                    <div>
                                        <label for="is_active" class="form-label">هل المنشأة نشطة الآن؟ <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" id="is_active" name="is_active" required>
                                            <option value="1" {{ $facility->is_active == 1 ? 'selected' : '' }}>نعم</option>
                                            <option value="0" {{ $facility->is_active == 0 ? 'selected' : '' }}>لا</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div>
                                        <label for="logo" class="form-label">الشعار</label>
                                        <input type="file" name="logo" class="form-control" id="logo" accept="image/*">
                                        @error('logo')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div>
                                        <label for="header" class="form-label">صورة الرأس</label>
                                        <input type="file" name="header" class="form-control" id="header" accept="image/*">
                                        @error('header')
                                            <p class="text-danger mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div>
                                        <label for="License" class="form-label">الرخصة</label>
                                        <input type="text" name="License" class="form-control" id="License"
                                            placeholder="أدخل الرخصة" value="{{ $facility->License }}">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div>
                                        <label for="autocomplete" class="form-label">الموقع</label>
                                        <input type="text" name="location" id="autocomplete" class="form-control mb-3"
                                            placeholder="أدخل الموقع" value="{{ $facility->location }}">
                                        <input class="form-control mb-3" type="text" id="latitude" name="latitude"
                                            placeholder="خط العرض" value="{{ $facility->latitude }}">
                                        <input class="form-control mb-3" type="text" id="longitude" name="longitude"
                                            placeholder="خط الطول" value="{{ $facility->longitude }}">
                                        <input class="form-control mb-3" type="text" id="google_maps_url"
                                            name="google_maps_url" placeholder="عنوان الخريطة" value="{{ $facility->google_maps_url }}">

                                        <div id="map" style="height: 400px;" class="mt-3"></div>
                                    </div>
                                </div>

                                <script
                                    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCXV38aeBDnAiNzIsI97wtDRLapY4vc1Ds&libraries=places&callback=initAutocomplete"
                                    async defer></script>
                                <script>
                                    var autocomplete, map, marker;

                                    function initAutocomplete() {
                                        map = new google.maps.Map(document.getElementById('map'), {
                                            center: {
                                                lat: {{ $facility->latitude }},
                                                lng: {{ $facility->longitude }}
                                            },
                                            zoom: 13
                                        });

                                        autocomplete = new google.maps.places.Autocomplete(
                                            document.getElementById('autocomplete'), {
                                                types: ['geocode']
                                            }
                                        );

                                        autocomplete.bindTo('bounds', map);

                                        marker = new google.maps.Marker({
                                            map: map,
                                            draggable: true,
                                            position: map.getCenter()
                                        });

                                        marker.addListener('dragend', function(event) {
                                            updateLocation(event.latLng.lat(), event.latLng.lng());
                                        });

                                        map.addListener('click', function(event) {
                                            marker.setPosition(event.latLng);
                                            updateLocation(event.latLng.lat(), event.latLng.lng());
                                        });

                                        autocomplete.addListener('place_changed', function() {
                                            var place = autocomplete.getPlace();
                                            if (!place.geometry) {
                                                window.alert("No details available for input: '" + place.name + "'");
                                                return;
                                            }

                                            marker.setPosition(place.geometry.location);
                                            marker.setVisible(true);
                                            map.setCenter(place.geometry.location);
                                            map.setZoom(17);

                                            updateLocation(place.geometry.location.lat(), place.geometry.location.lng());
                                        });
                                    }

                                    function updateLocation(lat, lng) {
                                        document.getElementById('latitude').value = lat;
                                        document.getElementById('longitude').value = lng;
                                        document.getElementById('google_maps_url').value = 'https://www.google.com/maps/search/?api=1&query=' +
                                            encodeURIComponent(lat + ',' + lng);
                                    }
                                </script>

                                <div class="col-md-6">
                                    <div>
                                        <label for="name_ar" class="form-label">الاسم (عربي) <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="translations[ar][name]" class="form-control"
                                            id="name_ar" placeholder="أدخل الاسم بالعربية" value="{{ $facility->getTranslation('name', 'ar') }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div>
                                        <label for="name_en" class="form-label">الاسم (إنجليزي) <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="translations[en][name]" class="form-control"
                                            id="name_en" placeholder="أدخل الاسم بالإنجليزية" value="{{ $facility->getTranslation('name', 'en') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div>
                                        <label for="info_ar" class="form-label">المعلومات (عربي)</label>
                                        <textarea name="translations[ar][info]" class="form-control" id="info_ar" rows="3">{{ $facility->getTranslation('info', 'ar') }}</textarea>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div>
                                        <label for="info_en" class="form-label">المعلومات (إنجليزي)</label>
                                        <textarea name="translations[en][info]" class="form-control" id="info_en" rows="3">{{ $facility->getTranslation('info', 'en') }}</textarea>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div>
                                        <label for="email" class="form-label">البريد الإلكتروني للمدير <span
                                                class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control" id="email"
                                            placeholder="أدخل البريد الإلكتروني" value="{{ $facility->email }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div>
                                        <label for="phone_number" class="form-label">رقم الهاتف</label>
                                        <input type="text" name="phone_number" class="form-control" id="phone_number"
                                            placeholder="أدخل رقم الهاتف" value="{{ $facility->phone_number }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div>
                                        <label for="password" class="form-label">كلمة المرور <span
                                                class="text-danger">*</span></label>
                                        <input type="password" name="password" class="form-control" id="password"
                                            placeholder="أدخل كلمة المرور">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div>
                                        <label for="user_name" class="form-label">اسم المدير <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="user_name" class="form-control" id="user_name"
                                            placeholder="أدخل اسم المدير" value="{{ $facility->user_name }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">تحديث المنشأة</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
