<head>
    <link rel="icon" type="image/x-icon" href="{{ @$facIocn->value ?? asset('GlassPipeNepal.jpg') }}">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="title" content="{{ @$admin_seo->title ?? 'Glass Pipe Nepal'}}">
    <meta name="description"
        content="{{ @$admin_seo->meta_description ?? 'Glass Pipe Nepal'}}">
    <meta name="keywords"
        content="{{ @$admin_seo->meta_keywords ?? 'Glass Pipe Nepal'}}">
        <meta property="og:image" content="{{ @$admin_seo->og_image ?? asset('Royal-Black.png')}}">
    <meta name="author" content="PIXINVENT">
    <title>@yield('title','DEFAULT_TITLE', 'Glass Pipe Nepal')</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600"
        rel="stylesheet">
    <!-- BEGIN: Vendor CSS-->

    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/vendors/css/vendors.min.css') }}">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/css/bootstrap-extended.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/css/colors.css') }}">
    <link href="{{ asset('dashboard/css/components.css?v=').time() }}" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/css/themes/dark-layout.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/css/themes/semi-dark-layout.css') }}">


    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/css/core/menu/menu-types/vertical-menu.css') }}">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/assets/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/css/pages/ui-feather.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/css/backend-new.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
    <script>
        window.user_id = "{{ auth()->user()->id ?? null }}";
    </script>
    <!-- END: Custom CSS-->
    @stack('style')
</head>
<!-- END: Head-->
