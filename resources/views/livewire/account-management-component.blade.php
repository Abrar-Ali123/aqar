<div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-6">
                    <div class="authincation-content">
                        <div class="row no-gutters">
                            <div class="col-xl-12">
                                <div class="auth-form">
									<div class="text-center mb-3">
										<a href="index.html"><img  class="logo-light" src="images/logo-full.png" alt=""></a>
										<a href="index.html"><img  class="logo-dark" src="images/logo-white-full.png" alt=""></a>
									</div>
                                    <h4 class="text-center mb-4">Sign in your account</h4>


                                    {{ $step }}
    @if (!$isVerified)
        <form id="loginForm" wire:ignore>



                                        @csrf

<select wire:model="countryCode" id="country_code">
    <option data-countryCode="GB" value="44">UK (+44)</option>
    <option data-countryCode="US" value="1">USA (+1)</option>
    <option data-countryCode="SA" value="966" Selected>Saudi Arabia (+966)</option>

    <optgroup label="Other countries">

    </optgroup>
</select>
                                        <div class="mb-3">
                                            <label class="mb-1"><strong> </strong></label>

                                            <input type="text" id="numbers" class="form-control" wire:model="phone_number" placeholder="رقم الهاتف">

             @error('phone_number')
            <span>{{ $message }}</span>
            @enderror
                                        </div>


										<div class="mb-3 position-relative">

                                            <label class="mb-1"><strong></strong></label>


											<input type="text" id="verificationCode" class="form-control" wire:model="verificationCode"  placeholder="رمز التحقق ">

            @error('verificationCode')
            <span>{{ $message }}</span>
            @enderror
            <br>

            <div class="text-center">
                                            <button type="submit" onclick="sendCode()" class="btn btn-primary btn-block">  ارسال رمز التحقق</button>
                                        </div>
<br>
                                        <div class="text-center">
                                            <button type="submit"  class="btn btn-primary btn-block">   تحقق  </button>
                                        </div>
 											<span class="show-pass eye">

												<i class="fa fa-eye-slash"></i>
												<i class="fa fa-eye"></i>

											</span>
                                        </div>

                                    </form>
                                    <div id="sentMessage"></div>
        <div id="sentError"></div>
        @if ($withConfirmed != false)
            <div id="sentMessage">
                {{ $withConfirmed }}
            </div>
        @endif
        @if ($withError != false)
            <div id="error">
                {{ $withError }}
            </div>
        @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>






        <form id="registerForm" style="display:none">

            register form
        </form>
        <div id="recaptcha-container"></div>
    @else
        @if ($step === 1)
             <form wire:submit.prevent="validateStep1" wire:key="step1">


                <input type="text" wire:model="email" placeholder="Email">
                @error('email')
                <span>{{ $message }}</span>
                @enderror

                <input type="password" wire:model="password" placeholder="Password">
                @error('password')
                <span>{{ $message }}</span>
                @enderror

                <!-- زر "التالي" للانتقال للخطوة التالية -->
                <button type="button" wire:click="nextStep">Next</button>
            </form>
        @elseif ($step === 2)
            <!-- خطوة 2: مجموعة حقول لمعلومات الاتصال -->
            <form wire:submit.prevent="validateStep2" wire:key="step2">
                <input type="text" wire:model="phone_number" placeholder="Phone Number">
                @error('phone_number')
                <span>{{ $message }}</span>
                @enderror

                <input type="text" wire:model="verificationCode" placeholder="Verification Code">
                @error('verificationCode')
                <span>{{ $message }}</span>
                @enderror

                <!-- زر "التالي" للانتقال للخطوة التالية أو "الرجوع" للعودة للخطوة السابقة -->
                <button type="button" wire:click="prevStep">Back</button>
                <button type="button" wire:click="nextStep">Next</button>
            </form>
        @elseif ($step === 3)
            <!-- خطوة 3: مجموعة حقول للمعلومات الإضافية -->
            <form wire:submit.prevent="registerUser" wire:key="step3">
                <!-- حقول المعلومات الإضافية -->
                <input type="text" wire:model="avatar" placeholder="Avatar">
                @error('avatar')
                <span>{{ $message }}</span>
                @enderror

                <input type="text" wire:model="bank_account" placeholder="Bank Account">
                @error('bank_account')
                <span>{{ $message }}</span>
                @enderror

                <!-- وهكذا، قم بإضافة جميع الحقول الموجودة في دالة loginOrRegister -->

                <!-- زر "التالي" لإرسال النموذج -->
                <button type="button" wire:click="prevStep">Back</button>
                <button type="submit">Submit</button>
            </form>
        @endif
    @endif

    <script src="{{ asset('livewire/livewire.js') }}"></script> <!-- تأكد من أن المسار صحيح -->

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
    </script>
    <script type="text/javascript">
        window.onload = function () {
            render();
        }

        function render() {
            this.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container');
            recaptchaVerifier.render();
        }

        function sendCode() {
            var countryCode = $('#country_code').val(); // الحصول على رمز الدولة المحدد
            var phoneNumber = $('#numbers').val(); // الحصول على رقم الهاتف

            // دمج رمز الدولة مع رقم الهاتف
            var number = '+' + countryCode + phoneNumber;
            console.log(number);

            // استخدام رقم الهاتف المدمج لإرسال رمز التحقق
            firebase.auth().signInWithPhoneNumber(number, window.recaptchaVerifier).then(function (confirmationResult) {
                window.confirmationResult = confirmationResult;
                coderesult = confirmationResult;
                $('#sentMessage').text('Code sent successfully');
                $('#sentMessage').show();
            }).catch(function (error) {
                $('#sentError').text(error.message);
                $('#sentError').show();
            });
        }

        $("#loginForm").on("submit", function (e) {
            e.preventDefault();
            var code = document.getElementById('verificationCode').value;

            coderesult.confirm(code).then(function (result) {
                var user = result.user;
                $('#sentMessage').hide();
                $('#sentError').hide();


                const idToken = await user.getIdToken();

                // إرسال رمز التحقق إلى الخادم
                $.ajax({
                    url: "{{ route('login.submit') }}",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        phone_number: $('#numbers').val(),
                        countryCode: $('#country_code').val(),
                        verificationCode: code,
                        idToken: idToken
                    },
                    success: function (res) {
                        if (res.status === 'done') {
                            window.location.href = "/dashboard";
                        } else if (res.status === 'register') {
                            $("#loginForm").hide();
                            $("#registerForm").show();
                        } else {
                            $('#sentError').text(res.message);
                            $('#sentError').show();
                        }
                    }
                });
            }).catch(function (error) {
                $('#sentError').text(error.message);
                $('#sentError').show();
            });
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


</div>






