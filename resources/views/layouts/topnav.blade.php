<!-- BEGIN: Header-->
<nav
    class="header-navbar navbar navbar-expand-lg align-items-center floating-nav  navbar-shadow container-xxl {{(request()->session()->get('DarkModeValue')['navValue']) ?? ''}}" id="navClassValue">
    <div class="navbar-container d-flex content" >
       <div class="bookmark-wrapper d-flex align-items-center">
            <ul class="nav navbar-nav d-xl-none">
                <li class="nav-item"><a class="nav-link menu-toggle" href="#"><i class="ficon"
                            data-feather="menu"></i></a></li>
            </ul>
            {{-- <ul class="nav navbar-nav bookmark-icons">
                <li class="nav-item d-none d-lg-block"><a class="nav-link" href="app-email.html"
                        data-bs-toggle="tooltip" data-bs-placement="bottom" title="Email"><i class="ficon"
                            data-feather="mail"></i></a></li>
                <li class="nav-item d-none d-lg-block"><a class="nav-link" href="{{ route('comments.index') }}"
                        data-bs-toggle="tooltip" data-bs-placement="bottom" title="Comments"><i class="ficon"
                            data-feather="message-square"></i></a></li>
                <li class="nav-item d-none d-lg-block"><a class="nav-link" href="app-calendar.html"
                        data-bs-toggle="tooltip" data-bs-placement="bottom" title="Calendar"><i class="ficon"
                            data-feather="calendar"></i></a></li>
                <li class="nav-item d-none d-lg-block"><a class="nav-link" href="app-todo.html" data-bs-toggle="tooltip"
                        data-bs-placement="bottom" title="Todo"><i class="ficon" data-feather="check-square"></i></a>
                </li>
            </ul>
            <ul class="nav navbar-nav">
                <li class="nav-item d-none d-lg-block"><a class="nav-link bookmark-star"><i class="ficon text-warning"
                            data-feather="star"></i></a>
                    <div class="bookmark-input search-input">
                        <div class="bookmark-input-icon"><i data-feather="search"></i></div>
                        <input class="form-control input" type="text" placeholder="Bookmark" tabindex="0"
                            data-search="search">
                        <ul class="search-list search-list-bookmark"></ul>
                    </div>
                </li>
            </ul> --}}
        </div>
        <ul class="nav navbar-nav align-items-center ms-auto">
            <li class="nav-item d-none d-lg-block"><a class="sellerChangeDashboradColor"><i class="ficon"
                data-feather="moon"></i></a></li>
            {{-- <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-style"><i class="ficon"
                        data-feather="moon"></i></a></li> --}}
            <li class="nav-item nav-search"><a class="nav-link nav-link-search"><i class="ficon"
                        data-feather="search"></i></a>
                <div class="search-input">
                    <div class="search-input-icon"><i data-feather="search"></i></div>
                    <input class="form-control input" type="text" placeholder="Explore Vuexy..." tabindex="-1"
                        data-search="search">
                    <div class="search-input-close"><i data-feather="x"></i></div>
                    <ul class="search-list search-list-main"></ul>
                </div>
            </li>

            <li class="nav-item dropdown dropdown-notification me-25"><a class="nav-link" href="#"
                    data-bs-toggle="dropdown"><i class="ficon" data-feather="bell"></i><span
                        class="badge rounded-pill bg-danger badge-up">{{ count($seller_notifications) > 0 ? count($seller_notifications) : '' }}</span></a>
                <ul class="dropdown-menu dropdown-menu-media dropdown-menu-end">
                    <li class="dropdown-menu-header">
                        <div class="dropdown-header d-flex">
                            <h4 class="notification-title mb-0 me-auto">Notifications</h4>
                            @if (count($seller_notifications) > 0)
                                <div class="badge rounded-pill badge-light-primary">{{ count($seller_notifications) }}
                                    New</div>
                            @endif
                        </div>
                    </li>

                    <li class="scrollable-container media-list">
                        @foreach ($seller_notifications as $notification)
                            <a class="d-flex" href="{{route('notification.show', $notification->id)}}">
                                <div class="list-item d-flex align-items-start">
                                    <div class="me-1">
                                        <div class="avatar bg-light-success">
                                            <div class="avatar-content"><i class="avatar-icon" data-feather="check"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list-item-body flex-grow-1">
                                        <p class="media-heading"><span class="fw-bolder">
                                                {{ $notification->title }}
                                        </p>
                                        @if($notification->is_read==1)
                                    <span class="badge bg-success">Read</span>
                                    @else
                                    <span class="badge bg-danger">Unread</span>
                                    @endif
                                        <small class="notification-text">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                            </a>
                        @endforeach

                    </li>
                    <li class="dropdown-menu-footer"><a class="btn btn-primary w-100" href="#">Read all
                            notifications</a></li>
                </ul>
            </li>
            @auth
                <li class="nav-item dropdown dropdown-user"><a class="nav-link dropdown-toggle dropdown-user-link"
                        id="dropdown-user" href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">


                        <div class="user-nav d-sm-flex d-none"><span
                                class="user-name fw-bolder">{{ auth()->user()->name }}</span><span
                                class="user-status">{{ implode(',',auth()->user()->roles->pluck('name')->toArray()) }}</span>
                        </div><span class="avatar">
                            <img class="round" src="{{ auth()->guard('seller')->user()->photo }}" alt="avatar"
                                height="40" width="40" alt="Profile"><span
                                class="avatar-status-online"></span></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-user">

                        <a class="dropdown-item" href="{{ route('profile.index') }}"><i class="me-50"
                                data-feather="user"></i> Profile</a>


                        <div class="dropdown-divider"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="dropdown-item" href="#">
                                <i class="me-50" data-feather="power"></i>
                                Logout</button>
                        </form>
                    </div>
                </li>
            @endauth
        </ul>
    </div>
</nav>
