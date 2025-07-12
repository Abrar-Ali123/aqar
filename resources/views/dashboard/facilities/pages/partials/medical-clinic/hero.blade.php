{{-- قسم Hero للعيادة الطبية --}}
<section class="medical-clinic-hero relative min-h-[60vh] bg-blue-50">
    <div class="container mx-auto py-16">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            {{-- معلومات العيادة --}}
            <div class="space-y-6">
                <div class="inline-block px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                    {{ $facility->businessCategory->name ?? 'عيادة طبية' }}
                </div>
                
                <h1 class="text-4xl font-bold text-gray-900">{{ $facility->name }}</h1>
                <p class="text-xl text-gray-600">{{ $facility->description }}</p>
                
                {{-- معلومات الاتصال --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4">
                    @if($facility->phone)
                        <div class="flex items-center gap-3 bg-white p-4 rounded-lg shadow-sm">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500">اتصل بنا</div>
                                <div class="font-medium">{{ $facility->phone }}</div>
                            </div>
                        </div>
                    @endif
                    
                    @if($facility->address)
                        <div class="flex items-center gap-3 bg-white p-4 rounded-lg shadow-sm">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500">موقعنا</div>
                                <div class="font-medium">{{ $facility->address }}</div>
                            </div>
                        </div>
                    @endif
                </div>
                
                {{-- أزرار العمل --}}
                <div class="flex gap-4 pt-4">
                    @if($facility->hasBooking())
                        <a href="#book" class="btn bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">
                            احجز موعد
                        </a>
                    @endif
                    <a href="#contact" class="btn border-2 border-blue-600 text-blue-600 hover:bg-blue-50 px-6 py-3 rounded-lg">
                        تواصل معنا
                    </a>
                </div>
            </div>
            
            {{-- صورة العيادة --}}
            <div class="relative">
                @if($facility->cover)
                    <div class="relative z-10">
                        <img src="{{ $facility->cover }}" alt="{{ $facility->name }}" 
                             class="w-full h-[500px] object-cover rounded-2xl shadow-lg">
                    </div>
                    <div class="absolute -top-4 -right-4 w-full h-full bg-blue-200 rounded-2xl"></div>
                @endif
            </div>
        </div>
    </div>
</section>
