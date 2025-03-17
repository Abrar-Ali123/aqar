@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid">

        <!-- Start Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">تفاصيل الميزة</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.features.index') }}">الميزات</a></li>
                            <li class="breadcrumb-item active">تفاصيل الميزة</li>
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
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">معلومات الميزة</h4>
                            <div>
                                <a href="{{ route('admin.features.edit', $feature->id) }}" class="btn btn-primary btn-sm">
                                    <i class="ri-pencil-line"></i> تعديل
                                </a>
                                <a href="{{ route('admin.features.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="ri-arrow-go-back-line"></i> العودة
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h5>الاسم (عربي)</h5>
                                    <p>{{ $feature->translations->where('locale', 'ar')->first()->name ?? 'غير متوفر' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h5>الاسم (إنجليزي)</h5>
                                    <p>{{ $feature->translations->where('locale', 'en')->first()->name ?? 'غير متوفر' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h5>الأيقونة</h5>
                                    @if($feature->icon)
                                        <p><i class="{{ $feature->icon }} me-2"></i> {{ $feature->icon }}</p>
                                    @else
                                        <p>لا توجد أيقونة</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h5>تاريخ الإنشاء</h5>
                                    <p>{{ $feature->created_at->format('Y-m-d H:i:s') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- المنتجات المرتبطة بالميزة -->
        <div class="row mt-4">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">المنتجات المرتبطة</h4>
                    </div>
                    <div class="card-body">
                        @if($feature->productFeatures->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>اسم المنتج</th>
                                            <th>الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($feature->productFeatures as $index => $productFeature)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    @if($productFeature->product)
                                                        {{ $productFeature->product->translations->where('locale', 'ar')->first()->name ?? 'بدون اسم' }}
                                                    @else
                                                        <span class="text-muted">منتج غير موجود</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($productFeature->product)
                                                        <a href="{{ route('admin.products.show', $productFeature->product->id) }}" class="btn btn-sm btn-info">
                                                            <i class="ri-eye-line"></i> عرض
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                لا توجد منتجات مرتبطة بهذه الميزة.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
