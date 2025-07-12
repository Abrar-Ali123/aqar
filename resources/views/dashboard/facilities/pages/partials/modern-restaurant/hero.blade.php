{{-- قسم Hero للمطعم العصري --}}
<section class="modern-restaurant-hero">
    {{-- القسم العلوي --}}
    <div class="relative h-screen">
        {{-- خلفية متحركة --}}
        <div class="absolute inset-0 bg-black">
            @if($facility->cover)
                <img src="{{ $facility->cover }}" alt="{{ $facility->name }}" 
                     class="w-full h-full object-cover opacity-70">
            @endif
        </div>
        
        {{-- المحتوى الرئيسي --}}
        <div class="relative h-full flex items-center justify-center text-center text-white">
            <div class="max-w-4xl mx-auto px-6">
                {{-- شعار المطعم --}}
                @if($facility->logo)
                    <img src="{{ $facility->logo }}" alt="Logo" class="w-32 h-32 mx-auto mb-8">
                @endif
                
                {{-- العنوان الرئيسي --}}
                <h1 class="text-6xl font-bold mb-6" style="font-family: var(--heading-font)">
                    {{ $facility->name }}
                </h1>
                
                {{-- الوصف --}}
                <p class="text-xl mb-12 text-gray-300">{{ $facility->description }}</p>
                
                {{-- أزرار العمل --}}
                <div class="flex flex-wrap justify-center gap-6">
                    @if($facility->hasMenu())
                        <a href="#menu" class="btn bg-accent-color hover:opacity-90 text-white px-8 py-4 rounded-none text-lg">
                            استعرض القائمة
                        </a>
                    @endif
                    
                    @if($facility->hasBooking())
                        <a href="#book" class="btn border-2 border-white hover:bg-white hover:text-black transition-colors px-8 py-4 rounded-none text-lg">
                            احجز طاولة
                        </a>
                    @endif
                </div>
            </div>
        </div>
        
        {{-- معلومات الاتصال --}}
        <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-75 text-white py-6">
            <div class="container mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                    @if($facility->phone)
                        <div>
                            <div class="text-accent-color mb-2">
                                <i class="fas fa-phone text-2xl"></i>
                            </div>
                            <div class="font-medium">{{ $facility->phone }}</div>
                            <div class="text-sm text-gray-400">اتصل بنا للحجز</div>
                        </div>
                    @endif
                    
                    @if($facility->address)
                        <div>
                            <div class="text-accent-color mb-2">
                                <i class="fas fa-map-marker-alt text-2xl"></i>
                            </div>
                            <div class="font-medium">{{ $facility->address }}</div>
                            <div class="text-sm text-gray-400">موقعنا</div>
                        </div>
                    @endif
                    
                    @if($facility->working_hours)
                        <div>
                            <div class="text-accent-color mb-2">
                                <i class="fas fa-clock text-2xl"></i>
                            </div>
                            <div class="font-medium">{{ $facility->working_hours['weekdays'] ?? '10:00 AM - 10:00 PM' }}</div>
                            <div class="text-sm text-gray-400">ساعات العمل</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
