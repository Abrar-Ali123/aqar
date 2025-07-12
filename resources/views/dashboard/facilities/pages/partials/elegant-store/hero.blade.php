{{-- قسم Hero للمتجر الأنيق --}}
<section class="elegant-store-hero relative h-[70vh] overflow-hidden">
    {{-- صورة الخلفية --}}
    <div class="absolute inset-0">
        @if($facility->cover)
            <img src="{{ $facility->cover }}" alt="{{ $facility->name }}" class="w-full h-full object-cover">
        @endif
        <div class="absolute inset-0 bg-gradient-to-r from-primary-900/80 to-primary-800/60"></div>
    </div>

    {{-- محتوى Hero --}}
    <div class="relative container mx-auto h-full flex items-center">
        <div class="text-white max-w-2xl">
            <h1 class="text-5xl font-bold mb-6">{{ $facility->name }}</h1>
            <p class="text-xl mb-8 text-gray-100">{{ $facility->description }}</p>
            
            {{-- معلومات الاتصال --}}
            <div class="flex items-center gap-6 text-lg">
                @if($facility->phone)
                    <div class="flex items-center gap-2">
                        <i class="fas fa-phone"></i>
                        <span>{{ $facility->phone }}</span>
                    </div>
                @endif
                
                @if($facility->address)
                    <div class="flex items-center gap-2">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>{{ $facility->address }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
