<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عقارات</title>

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* إضافة تنسيق للأيقونات */
        .icon-option {
            display: flex;
            align-items: center;
        }

        .icon-option i {
            margin-left: 5px;
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-900 flex flex-col min-h-screen">

    <!-- Navbar -->
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-6 py-3 flex justify-between items-center">
            <div>
                <a class="text-gray-800 text-xl font-bold" href="#">شعار الموقع</a>
            </div>
            <div class="flex space-x-4 rtl:space-x-reverse items-center">
                <a class="text-gray-800 hover:text-gray-600" href="#">الرئيسية</a>
                <a class="text-gray-800 hover:text-gray-600" href="#">عقارات</a>
                <a class="text-gray-800 hover:text-gray-600" href="#">خدمات</a>
                <a class="text-gray-800 hover:text-gray-600" href="#">اتصل بنا</a>

                <button onclick="toggleTheme()"
                    class="px-4 py-2 bg-blue-500 text-white font-bold rounded hover:bg-blue-600 transition duration-200">
                    <span id="themeIcon">☀️</span>
                </button>
                <button onclick="toggleLanguage()"
                    class="px-4 py-2 bg-green-500 text-white font-bold rounded hover:bg-green-600 transition duration-200">
                    تغيير اللغة
                </button>

                <div class="flex space-x-4 rtl:space-x-reverse">
                    <a href="#" class="text-gray-800 hover:text-gray-600">
                        <i class="fas fa-heart"></i>
                    </a>
                    <a href="#" class="text-gray-800 hover:text-gray-600">
                        <i class="fas fa-user"></i>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow container mx-auto px-6 py-8">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6">
                    <h2 class="mb-4">اختر الايقونة</h2>

                    <div class="form-group">
                        <label for="icon-picker" class="form-label">اختر الايقونة من هنا</label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1"></span>
                            <input type="text" id="icon-picker" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-6 w-full">
        <div class="container mx-auto text-center">
            هاي
        </div>
    </footer>

    <script>
        function toggleTheme() {
            document.documentElement.classList.toggle('dark');
            var themeIcon = document.getElementById('themeIcon');
            if (document.documentElement.classList.contains('dark')) {
                themeIcon.textContent = '🌙';
                document.documentElement.classList.add('bg-gray-800', 'text-gray-200');
                document.documentElement.classList.remove('bg-gray-100', 'text-gray-900');
            } else {
                themeIcon.textContent = '☀️';
                document.documentElement.classList.add('bg-gray-100', 'text-gray-900');
                document.documentElement.classList.remove('bg-gray-800', 'text-gray-200');
            }
        }

        function toggleLanguage() {
            const currentLang = "ar"; // قم بتغيير هذا بناءً على اللغة الحالية
            const newLang = currentLang === 'ar' ? 'en' : 'ar';
            window.location.href = `/${newLang}`;
        }

        document.addEventListener('DOMContentLoaded', function () {
            const currentLang = "ar"; // قم بتعديل هذا بناءً على اللغة الحالية
            const direction = currentLang === 'ar' ? 'rtl' : 'ltr';
            document.documentElement.setAttribute('dir', direction);

            // إضافة الأيقونات إلى قائمة الاختيار
            const icons = [
                'fa-user', 'fa-heart', 'fa-car', 'fa-bell', 'fa-cog', 'fa-camera',
                'fa-check', 'fa-times', 'fa-star', 'fa-home' // أضف الأيقونات المطلوبة هنا
            ];

            const select = document.getElementById('icon-select');

            icons.forEach(icon => {
                const option = document.createElement('option');
                option.value = icon;
                option.className = 'icon-option';
                option.innerHTML = `<i class="${icon}"></i> ${icon}`;
                if (select) {
                    select.appendChild(option);
                }
            });
        });
    </script>

</body>

</html>
