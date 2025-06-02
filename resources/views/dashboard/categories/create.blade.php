@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid">

        <!-- Start Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">إضافة فئة جديدة</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">الفئات</a></li>
                            <li class="breadcrumb-item active">إضافة فئة</li>
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
                        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row gy-4">
                                <!-- بيانات الفئة الأساسية -->
                                <div class="col-md-6">
                                    <x-translatable-field name="name" label="اسم الفئة" :languages="config('app.locales')" required placeholder="اسم الفئة" />
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="parent_id" class="form-label">الفئة الأب (اختياري)</label>
                                        <select name="parent_id" class="form-select" id="parent_id">
                                            <option value="">فئة رئيسية</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('parent_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->translations->where('locale', 'ar')->first()->name ?? 'بدون اسم' }}
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
                                        <input type="file" name="image" class="form-control" id="image"
                                            accept="image/*">
                                        @error('image')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mt-2">
                                        <img id="image-preview" src="#" alt="معاينة الصورة"
                                            class="img-thumbnail d-none" style="max-height: 200px;">
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">حفظ الفئة</button>
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
        });
    </script>
@endsection
