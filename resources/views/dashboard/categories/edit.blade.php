@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid">

        <!-- Start Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">تعديل الفئة</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">الفئات</a></li>
                            <li class="breadcrumb-item active">تعديل الفئة</li>
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
                        <h4 class="card-title mb-0">تفاصيل الفئة</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row gy-4">
                                <!-- بيانات الفئة الأساسية -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name_ar" class="form-label">اسم الفئة (عربي)</label>
                                        <input type="text" name="name_ar" class="form-control" id="name_ar"
                                            value="{{ old('name_ar', $category->translations->where('locale', 'ar')->first()->name ?? '') }}" required>
                                        @error('name_ar')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name_en" class="form-label">اسم الفئة (إنجليزي)</label>
                                        <input type="text" name="name_en" class="form-control" id="name_en"
                                            value="{{ old('name_en', $category->translations->where('locale', 'en')->first()->name ?? '') }}" required>
                                        @error('name_en')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="parent_id" class="form-label">الفئة الأب (اختياري)</label>
                                        <select name="parent_id" class="form-select" id="parent_id">
                                            <option value="">فئة رئيسية</option>
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}" {{ old('parent_id', $category->parent_id) == $cat->id ? 'selected' : '' }}>
                                                    {{ $cat->translations->where('locale', 'ar')->first()->name ?? 'بدون اسم' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('parent_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="image" class="form-label">صورة الفئة (اختياري)</label>
                                        @if($category->image)
                                            <div class="mb-2">
                                                <img src="{{ asset('storage/' . $category->image) }}" alt="صورة الفئة" class="img-thumbnail" style="max-height: 150px;">
                                                <div class="mt-1">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="delete_image" id="delete_image">
                                                        <label class="form-check-label text-danger" for="delete_image">
                                                            حذف الصورة الحالية
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <input type="file" name="image" class="form-control" id="image" accept="image/*">
                                        <small class="text-muted">اترك هذا الحقل فارغًا إذا كنت لا تريد تغيير الصورة</small>
                                        @error('image')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mt-2">
                                        <img id="image-preview" src="#" alt="معاينة الصورة" class="img-thumbnail d-none" style="max-height: 200px;">
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">تحديث الفئة</button>
                                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">إلغاء</a>
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
        // معاينة الصورة قبل الرفع
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

        // تعطيل حقل الصورة عند اختيار حذف الصورة الحالية
        $('#delete_image').change(function() {
            if ($(this).is(':checked')) {
                $('#image').prop('disabled', true);
            } else {
                $('#image').prop('disabled', false);
            }
        });
    });
</script>
@endsection
