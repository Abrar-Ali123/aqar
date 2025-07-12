{{-- قسم الخدمات الطبية للعيادة --}}
<section class="medical-clinic-services py-16">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">خدماتنا الطبية</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">نقدم مجموعة متكاملة من الخدمات الطبية عالية الجودة</p>
        </div>
        
        @if($facility->products->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($facility->products as $product)
                    <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow overflow-hidden border border-blue-100">
                        @if($product->images->first())
                            <div class="relative h-48">
                                <img src="{{ $product->images->first()->path }}" 
                                     alt="{{ $product->getTranslation('name', $locale) }}"
                                     class="w-full h-full object-cover">
                            </div>
                        @endif
                        
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">
                                {{ $product->getTranslation('name', $locale) }}
                            </h3>
                            <p class="text-gray-600 mb-4 line-clamp-3">
                                {{ $product->getTranslation('description', $locale) }}
                            </p>
                            
                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <div class="text-blue-600 font-bold">
                                    {{ $product->price }} ريال
                                </div>
                                
                                <a href="#book" class="btn bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                                    احجز موعد
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="text-center mt-12">
                <p class="text-gray-600 mb-4">هل لديك استفسارات حول خدماتنا؟</p>
                <a href="#contact" class="btn bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg inline-flex items-center">
                    <i class="fas fa-phone-alt mr-2"></i>
                    اتصل بنا
                </a>
            </div>
        @else
            <p class="text-center text-gray-500">لا توجد خدمات متاحة حالياً</p>
        @endif
    </div>
</section>
