@php
    $locale = app()->getLocale();
    $facilities = \DB::table('facilities')
        ->join('facility_translations', 'facilities.id', '=', 'facility_translations.facility_id')
        ->where('facility_translations.locale', $locale)
        ->select('facilities.id', 'facility_translations.name')
        ->get();

    $banks = \DB::table('banks')
        ->join('bank_translations', 'banks.id', '=', 'bank_translations.bank_id')
        ->where('bank_translations.locale', $locale)
        ->select('banks.id', 'bank_translations.name')
        ->get();

@endphp

<!doctype html>
<html lang="ar" dir="rtl" data-layout="vertical" data-sidebar="dark" data-sidebar-size="lg" data-preloader="disable"
    data-bs-theme="light">

<head>

    <meta charset="utf-8">
    <title>Aqar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- App favicon -->
    <!-- Layout config Js -->
    <script src="{{ asset('dashboard') }}/js/layout.js"></script>
    <!-- Bootstrap Css -->
    <link href="{{ asset('dashboard') }}/css/bootstrap-rtl.min.css" rel="stylesheet" type="text/css">
    <!-- Icons Css -->
    <link href="{{ asset('dashboard') }}/css/icons.min.css" rel="stylesheet" type="text/css">
    <!-- App Css-->
    <link href="{{ asset('dashboard') }}/css/app-rtl.min.css" rel="stylesheet" type="text/css">
    <!-- custom Css-->
    <link href="{{ asset('dashboard') }}/css/custom.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css"
        integrity="sha512-kJlvECunwXftkPwyvHbclArO8wszgBGisiLeuDFwNM8ws+wKIw0sv1os3ClWZOcrEB2eRXULYUsm8OVRGJKwGA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .card-option {
            cursor: pointer;
            transition: transform 0.2s;
        }

        .card-option:hover {
            transform: scale(1.05);
        }

        .card-option.active {
            border: 2px solid #007bff;
            transform: scale(1.1);
        }


        .hidden {
            display: none;
        }
    </style>

</head>

