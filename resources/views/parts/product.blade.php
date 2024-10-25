<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">المنتجات</h2>
        <a href="{{ route('products.create') }}" class="px-4 py-2 bg-green-500 text-white font-bold rounded hover:bg-green-600 transition duration-200">إضافة منتج جديد</a>
    </div>

    <!-- Filters Section -->
    <form method="GET" action="{{ route('products.index') }}" class="mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <input type="text" name="search_term" placeholder="ابحث عن منتج" value="{{ request('search_term') }}" class="p-2 border rounded">

            <select name="category_id" class="p-2 border rounded">
                <option value="">اختر الفئة</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <select name="facility_id" class="p-2 border rounded">
                <option value="">اختر المنشأة</option>
                @foreach($facilities as $facility)
                    <option value="{{ $facility->id }}" {{ request('facility_id') == $facility->id ? 'selected' : '' }}>
                        {{ $facility->name }}
                    </option>
                @endforeach
            </select>

            <input type="number" name="min_price" placeholder="السعر الأدنى" value="{{ request('min_price') }}" class="p-2 border rounded">
            <input type="number" name="max_price" placeholder="السعر الأعلى" value="{{ request('max_price') }}" class="p-2 border rounded">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
            @foreach($attributes as $attribute)
                <div>
                    <label for="attribute_{{ $attribute->id }}" class="block text-gray-700">{{ $attribute->name }}</label>
                    <input type="text" name="attribute_{{ $attribute->id }}" id="attribute_{{ $attribute->id }}" class="p-2 border rounded" value="{{ request('attribute_'.$attribute->id) }}">
                </div>
            @endforeach
        </div>

        <button type="submit" class="mt-4 px-4 py-2 bg-blue-500 text-white font-bold rounded hover:bg-blue-600 transition duration-200">تصفية</button>
    </form>

    <!-- Products Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($products as $product)
            <div class="bg-white rounded-lg shadow-md p-4">
                <div class="mb-4">
                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover rounded">
                </div>
                <p>الفئة: {{ $product->category->name }}</p>

                <h3 class="text-lg font-bold text-gray-800 mb-2">{{ $product->name }}</h3>
                <p class="text-gray-600 mb-4">{{ $product->description }}</p>
                <p class="text-gray-800 font-bold mb-2">{{ $product->price }} ريال</p>
                <p class="text-gray-600 mb-4">الموقع: {{ $product->latitude }}, {{ $product->longitude }}</p>
                <p class="text-gray-600 mb-4">المنشأة: {{ $product->facility->name }}</p>
                 <p class="text-gray-600 mb-4">العنوان: {{ $product->google_maps_url }}</p>

                @if($product->video)
                    <div class="mb-4">
                        <video controls class="w-full">
                            <source src="{{ Storage::url($product->video) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                @endif

                @if($product->image_gallery)
                    <div class="mb-4 grid grid-cols-3 gap-2">
                        @foreach(explode(',', $product->image_gallery) as $galleryImage)
                            <img src="{{ Storage::url('products/image_gallery/'.$galleryImage) }}" alt="Gallery Image" class="w-full h-32 object-cover rounded">
                        @endforeach
                    </div>
                @endif

                <a href="" class="px-4 py-2 bg-blue-500 text-white font-bold rounded hover:bg-blue-600 transition duration-200">عرض التفاصيل</a>
            </div>
        @empty
            <p class="text-gray-600">لا توجد منتجات لعرضها.</p>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
     </div>
</div>
