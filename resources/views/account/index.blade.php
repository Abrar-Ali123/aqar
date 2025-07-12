<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>تسجيل الدخول برقم الجوال</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Firebase (modular version) -->
  <script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/9.6.1/firebase-app.js";
    import { getAuth, RecaptchaVerifier, signInWithPhoneNumber } from "https://www.gstatic.com/firebasejs/9.6.1/firebase-auth.js";

    const firebaseConfig = {
      apiKey: "AIzaSyB1CZ32CfLBmutetlgTWByDmT3bkB03Sqs",
      authDomain: "aqar-dff1f.firebaseapp.com",
      projectId: "aqar-dff1f",
      storageBucket: "aqar-dff1f.appspot.com",
      messagingSenderId: "226306931247",
      appId: "1:226306931247:web:abeb29234ba55eeafaa4da"
    };

    const app = initializeApp(firebaseConfig);
    const auth = getAuth(app);

    let recaptchaVerifier;

    window.onload = () => {
      recaptchaVerifier = new RecaptchaVerifier('recaptcha-container', {
        size: 'normal',
        callback: () => {
          document.getElementById("captcha-message").textContent = "✔ تم التحقق من أنك لست روبوتًا.";
          document.getElementById("sendBtn").disabled = false;
        },
        'expired-callback': () => {
          document.getElementById("captcha-message").textContent = "⚠️ انتهى التحقق، الرجاء المحاولة مجددًا.";
          document.getElementById("sendBtn").disabled = true;
        }
      }, auth);
      recaptchaVerifier.render();
    };

    window.sendCode = () => {
      const phone = document.getElementById('phone').value.trim();
      const fullPhone = '+966' + phone;

      if (!/^5\d{8}$/.test(phone)) {
        document.getElementById("status-message").textContent = "⚠️ يرجى إدخال رقم جوال سعودي صحيح.";
        return;
      }

      signInWithPhoneNumber(auth, fullPhone, recaptchaVerifier)
  .then(result => {
    window.confirmationResult = result; // احتفظ بالنتيجة لإعادة الاستخدام في صفحة التحقق
    document.getElementById("status-message").textContent = "✅ تم إرسال رمز التحقق بنجاح.";

    // إظهار قسم إدخال الرمز (OTP)
    document.getElementById("otp-section").style.display = "block";
  })
  .catch(error => {
    document.getElementById("status-message").textContent = "❌ حدث خطأ: " + error.message;
  });

    };
  </script>

  <!-- Google reCAPTCHA (مطلوب من Firebase) -->
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;800&display=swap" rel="stylesheet">

  <!-- Toastify CSS -->
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

  <style>
    body {
      font-family: 'Cairo', sans-serif;
      background: linear-gradient(to top left, #f0f0f0, #e0e0e0);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .box {
      background: #fff;
      padding: 2rem;
      border-radius: 1rem;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 420px;
    }
    h2 {
      margin-bottom: 1rem;
      text-align: center;
      font-weight: 800;
      color: #333;
    }
    label {
      font-weight: 600;
      margin-bottom: 0.5rem;
      display: block;
    }
    .phone-input {
      display: flex;
      align-items: center;
      margin-bottom: 1rem;
    }
    .phone-input span {
      background: #eee;
      padding: 0.75rem;
      border-top-right-radius: 5px;
      border-bottom-right-radius: 5px;
    }
    .phone-input input {
      flex: 1;
      padding: 0.75rem;
      font-size: 16px;
      border: 1px solid #ccc;
      border-right: none;
      border-top-left-radius: 5px;
      border-bottom-left-radius: 5px;
    }
    input, button {
      width: 100%;
      padding: 0.75rem;
      margin: 0.5rem 0;
      font-size: 16px;
      border-radius: 5px;
      border: 1px solid #ddd;
    }
    button {
      background-color: #007bff;
      color: white;
      border: none;
      font-weight: 600;
      transition: background 0.3s;
    }
    button:hover:not(:disabled) {
      background-color: #0056b3;
    }
    button:disabled {
      background-color: #ccc;
      color: #888;
      cursor: not-allowed;
    }
    .status, .captcha-message {
      font-size: 14px;
      text-align: center;
      margin-top: 5px;
    }
    .captcha-message { color: green; }
    .status { color: #333; }
  </style>
</head>
<body>
<div class="box">
  <h2>تسجيل الدخول</h2>

  <label>رقم الجوال</label>
  <div class="phone-input">
    <span>+966</span>
    <input type="text" id="phone" placeholder="5xxxxxxxx" maxlength="9">
  </div>

  <div id="recaptcha-container"></div>
  <div class="captcha-message" id="captcha-message">يرجى التحقق من أنك لست روبوتًا</div>

  <button id="sendBtn" disabled onclick="sendCode()">إرسال رمز التحقق</button>

  <div class="status" id="status-message"></div>

  {{-- هنا فقط تستدعي صفحة التحقق --}}
  <div id="otp-section" style="display: none;">
    @include('account.otp_section')
  </div>
</div>
<!-- Toastify JS -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
</body>
</html>