<body>
    <section
        class="auth-page-wrapper py-5 position-relative bg-light d-flex align-items-center justify-content-center min-vh-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="row g-0 align-items-center">
                                <div class="col-xxl-12 mx-auto">
                                    <div class="card mb-0 border-0 shadow-none mb-0">
                                        <div class="card-body p-sm-5 m-lg-4">
                                            <div class="text-center mt-5">
                                                <h5 class="fs-3xl">اختر نوع التسجيل</h5>
                                                <p class="text-muted">تسجيل الدخول لمواصلة الي لوحة التحكم.</p>
                                            </div>
                                            <div class="container py-5">
                                                <div class="row g-4">
                                                    <div class="col-md-6 col-lg-3">
                                                        <div class="card card-option text-center" data-form="user-form">
                                                            <div class="card-body">
                                                                <h5 class="card-title">مستخدم عادي</h5>
                                                                <p class="card-text">سجل كمستخدم عادي</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-lg-3">
                                                        <div class="card card-option text-center"
                                                            data-form="company-form">
                                                            <div class="card-body">
                                                                <h5 class="card-title">منشأة</h5>
                                                                <p class="card-text">سجل كمنشأة</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-lg-3">
                                                        <div class="card card-option text-center"
                                                            data-form="employee-form">
                                                            <div class="card-body">
                                                                <h5 class="card-title">موظف داخل منشأة</h5>
                                                                <p class="card-text">سجل كموظف</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-lg-3">
                                                        <div class="card card-option text-center"
                                                            data-form="bank-employee-form">
                                                            <div class="card-body">
                                                                <h5 class="card-title">موظف بنك</h5>
                                                                <p class="card-text">سجل كموظف بنك</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Form تسجيل كمستخدم عادي -->
                                                <div id="user-form" class="hidden mt-4">
                                                    <h3>تسجيل كمستخدم عادي</h3>
                                                    <form action="{{ route('register') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="type" value="user">
                                                        <div class="mb-3">
                                                            <label class="form-label">الاسم</label>
                                                            <input type="text" name="name" class="form-control"
                                                                required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">البريد الإلكتروني</label>
                                                            <input type="email" name="email" class="form-control"
                                                                required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">رقم الهاتف</label>
                                                            <input type="tel" name="phone" class="form-control"
                                                                required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">كلمة المرور</label>
                                                            <input type="password" name="password"
                                                                class="form-control" required>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary">تسجيل</button>
                                                    </form>
                                                </div>

                                                <!-- Form تسجيل كمنشأة -->
                                                <div id="company-form" class="hidden mt-4">
                                                    <h3>تسجيل كمنشأة</h3>
                                                    <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <input type="hidden" name="type" value="organization">
                                                        <div class="mb-3">
                                                            <label class="form-label">اسم المنشأة</label>
                                                            <input type="text" name="facility_name" class="form-control"
                                                                required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">الشعار</label>
                                                            <input type="file" name="logo" class="form-control"
                                                                required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">الرخصة</label>
                                                            <input type="text" name="license" class="form-control"
                                                                required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">البريد الإلكتروني</label>
                                                            <input type="email" name="email" class="form-control"
                                                                required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">رقم الهاتف</label>
                                                            <input type="tel" name="phone" class="form-control"
                                                                required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">اسم المدير</label>
                                                            <input type="text" name="name"
                                                                class="form-control" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">كلمة المرور</label>
                                                            <input type="password" name="password"
                                                                class="form-control" required>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary">تسجيل</button>
                                                    </form>
                                                </div>
                                                <!-- Form تسجيل كموظف داخل منشأة -->
                                                <div id="employee-form" class="hidden mt-4">
                                                    <h3>تسجيل كموظف داخل منشأة</h3>
                                                    <form action="{{ route('register') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="type" value="employee">
                                                        <div class="mb-3">
                                                            <label class="form-label">الاسم</label>
                                                            <input type="text" name="name" class="form-control"
                                                                required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">البريد الإلكتروني</label>
                                                            <input type="email" name="email" class="form-control"
                                                                required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">رقم الهاتف</label>
                                                            <input type="tel" name="phone" class="form-control"
                                                                required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">اسم المنشأة</label>
                                                            <select name="organization_id" class="form-control"
                                                                required>
                                                                <option value="">اختر المنشأة</option>
                                                                @foreach ($facilities as $facility)
                                                                    <option value="{{ $facility->id }}">
                                                                        {{ $facility->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">كلمة المرور</label>
                                                            <input type="password" name="password"
                                                                class="form-control" required>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary">تسجيل</button>
                                                    </form>
                                                </div>

                                                <div id="bank-employee-form" class="hidden mt-4">
                                                    <h3>تسجيل كموظف بنك</h3>
                                                    <form action="{{ route('register') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="type" value="bank_employee">
                                                        <div class="mb-3">
                                                            <label class="form-label">الاسم</label>
                                                            <input type="text" name="name" class="form-control"
                                                                required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">البريد الإلكتروني</label>
                                                            <input type="email" name="email" class="form-control"
                                                                required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">رقم الهاتف</label>
                                                            <input type="tel" name="phone" class="form-control"
                                                                required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">اسم البنك</label>
                                                            <select name="bank_id" class="form-control" required>
                                                                <option value="">اختر البنك</option>
                                                                @foreach ($banks as $bank)
                                                                    <option value="{{ $bank->id }}">
                                                                        {{ $bank->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">كلمة المرور</label>
                                                            <input type="password" name="password"
                                                                class="form-control" required>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary">تسجيل</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </div>
        <!--end container-->
    </section>

    <!-- JAVASCRIPT -->
    <script src="{{ asset('dashboard') }}/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('dashboard') }}/libs/simplebar/simplebar.min.js"></script>
    <script src="{{ asset('dashboard') }}/js/plugins.js"></script>



    <script src="{{ asset('dashboard') }}/js/pages/password-addon.init.js"></script>

    <!--Swiper slider js-->
    <script src="{{ asset('dashboard') }}/libs/swiper/swiper-bundle.min.js"></script>

    <!-- swiper.init js -->
    <script src="{{ asset('dashboard') }}/js/pages/swiper.init.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.0.0/firebase.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const options = document.querySelectorAll('.card-option');
        const forms = document.querySelectorAll('.hidden');

        options.forEach(option => {
            option.addEventListener('click', () => {
                options.forEach(opt => opt.classList.remove('active'));
                option.classList.add('active');

                forms.forEach(form => form.classList.add('hidden'));

                const formId = option.getAttribute('data-form');
                document.getElementById(formId).classList.remove('hidden');
            });
        });
    </script>

</body>

</html>
