<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DEFAULT_TITLE', 'My Store')</title>
    {{-- <title>{{ $meta_setting['name'] ?? config('app.name') }}</title> --}}
    {{-- <link rel="stylesheet" href="{{ asset('frontend/lobibox/dist/css/Lobibox.min.css') }}" /> --}}
    <link rel="stylesheet" href="{{ asset('frontend/css/jquery-ui.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/flexslider.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('frontend/fonts/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/fonts/line-awesome-1.3.0/css/line-awesome.min.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('frontend/css/jquery.exzoom.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('frontend/css/stellarnav.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/lightgallery.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/animation.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/dashboardstyle.css') }}">
    <link href="{{ asset('frontend/css/style.css?v=').time() }}" rel="stylesheet">

    {{-- <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}"> --}}

    
    <link rel="stylesheet" href="{{ asset('frontend/css/responsive.css?v=').time() }}">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="icon" type="image/x-icon" href="{{ @$meta_setting['logo'] ?? asset('lecoo.jpg') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.4.1/mapbox-gl.css" rel="stylesheet" />
    <link
        rel="stylesheet"
        href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.7.0/mapbox-gl-geocoder.css"
        type="text/css"
    />
    @include('frontend.layouts.metatag')
    @yield('css')
    <script>
        window.user_id = "{{ auth()->guard('customer')->user()->id ?? null }}";
    </script>
    <noscript>
        <p>This website requires JavaScript to function properly.</p>
    </noscript>
    <!-- Google tag (gtag.js) --> <script async src="https://www.googletagmanager.com/gtag/js?id=G-H2XZXBN88K"></script> <script> window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag('js', new Date()); gtag('config', 'G-H2XZXBN88K'); </script>
</head>

<body>

    <!-- Voice Search Modal  -->
    {{-- <div class="modal fade voice-modal-search" id="voice-search-modal" tabindex="-1" aria-labelledby="voice-search-modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="border-bottom:none;">
                    <h1 class="modal-title" id="voice-search-modalLabel"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="voice-search-modal">
                        <span id="disconnect-microphone">Didn't hear that. Try again.</span>
                        <span id="connect-microphone">Listening...</span>
                        <div class="microphone-btn">
                            <i class="las la-microphone"></i>
                        </div>  
                        <p>Tap microphone to try again</p>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <!-- Voice Search Modal End  -->
   
  

    <!--Top Header  -->
    <div class="header_ribbon">
        <div class="container">
            <div class="d-flex justify-content-center align-items-center">
                <div class="wholesale">
                    @if (@auth()->guard('customer')->user()->wholeseller)
                    <div class="btn">
                        <a class="btn after-regiseter-btn">Your minimum order must be above Rs. {{@$settings[10]->value}}</a>
                    </div>
                    @else
                    {{-- <div class="btn">
                        <a href="{{ route('signup') }}" class="btn wholesell-register-btn">Merchant Register</a>
                    </div> --}}
                    <div class="deal-text">
                        <p style="margin-bottom: 0px; color:black; text-align:center; font-weight:800">{{$settings->where('key','slogan')->first()->value ?? null}} <span style="font-style: italic">Buy Now</span></p>
                    </div>
                    @endif
                </div>
                
                <div class="hb-wrap">
                    
                    <!-- Button trigger modal -->
                    {{-- <button type="button" id="locationButton" class="locationButton" data-bs-toggle="modal"
                        data-bs-target="#locationModal" style="background: none; border:none;">
                        <i class="las la-map-marker"></i>
                        @if (Session::get('city'))
                            {{ Session::get('city') }}
                        @else
                            {{ @$geoData->cityName ?? $currentLocation->city_name }}
                        @endif
                    </button> --}}

                    <ul class="float-right">
                        {{-- <li class="relative">
                            <a href="#" class="download-app">
                                <i class="las la-coins"></i>
                                Save more on app
                            </a>
                            <div class="downoad-app-wrap">
                                <h3>Download the App</h3>
                                <img src="{{ @$settings[6]->value }}" alt="images">
                            </div>
                        </li> --}}
                        {{-- @if (auth()->guard('seller')->user() != null)
                            <li>
                                <a href="{{ route('sellerDashboard') }}">
                                    <i class="las la-user"></i>
                                    {{ ucfirst(auth()->guard('seller')->user()->name) }}
                                </a>
                            </li>
                        @else
                            <li><a href="{{ route('sellerVerify') }}"><i class="las la-hand-holding-usd"></i> Sales on
                                    it</a></li>
                        @endif --}}
                        {{-- <li><a href="{{ route('general', 'customer-care') }}"><i class="las la-headset"></i> Customer
                                care</a></li> --}}
                        {{-- <li class="relative">
                            <a href="#" class="track-order-list">
                                <i class="las la-clipboard-list"></i>
                                Track my order
                            </a>
                            <div class="track-order-wrap">
                                <h3>Track my Order</h3>
                                <form action="{{ route('traceOrder') }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <label for="">Please confirm your email:</label>
                                        <input type="email" name="email" class="form-control">
                                    </div>
                                    <div class="form-group group-input-field">
                                        <label for="">Your order ref number:</label>
                                        <div class="input-wrap">
                                            <input type="text" name="refId" class="form-control"
                                                placeholder="eg.123456789">
                                            <button type="submit"><i class="las la-arrow-right"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li> --}}
                        @if (auth()->guard('customer')->user() != null)
                            <li>
                                <a href="{{ route('Cdashboard') }}">
                                    <i class="las la-user"></i>
                                    <strong>{{ ucfirst(auth()->guard('customer')->user()->name) }}</strong>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('Clogout') }}"> <i class="las la-sign-in-alt"></i> Logout </a>
                            </li>
                        @else
                            <li><a href="{{ route('Clogin') }}"><i class="las la-sign-in-alt"></i> Login</a></li>
                        @endif
                        @if (auth()->guard('customer')->user() == null)
                            <li><a href="{{ route('signup') }}"><i class="las la-user-check"></i> Sign up</a></li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!--Top Header End  -->
    {{-- <form action="">
                    <i class="las la-map-marker"></i>

    </form> --}}

    <!-- Modal -->
    <div class="modal fade" id="locationModal" tabindex="-1" aria-labelledby="locationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="locationModalLabel">Select Location</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="locationForm" action="{{ route('updateLocation') }}" method="POST">
                        @csrf
                        <label for="city">Select City:</label>
                        <!-- Populate with available cities -->
                        <input id="searchInput" type="text" placeholder="Search for a location" style="width: 300px; height:40px;">
                        <select name="city" id="city_id" class="citySelect">
                            {{-- @foreach (@$cities as $city)
                                <option value="{{ $city->city_name }}"
                                    @if (Session::get('city') == $city->city_name || @$geoData->cityName == $city->city_name || @$currentLocation->city_name == $city->city_name)
                                        selected
                                    @endif>
                                    {{ $city->city_name }}
                                </option>
                            @endforeach --}}
                        </select>

                        <button type="submit" class="btn btn-default">Save</button>
                    </form>
                    <div id="map" class="my-3">
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- Middle Header  -->
    <div class="branding_ribbon">
        <div class="container">
            <div class="hr-wrap">
                <div class="mobile-pane-left">
                    <div class="toggle-btn">
                        <i class="las la-bars"></i>
                    </div>
                    <div class="brand-logo desktop-only-logo">
                        <a href="{{ url('/') }}">
                            {{-- <img src="{{ $meta_setting['logo'] ?? asset('lecoo.jpg') }}" alt="Logo"> --}}
                            <img src="{{asset('frontend\images\logo1.png') }}" alt="Logo">
                        </a>
                    </div>
                    <div class="brand-logo mobile-only-logo">
                        <a href="{{ url('/') }}">
                            {{-- <img src="{{ $meta_setting['logo'] ?? asset('lecoo.jpg') }}" alt="Logo"> --}}
                            <img src="{{asset('frontend\images\logo1.png') }}" alt="Logo">
                        </a>
                    </div>
                </div>
                <div class="web_search_pannel desktop-only-panel">
                    <form action="{{ route('search') }}" method="get" class="search_box voice-data-submit">

                        {{-- <input type="text" name="search" id="search"
                            placeholder="what are you looking for..." class="typeahead form-control" onkeyup="showResult(this.value)" required>
                        <div class="search_holder">
                            <button type="submit"><i class="las la-search"></i></button>
                        </div>
                        <div id="livesearch"></div> --}}
                        <input type="text" name="search" placeholder="Search for products..."
                            class="typeahead form-control autosearch" id="search_data_value" required>
                        <div class="search_holder">
                            <button type="submit"><i class="las la-search"></i></button>
                        </div>
                        <div id="`"></div>
                    </form>
                    <div class="voice-search">
                        <a href="#" class="btn-voice-search" data-bs-toggle="modal"
                            data-bs-target="#voice-search-modal"><i class="las la-microphone"></i></a>
                    </div>
                </div>
                <div class="brand_icons_info">
                    <ul>
                        <li>
                            <a href="{{ route('Cdashboard') }}">
                                <i class="las la-user-check"></i>
                                Account
                            </a>
                        </li>

                        {{-- <li>
                            <a href="">
                                <i class="las la-comments-dollar"></i>
                                Message
                            </a>
                        </li> --}}

                        {{-- @if (auth()->guard('customer')->user() != null)
                            <li class="badge-list notification-box-item">
                                <a href="javascript:void(0)">
                                    <i class="las la-bell"></i>
                                    <span class="badge">
                                        @isset($customer_notifications)
                                            {{ count($customer_notifications) }}
                                        @endisset
                                    </span>
                                </a>                                
                                <div class="notification-box">
                                    <div class="notification-box-head">
                                        @isset($customer_notifications)
                                            <h3>{{ count($customer_notifications) }}</h3>
                                        @endisset
                                    </div>

                                    <div class="notification-box-body">
                                        <ul>
                                            @foreach ($customer_notifications as $notification)
                                                <li>                                                    
                                                    <a href="{{ route('notification.show', $notification->id) }}">
                                                        <div class="notification-box-icon">
                                                            <i class="las la-bell"></i>
                                                        </div>
                                                        <div class="notification-box-info">
                                                            <span>{{ $notification->title }}</span>
                                                            <b> {{ $notification->created_at->diffForHumans() }}
                                                            </b>
                                                        </div>
                                                    </a>
                                                </li>
                                            @endforeach

                                        </ul>
                                    </div>
                                </div>

                            </li>
                        @endif --}}

                        <li>
                            <a href="{{ route('Corder') }}">
                                <i class="las la-clipboard-list"></i>
                                Orders
                            </a>
                        </li>
                        <li class="cart">

                            <a href="javascript:volid(0)">
                                <i class="las la-shopping-cart"></i>
                                Cart
                                <b class="badge">
                                    <b class="badgee"> <span id="total_quantity" class="guesttotal_quantity">
                                            @if (auth()->guard('customer')->user() != null)
                                                @if ($total_quantity_on_cart != null)
                                                    {{ $total_quantity_on_cart }}
                                                @else
                                                    0
                                                @endif
                                            @elseif (request()->session()->get('guest_cart') != null)
                                                {{ request()->session()->get('guest_cart')['total_qty'] ?? 0 }}
                                            @else
                                                0
                                            @endif
                                            <span>
                                    </b>
                                </b>
                            </a>
                        </li>
                        <li>
                            {{-- <a href="{{route('signup')}}" class="btn wholesell-register-btn">Merchant Register</a> --}}
                        </li>
                    </ul>
                </div>
                <div class="mobile-pane">
                    <div class="mobile-utilities">
                        <ul>
                            {{-- <li>
                                <a href="{{ route('sellerVerify') }}"><i class="las la-hand-holding-usd"></i></a>
                            </li> --}}
                            @if (auth()->guard('customer')->user() != null)
                                <li class="badge-list notification-box-item">
                                    <a href="javascript:void(0)">
                                        <i class="las la-bell"></i>
                                        <span class="badge">
                                            @isset($customer_notifications)
                                                {{ count($customer_notifications) }}
                                            @endisset
                                        </span>
                                    </a>
                                    <div class="notification-box">
                                        <div class="notification-box-head">
                                            @isset($customer_notifications)
                                                <h3>{{ count($customer_notifications) }}</h3>
                                            @endisset
                                        </div>

                                        <div class="notification-box-body">
                                            <ul>
                                                @foreach ($customer_notifications as $notification)
                                                    <li>
                                                        {{-- $notification->url --}}
                                                        <a href="{{ route('notification.show', $notification->id) }}">
                                                            <div class="notification-box-icon">
                                                                <i class="las la-bell"></i>
                                                            </div>
                                                            <div class="notification-box-info">
                                                                <span>{{ $notification->title }}</span>
                                                                <b> {{ $notification->created_at->diffForHumans() }}
                                                                </b>
                                                            </div>
                                                        </a>
                                                    </li>
                                                @endforeach

                                            </ul>
                                        </div>
                                    </div>

                                </li>
                            @endif

                            <li><a href="{{ route('Cdashboard') }}"><i class="las la-user-check"></i></a></li>
                            <li class="badge-list cart">
                                <a href="javascript:void(0)">
                                    <i class="las la-shopping-cart"></i>
                                    <b class="badge">
                                        <b class="badgee"> <span id="total_quantity" class="guesttotal_quantity">
                                            @if (auth()->guard('customer')->user() != null)
                                                @if ($total_quantity_on_cart != null)
                                                    {{ $total_quantity_on_cart }}
                                                @else
                                                    0
                                                @endif
                                            @elseif (request()->session()->get('guest_cart') != null)
                                                {{ request()->session()->get('guest_cart')['total_qty'] ?? 0 }}
                                            @else
                                                0
                                            @endif
                                                <span>
                                        </b>
                                    </b>
                                    <b class="badge">
                                        <b class="badgee"> <span id="total_quantity" class="guesttotal_quantity">
                                            @if (auth()->guard('customer')->user() != null)
                                            @if ($total_quantity_on_cart != null)
                                                {{ $total_quantity_on_cart }}
                                            @else
                                                0
                                            @endif
                                        @elseif (request()->session()->get('guest_cart') != null)
                                            {{ request()->session()->get('guest_cart')['total_qty'] ?? 0 }}
                                        @else
                                            0
                                        @endif
                                                <span>
                                        </b>
                                    </b>
                                    <b class="badge">
                                        <b class="badgee"> <span id="total_quantity" class="guesttotal_quantity">
                                            @if (auth()->guard('customer')->user() != null)
                                            @if ($total_quantity_on_cart != null)
                                                {{ $total_quantity_on_cart }}
                                            @else
                                                0
                                            @endif
                                        @elseif (request()->session()->get('guest_cart') != null)
                                            {{ request()->session()->get('guest_cart')['total_qty'] ?? 0 }}
                                        @else
                                            0
                                        @endif
                                                <span>
                                        </b>
                                    </b>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Middle Header End  -->

    <!-- Scroll Header  -->
    <div class="branding_ribbon scroll-header">
        <div class="container">
            <div class="hr-wrap">
                <div class="mobile-pane-left">
                    <div class="toggle-btn">
                        <i class="las la-bars"></i>
                    </div>
                    <div class="brand-logo">
                        {{-- <a href="{{ url('/') }}"><img src="{{ $meta_setting['logo'] ?? asset('logo.jpeg') }}" alt="Logo"></a> --}}
                        <a href="{{ url('/') }}"><img src="{{asset('frontend\images\logo1.png') }}" alt="Logo"></a>
                    </div>
                </div>
                <div class="web_search_pannel desktop-only-panel">
                    <form action="{{ route('search') }}" method="get" class="search_box voice-data-submit">

                        <input type="text" name="search" placeholder="Search for products..."
                            class="typeahead form-control search autosearch">
                        <div class="search_holder">
                            <button type="submit"><i class="las la-search"></i></button>
                        </div>
                    </form>
                    <div class="voice-search">
                        <a href="#" class="btn-voice-search"><i class="las la-microphone"></i></a>
                        {{-- <button onclick="voiceSearch()"><i class="las la-microphone"></i></button> --}}
                    </div>
                </div>
                <div class="brand_icons_info">
                    <ul>
                        <li>
                            <a href="{{ route('Cdashboard') }}">
                                <i class="las la-user-check"></i>
                                Account
                            </a>
                        </li>
                        {{-- <li>
                            <a href="">
                                <i class="las la-comments-dollar"></i>
                                Message
                            </a>
                        </li> --}}
                        <li>
                            <a href="{{ route('Corder') }}">
                                <i class="las la-clipboard-list"></i>
                                Orders
                            </a>
                        </li>
                        <li class="cart">
                            <a href="javascript:volid(0)">
                                <i class="las la-shopping-cart"></i>
                                Cart
                                <b class="badge">
                                    <b class="badgee"> <span id="total_quantity" class="guesttotal_quantity">
                                            @if (auth()->guard('customer')->user() != null)
                                                @if ($total_quantity_on_cart != null)
                                                    {{ $total_quantity_on_cart }}
                                                @endif
                                            @elseif (request()->session()->get('guest_cart') != null)
                                                {{ request()->session()->get('guest_cart')['total_qty'] ?? 0 }}
                                            @else
                                                0
                                            @endif
                                            <span>
                                    </b>
                                </b>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="mobile-pane">
                    <div class="mobile-utilities">
                        <ul>
                            <li><a href="{{ route('Cdashboard') }}"><i class="las la-user-check"></i></a></li>
                            <li><a href="#"><i class="las la-shopping-cart"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Scroll Header End  -->

    <!-- Mobile Search  -->
    <div class="mobile-search">
        <div class="container">
            <div class="web_search_pannel mobile-only-panel">
                <form action="{{ route('search') }}" method="get" class="search_box voice-data-submit">

                    <input type="text" name="search" placeholder="what are you looking for..."
                        class="typeahead form-control search autosearch">
                    <div class="search_holder">
                        <button type="submit"><i class="las la-search"></i></button>
                    </div>
                    <div class="voice-search">
                        <a href="#" class="btn-voice-search" data-bs-toggle="modal"
                            data-bs-target="#voice-search-modal"><i class="las la-microphone"></i></a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Mobile Search End -->

    <!-- Navigation Menu  -->
    <nav class="navbar navbar-expand-lg navbar-light bgColor_with_shadow" id="navbar_navigation">
        <div class="container">
            <div class="nav-wrap">
                <div class="nav-menu">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link cat-menu" href="#">category
                                    <i class="las la-angle-down"></i></a>
                                <ul class="sub_nav_menu">
                                    {{-- @dd($categories) --}}
                                    @foreach ($categories as $category)
                                        <li>
                                            <a
                                                href="{{ route('category.show', $category->slug) }}">{{ $category->title }}</a>
                                            @if (count($category->children) > 0)
                                                <div class="nav_mega_menu">
                                                    <div class="top_menu_flex">
                                                        @foreach ($category->children as $secondChild)
                                                            {{-- @dd($secondChild) --}}
                                                            @if ($secondChild->status == '1' && $secondChild->showOnHome == '1')
                                                                <div class="inner_block">
                                                                    <ul>
                                                                        @if (count($secondChild->children) > 0)
                                                                            <li class="sub-child-parent">
                                                                                <a
                                                                                    href="{{ route('category.show', $secondChild->slug) }}">{{ $secondChild->title }}</a>
                                                                                <div class="sub-child-menu">
                                                                                    @foreach ($secondChild->children as $items)
                                                                                        @if ($items->status == '1' && $items->showOnHome == '1')
                                                                                            <ul>
                                                                                                <li>
                                                                                                    <a
                                                                                                        href="{{ route('category.show', $items->slug) }}">{{ $items->title }}</a>
                                                                                                </li>
                                                                                            </ul>
                                                                                        @endif
                                                                                    @endforeach
                                                                                </div>
                                                                            </li>
                                                                        @else
                                                                            <li class="">
                                                                                <a
                                                                                    href="{{ route('category.show', $secondChild->slug) }}">{{ $secondChild->title }}</a>
                                                                            </li>
                                                                        @endif

                                                                    </ul>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </li>          

                            @include('frontend.menu._menu')
                        </ul>
                    </div>
                </div>
                <div class="download">
                    @foreach ($advertisements as $key => $advertisement)
                        @if ($key == 0)
                        {{-- <a href="{{ $meta_setting['iosplaystore'] }}" target="_blank" title="IOS App"><i
                            class="lab la-app-store-ios"></i></a> --}}
                            {{-- <a href="https://play.google.com/store/apps/details?id=com.nectardigit.celer_mart" target="_blank" title="IOS App"><i
                                    class="lab la-app-store-ios"></i></a> --}}
                            {{-- <a href="{{ $meta_setting['androidplaystore'] }}" target="_blank" title="Android App"><i
                                class="lab la-google-play"></i></a> --}}
                            {{-- <a href="https://play.google.com/store/apps/details?id=com.nectardigit.celer_mart" target="_blank" title="Android App"><i
                                    class="lab la-google-play"></i></a> --}}
                        @endif
                    @endforeach
                    @isset($productDetails)
                        {{-- <li class="download-qr">
                        <a href="#"><i class="las la-eye"></i></a>
                        <div class="downoad-app-qr">
                            {{ QrCode::size(100)->generate(@$product->slug) }}
                           
                        </div>
                    </li>   --}}
                    @endisset
                </div>
            </div>
        </div>
    </nav>
    <!-- Navigation Menu End -->

    <!-- Mobile Menu -->
    <div id="mySidenav" class="sidenav">
        <div class="stellarnav">
            <ul class="menu-lists">
                <li>
                    <div class="mobile-logo">
                        <h3>All Categories</h3>
                        <a href="javascript:void(0)" class="closebtn"><i class="las la-times"></i></a>
                    </div>
                </li>
                @foreach ($categories as $category)
                    <li>
                        <a href="{{ route('category.show', $category->slug) }}" class="has-arrow">
                            {{ $category->title }}

                        </a>
                        @if (count($category->children) > 0)
                            @foreach ($category->children as $secondChild)
                                <ul>
                                    <li>
                                        <a
                                            href="{{ route('category.show', $secondChild->slug) }}">{{ $secondChild->title }}</a>
                                        @if (count($secondChild->children) > 0)
                                            @foreach ($secondChild->children as $thirdChild)
                                                <ul>
                                                    <li>
                                                        <a href="{{ route('category.show', $thirdChild->slug) }}"
                                                            class="has-arrow">{{ $thirdChild->title }}</a>
                                                    </li>
                                                </ul>
                                            @endforeach
                                        @endif
                                    </li>
                                </ul>
                            @endforeach
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <!-- Mobile Menu End -->

    <!-- Mobile Categories -->
    <div id="mySidenav1" class="sidenav">
        <div class="stellarnav">
            <ul class="menu-lists">
                <li>
                    <div class="mobile-logo">
                        <h3>All Categories</h3>
                        <a href="javascript:void(0)" class="closebtn"><i class="las la-times"></i></a>
                    </div>
                </li>
                @foreach ($categories as $category)
                    <li>
                        <a href="{{ route('category.show', $category->slug) }}" class="has-arrow">
                            {{ $category->title }}

                        </a>
                        @if (count($category->children) > 0)
                            @foreach ($category->children as $secondChild)
                                <ul>
                                    <li>
                                        <a
                                            href="{{ route('category.show', $secondChild->slug) }}">{{ $secondChild->title }}</a>
                                        @if (count($secondChild->children) > 0)
                                            @foreach ($secondChild->children as $thirdChild)
                                                <ul>
                                                    <li>
                                                        <a href="{{ route('category.show', $thirdChild->slug) }}"
                                                            class="has-arrow">{{ $thirdChild->title }}</a>
                                                    </li>
                                                </ul>
                                            @endforeach
                                        @endif
                                    </li>
                                </ul>
                            @endforeach
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <!-- Mobile Categories End -->

    <!-- Mobile Footer  -->

    <div class="mobile_fixed_footer">
        <ul>
            <li class="active">
                <a href="{{ route('index') }}"><i class="las la-home"></i><span>Home</span></a>
            </li>
            <li>
                <a href="javascript:void(0)" id="mob-cat"><i
                        class="las la-th-large"></i><span>Categories</span></a>
            </li>
            <li>
                <a href="{{ route('Corder') }}"><i class="las la-clipboard-list"></i><span>Orders</span></a>
            </li>
            <li>
                <a href="{{ route('Cwishlist') }}"><i class="lar la-heart"></i><span>Wishlist</span></a>
            </li>
        </ul>
    </div>
    <!-- Mobile Footer End  -->
    <!-- Cart Slide  -->
    <div class="cart-slide">
        <div class="cart-slide-title">
            <h3>Your Cart</h3>
            <span class="close-btn"><i class="las la-times"></i></span>
        </div>
        <div id="side-cart-update">
            @if (auth()->guard('customer')->user())
                <div class="cart-remove">
                    @if (!empty($cart))
                        <div class="cart-table">
                            <div class="cart-table-item">
                                <ul>
                                    @foreach ($cart->cartAssets as $item)
                                        <li>
                                            <div class="p-img">
                                                @if ($item->product != null)
                                                    @foreach ($item->product->images as $key => $image)
                                                        @if ($key == 0)
                                                            <a href="{{ route('product.details', $item->product->slug) }}"
                                                                title="{{ $item->product_name }}">
                                                                <img src="{{ $image->image }}" alt="images">
                                                            </a>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </div>
                                            <div class="p-name">
                                                <h3>{{ $item->product_name }}</h3>
                                                <div class="cart-del">
                                                    <span>{{ $item->qty }} * Rs. {{ $item->price }}</span>
                                                    <a href="javascript:void(0)" class="tbl-close"
                                                        data-id="{{ $item->id }}"><i
                                                            class="las la-trash"></i></a>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="sub-total">
                                <ul>
                                    <li>Sub Total:</li>
                                    <li><b>Rs. {{ $cart->total_price }}</b></li>
                                </ul>
                            </div>
                            {{-- @dd($item) --}}
                            <div class="group-btns">
                                <a href="{{ route('cart.index') }}" class="btns">View Cart</a>
                                <form action="{{ route('pre-checkout.post') }}" method="post">
                                    @csrf
                                    <input type="price" name="price" id="price"
                                        value="{{ @$item->qty * @$item->price }}" hidden>
                                    <input type="qty" name="qty" id="qty" value="{{ @$item->qty }}"
                                        hidden>
                                    @foreach ($cart->cartAssets as $item)
                                        <input type="checkbox" name="items[{{ @$item->id }}]" value="1"
                                            checked hidden>
                                    @endforeach
                                    <button type="submit" class="btns btns btns-second"> Checkout</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="empty-cart">
                            <img src="{{ asset('frontend/images/empty.png') }}" alt="images">
                            <h4>Your Cart is Empty</h4>
                            <p>Looks like you haven't added anything to your cart yet.</p>
                        </div>
                    @endif
                </div>
            @else
                <div class="cart-remove">
                    @if (request()->session()->get('guest_cart') != null && request()->session()->get('guest_cart')['cart_item'] !=null)
                        <div class="cart-table">
                            <div class="cart-table-item">
                                <ul>
                                    @foreach (request()->session()->get('guest_cart')['cart_item'] as $key => $item)
                                        <li>
                                            <div class="p-img">
                                                <a href="{{ route('product.details', $item['product_name']) }}"
                                                    title="{{ $item['product_name'] }}">
                                                    <img src="{{ $item['image'] }}" alt="images">
                                                </a>
                                            </div>
                                            <div class="p-name">
                                                <h3>{{ $item['product_name'] }}</h3>
                                                <div class="cart-del">
                                                    <span>{{ $item['qty'] }} * Rs. {{ $item['price'] }}</span>

                                                    <a href="javascript:;" class="deleteGuestItemData" data-itemId="{{$item['product_id']}}" > <i
                                                            class="las la-trash"></i> </a>
                                                    {{-- <a href="javascript:(0);" class="delete-a-guestcart-singleproduct"
                                                    data-id="{{ $key }}"><i class="las la-trash"></i></a> --}}

                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="sub-total">
                                <ul>
                                    <li>Sub Total:</li>
                                    <li><b>Rs. {{ request()->session()->get('guest_cart')['total_price'] ?? 0 }}</b></li>
                                </ul>
                            </div>
                            <div class="group-btns">
                                <a href="{{ route('directguestallcheckout-to-cart') }}" class="btns">Checkout </a>
                                <a href="{{ route('guest.cartDelete') }}" class="btns btns-second">Delete All </a>
                            </div>
                        </div>
                    @else
                        <div class="empty-cart">
                            <img src="{{ asset('frontend/images/empty.png') }}" alt="images">
                            <h4>Your Cart is Empty</h4>
                            <p>Looks like you haven't added anything to your cart yet.</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
    <!-- Card Slide End  -->

    @yield('content')

    {{-- <section id="secure_payment_detail">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">

                    <div class="secured_service">
                        @foreach ($header as $head)
                            @if ($head->status == '1')
                                <div class="secured_service_list">
                                    <a href="">
                                        <img
                                            src="{{ asset('icon_image') }}/{{ $head->icon_image }}"style="width:30px"class="rounded-circle">&emsp13;<strong>{{ $head->title }}</strong>

                                    </a>
                                </div>
                            @endif
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </section> --}}

    <!-- Footer  -->
    <footer id="footer_wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="footer-infos">
                        <a href="{{ url('/') }}" class="footer-logo">
                            {{-- <img src="{{ $meta_setting['logo'] ?? asset('lecoo.jpg') }}" alt="Logo"> --}}
                            <img src="{{asset('frontend/images/logo1.png') }}" alt="Logo">
                        </a>
                        <div class="site-info">
                            <p class="lead">{{ @$meta_setting['name'] . '-' . @$seo->meta_description }}</p>
                        </div>

                        <ul class="footer-social">
                                <h3>Follow us on:</h3>
                                <div class="social-icons">
                                    @foreach ($social_settings as $social_link)
                                {{-- @dd($social_link) --}}
                                @if ($social_link->title == 'facebook' || $social_link->title == 'Facebook' || $social_link->title == 'FACEBOOK')
                                    <li>
                                        <a href="{{ $social_link->url }}" class="facebook" target="_blank"
                                            title="{{ $social_link->title }}"><img
                                                src="{{ asset('icon_image/facebook.png') }}"
                                                alt="{{ $social_link->title }}" height="25"></a>
                                    </li>
                                @elseif ($social_link->title == 'instagram' || $social_link->title == 'Instagram' || $social_link->title == 'INSTAGRAM')
                                    <li>
                                        <a href="{{ $social_link->url }}" class="facebook" target="_blank"
                                            title="{{ $social_link->title }}"><img
                                                src="{{ asset('icon_image/instagram.png') }}"
                                                alt="{{ $social_link->title }}" height="25"></a>
                                    </li>
                                @elseif ($social_link->title == 'twitter' || $social_link->title == 'Twitter' || $social_link->title == 'TWITTER')
                                    <li>
                                        <a href="{{ $social_link->url }}" class="twitter" target="_blank"
                                            title="{{ $social_link->title }}"><img
                                                src="{{ asset('icon_image/twitter.png') }}"
                                                alt="{{ $social_link->title }}" height="25"></a>
                                    </li>
                                @elseif ($social_link->title == 'youtube' || $social_link->title == 'Youtube' || $social_link->title == 'YOUTUBE')
                                    <li>
                                        <a href="{{ $social_link->url }}" class="youtube" target="_blank"
                                            title="{{ $social_link->title }}"><img
                                                src="{{ asset('icon_image/youtube.png') }}"
                                                alt="{{ $social_link->title }}" height="25"></a>
                                    </li>
                                @elseif($social_link->title == 'tiktok' || $social_link->title == 'Tiktok' || $social_link->title == 'TIKTOK')
                                    <li>
                                        <a href="{{ $social_link->url }}" class="tiktok" target="_blank"
                                            title="{{ $social_link->title }}"><img
                                                src="{{ asset('icon_image/tik-tok.png') }}"
                                                alt="{{ $social_link->title }}" height="25"></a>
                                    </li>
                                @else
                                    <li>
                                        <a href="{{ $social_link->url }}" class="youtube" target="_blank"
                                            title="{{ $social_link->title }}">
                                            {{-- <img src="{{ $meta_setting['logo'] }}" alt="Logo"
                                                style="width:20px; height:20px;"> --}}
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                                                    </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="footer-menu">
                        <h3>Get to Know Us</h3>

                        <ul>
                            @foreach ($footer_menus as $footer_menu)
                                <li><a
                                        href="{{ route('general', $footer_menu->slug) }}">{{ $footer_menu->name }}</a>
                                </li>
                            @endforeach
                        </ul>

                    </div>
                </div>
                {{-- <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="footer-menu">
                        <h3>Top Categories</h3>
                        <ul>
                            @foreach ($top_category as $category)
                                <li><a
                                        href="{{ route('category.show', @$category->getCategory->slug) }}">{{ ucfirst(@$category->getCategory->title) }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div> --}}

                {{-- <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="footer-menu">
                        <h3>Help</h3>
                        <div class="help">
                            <ul>
                                <li><a href="#">Track Your Order</a></li>
                                <li><a href="#">Warranty & Support</a></li>
                                <li><a href="#">Return Policy</a></li>
                                <li><a href="#">Bulk Orders</a></li>
                                <li><a href="#">FAQ's</a></li>
                            </ul>
                    </div>
                    </div>
                </div> --}}

                <div class="col-lg-4 col-md-6 col-sm-6">
                    {{-- <div class="newsletter-form">
                        <h3>Subscribe Newsletter</h3>
                        <form action="">
                            <input type="email" class="form-control" name="subscribe" id="subscribe"
                                placeholder="Your email" />
                            <div name="captcha" class="g-recaptcha footerCaptcha"
                                data-sitekey="6LeQV5wpAAAAAAw4GSF3A5D8EQckw_q5232OZX_E" required></div>

                            <span class="text-danger captchaerror" hidden> The reCAPTCHA was invalid. Go back and try
                                it again.
                            </span>
                            <a href="javascript:void(0);" class="subscribe">Subscribe</a>
                        </form>
                    </div> --}}
                    <div class="footer-menu">
                        <div class="earn">
                            <h3>Earn With Us</h3>
                            <ul>
                                <li><a href="#">Sell Products</a></li>
                                <li><a href="#">Advetise Your Products</a></li>
                            </ul>
                            <div class="wholesale">
                                {{-- <a href="{{route('signup')}}" class="btn wholesell-register-btn">Merchant Register</a> --}}
                            </div>
                        </div>
                    </div>
                    {{-- @dd($settings) --}}
                    {{-- <div class="downloads">
                        <h3 class="text-uppercase">Get the App On</h3>
                        <ul>
                            <li><a href="{{ $settings[4]->value ?? 'https://play.google.com/store/apps/details?id=com.nectardigit.celer_mart' }}" target="blank"><img
                                        src="{{ asset('frontend/images/apple.svg') }}" alt="images"></a></li>
                            <li><a href="{{ $settings[5]->value ?? 'https://play.google.com/store/apps/details?id=com.nectardigit.celer_mart' }}" target="blank"><img
                                        src="{{ asset('frontend/images/play.svg') }}" alt="images"></a></li>
                        </ul>
                    </div> --}}
                    <div class="payment-card">
                        <p class="text-uppercase">
                            <i class="las la-hand-holding-heart"></i> We Accept
                        </p>
                        <ul>
                            <li><img src="{{ asset('frontend/vgjh.png') }}" alt=""></li>
                            {{-- <li><img src="{{ asset('frontend/paypal.png') }}" alt=""></li> --}}
                        </ul>
                    </div>
                </div>
            </div>
            <div class="copyright">
                <ul>
                    <li class="top">
                        Copyright &copy; {{date('Y')}}. All rights reserved.
                    </li>
                    <li>
                        <div class="support-list">
                            <ul>
                                @foreach ($footer_contacts as $contact)
                                    {{-- ContactTypeEnum --}}
                                    @if (@$contact->type == 4)
                                        {{-- @dd(@$contact)        --}}
                                        <li class="call1"><a href="tel:{{ @$contact->contact_no }}"><i
                                                    class="lab la-viber"></i>{{ @$contact->contact_no }}</a>
                                        </li>
                                    @endif
                                    @if (@$contact->type == 3)
                                        <li class="call2"><a target="_blank" href="https://wa.me/{{ @$contact->contact_no }}"><i
                                                    class="lab la-whatsapp"></i>{{ @$contact->contact_no }}</a>
                                        </li>
                                    @endif
                                    @if (@$contact->type == 2)
                                        <li class="call3"><a href="tel:{{ @$contact->contact_no }}"><i
                                                    class="las la-tty"></i>{{ @$contact->contact_no }}</a>
                                        </li>
                                    @endif
                                    @if (@$contact->type == 1)
                                        <li class="call4"><a href="tel:{{ @$contact->contact_no }}"><i
                                                    class="las la-phone-volume"></i>{{ @$contact->contact_no }}</a>
                                        </li>
                                    @endif
                                @endforeach

                            </ul>
                        </div>
                    </li>
                    <li class="top">Powered by: <a target="_blank" href="https://nectardigit.com/">Nectar
                            Digit</a></li>
                </ul>
            </div>
        </div>
    </footer>
    <!-- Footer End  -->
    <style>
        #map {
            width: 100%;
            height: 400px;
            /* Set an appropriate height for the map */
        }
    </style>
    <!-- Scroll Top -->
    <div class="go-top">
        <div class="pulse">
            <i class="las la-angle-up"></i>
        </div>
    </div>
    <!-- Scroll Top End -->

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>


    <script src="https://khalti.s3.ap-south-1.amazonaws.com/KPG/dist/2020.12.17.0.0.0/khalti-checkout.iffe.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="{{ asset('frontend/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('frontend/js/jquery-migrate-1.4.1.min.js') }}"></script>
    <script src="{{ asset('frontend/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('frontend/js/jquery.flexslider-min.js') }}"></script>
    <script src="{{ asset('frontend/js/jquery.ez-plus.js') }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/shCore.js') }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/shBrushXml.js') }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/shBrushJScript.js') }}"></script>
    <script src="{{ asset('frontend/js/jquery-ui.js') }}"></script>
    <script src="{{ asset('frontend/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('frontend/js/jQuery.scrollText.js') }}"></script>
    <script src="{{ asset('frontend/fonts/fontawesome/js/all.min.js') }}"></script>
    <script src="{{ asset('frontend/js/owl.carousel.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/js/jquery.ez-plus.js') }}"></script>
    <script src="{{ asset('frontend/js/stellarnav.min.js') }}"></script>
    <script src="{{ asset('frontend/js/lightgallery-all.min.js') }}"></script>
    <script src="{{ asset('frontend/js/animation.js') }}"></script>
    <script src="{{ asset('frontend/js/script.js') }}"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="{{ asset('frontend/lobibox/dist/js/Lobibox.min.js') }}"></script>
    <script src="{{ asset('frontend/js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script src="{{ asset('frontend/js/pusher-sample.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.ajax-add-to-cart-front', function() {
                var product_id = $(this).data('product_id');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                    $.ajax({
                        url: "{{ route('tag-to-cart') }}",
                        type: "post",
                        data: {
                            product_id: product_id
                        },
                        success: function(response) {
                            if (response.login) {
                                // toastr.options =
                                // {
                                //     "closeButton" : true,
                                //     "progressBar" : true
                                // }
                                // toastr.error(response.msg);
                                window.location.href = "/customer/login";
                            }
                            if (response.error) {
                                toastr.options =
                                {
                                    "closeButton" : true,
                                    "progressBar" : true
                                }
                                toastr.error(response.msg);
                                return false;
                            }
                            else
                            {
                                toastr.options =
                                    {
                                        "closeButton" : true,
                                        "progressBar" : true
                                    }
                                toastr.success("Successfully Added To The Cart !! ");
                            }
                                
                            $('.cart-remove').replaceWith(response);
                            @if (auth()->guard('customer')->user() != null)
                                removeProduct();
                                allCartCount();
                            @else
                                allGuestCartCount();
                                deleteGuestCart();
                            @endif      
                        }
                    });
            });
        });

        $(document).ready(function() {
            @if (session('success'))
                toastr.options = {
                    "closeButton": true,
                    "progressBar": true
                }
                toastr.success("{{ session('success') }}");
            @endif

            @if (session('error'))
                toastr.options = {
                    "closeButton": true,
                    "progressBar": true
                }
                toastr.error("{{ session('error') }}");
            @endif
        });
    </script>
    
    <script>
        // function voiceSearch(event){
        //     event.preventDefault();
        //     var recognition=new webkitSpeechRecognition();
        //     recognition.lang="en-GB";
        //     recognition.onresult=function(event){
        //         console.log(event);
        //         document.getElementById('search').value=event.results[0][0].transcript;
        //     }
        //     recognition.start();
        // }

        $('.btn-voice-search').on('click', function(e) {
            e.preventDefault();
            $('.btn-voice-search').toggleClass('active');
            if ('webkitSpeechRecognition' in window) {
                var recognition = new webkitSpeechRecognition();
                recognition.continuous = true;
                recognition.interimResults = true;
                recognition.lang = 'en-US';
                recognition.onstart = function() {
                    console.log("Speech recognition started.");
                };
                recognition.onresult = function(event) {
                    // $('.microphone-btn').click(function(){
                    //     $(this).toggleClass('active');
                    //     $('.voice-search-modal p').toggleClass('active');
                    //     $('#disconnect-microphone').toggleClass('active');
                    //     $('#connect-microphone').toggleClass('active');
                    // });
                    var transcript = event.results[event.resultIndex][0].transcript;
                    $('.autosearch').val(transcript);
                    $('.voice-data-submit').submit();
                    // @php
                        //     session()->forget('voice-search');
                        //     session()->put('voice-search','dell');
                        //
                    @endphp
                    // url="{{ route('final-search') }}";
                    // window.location.href=url;
                    // $('#voice-search-modal').hide('modal');
                };
                recognition.onerror = function(event) {
                    console.log("Error: " + event.error);
                };
                recognition.onend = function() {
                    console.log("Speech recognition ended.");
                };
                // Start the speech recognition
                recognition.start();
            } else {
                console.log("Sorry, your browser does not support the Web Speech API.");
            }
        });

        function search(transcript) {
            console.log("Searching for: " + transcript);
            $('search').val(transcript);
            $.ajax({
                url: "{{ route('voice-search') }}",
                type: "get",
                data: {
                    search_data: transcript
                },
                success: function(response) {
                }
            });
        }
    </script>
    <script type="text/javascript">
        var path = "{{ route('autoComplete') }}";
        $("#search").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: path,
                    type: 'GET',
                    dataType: "json",
                    data: {
                        search: request.term
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },
            select: function(event, ui) {
                $('#search').val(ui.item.label);
                console.log(ui.item);
                return false;
            }
        });
    </script>
    <script>
        setTimeout(function() {
            $('.header').slideUp();
        }, 3000);
    </script>
    <script>
        function allCartCount() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route('getCartCount') }}",
                type: 'get',
                success: function(response) {
                    console.log(response.total);
                    $('.badgee').empty();
                    $('.badgee').append('<span id="total_quantity" >' + response.total + '<span>');
                },

                error: function(response) {

                }
            });
        }
    </script>
    <script>
        function allCartData() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route('getCartData') }}",
                type: 'get',
                success: function(response) {
                    console.log(response);
                    $('.cart-remove').replaceWith(response);
                    removeProduct();
                    allCartCount();
                },

                error: function(response) {

                }
            });
        }
    </script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
        $(document).ready(function() {
            $('.tbl-close').on('click', function() {
                var cart_assets_id = $(this).data('id');
                $.ajax({
                    url: "{{ route('productRemove') }}",
                    type: 'post',
                    data: {
                        cart_assets_id: cart_assets_id,
                    },
                    // dataType: 'JSON',
                    success: function(response) {
                        if (response.error) {
                            return false;
                        }

                        console.log(response);
                        $('.cart-remove').replaceWith(response);
                        removeProduct();
                        allCartCount();
                    },

                    error: function(response) {

                    }
                });
            })
        })
    </script>
    <script>
        function removeProduct() {
            $('.tbl-close').on('click', function() {
                var cart_assets_id = $(this).data('id');
                $.ajax({
                    url: "{{ route('productRemove') }}",
                    type: 'post',
                    data: {
                        cart_assets_id: cart_assets_id,
                    },
                    // dataType: 'JSON',
                    success: function(response) {
                        console.log(response);
                        $('.cart-remove').replaceWith(response);
                        removeProduct();
                        allCartCount();
                    },

                    error: function(response) {

                    }
                });
            })
        }
    </script>
    <script>
        function priceCalculation() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route('priceCalculation') }}",
                type: 'get',
                success: function(response) {
                    console.log(response);
                    $('.item-qty').empty();
                    $('.item-qty').append('<span>Item (' + response.total_qty + ')</span>')
                    $('.all-product-total-price').empty();
                    $('.all-product-total-price').append('<span>$ ' + response.total_price + '</span>')
                    $('.grand-total').empty();
                    $('.grand-total').append('<span>$ ' + response.sub_total + '</span>')
                    $('.sub_total').empty();
                    $('.sub_total').append('<span>$' + response.sub_total + '</span>')
                },

                error: function(response) {

                }
            });
        }
    </script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function() {
            $('.subscribe').on('click', function() {
                var email = $('#subscribe').val();
                var captchaResponse = grecaptcha.getResponse();

                if (captchaResponse.length === 0) {
                    $('.captchaerror').removeAttr('hidden');
                    return false;
                } else {
                    $('.captchaerror').attr('hidden', true);
                }
                $.ajax({
                    url: "{{ route('subscribe') }}",
                    type: 'post',
                    data: {
                        email: email,
                    },
                    success: function(response) {
                        if (response.error) {
                            $('#subscribe').val('');
                            grecaptcha.reset();
                            swal({
                                title: "Sorry!",
                                text: response.message,
                                icon: "error",
                            });
                        } else {
                            $('#subscribe').val('');
                            grecaptcha.reset();
                            swal({
                                title: "Thank You!",
                                text: response.message,
                                icon: "success",
                            });

                        }
                    },

                    error: function(response) {},
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {

            $(document).on('change', '.citySelect', function() {
                let city = $(this).val();
                if (city) {
                    var owlCarouselInstance;

                    // Function to initialize the Owl Carousel
                    function initializeCarousel() {
                        owlCarouselInstance = $('#latestProductsCarousel').owlCarousel({
                            // Configure the carousel options
                            // Example options:
                            loop: true,
                            items: 6,
                            // Other options...
                        });
                    }

                    // Function to reinitialize the Owl Carousel after AJAX call
                    function reinitializeCarousel() {
                        // Destroy the existing carousel instance
                        if (owlCarouselInstance) {
                            owlCarouselInstance.owlCarousel('destroy');
                        }

                        // Reinitialize the carousel
                        initializeCarousel();
                    }

                    $.ajax({
                        type: "GET",
                        url: "{{ route('index') }}",
                        data: {
                            city: city
                        },
                        dataType: "json",
                        success: function(response) {
                            $('#latestProductsCarousel').empty().html(response.new_arrivals);

                            reinitializeCarousel();
                        }
                    });
                }

            });
        });
    </script>
    @stack('script')
    @if (
        !empty(request()->session()->get('success')
        ))
        <script>
            $(document).ready() {
                swal({
                    title: "Thank you!",
                    text: "{{ request()->session()->get('success') }}",
                    icon: "success",
                });
            }
        </script>
        @php
            session()->forget('success');
        @endphp
        {{-- @else --}}
    @endif

    @if (
        !empty(request()->session()->get('error')
        ))
        <script>
            $(document).ready() {
                swal({
                    title: "Sorry!",
                    text: "{{ request()->session()->get('error') }}",
                    icon: "error",
                });
            }
        </script>
        @php
            session()->forget('error');
        @endphp
    @endif

    <script>
        function allGuestCartCount() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route('getGuestCartCount') }}",
                type: 'get',

                success: function(response) {
                    $('.badgee').empty();
                    $('.badgee').append('<span id="total_quantity" >' + response.guest_cart_count + '<span>');
                },
                error: function(response) {

                }
            });
        }
    </script>
    <script>
        var availableTags = [];
        $.ajax({
            url: "{{ route('autosearchtag') }}",
            type: "get",
            success: function(response) {
                autoSearchTag(response);
            }
        });

        function autoSearchTag(availableTags) {
            $(".autosearch").autocomplete({
                source: function(request, response) {
                    var results = $.ui.autocomplete.filter(availableTags, request.term);
                    response(results.slice(0, 10));
                }
            });
        }
    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC_jDlfRb0lO5o1NZh2PxwwB1OtHUnrTVw&libraries=places"></script>

    <script>
        var map, marker, infowindow, searchBox;
        var defaultLatLng = { lat: 27.700321, lng: 85.312581 };
        var geocoder;
        var storedCity = {!! json_encode(Session::get('city')) !!}; 

        function initializeMap() {
            geocoder = new google.maps.Geocoder();

            var mapProp= {
            center:defaultLatLng,
            zoom:13,
            };

            map = new google.maps.Map(document.getElementById("map"), mapProp);
            marker = new google.maps.Marker({
                map: map,
                position: defaultLatLng,
                draggable: true,
            });

            geocoder.geocode({ address: storedCity }, function (results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                    var location = results[0].geometry.location;
                    map.setCenter(location);
                    marker.setPosition(location);
                    updateDropdownValue(location);
                } else {
                    console.log("Geocode was not successful for the following reason: " + status);
                }
            });

            infowindow = new google.maps.InfoWindow({
                content: "Drag the marker to select a location.",
            });

            infowindow.open(map, marker);

            marker.addListener("dragend", function () {
                var newLatLng = marker.getPosition();
                updateDropdownValue(newLatLng);
            });

            var input = document.getElementById("searchInput");
            searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

            map.addListener("bounds_changed", function () {
                searchBox.setBounds(map.getBounds());
            });

            searchBox.addListener("places_changed", function () {
                var places = searchBox.getPlaces();

                if (places.length === 0) {
                    return;
                }

                var place = places[0];
                if (!place.geometry) {
                    console.log("Returned place contains no geometry");
                    return;
                }

                // Set the map's center and marker position
                map.setCenter(place.geometry.location);
                marker.setPosition(place.geometry.location);
                updateDropdownValue(place.geometry.location);
            });

             if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    var userLatLng = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude,
                    };

                    map.setCenter(userLatLng);
                    marker.setPosition(userLatLng);
                    updateDropdownValue(userLatLng);
                });
         }

        }
        function updateDropdownValue(latLng) {
            geocoder.geocode({ location: latLng }, function (results, status) {
                if (status === "OK" && results[0]) {
                    var city = getCityNameFromGeocodeResults(results);
                    if (city) {
                        // Update the dropdown value with the selected city
                        var dropdown = document.getElementById("city_id");
                        dropdown.value = city;
                    }
                }
            });
        }
        function getCityNameFromGeocodeResults(results) {
            for (var i = 0; i < results.length; i++) {
                for (var j = 0; j < results[i].address_components.length; j++) {
                    var component = results[i].address_components[j];
                    if (component.types.includes("locality")) {
                        return component.long_name;
                    }
                }
            }
            return null;
        }
        google.maps.event.addDomListener(window, "load", initializeMap);

    </script>
        
        @include('guestaddtocart')
</body>

</html>
