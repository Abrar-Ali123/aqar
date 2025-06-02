<meta name="csrf-token" content="{{ csrf_token() }}">

<div>

    <form id="loginForm" method="POST" action="{{ route('login.submit') }}">

        <select wire:model="countryCode" id="country_code">
            <option data-countryCode="GB" value="44">UK (+44)</option>
            <option data-countryCode="US" value="1">USA (+1)</option>
            <option data-countryCode="SA" value="966" Selected>Saudi Arabia (+966)</option>
            <!-- باقي الخيارات هنا -->
        </select>

        <input type="text" id="numbers" wire:model="phone_number" placeholder="Phone Number">
        @error('phone_number')
            <span>{{ $message }}</span>
        @enderror

        <input type="text" id="verificationCode" wire:model="verificationCode" placeholder="Verification Code">
        @error('verificationCode')
            <span>{{ $message }}</span>
        @enderror

        <button type="button" onclick="sendCode()">إرسال رمز التحقق</button>
        <button type="submit">تحقق</button>
    </form>

    <div id="sentMessage"></div>
    <div id="sentError"></div>

    @if ($withConfirmed != false)
        <div id="sentMessage">{{ $withConfirmed }}</div>
    @endif
    @if ($withError != false)
        <div id="error">{{ $withError }}</div>
    @endif

    <div id="registerForm" style="display: none;">
        <form id="nameForm" method="POST">
            <label>أدخل اسمك بلغات مختلفة:</label>
            @php
                $languages = \App\Models\Language::where('is_active', true)->orderBy('order')->get();
            @endphp
            @foreach ($languages as $language)
                <div>
                    <label for="name_{{ $language->code }}">{{ $language->name }}:</label>
                    <input type="text" class="user_names" id="name_{{ $language->code }}"
                        data-locale="{{ $language->code }}" wire:model="translations.{{ $language->code }}" required>
                    @error('translations.' . $language->code)
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            @endforeach

            <button type="button" wire:click="loginOrRegister">إرسال</button>
        </form>
    </div>



    <div id="recaptcha-container"></div>




    <script src="{{ asset('livewire/livewire.js') }}"></script>
    <script src="https://www.gstatic.com/firebasejs/8.0.0/firebase.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // إعدادات Firebase
        const firebaseConfig = {
            apiKey: "AIzaSyD5QbmwuyyM9ySCMiwFZ4haK1uRFniXUrY",
            authDomain: "real-estate-80e99.firebaseapp.com",
            projectId: "real-estate-80e99",
            storageBucket: "real-estate-80e99.appspot.com",
            messagingSenderId: "635269315499",
            appId: "1:635269315499:web:6c326defae137947d0f0ee",
            measurementId: "G-ZKJQVWNFPH"
        };

        // تهيئة Firebase
        firebase.initializeApp(firebaseConfig);

        // تهيئة reCAPTCHA عند تحميل الصفحة
        window.onload = function() {
            initializeRecaptcha();
        };

        function initializeRecaptcha() {
            window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container');
            recaptchaVerifier.render();
        }

        // دالة إرسال رمز التحقق
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

        // إعداد ترويسة CSRF لـ Ajax
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
                                    window.location.href = "/dashboard";
                                } else if (res.status === 'register') {
                                    $("#loginForm").hide();
                                    $("#registerForm").show();
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
    </script>


    @if (session()->has('message'))
        <div>{{ session('message') }}</div>
    @endif
    @if (session()->has('error'))
        <div>{{ session('error') }}</div>
    @endif
    @if (session()->has('success'))
        <div>{{ session('success') }}</div>
    @endif
</div>