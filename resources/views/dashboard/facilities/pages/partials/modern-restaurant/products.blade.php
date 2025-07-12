{{-- قسم قائمة الطعام للمطعم --}}
<section class="modern-restaurant-menu py-20 bg-gray-900">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-white mb-4" style="font-family: var(--heading-font)">قائمة الطعام</h2>
            <div class="w-24 h-1 bg-accent-color mx-auto"></div>
        </div>
        
        @if($facility->products->count() > 0)
            {{-- تصنيف المنتجات حسب الفئة --}}
            @php
                $categories = $facility->products->groupBy('category.name');
            @endphp
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
                @foreach($categories as $category => $products)
                    <div class="space-y-12">
                        <h3 class="text-2xl font-semibold text-white mb-8 text-center">{{ $category }}</h3>
                        
                        @foreach($products as $product)
                            <div class="flex items-start gap-6 group">
                                @if($product->images->first())
                                    <div class="w-24 h-24 flex-shrink-0">
                                        <img src="{{ $product->images->first()->path }}" 
                                             alt="{{ $product->getTranslation('name', $locale) }}"
                                             class="w-full h-full object-cover rounded-lg">
                                    </div>
                                @endif
                                
                                <div class="flex-grow">
                                    <div class="flex items-center justify-between gap-4">
                                        <h4 class="text-xl font-medium text-white group-hover:text-accent-color transition-colors">
                                            {{ $product->getTranslation('name', $locale) }}
                                        </h4>
                                        <div class="border-b border-dotted border-gray-600 flex-grow"></div>
                                        <div class="text-xl font-bold text-accent-color">
                                            {{ $product->price }} ريال
                                        </div>
                                    </div>
                                    
                                    <p class="text-gray-400 mt-2 line-clamp-2">
                                        {{ $product->getTranslation('description', $locale) }}
                                    </p>
                                    
                                    @if($product->attributes)
                                        <div class="flex items-center gap-4 mt-3">
                                            @foreach($product->attributes as $attribute)
                                                <span class="text-sm text-gray-500">
                                                    <i class="fas {{ $attribute->icon }} text-accent-color mr-1"></i>
                                                    {{ $attribute->value }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
            
            <div class="text-center mt-16">
                <a href="#book" class="btn bg-accent-color hover:opacity-90 text-white px-12 py-4 rounded-none text-lg inline-block">
                    احجز طاولة الآن
                </a>
            </div>
        @else
            <p class="text-center text-gray-400">قائمة الطعام غير متوفرة حالياً</p>
        @endif
    </div>
</section>
