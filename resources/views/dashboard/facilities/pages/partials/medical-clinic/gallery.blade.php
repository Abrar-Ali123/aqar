{{-- معرض الصور للعيادة الطبية --}}
<section class="medical-clinic-gallery py-16 bg-blue-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">جولة في العيادة</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">تعرف على مرافقنا وتجهيزاتنا الطبية الحديثة</p>
        </div>
        
        @if($facility->images->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($facility->images as $image)
                    <div class="group relative overflow-hidden rounded-xl shadow-sm hover:shadow-md transition-shadow">
                        <div class="aspect-[4/3]">
                            <img src="{{ $image->path }}" 
                                 alt="صورة {{ $facility->name }}"
                                 class="w-full h-full object-cover">
                        </div>
                        
                        <div class="absolute inset-0 bg-blue-900/80 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <div class="text-white text-center p-6 transform translate-y-4 group-hover:translate-y-0 transition-transform">
                                <div class="w-12 h-12 mx-auto mb-4 rounded-full bg-white/20 flex items-center justify-center">
                                    <i class="fas fa-search-plus text-2xl"></i>
                                </div>
                                <div class="text-lg font-medium">{{ $image->caption ?? $facility->name }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="text-center mt-12">
                <button class="btn bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg inline-flex items-center">
                    <i class="fas fa-images mr-2"></i>
                    عرض جميع الصور
                </button>
            </div>
        @else
            <p class="text-center text-gray-500">لا توجد صور متاحة حالياً</p>
        @endif
    </div>
</section>
