@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid">

        <!-- Start Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">إضافة بنك جديد</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('banks.index') }}">البنوك</a></li>
                            <li class="breadcrumb-item active">إضافة بنك</li>
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
                        <h4 class="card-title mb-0">تفاصيل البنك</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('banks.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row gy-4">
                                <div class="col-md-12">
                                    <div>
                                        <label for="logo" class="form-label">شعار البنك</label>
                                        <input type="file" name="logo" class="form-control" id="logo"
                                            accept="image/*" required>
                                    </div>
                                </div>
                            </div>

                            <h5 class="mt-4">ترجمات الاسم</h5>
                            <div class="row gy-4">
                                <div class="col-md-6">
                                    <label for="name_ar" class="form-label">اسم البنك (العربية)</label>
                                    <input type="text" name="translations[ar][name]" class="form-control" id="name_ar"
                                        placeholder="أدخل اسم البنك باللغة العربية" required>
                                    <input type="hidden" name="translations[ar][locale]" value="ar">
                                    @error('translations.ar.name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="name_en" class="form-label">اسم البنك (الإنجليزية)</label>
                                    <input type="text" name="translations[en][name]" class="form-control" id="name_en"
                                        placeholder="Enter bank name in English" required>
                                    <input type="hidden" name="translations[en][locale]" value="en">
                                    @error('translations.en.name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">إضافة البنك</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
