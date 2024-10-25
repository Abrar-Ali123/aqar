@extends('dashboard.layouts.app1')

@section('content')
<form action="{{ route('products.update', $product->id) }}" dir="rtl" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="header">تعديل بيانات المنتج الأساسية</div>
    <div class="content">
        {{-- الحقول الأساسية --}}
        <label for="is_active">الحالة:</label>
        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ $product->is_active || old('is_active', $product->is_active) ? 'checked' : '' }}>
        <br><br>

        <label for="price">السعر:</label>
        <input type="number" class="form-control" id="price" name="price" value="{{ old('price', $product->price) }}" required>
        <br>


        <label for="room">عدد الغرف:</label>
        <input type="number" class="form-control" id="room" name="room" value="{{ old('room', $product->room) }}" required>
        <br>

        <label for="bathroom">عدد الحمامات:</label>
        <input type="number" class="form-control" id="bathroom" name="bathroom" value="{{ old('bathroom', $product->bathroom) }}" required>
        <br>

        <label for="Space">المساحة:</label>
        <input type="text" class="form-control" id="Space" name="Space" value="{{ old('Space', $product->Space) }}" required>
        <br>

        @foreach (config('app.locales') as $locale)
            @php
                $translation = $product->translations->firstWhere('locale', $locale);
            @endphp

            <label>اسم العقار:</label>
            <input type="text" class="form-control" id="name_{{ $locale }}" name="translations[{{ $locale }}][name]" value="{{ old('translations.'.$locale.'.name', $translation->name ?? '') }}" required>
            <br>

            <label>وصف العقار :</label>




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

    <div class="form-control rich-text-editor" contenteditable="true" id="description_{{ $locale }}" style="min-height: 100px;" placeholder="{{ __('facility.enter_facility_description', ['locale' => strtoupper($locale)]) }}">{!! $product->description !!}</div>

  </div>

  <!-- Content Editable Div -->
  <style>
    .rich-text-editor {
        width: 100%;
    padding: 10px;
    margin-top: 5px;
    border-radius: 5px;
    border: 1px solid #ccc;


    }

</style>


  <!-- Hidden Input Field to Store Data -->
  <input type="hidden" id="hidden_description_{{ $locale }}" name="translations[{{ $locale }}][description]">

  <!-- Save Button -->
</div>

<!-- JavaScript to handle the Rich Text Editor Commands and Save Action -->
<script>
  // Function to execute command from the tools
  function execCmd(command) {
    document.execCommand(command, false, null);
    // Update hidden input with contenteditable div's content after change
    document.getElementById('hidden_description_{{ $locale }}').value = document.getElementById('description_{{ $locale }}').innerHTML;
  }

  // Function to save content into the hidden input field
  function saveContent() {
    var content = document.getElementById('description_{{ $locale }}').innerHTML;
    document.getElementById('hidden_description_{{ $locale }}').value = content;
  }

  // Event listener to update hidden input whenever the content changes
  document.getElementById('description_{{ $locale }}').addEventListener('input', function() {
    document.getElementById('hidden_description_{{ $locale }}').value = this.innerHTML;
  });
</script>



                @endforeach


<!-- حقل رفع الصور لمعرض الصور -->
<input type="file" multiple id="imageInput" name="image_gallery[]" class="neomorphic p-2">
<div id="imageDisplay">
    <!-- هنا نعرض الصور الحالية لمعرض الصور -->
    @if(isset($product->image_gallery))
        @php
            $galleryImages = explode(',', $product->image_gallery); // أفترض أن image_gallery هو سلسلة نصية من الصور مفصولة بفواصل
        @endphp
        @foreach($galleryImages as $galleryImage)
            <span class="image-container">
            <img src="{{ Storage::url($galleryImage) }}" style="height: 100px; margin: 10px;">
                <!-- حقل مخفي لتحديد الصور التي يريد المستخدم حذفها -->
                <input type="checkbox" name="delete_images[]" value="{{ $galleryImage }}" class="imgCheckbox">
            </span>
        @endforeach
    @endif
</div>

<button type="button" id="deleteSelected">حذف المحدد</button>

<button type="button" id="showAlbum">عرض الألبوم</button>

<!-- نافذة منبثقة لمعاينة الألبوم -->
<div id="albumModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div id="albumImages"></div>
        <button type="button" id="prevImage">السابق</button>
        <button type="button" id="nextImage">التالي</button>
    </div>
</div>

<style>
    /* أنماط النافذة المنبثقة */
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgb(0,0,0);
        background-color: rgba(0,0,0,0.4);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    #imageDisplay img {
        margin: 5px;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 5px;
    }

    #imageDisplay span {
        display: inline-block;
        position: relative;
    }

    .imgCheckbox {
        position: absolute;
        top: 5px;
        left: 5px;
    }

    #albumImages img {
        display: none;
        max-width: 90%;
        max-height: 90%;
        margin: auto;
        display: block;
    }
</style>

