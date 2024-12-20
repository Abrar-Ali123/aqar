
<div class="container">
    <form method="POST" action="{{ route('facilities.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- Section for Facility Fields -->
        <h2>{{ __('facility.create_new_facility') }}</h2>


        <div class="form-group">
  <label for="is_active">{{ __('facility.is_active_now') }}: <span class="text-danger">*</span></label>
  <input type="checkbox" class="form-control" id="is_active" name="is_active" value="1">
</div>
        <div class="mb-4">
            <label for="logo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">شعار المنشأة</label>
            <input type="file" name="logo" id="logo" class="mt-1 block w-full">
            @error('logo')
            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-4">
            <label for="header" class="block text-sm font-medium text-gray-700 dark:text-gray-300">صورة الرأس</label>
            <input type="file" name="header" id="logo" class="mt-1 block w-full">
            @error('header')
            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
            @enderror
        </div>

<script>
function updateImagePreview(input, previewId) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
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
            <input type="text" class="form-control" id="License" name="License" placeholder="{{ __('facility.enter_license') }}">


<br>

 <input id="autocomplete" class="fas fa-search" placeholder="ادخل موقعك" type="text" style="width: 70%;" />
 <br>
<input type="text" id="latitude" name="latitude" placeholder="خط العرض">
<input type="text" id="longitude" name="longitude" placeholder="خط الطول">
<input type="text" id="google_maps_url" name="google_maps_url" placeholder="عنوان الخريطة">

  <div id="map" style="height:400px;"></div>



  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCXV38aeBDnAiNzIsI97wtDRLapY4vc1Ds&libraries=places&callback=initAutocomplete" async defer></script>
  <script>
  var autocomplete, map, marker;

  function initAutocomplete() {
    // إنشاء خريطة Google
    map = new google.maps.Map(document.getElementById('map'), {
      center: {lat: -34.397, lng: 150.644}, // يمكنك تغيير هذه الإحداثيات
      zoom: 13
    });

    // إنشاء كائن البحث التلقائي وربطه بحقل الإدخال
    autocomplete = new google.maps.places.Autocomplete(
        document.getElementById('autocomplete'),
        {types: ['geocode']}
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
    document.getElementById('google_maps_url').value = 'https://www.google.com/maps/search/?api=1&query=' + encodeURIComponent(lat + ',' + lng);
  }
</script>






        <!-- Section for Facility Translations -->
        @foreach (config('app.locales') as $locale)
            <div class="form-group">
                <label for="name_{{ $locale }}">{{ __('facility.name') }}: <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name_{{ $locale }}" name="translations[{{ $locale }}][name]" placeholder="{{ __('facility.enter_name', ['locale' => strtoupper($locale)]) }}" required>
            </div>






 <!-- Rich Text Editor Container -->
<div class="form-group">
  <!-- Rich Text Tools -->
  <div id="editor-toolbar" class="editor-toolbar">
  <div id="editor-toolbar" class="editor-toolbar" style="margin-bottom: 10px;"> <!-- زيادة المسافة بين الأدوات ومربع النص -->
    <button type="button" onclick="execCmd('bold')"><i class="fas fa-bold"></i></button>
    <button type="button" onclick="execCmd('italic')"><i class="fas fa-italic"></i></button>
    <button type="button" onclick="execCmd('underline')"><i class="fas fa-underline"></i></button>
    <!-- أضف هنا الأدوات الإضافية -->
    <!-- مثال للأدوات الإضافية -->
    <button type="button" onclick="execCmd('justifyLeft')"><i class="fas fa-align-left"></i></button>
    <button type="button" onclick="execCmd('justifyCenter')"><i class="fas fa-align-center"></i></button>
    <button type="button" onclick="execCmd('justifyRight')"><i class="fas fa-align-right"></i></button>
    <button type="button" onclick="execCmd('justifyFull')"><i class="fas fa-align-justify"></i></button>
    <button type="button" onclick="execCmd('insertUnorderedList')"><i class="fas fa-list-ul"></i></button>
    <button type="button" onclick="execCmd('insertOrderedList')"><i class="fas fa-list-ol"></i></button>
    <button type="button" onclick="execCmd('cut')"><i class="fas fa-cut"></i></button>
    <button type="button" onclick="execCmd('copy')"><i class="fas fa-copy"></i></button>
    <button type="button" onclick="execCmd('paste')"><i class="fas fa-paste"></i></button>
    <button type="button" onclick="execCmd('undo')"><i class="fas fa-undo-alt"></i></button>
    <button type="button" onclick="execCmd('redo')"><i class="fas fa-redo-alt"></i></button>

    <div class="form-control rich-text-editor" contenteditable="true" id="info_{{ $locale }}" style="min-height: 100px;" placeholder="{{ __('facility.enter_facility_info', ['locale' => strtoupper($locale)]) }}"></div>

  </div>

  <!-- Content Editable Div -->
  <style>
    .rich-text-editor {
        background-color: #ecf0f3;
   color: #333;
   border: none;
    margin : 2%;
   border-radius: 5px;
   cursor: pointer;
   box-shadow: inset 3px 3px 5px #c2c2c2, inset -3px -3px 5px #ffffff;
   position: relative;


    }

</style>


  <!-- Hidden Input Field to Store Data -->
  <input type="hidden" id="hidden_info_{{ $locale }}" name="translations[{{ $locale }}][info]">

  <!-- Save Button -->
</div>

<!-- JavaScript to handle the Rich Text Editor Commands and Save Action -->
<script>
  // Function to execute command from the tools
  function execCmd(command) {
    document.execCommand(command, false, null);
    // Update hidden input with contenteditable div's content after change
    document.getElementById('hidden_info_{{ $locale }}').value = document.getElementById('info_{{ $locale }}').innerHTML;
  }

  // Function to save content into the hidden input field
  function saveContent() {
    var content = document.getElementById('info_{{ $locale }}').innerHTML;
    document.getElementById('hidden_info_{{ $locale }}').value = content;
  }

  // Event listener to update hidden input whenever the content changes
  document.getElementById('info_{{ $locale }}').addEventListener('input', function() {
    document.getElementById('hidden_info_{{ $locale }}').value = this.innerHTML;
  });
</script>



        @endforeach

        <!-- Separator -->
        <hr>

        <!-- Section for Admin Account Fields -->
{{--        <h2>{{ __('facility.create_new_admin') }}</h2>--}}
{{--        <div class="form-group">--}}
{{--            <label for="email">{{ __('facility.admin_email') }}: <span class="text-danger">*</span></label>--}}
{{--            <input type="email" class="form-control" id="email" name="email" placeholder="{{ __('employee.enter_admin_email') }}" required>--}}
{{--        </div>--}}
{{--        <div class="form-group">--}}
{{--            <label for="phone_number">{{ __('facility.phone_number') }}:</label>--}}
{{--            <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="{{ __('employee.enter_phone_number') }}">--}}
{{--        </div>--}}
{{--        <div class="form-group">--}}
{{--            <label for="password">{{ __('facility.admin_password') }}: <span class="text-danger">*</span></label>--}}
{{--            <input type="password" class="form-control" id="password" name="password" placeholder="{{ __('employee.enter_admin_password') }}" required>--}}
{{--        </div>--}}
        <br>
        <br>
        <br>
        <br>

        <!-- Section for Admin Account Fields -->
<h2>{{ __('facility.create_new_admin') }}</h2>

<div class="form-group">
    <label for="email">{{ __('facility.admin_email') }}: <span class="text-danger">*</span></label>
    <input type="email" class="form-control" id="email" name="email" placeholder="{{ __('facility.enter_admin_email') }}" required>
</div>

<div class="form-group">
    <label for="phone_number">{{ __('facility.phone_number') }}:</label>
    <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="{{ __('facility.enter_phone_number') }}">
</div>

<div class="form-group">
    <label for="password">{{ __('facility.admin_password') }}: <span class="text-danger">*</span></label>
    <input type="password" class="form-control" id="password" name="password" placeholder="{{ __('facility.enter_admin_password') }}" required>
</div>

<!-- Section for Admin User Translations -->
@foreach (config('app.locales') as $locale)
    <div class="form-group">
        <label for="user_name_{{ $locale }}">{{ __('facility.admin_name') }} ({{ strtoupper($locale) }}): <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="user_name_{{ $locale }}" name="user_translations[{{ $locale }}][name]" placeholder="{{ __('facility.enter_admin_name', ['locale' => strtoupper($locale)]) }}" required>
    </div>
@endforeach

        <!-- Control Buttons -->
        <button type="submit" class="btn btn-primary">{{ __('facility.create_facility') }}</button>
</div></div></form>
</div>
