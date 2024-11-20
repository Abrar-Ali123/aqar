<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <!-- jQuery CDN -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

 <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عرض العقارات</title>
      <link href="{{ asset('css/app.css') }}" rel="stylesheet">
<!-- Add these to the head section of your main layout file -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>


    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script>
        function toggleTheme() {
            document.body.classList.toggle('dark-theme');
            var themeIcon = document.getElementById('themeIcon');
            if (document.body.classList.contains('dark-theme')) {
                themeIcon.textContent = '🌙';
            } else {
                themeIcon.textContent = '☀️';
            }
        }

        function toggleLanguage() {
    const currentLang = document.documentElement.getAttribute('lang');
    const newLang = currentLang === 'ar' ? 'en' : 'ar';

    document.documentElement.setAttribute('lang', newLang);
    document.documentElement.setAttribute('dir', newLang === 'ar' ? 'rtl' : 'ltr');

    // تغيير النصوص إذا كانت مدمجة مباشرة في الصفحة
    document.getElementById('langButton').textContent = newLang === 'ar' ? 'English' : 'العربية';
}

    </script>

</head>
<body>




    <!-- شريط جانبي -->
    <div class="sidebar">

    <div>

    <a href="#">
    <div class="company-logo">
    <img src="{{ asset('/images/9.png') }}" class="w-20 h-20" />
    </div>
    </a>

            <a  onclick="toggleTheme()" id="themeIcon">☀️</a>
         </div>


         <a href="">
    <div class="icon"><i class="fas fa-home"></i></div>
    <span>الرئيسية</span>
</a>




<a href="">

    <div class="icon"><i class="fas fa-tags"></i></div>
    <span>طلبات التواصل </span>
</a>

<a href="">
    <div class="icon"><i class="fas fa-city"></i></div>
    <span>المدن</span>
</a>



<a href="">

         <div class="icon"><i class="fas fa-building"></i></div>
        <span>العقارات</span>
    </a>

    </div>




    <!-- محتوى الصفحة -->
    <div class="content">
        <div class="container mt-5">

        @yield('content')


        </div>
    </div>

    <script>
        function toggleView(view) {
            document.getElementById('tableView').classList.toggle('d-none', view !== 'table');
            document.getElementById('gridView').classList.toggle('d-none', view !== 'grid');
        }
    </script>
</body>
</html>
