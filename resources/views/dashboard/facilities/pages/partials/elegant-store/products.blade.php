{{-- قسم المنتجات للمتجر الأنيق --}}
<section class="elegant-store-products py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">منتجاتنا المميزة</h2>
        
        @if($facility->products->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @foreach($facility->products as $product)
                    <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow overflow-hidden">
                        @if($product->images->first())
                            <div class="relative h-64">
                                <img src="{{ $product->images->first()->path }}" 
                                     alt="{{ $product->getTranslation('name', $locale) }}"
                                     class="w-full h-full object-cover">
                                @if($product->discount_price)
                                    <div class="absolute top-4 right-4 bg-red-500 text-white px-3 py-1 rounded-full text-sm">
                                        تخفيض
                                    </div>
                                @endif
                            </div>
                        @endif
                        
                        <div class="p-6">
                            <h3 class="text-xl font-semibold mb-2">{{ $product->getTranslation('name', $locale) }}</h3>
                            <p class="text-gray-600 mb-4 line-clamp-2">{{ $product->getTranslation('description', $locale) }}</p>
                            
                            <div class="flex items-center justify-between">
                                <div class="space-y-1">
                                    @if($product->discount_price)
                                        <div class="text-gray-400 line-through">{{ $product->price }} ريال</div>
                                        <div class="text-xl font-bold text-primary-600">{{ $product->discount_price }} ريال</div>
                                    @else
                                        <div class="text-xl font-bold text-primary-600">{{ $product->price }} ريال</div>
                                    @endif
                                </div>
                                
                                <button class="btn bg-primary-600 hover:bg-primary-700 text-white px-6 py-2 rounded-lg">
                                    أضف للسلة
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center text-gray-500">لا توجد منتجات متاحة حالياً</p>
        @endif
    </div>
</section>
