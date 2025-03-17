@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid">

        <!-- Start Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">إضافة منتج جديد</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">المنتجات</a></li>
                            <li class="breadcrumb-item active">إضافة منتج</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Page Title -->

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">تفاصيل المنتج</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row gy-4">
                                <!-- بيانات المنتج الأساسية -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name_ar" class="form-label">اسم المنتج (عربي)</label>
                                        <input type="text" name="name_ar" class="form-control" id="name_ar"
                                            value="{{ old('name_ar') }}" required>
                                        @error('name_ar')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name_en" class="form-label">اسم المنتج (إنجليزي)</label>
                                        <input type="text" name="name_en" class="form-control" id="name_en"
                                            value="{{ old('name_en') }}" required>
                                        @error('name_en')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="description_ar" class="form-label">وصف المنتج (عربي)</label>
                                        <textarea name="description_ar" class="form-control" id="description_ar" rows="4" required>{{ old('description_ar') }}</textarea>
                                        @error('description_ar')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="description_en" class="form-label">وصف المنتج (إنجليزي)</label>
                                        <textarea name="description_en" class="form-control" id="description_en" rows="4" required>{{ old('description_en') }}</textarea>
                                        @error('description_en')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="price" class="form-label">السعر</label>
                                        <input type="number" name="price" class="form-control" id="price"
                                            value="{{ old('price') }}" step="0.01" required>
                                        @error('price')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="category_id" class="form-label">التصنيف</label>
                                        <select name="category_id" class="form-select" id="category_id" required>
                                            <option value="">اختر التصنيف</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="facility_id" class="form-label">المنشأة</label>
                                        <select name="facility_id" class="form-select" id="facility_id">
                                            <option value="">اختر المنشأة</option>
                                            @foreach ($facilities as $facility)
                                                <option value="{{ $facility->id }}"
                                                    {{ old('facility_id') == $facility->id ? 'selected' : '' }}>
                                                    {{ $facility->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('facility_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="property_type" class="form-label">نوع العقار</label>
                                        <select name="property_type" class="form-select" id="property_type" required>
                                            <option value="">اختر نوع العقار</option>
                                            <option value="apt" {{ old('property_type') == 'apt' ? 'selected' : '' }}>شقة</option>
                                            <option value="vil" {{ old('property_type') == 'vil' ? 'selected' : '' }}>فيلا</option>
                                            <option value="land" {{ old('property_type') == 'land' ? 'selected' : '' }}>أرض</option>
                                            <option value="com" {{ old('property_type') == 'com' ? 'selected' : '' }}>تجاري</option>
                                        </select>
                                        @error('property_type')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- الصور والفيديو -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="image" class="form-label">الصورة الرئيسية</label>
                                        <input type="file" name="image" class="form-control" id="image"
                                            accept="image/*" required>
                                        @error('image')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="video" class="form-label">فيديو (اختياري)</label>
                                        <input type="file" name="video" class="form-control" id="video"
                                            accept="video/*">
                                        @error('video')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="image_gallery" class="form-label">معرض الصور (اختياري)</label>
                                        <input type="file" name="image_gallery[]" class="form-control"
                                            id="image_gallery" accept="image/*" multiple>
                                        @error('image_gallery.*')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- الموقع -->
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="latitude" class="form-label">خط العرض (اختياري)</label>
                                        <input type="text" name="latitude" class="form-control" id="latitude"
                                            value="{{ old('latitude') }}">
                                        @error('latitude')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="longitude" class="form-label">خط الطول (اختياري)</label>
                                        <input type="text" name="longitude" class="form-control" id="longitude"
                                            value="{{ old('longitude') }}">
                                        @error('longitude')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="google_maps_url" class="form-label">رابط خرائط جوجل (اختياري)</label>
                                        <input type="url" name="google_maps_url" class="form-control"
                                            id="google_maps_url" value="{{ old('google_maps_url') }}">
                                        @error('google_maps_url')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- الحالة -->
                                <div class="col-md-12">
                                    <div class="mb-3 form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                            {{ old('is_active') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">تفعيل المنتج</label>
                                    </div>
                                </div>

                                <!-- الخصائص -->
                                <div class="col-md-12">
                                    <h5 class="mt-3 mb-3">خصائص المنتج</h5>
                                    <div class="row">
                                        @foreach ($attributes as $attribute)
                                        <div class="col-md-3 mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="attributes[]"
                                                    value="{{ $attribute->id }}" id="attribute_{{ $attribute->id }}"
                                                    {{ is_array(old('attributes')) && in_array($attribute->id, old('attributes')) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="attribute_{{ $attribute->id }}">
                                                    @if ($attribute->icon)
                                                        <i class="{{ $attribute->icon }}"></i>
                                                    @endif
                                                    {{ $attribute->name }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                    </div>
                                </div>

                                <!-- الميزات -->
                                <div class="col-md-12">
                                    <h5 class="mt-3 mb-3">ميزات المنتج</h5>
                                    <div class="row">
                                        @foreach ($features as $feature)
                                            <div class="col-md-3 mb-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="features[]"
                                                        value="{{ $feature->id }}" id="feature_{{ $feature->id }}"
                                                        {{ is_array(old('features')) && in_array($feature->id, old('features')) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="feature_{{ $feature->id }}">
                                                        @if ($feature->icon)
                                                            <i class="{{ $feature->icon }}"></i>
                                                        @endif
                                                        {{ $feature->name }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">حفظ المنتج</button>
                                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">إلغاء</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // يمكن إضافة أي سكريبت خاص بالصفحة هنا
            // مثل معاينة الصور قبل الرفع أو التحقق من الحقول

            // معاينة الصورة الرئيسية قبل الرفع
            $('#image').change(function() {
                const file = this.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(event) {
                        $('#image-preview').attr('src', event.target.result);
                        $('#image-preview').removeClass('d-none');
                    }
                    reader.readAsDataURL(file);
                }
            });

            // تحديث الخصائص بناءً على التصنيف المختار
            $('#category_id').change(function() {
                // يمكن إضافة طلب AJAX لجلب الخصائص المرتبطة بالتصنيف المحدد
                // وتحديث قسم الخصائص ديناميكيًا
            });
        });
    </script>
@endsection
