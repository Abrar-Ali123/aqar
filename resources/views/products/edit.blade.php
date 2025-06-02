@extends('dashboard.layouts.app1')

@section('content')

<div class="container">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">تعديل المنتج</h3>
        </div>
        
        <div class="card-body">
            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- نوع المنتج -->
                <div class="form-group mb-3">
                    <label for="type">نوع المنتج <span class="text-danger">*</span></label>
                    <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
                        <option value="">اختر النوع</option>
                        <option value="property" {{ old('type', $product->type) == 'property' ? 'selected' : '' }}>عقار</option>
                        <option value="service" {{ old('type', $product->type) == 'service' ? 'selected' : '' }}>خدمة</option>
                        <option value="product" {{ old('type', $product->type) == 'product' ? 'selected' : '' }}>منتج</option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- معلومات المنتج -->
                <div class="card mb-3">
                    <div class="card-header">
                        المعلومات الأساسية
                    </div>
                    <div class="card-body">
                        <x-translatable-field 
                            name="name" 
                            label="الاسم"
                            :languages="$languages"
                            :translations="$product->translations"
                            required
                        />

                        <x-translatable-field 
                            name="description" 
                            label="الوصف"
                            type="textarea"
                            :languages="$languages"
                            :translations="$product->translations"
                        />
                    </div>
                </div>

                <!-- معلومات إضافية -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="price">السعر <span class="text-danger">*</span></label>
                            <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $product->price) }}" required step="0.01">
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="category_id">الفئة <span class="text-danger">*</span></label>
                            <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                                <option value="">اختر الفئة</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="facility_id">المنشأة</label>
                            <select name="facility_id" id="facility_id" class="form-control @error('facility_id') is-invalid @enderror">
                                <option value="">اختر المنشأة</option>
                                @foreach($facilities as $facility)
                                    <option value="{{ $facility->id }}" {{ old('facility_id', $product->facility_id) == $facility->id ? 'selected' : '' }}>
                                        {{ $facility->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('facility_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="seller_user_id">البائع</label>
                            <select name="seller_user_id" id="seller_user_id" class="form-control @error('seller_user_id') is-invalid @enderror">
                                <option value="">اختر البائع</option>
                                @foreach($sellers as $seller)
                                    <option value="{{ $seller->id }}" {{ old('seller_user_id', $product->seller_user_id) == $seller->id ? 'selected' : '' }}>
                                        {{ $seller->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('seller_user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- الصور والوسائط -->
                <div class="card mb-3">
                    <div class="card-header">
                        الصور والوسائط
                    </div>
                    <div class="card-body">
                        <!-- عرض الصور الحالية -->
                        @if($product->images->count() > 0)
                            <div class="row mb-3">
                                @foreach($product->images as $image)
                                    <div class="col-md-3 mb-3">
                                        <div class="position-relative">
                                            <img src="{{ $image->url }}" class="img-thumbnail" alt="صورة المنتج">
                                            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 delete-image" data-image-id="{{ $image->id }}">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="form-group mb-3">
                            <label for="images">إضافة صور جديدة</label>
                            <input type="file" name="images[]" id="images" class="form-control @error('images.*') is-invalid @enderror" multiple accept="image/*">
                            <small class="form-text text-muted">يمكنك اختيار عدة صور في نفس الوقت</small>
                            @error('images.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- معلومات الموقع -->
                <div class="card mb-3" id="location-info" style="display: {{ $product->type === 'property' ? 'block' : 'none' }};">
                    <div class="card-header">
                        معلومات الموقع
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <x-translatable-field 
                                    name="address" 
                                    label="العنوان"
                                    :languages="$languages"
                                    :translations="$product->translations"
                                />
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="location">الموقع على الخريطة</label>
                                    <div id="map" style="height: 300px;"></div>
                                    <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $product->latitude) }}">
                                    <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $product->longitude) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- الخصائص -->
                <div class="card mb-3">
                    <div class="card-header">
                        الخصائص
                    </div>
                    <div class="card-body">
                        <div id="attributes-container">
                            @foreach($attributes as $attribute)
                                <div class="form-group mb-3 attribute-field" data-categories="{{ json_encode($attribute->categories->pluck('id')) }}" style="{{ $attribute->categories->contains($product->category_id) ? 'display: block;' : 'display: none;' }}">
                                    <label for="attribute_{{ $attribute->id }}">{{ $attribute->name }}</label>
                                    @switch($attribute->type)
                                        @case('text')
                                            <input type="text" name="attributes[{{ $attribute->id }}]" id="attribute_{{ $attribute->id }}" class="form-control" value="{{ $product->getAttributeValue($attribute->id) }}">
                                            @break
                                        @case('number')
                                            <input type="number" name="attributes[{{ $attribute->id }}]" id="attribute_{{ $attribute->id }}" class="form-control" value="{{ $product->getAttributeValue($attribute->id) }}">
                                            @break
                                        @case('boolean')
                                            <select name="attributes[{{ $attribute->id }}]" id="attribute_{{ $attribute->id }}" class="form-control">
                                                <option value="">اختر</option>
                                                <option value="1" {{ $product->getAttributeValue($attribute->id) === true ? 'selected' : '' }}>نعم</option>
                                                <option value="0" {{ $product->getAttributeValue($attribute->id) === false ? 'selected' : '' }}>لا</option>
                                            </select>
                                            @break
                                        @case('date')
                                            <input type="date" name="attributes[{{ $attribute->id }}]" id="attribute_{{ $attribute->id }}" class="form-control" value="{{ $product->getAttributeValue($attribute->id) }}">
                                            @break
                                    @endswitch
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="is_active" class="custom-control-input" id="is_active" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="is_active">تفعيل المنتج</label>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // إظهار/إخفاء حقول الموقع حسب نوع المنتج
    const typeSelect = document.getElementById('type');
    const locationInfo = document.getElementById('location-info');
    
    typeSelect.addEventListener('change', function() {
        locationInfo.style.display = this.value === 'property' ? 'block' : 'none';
    });

    // إظهار/إخفاء الخصائص حسب الفئة
    const categorySelect = document.getElementById('category_id');
    const attributeFields = document.querySelectorAll('.attribute-field');
    
    categorySelect.addEventListener('change', function() {
        const selectedCategoryId = parseInt(this.value);
        
        attributeFields.forEach(field => {
            const categories = JSON.parse(field.dataset.categories);
            field.style.display = categories.includes(selectedCategoryId) ? 'block' : 'none';
        });
    });

    // تهيئة الخريطة
    if (document.getElementById('map')) {
        const latitude = parseFloat(document.getElementById('latitude').value) || 24.7136;
        const longitude = parseFloat(document.getElementById('longitude').value) || 46.6753;
        
        const map = L.map('map').setView([latitude, longitude], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        let marker = L.marker([latitude, longitude]).addTo(map);
        
        map.on('click', function(e) {
            if (marker) {
                map.removeLayer(marker);
            }
            marker = L.marker(e.latlng).addTo(map);
            document.getElementById('latitude').value = e.latlng.lat;
            document.getElementById('longitude').value = e.latlng.lng;
        });
    }

    // حذف الصور
    document.querySelectorAll('.delete-image').forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('هل أنت متأكد من حذف هذه الصورة؟')) {
                const imageId = this.dataset.imageId;
                fetch(`/admin/products/images/${imageId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                }).then(response => {
                    if (response.ok) {
                        this.closest('.col-md-3').remove();
                    }
                });
            }
        });
    });
});
</script>
@endpush

@endsection
