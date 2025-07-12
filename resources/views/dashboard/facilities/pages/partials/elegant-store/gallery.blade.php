{{-- معرض الصور للمتجر الأنيق --}}
<section class="elegant-store-gallery py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">معرض الصور</h2>
        
        @if($facility->images->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($facility->images as $image)
                    <div class="group relative aspect-square overflow-hidden rounded-lg shadow-sm hover:shadow-md transition-shadow">
                        <img src="{{ $image->path }}" 
                             alt="صورة {{ $facility->name }}"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                             
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                            <div class="absolute bottom-4 left-4 right-4">
                                <div class="text-white text-sm">{{ $image->caption ?? $facility->name }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center text-gray-500">لا توجد صور متاحة حالياً</p>
        @endif
    </div>
</section>