<script>
    // البرمجة النصية للنافذة المنبثقة والألبوم
    var modal = document.getElementById('albumModal');
    var span = document.getElementsByClassName('close')[0];
    var currentIndex = 0;

    document.getElementById('imageInput').addEventListener('change', function(event) {
        var imageContainer = document.getElementById('imageDisplay');
        imageContainer.innerHTML = '';

        var files = event.target.files;
        for (var i = 0; i < files.length; i++) {
            var file = files[i];

            if (!file.type.match('image.*')) {
                continue;
            }

            var reader = new FileReader();

            reader.onload = (function(theFile) {
                return function(e) {
                    var span = document.createElement('span');
                    span.innerHTML = '<img src="' + e.target.result +
                                     '" title="' + escape(theFile.name) +
                                     '" style="height: 100px; margin: 10px"/><input type="checkbox" class="imgCheckbox">';
                    imageContainer.appendChild(span);
                };
            })(file);

            reader.readAsDataURL(file);
        }
    });

    document.getElementById('deleteSelected').addEventListener('click', function() {
        var checkboxes = document.getElementsByClassName('imgCheckbox');
        for (var i = checkboxes.length - 1; i >= 0; i--) {
            if (checkboxes[i].checked) {
                checkboxes[i].parentNode.remove();
            }
        }
    });

    document.getElementById('showAlbum').onclick = function() {
        modal.style.display = 'block';
        var images = document.querySelectorAll('#imageDisplay img');
        var albumImages = document.getElementById('albumImages');
        albumImages.innerHTML = '';
        images.forEach(function(img, index) {
            var imgClone = img.cloneNode();
            imgClone.style.display = index === 0 ? 'block' : 'none'; // Show only the first image initially
            albumImages.appendChild(imgClone);
        });
        currentIndex = 0; // Reset index on opening the album
    }

    span.onclick = function() {
        modal.style.display = 'none';
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }

    document.getElementById('nextImage').addEventListener('click', function() {
        var images = document.querySelectorAll('#albumImages img');
        if (currentIndex < images.length - 1) {
            images[currentIndex].style.display = 'none';
            currentIndex++;
            images[currentIndex].style.display = 'block';
        }
    });

    document.getElementById('prevImage').addEventListener('click', function() {
        var images = document.querySelectorAll('#albumImages img'); // تعريف المتغير images داخل الدالة

        if (currentIndex > 0) {
            images[currentIndex].style.display = 'none';
            currentIndex--;
            images[currentIndex].style.display = 'block';
        }
    });
</script>


<!-- حقل رفع صورة المنتج الرئيسية -->
<input type="file" id="productImageInput" name="image" accept="image/*">
<div id="productImageDisplay">
    <!-- إذا كان هناك صورة رئيسية محملة مسبقاً، نعرضها هنا -->
    @if($product->image)
    <img src="{{ asset('storage/products' . $product->image) }}" alt="Product Image">

    <img src="{{ Storage::url($product->image) }}" style="max-width: 100%;">
@endif

</div>


<script>
var productImageDisplay = document.getElementById('productImageDisplay');
var deleteProductImageButton = document.getElementById('deleteProductImage');

document.getElementById('productImageInput').addEventListener('change', function(event) {
    productImageDisplay.innerHTML = '';
    var file = event.target.files[0];
    if (file && file.type.match('image.*')) {
        var img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        img.style.maxWidth = '100%';
        productImageDisplay.appendChild(img);
        deleteProductImageButton.style.display = 'block'; // Show delete button
    }
});

deleteProductImageButton.addEventListener('click', function() {
    productImageDisplay.innerHTML = '';
    deleteProductImageButton.style.display = 'none'; // Hide delete button
});
</script>


<!-- حقل رفع فيديو المنتج -->
<input type="file" id="productVideoInput" name="video" accept="video/*">
<div id="productVideoDisplay">
    <!-- إذا كان هناك فيديو محمل مسبقاً، نعرضه هنا -->
    @if($product->video)
        <video src="{{ Storage::url($product->video) }}" controls style="max-width: 100%;"></video>
        <!-- حقل مخفي لتحديد الفيديو الذي يريد المستخدم حذفه -->
        <input type="checkbox" name="delete_video" value="{{ $product->video }}">
        <label for="delete_video">حذف الفيديو</label>
    @endif
</div>

<script>
var productVideoDisplay = document.getElementById('productVideoDisplay');
var deleteProductVideoButton = document.getElementById('deleteProductVideo');

document.getElementById('productVideoInput').addEventListener('change', function(event) {
    productVideoDisplay.innerHTML = '';
    var file = event.target.files[0];
    if (file && file.type.match('video.*')) {
        var video = document.createElement('video');
        video.src = URL.createObjectURL(file);
        video.controls = true;
        video.style.maxWidth = '100%';
        productVideoDisplay.appendChild(video);
        deleteProductVideoButton.style.display = 'block'; // Show delete button
    }
});

deleteProductVideoButton.addEventListener('click', function() {
    productVideoDisplay.innerHTML = '';
    deleteProductVideoButton.style.display = 'none'; // Hide delete button
});
</script>

        <button type="submit" class="btn btn-primary">تحديث</button>
    </div>
</form>
@endsection
