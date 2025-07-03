<div>
  <label>رمز التحقق</label>
  <input type="text" id="otp" placeholder="أدخل الرمز هنا" maxlength="6">
  <button id="verifyBtn">تحقق من الرمز</button>

  <div id="register-form" style="display:none;">
    @include('account.register_form')
  </div>

  <script>
    const registerFormDiv = document.getElementById('register-form');
    const verifyBtn = document.getElementById('verifyBtn');

    // 1. التحقق من الرمز
    verifyBtn.addEventListener('click', () => {
      const code = document.getElementById('otp').value.trim();
      if (!code) return alert("الرجاء إدخال الرمز");
      if (!window.confirmationResult) return alert("اطلب رمز أولاً");

      window.confirmationResult.confirm(code).then(result => {
        const user = result.user;
        const phone = '+966' + document.getElementById('phone').value.trim();

        // إرسال طلب للتحقق من وجود المستخدم أو الحاجة للتسجيل
        fetch('/authenticate-or-register', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify({ firebase_uid: user.uid, phone_number: phone })
        })
        .then(res => res.json())
        .then(data => {
          if (data.status === 'need_register') {
            // تحديث قيم الحقول المخفية الموجودة مسبقًا
            document.getElementById('reg_firebase_uid').value = user.uid;
            document.getElementById('reg_phone_number').value = phone;
            registerFormDiv.style.display = 'block'; // إظهار فورم التسجيل
          } else if (data.status === 'logged_in') {
            alert(data.message);
            window.location.href = '/dashboard';
          } else {
            alert(data.message || 'حدث خطأ غير متوقع');
          }
        })
        .catch(() => alert('حدث خطأ أثناء الاتصال بالخادم للتحقق من الرمز.'));

      }).catch(() => alert("رمز التحقق الذي أدخلته غير صحيح."));
    });

    // 2. إرسال فورم التسجيل
    registerFormDiv.querySelector('form').addEventListener('submit', (e) => {
      e.preventDefault();
      const form = e.target;

      // قراءة البيانات يدويًا من الفورم لضمان الدقة المطلقة
      const data = {
        name: document.getElementById('reg_name').value,
        type: document.getElementById('reg_type').value,
        firebase_uid: document.getElementById('reg_firebase_uid').value,
        phone_number: document.getElementById('reg_phone_number').value,
        locale: '{{ app()->getLocale() }}'
      };



      fetch('/authenticate-or-register', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Accept': 'application/json',
        },
        body: JSON.stringify(data)
      })
      .then(async res => {
        const responseJson = await res.json();
        if (!res.ok) throw responseJson;
        return responseJson;
      })
      .then(data => {
        alert(data.message);
        if (data.status === 'registered_and_logged_in') {
          window.location.href = '/dashboard';
        }
      })
      .catch(errorData => {
        console.error('Server Error:', errorData);
        let errorMessage = errorData.message || 'حدث خطأ غير متوقع.';
        if (errorData.errors) {
          errorMessage = "الرجاء تصحيح الأخطاء التالية:\n";
          for (const key in errorData.errors) {
            errorMessage += `- ${errorData.errors[key].join(', ')}\n`;
          }
        }
        alert(errorMessage);
      });
    });
  </script>
</div>
