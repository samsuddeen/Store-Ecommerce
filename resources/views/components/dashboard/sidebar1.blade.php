<!-- BEGIN: Main Menu-->
<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true" id="menuClassValue">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item me-auto"><a class="navbar-brand" href="{{ route('sellerDashboard') }}">
                    <span class="brand-logo">
                        <img src="{{ @$adminSettingSiteLogo->value }}" alt="">
                    </span>
                    <h2 class="brand-text">{{ @$adminSettingSiteName->value }}</h2>
                </a></li>
            <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pe-0" data-bs-toggle="collapse"><i
                        class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i
                        class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc"
                        data-ticon="disc"></i></a></li>

        </ul>
    </div>
    {{-- @dd(request()->url()) --}}
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main text-capitalize" id="main-menu-navigation" data-menu="menu-navigation">

            @foreach ($menus as $key => $menu)
                @if (count($menu['children']))
                    @canany($menu['permission'])
                        <li class=" nav-item {{ checkActive($menu['href']) }}">
                            <a class="d-flex align-items-center" href="#">
                                <i data-feather="{{ $menu['icon'] }}"></i>
                                <span class="menu-title text-truncate" data-i18n="products">{{ $menu['name'] }}</span></a>
                            <ul class="menu-content">

                                @foreach ($menu['children'] as $children)
                                    @canany($children['permission'])
                                        <li class=" nav-item {{ checkChildActive($children['href']) }}"><a class="d-flex align-items-center " href="{{ $children['href'] }}"><i
                                                    data-feather="{{ $children['icon'] }}">
                                                </i><span class="menu-item text-truncate"
                                                    data-i18n="Index">{{ $children['name'] }}</span></a>
                                        </li>
                                    @endcanany
                                @endforeach
                            </ul>
                        </li>
                    @endcanany
                @else
                @canany($menu['permission'])
               
                        <li class="nav-item {{ checkActive($menu['href']) }}">
                            <a class="d-flex align-items-center" href="{{ $menu['href'] }}">
                                <i data-feather="{{ $menu['icon'] }}"></i><span class="menu-title text-truncate "
                                    data-i18n="Home">{{ $menu['name'] }}</span></a>
                        </li>
                    @endcanany
                @endif
            @endforeach
        </ul>
    </div>
</div>
<!-- END: Main Menu-->
