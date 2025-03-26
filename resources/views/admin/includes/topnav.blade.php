<!-- BEGIN: Header-->
<nav
    class="header-navbar navbar navbar-expand-lg align-items-center floating-nav  navbar-shadow container-xxl {{(request()->session()->get('DarkModeValue')['navValue']) ?? ''}}" id="navClassValue">
    <div class="navbar-container d-flex content" style="background-color: #fff;">
        <div class="bookmark-wrapper d-flex align-items-center">
            <ul class="nav navbar-nav d-xl-none">
                <li class="nav-item"><a class="nav-link menu-toggle" href="#"><i class="ficon"
                            data-feather="menu"></i></a></li>
            </ul>
            {{-- <ul class="nav navbar-nav bookmark-icons">
                <li class="nav-item d-none d-lg-block"><a class="nav-link" href="app-email.html"
                        data-bs-toggle="tooltip" data-bs-placement="bottom" title="Email"><i class="ficon"
                            data-feather="mail"></i></a></li>
                <li class="nav-item d-none d-lg-block"><a class="nav-link" href="app-chat.html" data-bs-toggle="tooltip"
                        data-bs-placement="bottom" title="Chat"><i class="ficon"
                            data-feather="message-square"></i></a></li>
                <li class="nav-item d-none d-lg-block"><a class="nav-link" href="app-calendar.html"
                        data-bs-toggle="tooltip" data-bs-placement="bottom" title="Calendar"><i class="ficon"
                            data-feather="calendar"></i></a></li>
                <li class="nav-item d-none d-lg-block"><a class="nav-link" href="app-todo.html" data-bs-toggle="tooltip"
                        data-bs-placement="bottom" title="Todo"><i class="ficon" data-feather="check-square"></i></a>
                </li>
            </ul> --}}
            {{-- <ul class="nav navbar-nav">
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
        {{-- <div class="currentlocation" style="display: flex;align-items: center;flex-direction: row;">
            <p>My Current Location: {{ @$geoData ? @$geoData->cityName : @$currentLocation->city_name }}</p>
            @if(!Auth::user()->hasRole('delivery'))
            <div class="searchForm" style="margin-left: 15px;">
                <form action="" id="searchUserLocation">
                    <div class="form-group" style="position:relative;">
                        <input type="text" class="form-control" name="city" id="city" placeholder="Enter Location Name">
                        <button type="button" class="btn" id="searchStaffBtn" style="position: absolute; top: 0; right:-136px; background-color: #0080ff; color:#fff">Search Staff</button>
                    </div>
                </form>
            </div>

            @endif

        </div> --}}
        <ul class="nav navbar-nav align-items-center ms-auto">
            <li class="nav-item d-none d-lg-block"><a class="changeDashboradColor"><i class="ficon"
                        data-feather="moon"></i></a></li>
            {{-- <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-style"><i class="ficon"
                            data-feather="moon"></i></a></li> --}}
            @if(Auth::user()->can('order-read'))
            <li class="nav-item dropdown dropdown-notification me-25"><a class="nav-link" href="#"
                    data-bs-toggle="dropdown"><i class="ficon" data-feather="bell"></i><span
                        class="badge rounded-pill bg-danger badge-up">{{count($notifications_count) > 0 ? count($notifications_count) : ''}}</span></a>
                <ul class="dropdown-menu dropdown-menu-media dropdown-menu-end">
                    <li class="dropdown-menu-header">                        
                        <div class="dropdown-header d-flex">
                            <h4 class="notification-title mb-0 me-auto">Notifications</h4>
                            @if(count($notifications_count) > 0)
                                <div class="badge rounded-pill badge-light-primary">{{count($notifications_count)}} New</div>
                            @endif
                        </div>
                    </li>
                    <li class="scrollable-container media-list" id="notifications-list">
                        @foreach($admin_notifications as $notification)
                        <a class="d-flex" href="{{route('notification.show', $notification->id)}}">
                            <div class="list-item d-flex align-items-start">
                                <div class="me-1">
                                    <div class="avatar bg-light-success">
                                        <div class="avatar-content"><i class="avatar-icon" data-feather="check"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-item-body flex-grow-1">
                                    <p class="media-heading">{{$notification->title}}</p><small class="notification-text">{{$notification->created_at->diffForHumans()}}</small>
                                    @if($notification->is_read==1)
                                    <span class="badge bg-success">Read</span>
                                    @else
                                    <span class="badge bg-danger">Unread</span>
                                    @endif
                                    
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </li>
                    <li class="dropdown-menu-footer"><a class="btn btn-primary w-100" href="{{route('view.all-notifications')}}">Read all
                            notifications</a></li>
                </ul>
            </li>
            @elseif(Auth::user()->can('task-read'))
            <li class="nav-item dropdown dropdown-notification me-25"><a class="nav-link" href="#"
                data-bs-toggle="dropdown"><i class="ficon" data-feather="bell"></i><span
                    class="badge rounded-pill bg-danger badge-up">{{ $task_notifications->where('read_at',NULL)->count() ?? ''}}</span></a>
            <ul class="dropdown-menu dropdown-menu-media dropdown-menu-end">
                <li class="dropdown-menu-header">                        
                    <div class="dropdown-header d-flex">
                        <h4 class="notification-title mb-0 me-auto">Notifications</h4>
                        @if($task_notifications->count() > 0)
                            <div class="badge rounded-pill badge-light-primary">{{$task_notifications->where('read_at',NULL)->count()}} New</div>
                        @endif
                    </div>
                </li>
                <li class="scrollable-container media-list" id="notifications-list">
                    @foreach($task_notifications as $notification)
                    <a class="d-flex" href="{{route('task-notification.show', $notification->id)}}">
                        <div class="list-item d-flex align-items-start">
                            <div class="me-1">
                                <div class="avatar bg-light-success">
                                    <div class="avatar-content"><i class="avatar-icon" data-feather="check"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="list-item-body flex-grow-1">
                                <p class="media-heading">{{$notification->data['message'] }}</p><small class="notification-text">{{$notification->created_at->diffForHumans()}}</small>
                                @if($notification->read_at)
                                <span class="badge bg-success">Read</span>
                                @else
                                <span class="badge bg-danger">Unread</span>
                                @endif
                                
                            </div>
                        </div>
                    </a>
                    @endforeach
                </li>
                {{-- <li class="dropdown-menu-footer"><a class="btn btn-primary w-100" href=s"{{route('view.all-notifications')}}">Read all
                        notifications</a></li> --}}
            </ul>
        </li>
            @endif
            @auth
                <li class="nav-item dropdown dropdown-user"><a class="nav-link dropdown-toggle dropdown-user-link"
                        id="dropdown-user" href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <div class="user-nav d-sm-flex d-none"><span
                                class="user-name fw-bolder">{{ auth()->user()->name }}</span><span
                                class="user-status">{{ implode(',',auth()->user()->roles->pluck('name')->toArray()) }}</span>
                        </div><span class="avatar"><img class="round"
                                src="{{ auth()->user()->photo ?? asset('dashboard/images/portrait/small/avatar-s-11.jpg') }}" alt="avatar"
                                height="40" width="40"><span class="avatar-status-online"></span></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-user">
                        <a class="dropdown-item" href="{{ route('user.show', auth()->id()) }}"><i class="me-50"
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
<div class="modal" tabindex="-1" id="userListModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Nearby Users</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <ul id="locationData">
            </ul>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <a href="{{route('user.index')}}"  class="btn btn-primary">View Staff</a>
        </div>
      </div>
    </div>
  </div>

@push('script')
    <script>
        $(document).ready(function(){
            $(document).on('click','#searchStaffBtn', function(){
                let location = $('#city').val();
                $.ajax({
                    url:"{{ url('api/system/get_user_by_location') }}",
                    type: "GET",
                    data:{
                        location: location
                    },
                    dataType:"json",
                    success:function(response)
                    {   
                        $('#userListModal').modal('show');
                        $('#locationData').html('');
                        if(response.users)
                        {
                            $.each(response.users, function(key, val){
                                $('#locationData').append(`<li>${val.name}</li>`);
                            });
                        }else{
                            $('#locationData').append(`${response.message}`);

                        }

                    }
                });
            });
        });
    </script>
@endpush