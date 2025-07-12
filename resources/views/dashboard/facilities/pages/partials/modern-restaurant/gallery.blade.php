{{-- معرض الصور للمطعم --}}
<section class="modern-restaurant-gallery py-20">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold mb-4" style="font-family: var(--heading-font)">أجواء المطعم</h2>
            <div class="w-24 h-1 bg-accent-color mx-auto"></div>
        </div>
        
        @if($facility->images->count() > 0)
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($facility->images->take(8) as $key => $image)
                    <div class="group relative {{ $key === 0 ? 'col-span-2 row-span-2' : '' }} overflow-hidden">
                        <div class="aspect-square w-full h-full">
                            <img src="{{ $image->path }}" 
                                 alt="صورة {{ $facility->name }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        </div>
                        
                        <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="text-white text-center transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                                    <div class="w-16 h-16 mx-auto mb-4 rounded-full border-2 border-white/50 flex items-center justify-center">
                                        <i class="fas fa-expand-alt text-2xl"></i>
                                    </div>
                                    <div class="text-lg font-medium">{{ $image->caption ?? 'عرض الصورة' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            @if($facility->images->count() > 8)
                <div class="text-center mt-12">
                    <button class="btn border-2 border-accent-color text-accent-color hover:bg-accent-color hover:text-white px-12 py-4 rounded-none text-lg transition-colors">
                        عرض المزيد من الصور
                    </button>
                </div>
            @endif
        @else
            <p class="text-center text-gray-500">لا توجد صور متاحة حالياً</p>
        @endif
    </div>
</section>
