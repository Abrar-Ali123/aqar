<!-- حقل رفع الصور -->
<div class="mb-4">
    <label for="imageInput" class="block text-sm font-medium text-gray-700">رفع الصور:</label>
    <input type="file" multiple id="imageInput" name="image_gallery[]" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
    <div id="imageDisplay" class="mt-2 flex flex-wrap"></div>
    <div class="mt-2 space-x-2">
        <button type="button" id="deleteSelected" class="px-4 py-2 bg-red-500 text-white rounded">حذف المحدد</button>
        <button type="button" id="showAlbum" class="px-4 py-2 bg-blue-500 text-white rounded">عرض الألبوم</button>
    </div>
</div>

<!-- نافذة منبثقة لمعاينة الألبوم -->
<div id="albumModal" class="modal fixed z-10 inset-0 overflow-y-auto hidden">
    <div class="modal-content relative bg-white rounded-lg p-6 mx-auto mt-20 w-4/5 shadow-lg">
        <span class="close absolute top-2 right-2 text-gray-600 cursor-pointer">&times;</span>
        <div id="albumImages" class="flex justify-center"></div>
        <div class="mt-4 flex justify-between">
            <button type="button" id="prevImage" class="px-4 py-2 bg-gray-500 text-white rounded">السابق</button>
            <button type="button" id="nextImage" class="px-4 py-2 bg-gray-500 text-white rounded">التالي</button>
        </div>
    </div>
</div>

<style>
    /* أنماط النافذة المنبثقة */
    .modal {
        display: none;
    }

    .modal-content {
        margin: 10% auto;
        padding: 20px;
        width: 80%;
    }

    .close {
        font-size: 28px;
        font-weight: bold;
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
        var images = document.querySelectorAll('#albumImages img');
        if (currentIndex > 0) {
            images[currentIndex].style.display = 'none';
            currentIndex--;
            images[currentIndex].style.display = 'block';
        }
    });
</script>

<!-- حقل رفع صورة المنتج الرئيسية -->
<div class="mb-4">
    <label for="productImageInput" class="block text-sm font-medium text-gray-700">رفع صورة المنتج الرئيسية:</label>
    <input type="file" id="productImageInput" name="image" accept="image/*" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
    <div id="productImageDisplay" class="mt-2"></div>
    <button type="button" id="deleteProductImage" class="mt-2 px-4 py-2 bg-red-500 text-white rounded hidden">حذف الصورة</button>
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
<div class="mb-4">
    <label for="productVideoInput" class="block text-sm font-medium text-gray-700">رفع فيديو المنتج:</label>
    <input type="file" id="productVideoInput" name="video" accept="video/*" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
    <div id="productVideoDisplay" class="mt-2"></div>
    <button type="button" id="deleteProductVideo" class="mt-2 px-4 py-2 bg-red-500 text-white rounded hidden">حذف الفيديو</button>
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
