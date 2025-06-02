@props(['facility'])

<div class="bg-white rounded-lg shadow-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">المنتجات</h2>
        
        {{-- Filter and Sort Options --}}
        <div class="flex space-x-4">
            <select class="form-select text-sm" x-model="sortBy">
                <option value="newest">الأحدث</option>
                <option value="price-asc">السعر: الأقل إلى الأعلى</option>
                <option value="price-desc">السعر: الأعلى إلى الأقل</option>
                <option value="name">الاسم</option>
            </select>
        </div>
    </div>

    @if($facility->products->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($facility->products as $product)
                <div class="bg-white rounded-lg shadow p-4 border hover:shadow-lg transition duration-300">
                    {{-- Product Image --}}
                    @if($product->images->count() > 0)
                        <div class="relative group">
                            <img src="{{ Storage::url($product->images->first()->path) }}" 
                                 alt="{{ $product->name }}"
                                 class="w-full h-48 object-cover rounded-lg mb-4">
                            
                            {{-- Image Gallery Overlay --}}
                            @if($product->images->count() > 1)
                                <div class="absolute inset-0 bg-black bg-opacity-50 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                    <button class="text-white px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700">
                                        عرض الصور ({{ $product->images->count() }})
                                    </button>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Product Info --}}
                    <div class="space-y-2">
                        <h3 class="text-xl font-semibold">{{ $product->name }}</h3>
                        <p class="text-gray-600 line-clamp-2">{{ $product->description }}</p>
                        <div class="flex justify-between items-center mt-4">
                            <span class="text-green-600 font-bold text-lg">{{ $product->price }} ريال</span>
                            
                            {{-- Action Buttons --}}
                            <div class="flex space-x-2">
                                @if($product->is_available)
                                    <button class="btn btn-primary btn-sm">
                                        <i class="fas fa-shopping-cart mr-1"></i>
                                        أضف للسلة
                                    </button>
                                @else
                                    <span class="text-red-500 text-sm">غير متوفر</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($facility->products->hasPages())
            <div class="mt-6">
                {{ $facility->products->links() }}
            </div>
        @endif
    @else
        <div class="text-center py-8">
            <i class="fas fa-box-open text-gray-400 text-5xl mb-4"></i>
            <p class="text-gray-600">لا توجد منتجات متاحة حالياً</p>
        </div>
    @endif
</div>
