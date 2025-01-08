<!DOCTYPE html>
<html lang="en" class="h-100">


<!-- Mirrored from griya.dexignzone.com/xhtml/page-login.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 28 Oct 2024 14:04:17 GMT -->
<head>
  	   <!-- Title -->
	<title>Griya - Real Estate Admin & Dashboard Bootstrap 5 Template</title>

	<!-- Meta -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="author" content="DexignZone">
	<meta name="robots" content="">

	<meta name="keywords" content="admin dashboard, admin template, administration, analytics, bootstrap, broker, medical dashboard, modern, property, property admin, real estate, responsive admin dashboard">
	<meta name="description" content="Griya is a premier real estate development that offers a range of high-quality residential properties designed to meet the diverse needs and preferences of buyers. With its commitment to excellence and attention to detail, Griya has established itself as a trusted brand in the real estate industry.">

	<meta property="og:title" content="Griya - Real Estate Admin & Dashboard Bootstrap 5 Template">
	<meta property="og:description" content="Griya is a premier real estate development that offers a range of high-quality residential properties designed to meet the diverse needs and preferences of buyers. With its commitment to excellence and attention to detail, Griya has established itself as a trusted brand in the real estate industry.">
	<meta property="og:image" content="social-image.png">
	<meta name="format-detection" content="telephone=no">

	<!-- Mobile Specific -->
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Favicon icon -->
	<link rel="icon" type="image/png" href="images/favicon.png">

	<link href="{{ asset('assets/vendor/jquery-nice-select/css/nice-select.css') }}" rel="stylesheet">
      <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

</head>

<body class="vh-100">
    <div class="authincation h-100">
        <div class="container h-100">
        {{ $slot ?? '' }}
            @yield('content')
        </div>
    </div>


    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
   <script src="{{ asset('assets/vendor/global/global.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom.min.js') }}"></script>
	<script src="{{ asset('assets/vendor/jquery-nice-select/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('assets/js/deznav-init.js') }}"></script>
	<script src="{{ asset('assets/js/demo.js') }}"></script>
	<script src="{{ asset('assets/js/styleSwitcher.js') }}"></script>

    @livewireScripts

</body>

<!-- Mirrored from griya.dexignzone.com/xhtml/page-login.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 28 Oct 2024 14:04:18 GMT -->
</html>
