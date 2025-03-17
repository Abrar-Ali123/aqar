@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid">

        <!-- Start Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">إضافة خاصية جديدة</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.attributes.index') }}">الخصائص</a></li>
                            <li class="breadcrumb-item active">إضافة خاصية</li>
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
                        <h4 class="card-title mb-0">تفاصيل الخاصية</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.attributes.store') }}" method="POST">
                            @csrf

                            <div class="row gy-4">
                                <!-- بيانات الخاصية الأساسية -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name_ar" class="form-label">اسم الخاصية (عربي)</label>
                                        <input type="text" name="name_ar" class="form-control" id="name_ar" value="{{ old('name_ar') }}" required>
                                        @error('name_ar')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name_en" class="form-label">اسم الخاصية (إنجليزي)</label>
                                        <input type="text" name="name_en" class="form-control" id="name_en" value="{{ old('name_en') }}" required>
                                        @error('name_en')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="symbol_ar" class="form-label">الرمز (عربي)</label>
                                        <input type="text" name="symbol_ar" class="form-control" id="symbol_ar" value="{{ old('symbol_ar') }}">
                                        @error('symbol_ar')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="symbol_en" class="form-label">الرمز (إنجليزي)</label>
                                        <input type="text" name="symbol_en" class="form-control" id="symbol_en" value="{{ old('symbol_en') }}">
                                        @error('symbol_en')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="type" class="form-label">نوع الخاصية</label>
                                        <select name="type" class="form-select" id="type" required>
                                            <option value="">اختر النوع</option>
                                            <option value="text" {{ old('type') == 'text' ? 'selected' : '' }}>نص</option>
                                            <option value="number" {{ old('type') == 'number' ? 'selected' : '' }}>رقم</option>
                                            <option value="select" {{ old('type') == 'select' ? 'selected' : '' }}>قائمة منسدلة</option>
                                            <option value="checkbox" {{ old('type') == 'checkbox' ? 'selected' : '' }}>مربع اختيار</option>
                                            <option value="radio" {{ old('type') == 'radio' ? 'selected' : '' }}>زر راديو</option>
                                            <option value="date" {{ old('type') == 'date' ? 'selected' : '' }}>تاريخ</option>
                                            <option value="color" {{ old('type') == 'color' ? 'selected' : '' }}>لون</option>
                                        </select>
                                        @error('type')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="category_id" class="form-label">الفئة (اختياري)</label>
                                        <select name="category_id" class="form-select" id="category_id">
                                            <option value="">جميع الفئات</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->translations->where('locale', 'ar')->first()->name ?? 'بدون اسم' }}
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
                                        <label for="icon" class="form-label">أيقونة الخاصية (اختياري)</label>
                                        <input type="text" name="icon" class="form-control" id="icon" value="{{ old('icon') }}">
                                        <small class="text-muted">مثال: ri-home-line, fa fa-user</small>
                                        @error('icon')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="Symbol" class="form-label">رمز افتراضي (اختياري)</label>
                                        <input type="text" name="Symbol" class="form-control" id="Symbol" value="{{ old('Symbol') }}">
                                        <small class="text-muted">مثال: م², كجم</small>
                                        @error('Symbol')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" name="required" class="form-check-input" id="required" {{ old('required') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="required">مطلوب</label>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">حفظ الخاصية</button>
                                <a href="{{ route('admin.attributes.index') }}" class="btn btn-secondary">إلغاء</a>
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
    });
</script>
@endsection
