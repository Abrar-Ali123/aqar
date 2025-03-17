@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">إعدادات الملف الشخصي</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">المستخدم</a></li>
                            <li class="breadcrumb-item active">إعدادات الملف الشخصي</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="card">
            <div class="profile-foreground position-relative">
                <div class="profile-wid-bg position-static">

                </div>
                <div class="bg-overlay bg-primary bg-opacity-75 card-img-top"></div>
            </div>

            <div class="card-body mt-n5">
                <div class="position-relative mt-n3">
                    <div class="avatar-lg position-relative">
                        <img src="{{ asset('dashboard') }}/images/users/avatar-4.jpg" alt="user-img"
                            class="img-thumbnail rounded-circle user-profile-image" style="z-index: 1;">
                        <div class="avatar-xs p-0 rounded-circle profile-photo-edit position-absolute end-0 bottom-0">
                            <input id="profile-img-file-input" type="file" class="profile-img-file-input d-none">
                            <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                <span class="avatar-title rounded-circle bg-light text-body">
                                    <i class="bi bi-camera"></i>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <div class="mt-3">
                        <h3 class="fs-xl mb-1 text-center">{{ auth()->user()->name }}</h3>


                        @if (auth()->user()->bank_id != null)
                            <p class="fs-md text-muted mb-0">موظف بنك</p>

                            @elseif(auth()->user()->facility_id != null)

                            <p class="fs-md text-muted mb-0">تابع لمنشأة</p>
                            @else
                            <p class="fs-md text-muted mb-0">مسؤل عام</p>

                        @endif


                    </div>

                    <div class="">

                        <a href="#" class="btn btn-primary"><i
                                class="ri-edit-box-line align-bottom"></i> تحرير الملف الشخصي</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-3">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4 pb-2">
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-0">أكمل ملف التعريف الخاص بك</h5>
                            </div>
                            <div class="flex-shrink-0">
                                <a href="javascript:void(0);" class="badge bg-light text-secondary"><i
                                        class="ri-edit-box-line align-bottom me-1"></i> يحرر</a>
                            </div>
                        </div>
                        <div class="progress animated-progress custom-progress progress-label">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 30%" aria-valuenow="30"
                                aria-valuemin="0" aria-valuemax="100">
                                <div class="label">30%</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-0">مَلَفّ</h5>
                            </div>
                            <div class="flex-shrink-0">
                                <a href="javascript:void(0);" class="badge bg-info-subtle text-info fs-12"><i
                                        class="ri-add-fill align-bottom me-1"></i> يضيف</a>
                            </div>
                        </div>
                        <div class="mb-3 d-flex">
                            <div class="avatar-xs d-block flex-shrink-0 me-3">
                                <span class="avatar-title rounded-circle bg-dark-subtle text-body">
                                    <i class="bi bi-github"></i>
                                </span>
                            </div>
                            <input type="email" class="form-control" id="gitUsername" placeholder="Username"
                                value="@alexandramarshall">
                        </div>
                        <div class="mb-3 d-flex">
                            <div class="avatar-xs d-block flex-shrink-0 me-3">
                                <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                    <i class="bi bi-facebook"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" id="websiteInput" placeholder="www.example.com"
                                value="www.vixon.com">
                        </div>
                        <div class="mb-3 d-flex">
                            <div class="avatar-xs d-block flex-shrink-0 me-3">
                                <span class="avatar-title rounded-circle bg-success-subtle text-success">
                                    <i class="bi bi-dribbble"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" id="dribbleName" placeholder="Username"
                                value="@alexandra_marshall">
                        </div>
                        <div class="d-flex">
                            <div class="avatar-xs d-block flex-shrink-0 me-3">
                                <span class="avatar-title rounded-circle bg-danger-subtle text-danger">
                                    <i class="bi bi-instagram"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" id="pinterestName" placeholder="Username"
                                value="Alexandra Marshall">
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class=" ">
                            <div class="d-flex align-items-center mb-3 pb-1">
                                <div class="flex-grow-1">
                                    <h5 class="card-title mb-0">مهارات</h5>
                                </div>
                                <div class="flex-shrink-0">
                                    <a href="javascript:void(0);" class="badge bg-light text-secondary"><i
                                            class="ri-edit-box-line align-bottom me-1"></i> يحرر</a>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-2 fs-15">
                                <a href="javascript:void(0);" class="badge text-primary bg-primary-subtle">فوتوشوب</a>
                                <a href="javascript:void(0);" class="badge text-primary bg-primary-subtle">الرسام</a>
                                <a href="javascript:void(0);" class="badge text-primary bg-primary-subtle">html</a>
                                <a href="javascript:void(0);" class="badge text-primary bg-primary-subtle">css</a>
                                <a href="javascript:void(0);" class="badge text-primary bg-primary-subtle">جافا سكريبت</a>
                                <a href="javascript:void(0);" class="badge text-primary bg-primary-subtle">PHP</a>
                                <a href="javascript:void(0);" class="badge text-primary bg-primary-subtle">بيثون</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end col-->
            <div class="col-xl-9">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-pills nav-custom-outline nav-info gap-2 flex-grow-1 mb-0" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link fs-md active" data-bs-toggle="tab" href="#personalDetails"
                                    role="tab" aria-selected="true">
                                    التفاصيل الشخصية
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link fs-md" data-bs-toggle="tab" href="#changePassword" role="tab"
                                    aria-selected="false" tabindex="-1">
                                    يغير كلمة المرور
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card">
                    <div class="tab-content">

                        <div class="tab-pane active" id="personalDetails" role="tabpanel">
                            <div class="card-header">
                                <h6 class="card-title mb-0">التفاصيل الشخصية</h6>
                            </div>
                            <div class="card-body">
                                @if(session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                @if(session('error'))
                                    <div class="alert alert-danger">
                                        {{ session('error') }}
                                    </div>
                                @endif

                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="nameInput" class="form-label fs-md">الاسم</label>
                                                <input type="text" class="form-control" id="nameInput" name="name"
                                                    value="{{ auth()->user()->name }}" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="emailInput" class="form-label fs-md">البريد الإلكتروني</label>
                                                <input type="email" class="form-control" id="emailInput" name="email"
                                                    value="{{ auth()->user()->email }}" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="phoneInput" class="form-label fs-md">رقم الهاتف</label>
                                                <input type="text" class="form-control" id="phoneInput" name="phone_number"
                                                    value="{{ auth()->user()->phone_number }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="whatsappInput" class="form-label fs-md">رقم الواتساب</label>
                                                <input type="text" class="form-control" id="whatsappInput" name="whatsapp_number"
                                                    value="{{ auth()->user()->whatsapp_number }}">
                                            </div>
                                        </div>
                                        
                                        <!-- Social Media Links -->
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="facebookInput" class="form-label fs-md">فيسبوك</label>
                                                <input type="url" class="form-control" id="facebookInput" name="facebook"
                                                    value="{{ auth()->user()->facebook }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="twitterInput" class="form-label fs-md">تويتر</label>
                                                <input type="url" class="form-control" id="twitterInput" name="twitter"
                                                    value="{{ auth()->user()->twitter }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="instagramInput" class="form-label fs-md">انستغرام</label>
                                                <input type="url" class="form-control" id="instagramInput" name="instagram"
                                                    value="{{ auth()->user()->instagram }}">
                                            </div>
                                        </div>
                                        
                                        <!-- Location Information -->
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="locationInput" class="form-label fs-md">رابط خرائط جوجل</label>
                                                <input type="url" class="form-control" id="locationInput" name="google_maps_url"
                                                    value="{{ auth()->user()->google_maps_url }}">
                                            </div>
                                        </div>
                                        
                                        <!-- Bank Information if user has bank_id -->
                                        @if(auth()->user()->bank_id)
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="bankAccountInput" class="form-label fs-md">رقم الحساب البنكي</label>
                                                <input type="text" class="form-control" id="bankAccountInput" name="bank_account"
                                                    value="{{ auth()->user()->bank_account }}">
                                            </div>
                                        </div>
                                        @endif
                                        
                                        <!-- Avatar Upload -->
                                        <div class="col-lg-12">
                                            <div class="mb-3">
                                                <label for="avatarInput" class="form-label fs-md">الصورة الشخصية</label>
                                                <input type="file" class="form-control" id="avatarInput" name="avatar">
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-12">
                                            <div class="hstack gap-2 justify-content-end">
                                                <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!--end tab-pane-->
                        <div class="tab-pane" id="changePassword" role="tabpanel">
                            <div class="card-header">
                                <h6 class="card-title mb-0">يغير كلمة المرور</h6>
                            </div>
                            <div class="card-body">
                                <form action="pages-profile-settings.html">
                                    <div class="row g-2 justify-content-lg-between align-items-center">
                                        <div class="col-lg-4">
                                            <div class="auth-pass-inputgroup">
                                                <label for="oldpasswordInput" class="form-label fs-md">قديم
كلمة المرور*</label>
                                                <div class="position-relative">
                                                    <input type="password" class="form-control  password-input fs-md"
                                                        id="oldpasswordInput" placeholder="Enter current password">
                                                    <button
                                                        class="btn btn-link shadow-none position-absolute top-0 end-0 text-decoration-none text-muted password-addon"
                                                        type="button"><i class="ri-eye-fill align-middle"></i></button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="auth-pass-inputgroup">
                                                <label for="password-input" class="form-label fs-md">جديد
كلمة المرور*</label>
                                                <div class="position-relative">
                                                    <input type="password" class="form-control password-input fs-md"
                                                        id="password-input" onpaste="return false"
                                                        placeholder="Enter new password" aria-describedby="passwordInput"
                                                        pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required>
                                                    <button
                                                        class="btn btn-link shadow-none position-absolute end-0 top-0 text-decoration-none text-muted password-addon"
                                                        type="button"><i class="ri-eye-fill align-middle"></i></button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="auth-pass-inputgroup">
                                                <label for="confirm-password-input" class="form-label fs-md">يتأكد
كلمة المرور*</label>
                                                <div class="position-relative">
                                                    <input type="password" class="form-control password-input fs-md"
                                                        onpaste="return false" id="confirm-password-input"
                                                        placeholder="Confirm password"
                                                        pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required>
                                                    <button
                                                        class="btn btn-link shadow-none position-absolute end-0 top-0 text-decoration-none text-muted password-addon"
                                                        type="button"><i class="ri-eye-fill align-middle"></i></button>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <a href="javascript:void(0);"
                                                class="link-primary text-decoration-underline fs-md">نسيت
كلمة المرور ?</a>
                                            <div class="">

                                                <button type="submit" class="btn btn-info">يتغير
كلمة المرور</button>
                                            </div>
                                        </div>

                                        <!--end col-->                                    </div>
                                    <!--end row-->
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </div>
@endsection
