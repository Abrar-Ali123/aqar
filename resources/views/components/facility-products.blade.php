@props(['facility'])

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    @foreach($facility->products as $product)
        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
            {{-- Product Image --}}
            <div class="relative h-48">
                <img src="{{ asset('images/products/default.jpg') }}" 
                     alt="{{ $product->translate(app()->getLocale())->name }}"
                     class="w-full h-full object-cover">
                
                @if($product->is_featured)
                    <div class="absolute top-2 right-2 bg-yellow-500 text-white px-2 py-1 rounded-full text-xs">
                        <i class="fas fa-star mr-1"></i>مميز
                    </div>
                @endif
            </div>

            {{-- Product Info --}}
            <div class="p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-800 line-clamp-1">
                        {{ $product->translate(app()->getLocale())->name }}
                    </h3>
                    @if($product->category)
                        <span class="text-xs text-gray-500">
                            {{ $product->category->translate(app()->getLocale())->name }}
                        </span>
                    @endif
                </div>

                <p class="text-gray-600 text-sm line-clamp-2">
                    {{ $product->translate(app()->getLocale())->description }}
                </p>

                <div class="flex justify-between items-center pt-3 border-t">
                    <div>
                        <span class="text-2xl font-bold text-primary">{{ number_format($product->price) }}</span>
                        <span class="text-gray-600 text-sm">ريال</span>
                    </div>
                    
                    <a href="{{ route('products.show', ['locale' => app()->getLocale(), 'product' => $product->id]) }}" 
                       class="btn btn-primary btn-sm">
                        <i class="fas fa-eye mr-1"></i>
                        عرض التفاصيل
                    </a>
                </div>

                @if($product->facility)
                    <div class="text-xs text-gray-500 flex items-center mt-2">
                        <i class="fas fa-building mr-1"></i>
                        {{ $product->facility->translate(app()->getLocale())->name }}
                    </div>
                @endif
            </div>
        </div>
    @endforeach
</div>

@if($facility->products->isEmpty())
    <div class="text-center py-8">
        <i class="fas fa-box-open text-gray-400 text-5xl mb-4"></i>
        <p class="text-gray-600">لا توجد منتجات متاحة حالياً</p>
    </div>
@endif
