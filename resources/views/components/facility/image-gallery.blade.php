@props(['data', 'content', 'facility'])

@php
    $componentId = 'image-gallery-' . ($data['id'] ?? uniqid());
    $settings = $facility->getComponentSettings($componentId);
    $images = $content[$data['images_key']] ?? [];
@endphp

<x-facility.customizable-wrapper :facility="$facility" :componentId="$componentId">
    <div class="image-gallery" x-data="{ 
        currentImage: 0,
        images: {{ json_encode($images) }}
    }" 
    style="
        background-color: {{ $settings['style']['backgroundColor'] ?? 'transparent' }};
        padding: {{ $settings['style']['padding'] ?? '0' }};
    ">
    @if(isset($data['title']))
        <h2 class="text-2xl font-bold mb-4">{{ $data['title'] }}</h2>
    @endif

    {{-- Main Image Display --}}
    <div class="relative aspect-video mb-4">
        <template x-for="(image, index) in images" :key="index">
            <img :src="image.url" 
                 :alt="image.alt" 
                 class="absolute inset-0 w-full h-full object-cover rounded-lg transition-opacity duration-300"
                 :class="{ 'opacity-0': currentImage !== index }"
                 style="display: none;"
                 x-show="currentImage === index"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100">
        </template>

        {{-- Navigation Arrows --}}
        <button @click="currentImage = (currentImage - 1 + images.length) % images.length"
                class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75"
                x-show="images.length > 1">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button @click="currentImage = (currentImage + 1) % images.length"
                class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75"
                x-show="images.length > 1">
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>

    {{-- Thumbnails --}}
    <div x-data="{ isOpen: false, currentImage: '', images: @json($facility->gallery) }" class="relative">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($facility->gallery as $index => $image)
                <div class="relative group cursor-pointer overflow-hidden rounded-lg">
                    <img src="{{ $image }}" 
                         alt="Gallery image {{ $index + 1 }}" 
                         class="h-48 w-full object-cover transition-transform duration-300 group-hover:scale-110"
                         @click="isOpen = true; currentImage = '{{ $image }}'">
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-opacity duration-300 flex items-center justify-center">
                        <i class="fas fa-expand text-white opacity-0 group-hover:opacity-100 transform scale-0 group-hover:scale-100 transition-all duration-300"></i>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Modal -->
        <div x-show="isOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75"
             @click.self="isOpen = false">
            <div class="relative max-w-4xl mx-auto">
                <!-- Navigation Buttons -->
                <button @click="let idx = images.indexOf(currentImage); currentImage = images[idx === 0 ? images.length - 1 : idx - 1];"
                        class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white hover:text-primary-400 transition-colors">
                    <i class="fas fa-chevron-left text-3xl"></i>
                </button>
                <button @click="let idx = images.indexOf(currentImage); currentImage = images[idx === images.length - 1 ? 0 : idx + 1];"
                        class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white hover:text-primary-400 transition-colors">
                    <i class="fas fa-chevron-right text-3xl"></i>
                </button>

                <!-- Close Button -->
                <button @click="isOpen = false"
                        class="absolute top-4 right-4 text-white hover:text-primary-400 transition-colors">
                    <i class="fas fa-times text-2xl"></i>
                </button>

                <!-- Image -->
                <img :src="currentImage" 
                     alt="Gallery image"
                     class="max-h-[80vh] mx-auto rounded-lg shadow-xl">

                <!-- Image Counter -->
                <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 text-white bg-black bg-opacity-50 px-3 py-1 rounded-full text-sm">
                    <span x-text="images.indexOf(currentImage) + 1"></span> / <span x-text="images.length"></span>
                </div>
            </div>
        </div>
        </template>
    </div>
</x-facility.customizable-wrapper>
