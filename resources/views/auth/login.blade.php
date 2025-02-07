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

</head>

<body>
    <section
        class="auth-page-wrapper py-5 position-relative bg-light d-flex align-items-center justify-content-center min-vh-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="row g-0 align-items-center">
                                <div class="col-xxl-12 mx-auto">
                                    <div class="card mb-0 border-0 shadow-none mb-0">
                                        <div class="card-body p-sm-5 m-lg-4">
                                            <div class="text-center mt-5">
                                                <h5 class="fs-3xl">مرحبًا بعودتك</h5>
                                                <p class="text-muted">تسجيل الدخول لمواصلة الي لوحة التحكم.</p>
                                            </div>
                                            <div class="p-2 mt-5">
                                                <form id="loginForm" method="POST"
                                                    action="{{ route('login.submit') }}">
                                                    @csrf
                                                    <div class="mb-3">
                                                        <div class="input-group">

                                                            <select id="country_code" class="form-control">
                                                                <option data-countryCode="GB" value="44">المملكة
                                                                    المتحدة (+44)
                                                                </option>
                                                                <option data-countryCode="US" value="1">الولايات
                                                                    المتحدة الأمريكية (+1)
                                                                </option>
                                                                <option data-countryCode="SA" value="966" Selected>
                                                                    المملكة العربية السعودية (+966)</option>
                                                            </select>

                                                        </div>
                                                    </div>

                                                    <div class="mb-3">

                                                        <div class="input-group">

                                                            <input type="text" class="form-control" id="numbers"
                                                                placeholder="رقم التليفون">
                                                        </div>
                                                    </div>


                                                    <div class="mb-3">

                                                        <div class="input-group">

                                                            <input type="text" class="form-control"
                                                                id="verificationCode" placeholder="رمز التحقق">
                                                        </div>

                                                    </div>

                                                    <div id="sentMessage"></div>
                                                    <div id="sentError"></div>


                                                    <div id="recaptcha-container"></div>


                                                    <div class="mt-2">
                                                        <button type="button" class="btn btn-primary w-100"
                                                            onclick="sendCode()">إرسال رمز
                                                            التحقق</button>
                                                    </div>

                                                    <div class="mt-2">

                                                        <button type="submit"
                                                            class="btn btn-primary w-100">تحقق</button>
                                                    </div>

                                                    <div class="mt-4 pt-2 text-center">
                                                        <div class="signin-other-title position-relative">
                                                            <h5 class="fs-sm mb-4 title">تسجيل الدخول مع</h5>
                                                        </div>
                                                        <div class="pt-2 hstack gap-2 justify-content-center">
                                                            <button type="button"
                                                                class="btn btn-subtle-primary btn-icon"><i
                                                                    class="ri-facebook-fill fs-lg"></i></button>
                                                            <button type="button"
                                                                class="btn btn-subtle-danger btn-icon"><i
                                                                    class="ri-google-fill fs-lg"></i></button>
                                                            <button type="button"
                                                                class="btn btn-subtle-dark btn-icon"><i
                                                                    class="ri-github-fill fs-lg"></i></button>
                                                            <button type="button"
                                                                class="btn btn-subtle-info btn-icon"><i
                                                                    class="ri-twitter-fill fs-lg"></i></button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <!-- end card body -->
                                    </div>
                                    <!-- end card -->
                                </div>
                            </div>
                            <!--end row-->
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
        const firebaseConfig = {
            apiKey: "AIzaSyD5QbmwuyyM9ySCMiwFZ4haK1uRFniXUrY",
            authDomain: "real-estate-80e99.firebaseapp.com",
            projectId: "real-estate-80e99",
            storageBucket: "real-estate-80e99.appspot.com",
            messagingSenderId: "635269315499",
            appId: "1:635269315499:web:6c326defae137947d0f0ee",
            measurementId: "G-ZKJQVWNFPH"
        };

        firebase.initializeApp(firebaseConfig);

        window.onload = function() {
            initializeRecaptcha();
        };

        function initializeRecaptcha() {
            window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container');
            recaptchaVerifier.render();
        }

        function sendCode() {
            const countryCode = $('#country_code').val();
            const phoneNumber = $('#numbers').val();
            const fullNumber = `+${countryCode}${phoneNumber}`;

            firebase.auth().signInWithPhoneNumber(fullNumber, window.recaptchaVerifier)
                .then(function(confirmationResult) {
                    window.confirmationResult = confirmationResult;
                    $('#sentMessage').text('Code sent successfully').show();
                })
                .catch(function(error) {
                    $('#sentError').text(error.message).show();
                });
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $("#loginForm").on("submit", function(e) {
            e.preventDefault();
            const code = $('#verificationCode').val();

            if (window.confirmationResult) {
                window.confirmationResult.confirm(code)
                    .then(function(result) {
                        $('#sentMessage').hide();
                        $('#sentError').hide();

                        $.ajax({
                            url: "{{ route('login.submit') }}",
                            method: "POST",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                phone_number: $('#numbers').val(),
                                countryCode: $('#country_code').val(),
                                verificationCode: code
                            },
                            success: function(res) {
                                if (res.status === 'done') {
                                    window.location.href = "/user/profile";
                                } else if (res.status === 'register') {
                                    window.location.href =
                                    "/register"; // تحويل المستخدم إلى صفحة التسجيل
                                } else {
                                    $('#sentError').text(res.message).show();
                                }
                            },
                            error: function(xhr) {
                                $('#sentError').text("An error occurred: " + xhr.responseText)
                                .show();
                            }
                        });
                    })
                    .catch(function(error) {
                        $('#sentError').text(error.message).show();
                    });
            } else {
                $('#sentError').text("Please send the verification code first.").show();
            }
        });

        function submitName() {
            let names = {};
            $('.user_names').each(function() {
                let locale = $(this).data('locale');
                let name = $(this).val();
                names[locale] = name;
            });

            $.ajax({
                url: "{{ route('login.loginOrRegister') }}",
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Content-Type': 'application/json'
                },
                data: JSON.stringify({
                    phone_number: $('#numbers').val(),
                    countryCode: $('#country_code').val(),
                    names: names
                }),
                success: function(res) {
                    if (res.status === 'registered') {
                        window.location.href = "/user/profile";
                    }
                },
                error: function(xhr) {
                    $('#sentError').text("حدث خطأ: " + xhr.responseText).show();
                }
            });
        }
    </script>


</body>

</html>
