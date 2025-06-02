<div style="max-width: 400px; margin: 50px auto; font-family: 'Segoe UI', sans-serif;">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 8px 10px;
            margin-top: 5px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        button {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        #sentMessage { color: green; margin-top: 10px; }
        #sentError { color: red; margin-top: 10px; }
    </style>

    <!-- نموذج إرسال رقم الجوال -->
    <form id="loginForm">
        <label>رمز الدولة</label>
        <select wire:model="countryCode" id="country_code">
            <option value="1">USA (+1)</option>
            <option value="44">UK (+44)</option>
            <option value="966" selected>Saudi Arabia (+966)</option>
        </select>

        <label>رقم الجوال</label>
        <input type="text" wire:model="phone_number" id="numbers" placeholder="Phone Number">
        @error('phone_number') <span style="color:red">{{ $message }}</span> @enderror

        <label>رمز التحقق</label>
        <input type="text" wire:model="verificationCode" id="verificationCode" placeholder="Verification Code">
        @error('verificationCode') <span style="color:red">{{ $message }}</span> @enderror

        <button type="button" onclick="sendCode()">إرسال رمز التحقق</button>
        <button type="submit">تحقق</button>
    </form>

    <div id="sentMessage"></div>
    <div id="sentError"></div>

    <!-- نموذج الاسم عند التسجيل -->
    @if ($showNameForm)
        <div>
            @if (session()->has('error'))
                <div style="color: red; margin-bottom: 10px;">
                    {{ session('error') }}
                </div>
            @endif

            <form wire:submit.prevent="loginOrRegister" id="nameForm">
                <input type="hidden" wire:model="firebase_uid">
                <h4 style="margin-top: 30px;">أدخل اسمك باللغات المطلوبة:</h4>
                <p style="color: #666; margin-bottom: 15px; font-size: 14px;">يمكنك إدخال اسمك باللغة التي تفضلها، لا يلزم ملء جميع اللغات.</p>
                @foreach (App\Models\Language::where('is_active', true)->get() as $language)
                    <div dir="{{ $language->direction }}">
                        <label>الاسم ({{ $language->name }}):</label>
                        <input type="text" wire:model.defer="translations.{{ $language->code }}.name">
                        @error("translations.{$language->code}.name") <span style="color:red">{{ $message }}</span> @enderror
                    </div>
                @endforeach
                <button type="submit">تسجيل</button>
            </form>
        </div>

        <script>
            document.addEventListener('livewire:load', function () {
                const uid = localStorage.getItem('firebase_uid');
                if (uid) {
                    @this.set('firebase_uid', uid);
                }
            });
        </script>
    @endif

    <!-- reCAPTCHA Container -->
    <div id="recaptcha-container"></div>

    <!-- Firebase -->
    <script src="https://www.gstatic.com/firebasejs/8.0.0/firebase.js"></script>
    <script>
        const firebaseConfig = {
    apiKey: "AIzaSyDb2LntV9hmd2xtHW2BRU5R-gD9KJBMlj4",
    authDomain: "real-estate-80e99.firebaseapp.com",
    projectId: "real-estate-80e99",
    storageBucket: "real-estate-80e99.firebasestorage.app",
    messagingSenderId: "635269315499",
    appId: "1:635269315499:web:61c818d6060ee8a0d0f0ee",
    measurementId: "G-37SGMPBWLL"
        };
        firebase.initializeApp(firebaseConfig);

        // reCAPTCHA إعداد
        window.onload = () => {
            window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container', {
                'size': 'normal',
                'callback': function(response) {
                    console.log("reCAPTCHA passed ✅");
                },
                'expired-callback': function() {
                    console.error("reCAPTCHA expired ❌");
                }
            });

            recaptchaVerifier.render().then(widgetId => {
                window.recaptchaWidgetId = widgetId;
            });
        };

        // إرسال رمز التحقق
        function sendCode() {
            const code = document.getElementById("country_code").value;
            const number = document.getElementById("numbers").value;
            const fullNumber = `+${code}${number}`;

            firebase.auth().signInWithPhoneNumber(fullNumber, window.recaptchaVerifier)
                .then(confirmationResult => {
                    window.confirmationResult = confirmationResult;
                    document.getElementById("sentMessage").innerText = "تم إرسال الرمز بنجاح";
                    document.getElementById("sentError").innerText = "";
                })
                .catch(error => {
                    document.getElementById("sentError").innerText = error.message;
                });
        }

        // التحقق من الرمز المرسل
        document.getElementById("loginForm").addEventListener("submit", function (e) {
            e.preventDefault();
            const verificationCode = document.getElementById("verificationCode").value;

            if (!window.confirmationResult) {
                document.getElementById("sentError").innerText = "أرسل الرمز أولاً";
                return;
            }

            confirmationResult.confirm(verificationCode).then((result) => {
                result.user.getIdToken().then(function(idToken) {
                    fetch('/api/firebase-login', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            firebase_token: idToken
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'register') {
                            localStorage.setItem('firebase_uid', data.uid);
                            @this.set('firebase_uid', data.uid);
                            Livewire.dispatch('showNameForm');
                        } else if (data.status === 'done') {
                            window.location.href = '/dashboard';
                        }
                    });
                });
            })
            .catch(error => {
                document.getElementById("sentError").innerText = error.message;
            });
        });
    </script>
</div>
