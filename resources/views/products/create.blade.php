@extends('dashboard.layouts.app1')
@section('content')

<div class="container">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">إضافة منتج جديد</h3>
        </div>
        
        <div class="card-body">
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- نوع المنتج -->
                <div class="form-group mb-3">
                    <label for="type">نوع المنتج <span class="text-danger">*</span></label>
                    <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
                        <option value="">اختر النوع</option>
                        <option value="property" {{ old('type') == 'property' ? 'selected' : '' }}>عقار</option>
                        <option value="service" {{ old('type') == 'service' ? 'selected' : '' }}>خدمة</option>
                        <option value="product" {{ old('type') == 'product' ? 'selected' : '' }}>منتج</option>
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
                            required
                        />

                        <x-translatable-field 
                            name="description" 
                            label="الوصف"
                            type="textarea"
                            :languages="$languages"
                        />
                    </div>
                </div>

                <!-- معلومات إضافية -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="price">السعر <span class="text-danger">*</span></label>
                            <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" required step="0.01">
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
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                                    <option value="{{ $facility->id }}" {{ old('facility_id') == $facility->id ? 'selected' : '' }}>
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
                                    <option value="{{ $seller->id }}" {{ old('seller_user_id') == $seller->id ? 'selected' : '' }}>
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
                        <div class="form-group mb-3">
                            <label for="images">رفع صور المنتج</label>
                            <input type="file" name="images[]" id="images" class="form-control @error('images') is-invalid @enderror" accept="image/*" multiple>
                            <div id="image-preview" class="row mt-2"></div>
                            @error('images')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="digital_files">رفع ملفات رقمية (PDF, ZIP, DOCX...)</label>
                            <input type="file" name="digital_files[]" id="digital_files" class="form-control @error('digital_files') is-invalid @enderror" multiple>
                            <div id="file-preview" class="row mt-2"></div>
                            @error('digital_files')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- معلومات الموقع -->
                <div class="card mb-3" id="location-info" style="display: none;">
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
                                />
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="location">الموقع على الخريطة</label>
                                    <div id="map" style="height: 300px;"></div>
                                    <input type="hidden" name="latitude" id="latitude">
                                    <input type="hidden" name="longitude" id="longitude">
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
                                <div class="form-group mb-3 attribute-field" data-categories="{{ json_encode($attribute->categories->pluck('id')) }}">
                                    <label for="attribute_{{ $attribute->id }}">{{ $attribute->name }}</label>
                                    @switch($attribute->type)
                                        @case('text')
                                            <input type="text" name="attributes[{{ $attribute->id }}]" id="attribute_{{ $attribute->id }}" class="form-control">
                                            @break
                                        @case('number')
                                            <input type="number" name="attributes[{{ $attribute->id }}]" id="attribute_{{ $attribute->id }}" class="form-control">
                                            @break
                                        @case('boolean')
                                            <select name="attributes[{{ $attribute->id }}]" id="attribute_{{ $attribute->id }}" class="form-control">
                                                <option value="">اختر</option>
                                                <option value="1">نعم</option>
                                                <option value="0">لا</option>
                                            </select>
                                            @break
                                        @case('date')
                                            <input type="date" name="attributes[{{ $attribute->id }}]" id="attribute_{{ $attribute->id }}" class="form-control">
                                            @break
                                        @case('select')
                                            <select name="attributes[{{ $attribute->id }}]" id="attribute_{{ $attribute->id }}" class="form-control">
                                                <option value="">اختر</option>
                                                @foreach($attribute->options as $option)
                                                    <option value="{{ $option->id }}">{{ $option->name }}</option>
                                                @endforeach
                                            </select>
                                            @break
                                        @case('multiselect')
                                            <select name="attributes[{{ $attribute->id }}][]" id="attribute_{{ $attribute->id }}" class="form-control" multiple>
                                                @foreach($attribute->options as $option)
                                                    <option value="{{ $option->id }}">{{ $option->name }}</option>
                                                @endforeach
                                            </select>
                                            @break
                                    @endswitch
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="is_active" class="custom-control-input" id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="is_active">تفعيل المنتج</label>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">حفظ</button>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // الصور
    const imagesInput = document.getElementById('images');
    const imagePreview = document.getElementById('image-preview');
    if (imagesInput) {
        imagesInput.addEventListener('change', function(event) {
            imagePreview.innerHTML = '';
            Array.from(event.target.files).forEach((file, idx) => {
                if (!file.type.startsWith('image/')) return;
                const reader = new FileReader();
                reader.onload = function(e) {
                    const col = document.createElement('div');
                    col.className = 'col-md-3 mb-2';
                    col.innerHTML = `<div class='position-relative'><img src='${e.target.result}' class='img-thumbnail' style='height:120px;object-fit:cover;'><button type='button' class='btn btn-danger btn-sm position-absolute' style='top:2px;right:2px;' onclick='removeImage(${idx})'>&times;</button></div>`;
                    imagePreview.appendChild(col);
                };
                reader.readAsDataURL(file);
            });
        });
    }
    window.removeImage = function(idx) {
        let dt = new DataTransfer();
        Array.from(imagesInput.files).forEach((file, i) => {
            if (i !== idx) dt.items.add(file);
        });
        imagesInput.files = dt.files;
        imagesInput.dispatchEvent(new Event('change'));
    }

    // الملفات الرقمية
    const filesInput = document.getElementById('digital_files');
    const filePreview = document.getElementById('file-preview');
    if (filesInput) {
        filesInput.addEventListener('change', function(event) {
            filePreview.innerHTML = '';
            Array.from(event.target.files).forEach((file, idx) => {
                const col = document.createElement('div');
                col.className = 'col-md-4 mb-2';
                col.innerHTML = `<div class='border p-2 d-flex align-items-center'><i class='fas fa-file mr-2'></i> ${file.name} <button type='button' class='btn btn-danger btn-sm ml-auto' onclick='removeFile(${idx})'>&times;</button></div>`;
                filePreview.appendChild(col);
            });
        });
    }
    window.removeFile = function(idx) {
        let dt = new DataTransfer();
        Array.from(filesInput.files).forEach((file, i) => {
            if (i !== idx) dt.items.add(file);
        });
        filesInput.files = dt.files;
        filesInput.dispatchEvent(new Event('change'));
    }

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

    // السمات الديناميكية
    function updateAttributesVisibility() {
        const selectedCategoryId = parseInt(categorySelect.value);
        attributeFields.forEach(field => {
            const categories = JSON.parse(field.dataset.categories);
            field.style.display = categories.includes(selectedCategoryId) ? 'block' : 'none';
        });
    }
    if (categorySelect) {
        categorySelect.addEventListener('change', updateAttributesVisibility);
        updateAttributesVisibility();
    }

    // التحقق من السمات قبل الإرسال
    document.querySelector('form').addEventListener('submit', function(e) {
        let valid = true;
        attributeFields.forEach(field => {
            if (field.style.display !== 'none') {
                const input = field.querySelector('input,select,textarea');
                if (input && input.hasAttribute('required') && !input.value) {
                    input.classList.add('is-invalid');
                    valid = false;
                } else if (input) {
                    input.classList.remove('is-invalid');
                }
            }
        });
        if (!valid) {
            e.preventDefault();
        }
    });

    // تهيئة الخريطة
    if (document.getElementById('map')) {
        const map = L.map('map').setView([24.7136, 46.6753], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        let marker;
        map.on('click', function(e) {
            if (marker) {
                map.removeLayer(marker);
            }
            marker = L.marker(e.latlng).addTo(map);
            document.getElementById('latitude').value = e.latlng.lat;
            document.getElementById('longitude').value = e.latlng.lng;
        });
    }
});
</script>
@endpush

@endsection
