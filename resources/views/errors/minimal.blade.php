<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    @include('frontend.layouts.metatag')
    <title>{{ config('app.name') }}</title>
    <link rel="apple-touch-icon" href="{{ @$meta_setting['logo'] ?? asset('lecoo.jpg')}}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ @$meta_setting['logo'] ?? asset('lecoo.jpg')}}">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600"
        rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/vendors/css/vendors.min.css') }}">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/css/bootstrap-extended.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/css/colors.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/css/components.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/css/themes/dark-layout.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/css/themes/bordered-layout.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/css/themes/semi-dark-layout.css') }}">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('dashboard/css/core/menu/menu-types/vertical-menu.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/css/pages/page-misc.css') }}">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/css/style.css') }}">
    <!-- END: Custom CSS-->

</head>
<!-- END: Head-->
<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern blank-page navbar-floating footer-static" data-open="click"
    data-menu="vertical-menu-modern" data-col="blank-page">
    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <!-- Not authorized-->
                <div class="misc-wrapper" >
                    <div>
                        <a class="brand-logo" href="{{ route('dashboard') }}">
                            <a href="{{ url('/') }}" class="mb-auto ms-2 mt-2">
                                <img src="{{ @$system_setting['value'] }}" alt="Logo"
                                    style="width: auto; height:100px;">
                            </a>
                        </a>
                    </div>
                    <div>
                        @yield('content')

                    </div>

                </div>
                <!-- / Not authorized-->
            </div>
        </div>
    </div>
    <!-- END: Content-->


    <!-- BEGIN: Vendor JS-->
    <script src="{{ asset('vendors/js/vendors.min.js') }}"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src=" {{ asset('js/core/app-menu.js') }}"></script>
    <script src=" {{ asset('js/core/app.js') }}"></script>
    <!--END: Theme JS-->

    <!--BEGIN: Page JS-->
    <!--END: Page JS-->


</body>
<!-- END: Body-->

</html>
