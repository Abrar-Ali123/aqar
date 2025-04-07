@extends('dashboard.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">تفاصيل الدور</h5>
                        <div>
                            <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-primary">
                                <i class="ri-pencil-fill align-middle"></i> تعديل
                            </a>
                            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
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
                                <p>{{ $role->translations->where('locale', 'ar')->first()->name ?? 'بدون اسم' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">الاسم بالإنجليزية</label>
                                <p>{{ $role->translations->where('locale', 'en')->first()->name ?? 'بدون اسم' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">نوع الدور</label>
                                <p>
                                    @if($role->is_primary)
                                        <span class="badge bg-primary">دور أساسي</span>
                                    @else
                                        <span class="badge bg-secondary">دور عادي</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">حالة الدفع</label>
                                <p>
                                    @if($role->is_paid)
                                        <span class="badge bg-info">مدفوع</span>
                                        <span class="ms-2">{{ number_format($role->price, 2) }} ريال</span>
                                    @else
                                        <span class="badge bg-success">مجاني</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">الصلاحيات</label>
                        <div class="row">
                            @if($role->permissions->count() > 0)
                                @foreach($role->permissions as $permission)
                                    <div class="col-md-3">
                                        <div class="card border">
                                            <div class="card-body">
                                                <h6 class="card-title">{{ $permission->translations->where('locale', 'ar')->first()->name ?? 'بدون اسم' }}</h6>
                                                <div class="mt-2">
                                                    @php
                                                        $pages = json_decode($permission->pages, true) ?? [];
                                                    @endphp
                                                    @foreach($pages as $page)
                                                        <span class="badge bg-info">{{ $page }}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-12">
                                    <p class="text-muted">لا توجد صلاحيات محددة</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">تاريخ الإنشاء</label>
                                <p>{{ $role->created_at->format('Y-m-d H:i:s') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">تاريخ آخر تحديث</label>
                                <p>{{ $role->updated_at->format('Y-m-d H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
