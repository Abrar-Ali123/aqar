<div class="gallery-component" x-data="{ 
    images: @entangle('content.images'),
    selectedImage: null,
    showLightbox: false,
    uploadingImages: false
}">
    <!-- عرض الصور -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 p-4">
        @foreach($content['images'] ?? [] as $index => $image)
            <div class="relative group aspect-square">
                <img src="{{ Storage::url($image['path']) }}" 
                     alt="{{ $image['caption'] ?? '' }}"
                     class="w-full h-full object-cover rounded-lg cursor-pointer"
                     x-on:click="selectedImage = {{ $index }}; showLightbox = true">
                
                <!-- شريط التحكم -->
                <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                    <button class="text-white p-2 rounded-full bg-blue-500 hover:bg-blue-600"
                            x-on:click.stop="$wire.emit('editImageCaption', {{ $index }})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="text-white p-2 rounded-full bg-red-500 hover:bg-red-600"
                            x-on:click.stop="$wire.emit('removeImage', {{ $index }})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        @endforeach

        <!-- زر إضافة صور -->
        <div class="aspect-square border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center cursor-pointer hover:bg-gray-50"
             x-on:click="$refs.fileInput.click()"
             x-bind:class="{ 'opacity-50': uploadingImages }">
            <input type="file" 
                   x-ref="fileInput" 
                   class="hidden" 
                   multiple 
                   accept="image/*"
                   x-on:change="
                        uploadingImages = true;
                        $wire.upload('newImages', $event.target.files, () => {
                            uploadingImages = false;
                            $refs.fileInput.value = '';
                        })">
            <div class="text-center">
                <i class="fas fa-plus text-2xl text-gray-400"></i>
                <p class="text-sm text-gray-500 mt-2">إضافة صور</p>
            </div>
        </div>
    </div>

    <!-- معرض الصور (Lightbox) -->
    <div x-show="showLightbox" 
         x-on:click.self="showLightbox = false"
         class="fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center">
        
        <div class="relative max-w-4xl mx-auto">
            <!-- زر الإغلاق -->
            <button class="absolute top-4 right-4 text-white text-2xl hover:text-gray-300"
                    x-on:click="showLightbox = false">
                <i class="fas fa-times"></i>
            </button>

            <!-- الصورة -->
            <template x-if="selectedImage !== null && images[selectedImage]">
                <img x-bind:src="images[selectedImage].path" 
                     x-bind:alt="images[selectedImage].caption"
                     class="max-h-[80vh] max-w-full">
            </template>

            <!-- أزرار التنقل -->
            <button class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white text-3xl hover:text-gray-300"
                    x-show="selectedImage > 0"
                    x-on:click="selectedImage--">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white text-3xl hover:text-gray-300"
                    x-show="selectedImage < images.length - 1"
                    x-on:click="selectedImage++">
                <i class="fas fa-chevron-right"></i>
            </button>

            <!-- التعليق -->
            <div class="absolute bottom-4 left-0 right-0 text-center text-white"
                 x-text="images[selectedImage]?.caption">
            </div>
        </div>
    </div>
</div>
