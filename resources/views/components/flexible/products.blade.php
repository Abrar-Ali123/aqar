@props(['settings', 'content', 'facility'])

<div class="products-component">
    <div class="grid grid-cols-1 md:grid-cols-{{ $settings['layout'] === 'grid' ? '3' : '1' }} gap-6">
        @foreach($facility->products->take($settings['limit'] ?? 6) as $product)
            <div class="product-card bg-white rounded-lg shadow p-4 border transition-transform hover:scale-105">
                @if($product->images->count() > 0)
                    <img src="{{ Storage::url($product->images->first()->path) }}" 
                         alt="{{ $product->name }}"
                         class="w-full h-48 object-cover rounded-lg mb-4">
                @endif
                <h3 class="text-xl font-semibold mb-2">{{ $product->name }}</h3>
                <p class="text-gray-600 mb-4 line-clamp-2">{{ $product->description }}</p>
                <div class="flex justify-between items-center">
                    <span class="text-green-600 font-bold">{{ $product->price }} ريال</span>
                    <button class="btn btn-primary">عرض التفاصيل</button>
                </div>
            </div>
        @endforeach
    </div>

    @if($settings['filters'] ?? false)
        <div class="filters mt-6 flex gap-4 flex-wrap">
            @if(in_array('category', $settings['filters']))
                <select class="form-select" onchange="filterProducts(this.value, 'category')">
                    <option value="">جميع الفئات</option>
                    @foreach($facility->products->pluck('category')->unique() as $category)
                        <option value="{{ $category }}">{{ $category }}</option>
                    @endforeach
                </select>
            @endif

            @if(in_array('price', $settings['filters']))
                <select class="form-select" onchange="filterProducts(this.value, 'price')">
                    <option value="">السعر</option>
                    <option value="asc">الأقل سعراً</option>
                    <option value="desc">الأعلى سعراً</option>
                </select>
            @endif
        </div>
    @endif
</div>

@push('scripts')
<script>
function filterProducts(value, type) {
    // يمكن إضافة منطق التصفية هنا
    console.log(`Filtering by ${type}: ${value}`);
}
</script>
@endpush
