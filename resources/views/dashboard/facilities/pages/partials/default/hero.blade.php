{{-- قسم Hero الافتراضي --}}
<section class="default-hero relative h-[60vh] overflow-hidden">
    {{-- صورة الخلفية --}}
    <div class="absolute inset-0">
        @if($facility->cover)
            <img src="{{ $facility->cover }}" alt="{{ $facility->name }}" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full bg-gradient-to-r from-primary-600 to-primary-400"></div>
        @endif
        <div class="absolute inset-0 bg-black/50"></div>
    </div>

    {{-- محتوى Hero --}}
    <div class="relative container mx-auto h-full flex items-center">
        <div class="text-white max-w-2xl">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ $facility->name }}</h1>
            @if($facility->rating)
                <div class="mb-4">
                    <x-rating-stars :rating="$facility->rating" />
                </div>
            @endif
            <p class="text-xl mb-8 text-gray-100">{{ $facility->description }}</p>
            
            {{-- معلومات الاتصال --}}
            <div class="flex items-center gap-6 text-lg">
                @if($facility->address)
                    <div class="flex items-center gap-2">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>{{ $facility->address }}</span>
                    </div>
                @endif
                
                @if($facility->phone)
                    <div class="flex items-center gap-2">
                        <i class="fas fa-phone"></i>
                        <span>{{ $facility->phone }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
