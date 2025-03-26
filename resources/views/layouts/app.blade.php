<!DOCTYPE html>
<html class="loading {{(request()->session()->get('DarkModeValue')['htmlValue'] ?? '')}}" lang="en" data-textdirection="ltr" id="htmlClassValue">
@include('admin.includes.header')

<body class="vertical-layout vertical-menu-modern  navbar-floating footer-static  " data-open="click"
    data-menu="vertical-menu-modern" data-col="" data-framework="laravel" data-asset-path={{ asset('dashboard') }}>
    @if (Auth::guard('seller')->user())
        @include('layouts.topnav')
    @else
        @include('admin.includes.topnav')
    @endif

    <x-dashboard.sidebar-component />
    <div class="app-content content {{(request()->session()->get('DarkModeValue')['darkClassValue'] ?? '')}}" id="darkModeLabel">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <x-dashboard.breadcrumb></x-dashboard.breadcrumb>
            <div class="content-body" id="app">
                @yield('content')
            </div>
        </div>
    </div>
    <div>
        @if (Session::has('success'))
            {{ Session::get('success') }}
        @endif
    </div>
    @include('admin.includes.footer')
    @include('admin.includes.script')
</body>

</html>
