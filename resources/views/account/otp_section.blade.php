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

    const showToast = (message, type = 'info') => {
      const backgroundColor = {
        success: '#16a34a', // أخضر للنجاح
        error: '#dc2626',   // أحمر للخطأ
        info: '#2563eb'     // أزرق للمعلومات
      }[type];

      Toastify({
        text: message,
        duration: 3000,
        gravity: "top",
        position: "center",
        backgroundColor: backgroundColor,
        stopOnFocus: true,
      }).showToast();
    };

    // 1. التحقق من الرمز
    verifyBtn.addEventListener('click', () => {
      const code = document.getElementById('otp').value.trim();
      if (!code) {
        showToast("الرجاء إدخال الرمز", 'error');
        return;
      }
      if (!window.confirmationResult) {
        showToast("اطلب رمز أولاً", 'error');
        return;
      }

      window.confirmationResult.confirm(code).then(result => {
        const user = result.user;
        const phone = '+966' + document.getElementById('phone').value.trim();

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
            document.getElementById('reg_firebase_uid').value = user.uid;
            document.getElementById('reg_phone_number').value = phone;
            registerFormDiv.style.display = 'block';
            showToast('يرجى إكمال بيانات التسجيل', 'info');

          } else if (data.status === 'logged_in') {
            showToast(data.message, 'success');
            setTimeout(() => window.location.href = '/dashboard', 1500);
          } else {
            showToast(data.message || 'حدث خطأ غير متوقع', 'error');
          }
        })
        .catch(() => showToast('حدث خطأ أثناء الاتصال بالخادم للتحقق من الرمز.', 'error'));

      }).catch(() => showToast("رمز التحقق الذي أدخلته غير صحيح.", 'error'));
    });

    // 2. إرسال فورم التسجيل
    registerFormDiv.querySelector('form').addEventListener('submit', (e) => {
      e.preventDefault();
      const form = e.target;

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
        showToast(data.message, 'success');
        if (data.status === 'registered_individual' || data.status === 'registered_company') {
          setTimeout(() => {
            window.location.href = data.redirect_url;
          }, 2000);
        }
      })
      .catch(errorData => {
        console.error('Server Error:', errorData);
        let errorMessage = errorData.message || 'حدث خطأ غير متوقع.';
        if (errorData.errors) {
          let errors = Object.values(errorData.errors).flat().join('. ');
          errorMessage = errors || errorMessage;
        }
        showToast(errorMessage, 'error');
      });
    });
  </script>
</div>
