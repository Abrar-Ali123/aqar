@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid">

        <!-- Start Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">تعديل الميزة</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.features.index') }}">الميزات</a></li>
                            <li class="breadcrumb-item active">تعديل الميزة</li>
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
                        <h4 class="card-title mb-0">تفاصيل الميزة</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.features.update', $feature->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row gy-4">
                                <!-- بيانات الميزة الأساسية -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name_ar" class="form-label">اسم الميزة (عربي)</label>
                                        <input type="text" name="name_ar" class="form-control" id="name_ar"
                                            value="{{ old('name_ar', $feature->translations->where('locale', 'ar')->first()->name ?? '') }}" required>
                                        @error('name_ar')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name_en" class="form-label">اسم الميزة (إنجليزي)</label>
                                        <input type="text" name="name_en" class="form-control" id="name_en"
                                            value="{{ old('name_en', $feature->translations->where('locale', 'en')->first()->name ?? '') }}" required>
                                        @error('name_en')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="icon" class="form-label">أيقونة الميزة (اختياري)</label>
                                        <input type="text" name="icon" class="form-control" id="icon" value="{{ old('icon', $feature->icon) }}">
                                        <small class="text-muted">مثال: ri-home-line, fa fa-user</small>
                                        @error('icon')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mt-2" id="icon-preview">
                                        @if($feature->icon)
                                            <i class="{{ $feature->icon }} fa-2x"></i>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">تحديث الميزة</button>
                                <a href="{{ route('admin.features.index') }}" class="btn btn-secondary">إلغاء</a>
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
        // معاينة الأيقونة
        $('#icon').on('input', function() {
            var iconClass = $(this).val();
            if (iconClass) {
                $('#icon-preview').html('<i class="' + iconClass + ' fa-2x"></i>');
            } else {
                $('#icon-preview').html('');
            }
        });
    });
</script>
@endsection
