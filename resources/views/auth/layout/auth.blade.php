<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="description" content="{{ strip_tags(@$meta['meta_description'] ?? @$seo->meta_description) }}">
    <meta name="keywords" content="{{ @$meta['meta_keywords'] ?? @$seo->meta_keywords }}">
    <meta name="author" content="Nectar Digit">
    <title>{{ config('app.name') }}</title>
    <meta property="og:title" content="{{ @$meta['meta_title'] ?? @$seo->meta_title }}">
    <meta property="og:image" content="{{ @$meta['og_image'] ?? (@$seo->og_image ?? @$meta_setting['logo']) }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="675">
    <meta property="og:image:alt" content="{{ @$meta['meta_title'] ?? @$seo->meta_title }}">
    <meta property="og:description" content="{{ strip_tags(@$meta['meta_description'] ?? @$seo->meta_description) }}">
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:site_name" content="{{ @$meta_setting['name'] }}" />
    <link rel="apple-touch-icon" href="{{ @$meta_setting['logo'] ?? asset('celermart-logo.png')}}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ @$meta_setting['logo'] ?? asset('celermart-logo.png')}}">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600"
        rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('/dashboard/vendors/css/vendors.min.css') }}">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('/dashboard/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/dashboard/css/bootstrap-extended.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/dashboard/css/colors.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/dashboard/css/components.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/dashboard/css/themes/dark-layout.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/dashboard/css/themes/bordered-layout.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/dashboard/css/themes/semi-dark-layout.css') }}">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('/dashboard/css/core/menu/menu-types/vertical-menu.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/dashboard/css/plugins/forms/form-validation.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/dashboard/css/pages/authentication.css') }}">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('/dashboard/assets/css/style.css') }}">
    <!-- END: Custom CSS-->

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern blank-page navbar-floating footer-static  " data-open="click"
    data-menu="vertical-menu-modern" data-col="blank-page">
    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <div class="auth-wrapper auth-cover">
                    <div class="auth-inner m-0">
                        <!-- Brand logo--><a class="brand-logo" href="{{ route('index') }}">
                            <a href="{{ url('/') }}" class="m-2">
                                {{-- <img src="{{ @$system_setting['value'] }}" alt="Logo"
                                    style="width: auto; height:100px;position: absolute;top: 15%; left: 50%; transform: translate(-50%, -50%);"> --}}
                                    <img src="{{ asset('frontend/images/logo1.png')}}" alt="Logo"
                                    style="width: auto; height:100px;position: absolute;top: 15%; left: 50%; transform: translate(-50%, -50%);">
                            </a>
                            {{-- <h2 class="brand-text text-primary m-2">{{ env('APP_NAME') }}</h2> --}}
                        </a>
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Content-->


    <!-- BEGIN: Vendor JS-->
    <script src="/dashboard/vendors/js/vendors.min.js"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="/dashboard/vendors/js/forms/validation/jquery.validate.min.js"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="/dashboard/js/core/app-menu.js"></script>
    <script src="/dashboard/js/core/app.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="/dashboard/js/scripts/pages/auth-login.js"></script>
    <!-- END: Page JS-->

    <script>
        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        })
    </script>
</body>
<!-- END: Body-->

</html>
