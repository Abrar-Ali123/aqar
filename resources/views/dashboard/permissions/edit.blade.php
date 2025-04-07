@extends('dashboard.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">تعديل الصلاحية</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.permissions.update', $permission->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name_ar" class="form-label">الاسم بالعربية</label>
                                    <input type="text" class="form-control @error('name_ar') is-invalid @enderror" id="name_ar" name="name_ar" value="{{ old('name_ar', $permission->translations->where('locale', 'ar')->first()->name ?? '') }}" required>
                                    @error('name_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name_en" class="form-label">الاسم بالإنجليزية</label>
                                    <input type="text" class="form-control @error('name_en') is-invalid @enderror" id="name_en" name="name_en" value="{{ old('name_en', $permission->translations->where('locale', 'en')->first()->name ?? '') }}" required>
                                    @error('name_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">الصفحات</label>
                            <div class="row">
                                @php
                                    $pages = [
                                        'dashboard' => 'لوحة التحكم',
                                        'users' => 'المستخدمين',
                                        'roles' => 'الأدوار',
                                        'permissions' => 'الصلاحيات',
                                        'categories' => 'التصنيفات',
                                        'products' => 'المنتجات',
                                        'features' => 'الميزات',
                                        'attributes' => 'الخصائص',
                                    ];
                                    $selectedPages = json_decode($permission->pages, true) ?? [];
                                @endphp
                                @foreach($pages as $key => $value)
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="pages[]" value="{{ $key }}" id="page_{{ $key }}" {{ in_array($key, $selectedPages) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="page_{{ $key }}">
                                                {{ $value }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('pages')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                            <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
