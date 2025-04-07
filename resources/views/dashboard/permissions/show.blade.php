@extends('dashboard.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">تفاصيل الصلاحية</h5>
                        <div>
                            <a href="{{ route('admin.permissions.edit', $permission->id) }}" class="btn btn-primary">
                                <i class="ri-pencil-fill align-middle"></i> تعديل
                            </a>
                            <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">
                                <i class="ri-arrow-left-line align-middle"></i> رجوع
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">الاسم بالعربية</label>
                                <p>{{ $permission->translations->where('locale', 'ar')->first()->name ?? 'بدون اسم' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">الاسم بالإنجليزية</label>
                                <p>{{ $permission->translations->where('locale', 'en')->first()->name ?? 'بدون اسم' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">الصفحات</label>
                        <div class="row">
                            @php
                                $pages = json_decode($permission->pages, true) ?? [];
                            @endphp
                            @if(count($pages) > 0)
                                @foreach($pages as $page)
                                    <div class="col-md-3">
                                        <span class="badge bg-info">{{ $page }}</span>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-12">
                                    <p class="text-muted">لا توجد صفحات محددة</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">تاريخ الإنشاء</label>
                                <p>{{ $permission->created_at->format('Y-m-d H:i:s') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">تاريخ آخر تحديث</label>
                                <p>{{ $permission->updated_at->format('Y-m-d H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
