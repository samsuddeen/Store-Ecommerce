
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin')?'':'collapsed'}}" href="{{route('home')}}">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <li class="nav-heading">MENU</li>

      {{-- ----------------------------Setting----------------------------------- --}}
      @if(Auth::user()->can("create-setting")  || Auth::user()->can("edit-setting"))
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('setting*')?'':'collapsed'}}" data-bs-target="#components-setting" data-bs-toggle="collapse" href="#">
          <i class="bi bi-gear-fill"></i><span>Setting</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>

        <ul id="components-setting" class="nav-content collapse {{ request()->routeIs('setting*')?'show':''}}" data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{route('setting.index')}}" class="{{ request()->routeIs('setting.edit')?'active':''}} small-link">
              <i class="bi bi-circle"></i><span>Site Setting</span>
            </a>
          </li>
        </ul>
      </li>
      @endif
      {{-- ----------------------------/Setting----------------------------------- --}}


      {{-- -------------------------All Users--------------------------------------------}}
      @if (Auth::user()->can('view-user') || Auth::user()->can('create-user') || Auth::user()->can('edit-user') || Auth::user()->can('remove-user'))
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('user*')?'':'collapsed'}}" data-bs-target="#components-user" data-bs-toggle="collapse" href="#">
            <i class="nav-icon fas fa-user"></i><span>User</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="components-user" class="nav-content collapse {{ request()->routeIs('user*')?'show':''}}" data-bs-parent="#sidebar-nav">
            @if (Auth::user()->can('view-user'))
            <li>
                <a href="{{ route('user.index') }}" class="{{ request()->routeIs('user.index')?'active':''}}">
                  <i class="bi bi-circle"></i><span>View All Users</span>
                </a>
            </li>
            @endif
            @if (Auth::user()->can('create-user'))
            <li>
                <a href="{{ route('user.create')}}" class="{{ request()->routeIs('user.create')?'active':''}}">
                    <i class="bi bi-circle"></i><span>Create New User</span>
                </a>
            </li>
            @endif
        </ul>
     </li>
     @endif



      {{---------- ---------------Permission-------------------------------------------------------- --}}
    @if (Auth::user()->can('view-permission') || Auth::user()->can('create-permission') || Auth::user()->can('edit-permission') || Auth::user()->can('remove-permission'))
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('permission*')?'':'collapsed'}}" data-bs-target="#components-permission" data-bs-toggle="collapse" href="#">
            <i class="nav-icon fas fa-shield-alt"></i><span>Permission</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="components-permission" class="nav-content collapse {{ request()->routeIs('permission*')?'show':''}}" data-bs-parent="#sidebar-nav">
            @if (Auth::user()->can('view-permission'))
            <li>
                <a href="{{ route('permission.index') }}" class="{{ request()->routeIs('permission.index')?'active':''}}">
                  <i class="bi bi-circle"></i><span>All Permision</span>
                </a>
            </li>
            @endif
            @if (Auth::user()->can('create-permission'))
            <li>
            <a href="{{ route('permission.create')}}" class="{{ request()->routeIs('permission.create')?'active':''}}">
              <i class="bi bi-circle"></i><span>Add Permission</span>
            </a>
          </li>
          @endif
        </ul>
      </li>
    @endif


{{-- ---------------------------------Roles-------------------------------------------- --}}
<li class="nav-item">
    @if(Auth::user()->can('view-roles') || Auth::user()->can('create-roles') || Auth::user()->can('edit-role') || Auth::user()->can('remove-role'))
    <a class="nav-link {{ request()->routeIs('roles*')?'':'collapsed'}}" data-bs-target="#components-roles" data-bs-toggle="collapse" href="#">
        <i class="nav-icon fas fa-user-tag"></i><span>Roles</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="components-roles" class="nav-content collapse {{ request()->routeIs('roles*')?'show':''}}" data-bs-parent="#sidebar-nav">
        @if (Auth::user()->can('view-role'))
        <li>
            <a href="{{ route('roles.index') }}" class="{{ request()->routeIs('roles.index')?'active':''}}">
              <i class="bi bi-circle"></i><span>All Roles</span>
            </a>
        </li>
        @endif
        @if (Auth::user()->can('create-role'))
        <li>
        <a href="{{ route('roles.create')}}" class="{{ request()->routeIs('roles.create')?'active':''}}">
          <i class="bi bi-circle"></i><span>Add Roles</span>
        </a>
      </li>
      @endif
    </ul>
  </li>
  @endif



  {{-- ----------------------------Menu----------------------------------- --}}
  @if (Auth::user()->can('view-menu') || Auth::user()->can('create-menu') || Auth::user()->can('edit-menu') || Auth::user()->can('remove-menu'))
  <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('menu*')?'':'collapsed'}}" data-bs-target="#components-menu" data-bs-toggle="collapse" href="#">
        <i class="bi bi-card-image"></i><span>Menu</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="components-menu" class="nav-content collapse {{ request()->routeIs('menu*')?'show':''}}" data-bs-parent="#sidebar-nav">
        @if (Auth::user()->can("view-menu"))
        <li>
            <a href="{{ route('menu.index') }}" class="{{ request()->routeIs('menu.index')?'active':''}}">
              <i class="bi bi-circle"></i><span>View Menu</span>
            </a>
        </li>
        @endif
        @if (Auth::user()->Can("create-menu"))
        <li>
        <a href="{{ route('menu.create')}}" class="{{ request()->routeIs('menu.create')?'active':''}}">
          <i class="bi bi-circle"></i><span>Create Menu</span>
        </a>
      </li>
      @endif
    </ul>
  </li>
  @endif
  {{-- ----------------------------/Menu----------------------------------- --}}



      {{-- ----------------------------Banner----------------------------------- --}}
      @if (Auth::user()->can('view-banner') || Auth::user()->can('create-banner') || Auth::user()->can('edit-banner') || Auth::user()->can('remove-banner'))
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('banner*')?'':'collapsed'}}" data-bs-target="#components-banner" data-bs-toggle="collapse" href="#">
            <i class="bi bi-card-image"></i><span>Banner</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="components-banner" class="nav-content collapse {{ request()->routeIs('banner*')?'show':''}}" data-bs-parent="#sidebar-nav">
            @if (Auth::user()->can("view-banner"))
            <li>
                <a href="{{ route('banner.index') }}" class="{{ request()->routeIs('banner.index')?'active':''}}">
                  <i class="bi bi-circle"></i><span>All Banner</span>
                </a>
            </li>
            @endif
            @if (Auth::user()->Can("create-banner"))
            <li>
            <a href="{{ route('banner.create')}}" class="{{ request()->routeIs('banner.create')?'active':''}}">
              <i class="bi bi-circle"></i><span>Add Banner</span>
            </a>
          </li>
          @endif
        </ul>
      </li>
      @endif
      {{-- ----------------------------/Banner----------------------------------- --}}




       {{-- ----------------------------Post----------------------------------- --}}
       @if (Auth::user()->can("view-post") || Auth::user()->can("create-post") || Auth::user()->Can("edit-post") || Auth::user()->can("remove-post") || Auth::user()->can("view-postcategory") || Auth::user()->can("create-postcategory") || Auth::user()->can("edit-postcategory") || Auth::user()->can("remove-postcategory") || Auth::user()->can("create-posttag") || Auth::user()->can("view-posttag") || Auth::user()->can("create-posttag") || Auth::user()->can("edit-posttag") || Auth::user()->can("remove-posttag"))
       <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('post*')?'':'collapsed'}}" data-bs-target="#components-post" data-bs-toggle="collapse" href="#">
            <i class="fa-solid fa-blog"></i></i><span>Post</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>

        <ul id="components-post" class="nav-content collapse {{ request()->routeIs('post*')?'show':''}}" data-bs-parent="#sidebar-nav">
            @if (Auth::user()->can("view-post"))
            <li>
                <a href="{{ route('post.index')}}" class="{{ request()->routeIs('post.index')?'active':''}}">
                <i class="bi bi-circle"></i><span>All Post</span>
                </a>
            </li>
            @endif
            @if (Auth::user()->can("create-post"))
            <li>
                <a href="{{ route('post.create')}}" class="{{ request()->routeIs('post.create')?'active':''}}">
                <i class="bi bi-circle"></i><span>Add Post</span>
                </a>
            </li>
            @endif

            @if (Auth::user()->can("view-postcategory") || Auth::user()->can("create-postcategory") || Auth::user()->can("edit-postcategory") || Auth::user()->can("remove-postcategory"))
            <li>
                <a href="{{ route('postcategory.index')}}" class="{{ request()->routeIs('postcategory.index')?'active':''}}">
                <i class="bi bi-circle"></i><span>Post Categories</span>
                </a>
            </li>
            @endif
            @if (Auth::user()->Can("view-posttag") || Auth::user()->can("create-posttag") || Auth::user()->can("edit-posttag") || Auth::user()->can("remove-posttag"))
            <li>
                <a href="{{route('posttag.index') }}" class="{{ request()->routeIs('posttag.index')?'active':''}}">
                <i class="bi bi-circle"></i><span>Tags</span>
                </a>
            </li>
            @endif
        </ul>
      </li>
      @endif
       {{-- ----------------------------Post----------------------------------- --}}

        {{-- ------------------------General Pages --------------------------- --}}
        @if (Auth::user()->can("create-generalpage") || Auth::user()->can("view-generalpage") || Auth::user()->can("edit-generalpage") || Auth::user()->can("remove-generalpage"))
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('generalPage*')?'':'collapsed'}}" data-bs-target="#components-generalPage" data-bs-toggle="collapse" href="#">
                <i class="bi bi-file-earmark-post"></i><span>Pages</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>

            <ul id="components-generalPage" class="nav-content collapse {{ request()->routeIs('generalPage*')?'show':''}}" data-bs-parent="#sidebar-nav">
                @if (Auth::user()->can("view-generalpage"))
                <li>
                    <a href="{{ route('generalPage.index') }}" class="{{ request()->routeIs('generalPage.index')?'active':''}}">
                      <i class="bi bi-circle"></i><span>All Pages</span>
                    </a>
                  </li>
                @endif
                @if(Auth::user()->can("create-generalpage"))
                <li>
                <a href="{{ route('generalPage.create')}}" class="{{ request()->routeIs('generalPage.create')?'active':''}}">
                  <i class="bi bi-circle"></i><span>Add New</span>
                </a>
                </li>
                @endif
            </ul>
          </li>
          @endif
          {{-- -------------------------end Pages---------------------------------- --}}


        {{-- ----------------------------Trip----------------------------------- --}}
        @if(Auth::user()->can
        ("view-trip") || Auth::user()->can("create-trip") || Auth::user()->can("edit-trip") || Auth::user()->can("remove-trip") || Auth::user()->can("view-tripcategory") || Auth::user()->can("edit-tripcategory") || Auth::user()->can("create-tripcategory") || Auth::user()->can("remove-tripcategory") || Auth::user()->can("create-triptag") || Auth::user()->Can("edit-triptag") || Auth::user()->can("view-triptag") || Auth::user()->can("remove-triptag"))
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('trip*')?'':'collapsed'}}" data-bs-target="#components-trip" data-bs-toggle="collapse" href="#">
                <i class="fa-solid fa-mountain-city"></i><span>Trip</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>

            <ul id="components-trip" class="nav-content collapse {{ request()->routeIs('trip*')?'show':''}}" data-bs-parent="#sidebar-nav">
              @if (Auth::user()->can("view-trip"))
                <li>
                    <a href="{{ route('trip.index')}}" class="{{ request()->routeIs('trip.index')?'active':''}}">
                    <i class="bi bi-circle"></i><span>All Trip</span>
                    </a>
                </li>
              @endif
              @if (Auth::user()->can("create-trip"))
                <li>
                    <a href="{{ route('trip.create')}}" class="{{ request()->routeIs('trip.create')?'active':''}}">
                    <i class="bi bi-circle"></i><span>Add Trip</span>
                    </a>
                </li>
                @endif
                @if (Auth::user()->can("view-tripcategory") || Auth::user()->Can("create-tripcategory") || Auth::user()->can("edit-tripcategory") || Auth::user()->can("remove-tripcategory"))
                <li>
                    <a href="{{ route('tripcategory.index')}}" class="{{ request()->routeIs('tripcategory.index')?'active':''}}">
                    <i class="bi bi-circle"></i><span>Trip Categories</span>
                    </a>
                </li>
                @endif
                @if (Auth::user()->can("create-triptag") || Auth::user()->can("view-triptag") || Auth::user()->can("edit-triptg") || Auth::user()->can("removes-triptag"))
                {{-- <li>
                    <a href="{{route('triptag.index') }}" class="{{ request()->routeIs('triptag.index')?'active':''}}">
                    <i class="bi bi-circle"></i><span>Trip Type</span>
                    </a>
                </li> --}}
                @endif
                @if (Auth::user()->can("create-trip") || Auth::user()->can("edit-trip"))
                <li>
                    <a href="{{ route('tripwhyMega.index')}}" class="{{ request()->routeIs('tripwhyMega.index')?'active':''}}">
                    <i class="bi bi-circle"></i><span>Why   Mega</span>
                    </a>
                </li>
                @endif
            </ul>
          </li>
          @endif
           {{-- ----------------------------Trip----------------------------------- --}}

        {{-- -------------------------------TestiMonials----------------------------------------- --}}
        @if (Auth::user()->can("view-testimonial") || Auth::user()->can("create-testimonial") || Auth::user()->can("edit-testimonial") || Auth::user()->can("remove-testimonial"))
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('testimonial*')?'':'collapsed'}}" data-bs-target="#components-testimonial" data-bs-toggle="collapse" href="#">
            <i class="fa fa-comments"></i><span>Testimonial</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>

        <ul id="components-testimonial" class="nav-content collapse {{ request()->routeIs('testimonial*')?'show':''}}" data-bs-parent="#sidebar-nav">
            @if (Auth::user()->can("view-testimonial"))
            <li>
                <a href="{{ route('testimonial.index') }}" class="{{ request()->routeIs('testimonial.index')?'active':''}}">
                  <i class="bi bi-circle"></i><span>All Testimonial</span>
                </a>
              </li>
            @endif
            @if (Auth::user()->can("create-testimonial"))
            <li>
            <a href="{{ route('testimonial.create')}}" class="{{ request()->routeIs('testimonial.create')?'active':''}}">
              <i class="bi bi-circle"></i><span>Add Testimonial</span>
            </a>
            </li>
            @endif
        </ul>
      </li>
      @endif
        {{-- ---------------------------------------------TestiMonials--------------------------------- --}}

         {{---------------------------------------------- Team -----------------------------------------------}}
         @if (Auth::user()->can("create-teammember") || Auth::user()->can("view-teammember") || Auth::user()->can("edit-teammember") || Auth::user()->can("remove-teammember") || Auth::user()->can("view-teamcategory") || Auth::user()->can("create-teamcategory") || Auth::user()->can("edit-teamcategory") || Auth::user()->can("remove-teamcategory"))
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('team*')?'':'collapsed'}}" data-bs-target="#components-team" data-bs-toggle="collapse" href="#">
                <i class="bi bi-people"></i><span>Team Member</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="components-team" class="nav-content collapse {{ request()->routeIs('team*')?'show':''}}" data-bs-parent="#sidebar-nav">
                @if (Auth::user()->can("view-teammember"))
                <li>
                    <a href="{{ route('team.index') }}" class="{{ request()->routeIs('team.index')?'active':''}}">
                    <i class="bi bi-circle"></i><span>All Team Member</span>
                    </a>
                </li>
                @endif
                @if (Auth::user()->can("create-teammember"))
                <li>
                    <a href="{{ route('team.create')}}" class="{{ request()->routeIs('team.create')?'active':''}}">
                    <i class="bi bi-circle"></i><span>Add New Member</span>
                    </a>
                </li>
                @endif
                @if (Auth::user()->can("view-teamcategory") || Auth::user()->can("create-teamcategory") || Auth::user()->can("edit-teamcategory") || Auth::user()->can("remove-teamcategory"))
                <li>
                    <a href="{{ route('teamcategory.index')}}" class="{{ request()->routeIs('teamcategory.index')?'active':''}}">
                    <i class="bi bi-circle"></i><span>Team Categories</span>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif
        {{---------------------------------------------------------------- Team --------------------------------------------------}}


        {{-- triptravel --}}
        @if (Auth::user()->can("view-travelinfo") || Auth::user()->can("create-travelinfo") || Auth::user()->can("edit-travelinfo") || Auth::user()->can("remove-tarvelinfo")
        || Auth::user()->can("view-travelcategory") || Auth::user()->can("create-travelcategory") || Auth::user()->can("edith-tarvelcategory") || Auth::user()->can("remove-travelcategory")
         || Auth::user()->can("create-traveltriptype") || Auth::user()->can("view-traveltriptype") || Auth::user()->can("edit-traveltriptype") || Auth::user()->can("remove-traveltriptype"))
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('travel*')?'':'collapsed'}}" data-bs-target="#components-travel" data-bs-toggle="collapse" href="#">
                <i class="fa-solid fa-helicopter"></i><span>Travel Infos</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="components-travel" class="nav-content collapse {{request()->routeIs('travel*')?'show':''}}" data-bs-parent="#sidebar-nav">
                @if (Auth::user()->can("view-travelinfo"))
                <li>
                    <a href="{{route('travelinfo.index')}}" class="{{ request()->routeIs('travelinfo.index')?'active':''}}">
                      <i class="bi bi-circle"></i><span> All Travel Infos</span>
                    </a>
                </li>
                @endif
                @if (Auth::user()->can("create-travelinfo"))
                <li>
                    <a href="{{route('travelinfo.create')}}" class="{{ request()->routeIs('travelinfo.create')?'active':''}}">
                      <i class="bi bi-circle"></i><span> Add New</span>
                    </a>
                </li>
                @endif
                @if(Auth::user()->can("view-travelcategory") || Auth::user()->can("create-travelcategory") || Auth::user()->can("edit-travelcategory") || Auth::user()->can("remove-travelcategory"))
                <li>
                    <a href="{{route('travelcategory.index')}}" class="{{ request()->routeIs('travelcategory.index')?'active':''}}">
                      <i class="bi bi-circle"></i><span>Travel Categories</span>
                    </a>
                </li>
                @endif
                @if (Auth::user()->can("view-traveltriptype") || Auth::user()->can("create-traveltriptype") || Auth::user()->can("edit-traveltriptype") || Auth::user()->can("remove-traveltriptype"))
                <li>
                <a href="{{ route('traveltriptype.index')}}" class="{{ request()->routeIs('traveltriptype.index')?'active':''}}">
                  <i class="bi bi-circle"></i><span>Trip Type</span>
                </a>
              </li>
              @endif
            </ul>
          </li>
          @endif

      {{-- Cyber cast --}}
      @if (Auth::user()->can("view-cybercast") || Auth::user()->can("create-cybercast") || Auth::user()->can("edit-cybercast") || Auth::user()->can("remove-cybercast") ||Auth::user()->can("view-cybercastpost")
          || Auth::user()->can("create-cybercastpost") || Auth::user()->can("edit-cybercastpost") || Auth::user()->can("remove-cybercastpost") || Auth::user()->can("view-cybercategory") || Auth::user()->can("create-cybercategory") || Auth::user()->can("edit-cybercategory") || Auth::user()->can("remove-cybercategory"))
      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('cyberCast*')?'':'collapsed'}}" data-bs-target="#components-cyberCast" data-bs-toggle="collapse" href="#">
            <i class="fa-solid fa-robot"></i><span>Cyber Casts</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="components-cyberCast" class="nav-content collapse {{ request()->routeIs('cyberCast*')?'show':''}}" data-bs-parent="#sidebar-nav">
            @if (Auth::user()->can("view-cybercast"))
            <li>
                <a href="{{route('cyberCast.index')}}" class="{{ request()->routeIs('cyberCast.index')?'active':''}}">
                <i class="bi bi-circle"></i><span>All Cyber Casts</span>
                </a>
            </li>
            @endif
            @if (Auth::user()->can("create-cybercast"))
            <li>
                <a href="{{route('cyberCast.create')}}" class="{{ request()->routeIs('cyberCast.create')?'active':''}}">
                <i class="bi bi-circle"></i><span>Add New</span>
                </a>
            </li>
            @endif
            @if (Auth::user()->can("view-cybercategory") || Auth::user()->can("create-cybercategory") || Auth::user()->can("edit-cybercategory") || Auth::user()->can("remove-cybercategory"))
            <li>
                <a href="{{ route('cyberCategory.index')}}" class="{{ request()->routeIs('cyberCategory.index')?'active':''}}">
                <i class="bi bi-circle"></i><span>Cyber Categories</span>
                </a>
            </li>
            @endif
            {{-- Cyber Cast Post --}}
            @if (Auth::user()->can("view-cybercastpost"))
            <li>
                <a href="{{route('cyberCastPost.index')}}" class="{{ request()->routeIs('cyberCastPost.index')?'active':''}}">
                <i class="bi bi-circle"></i><span>Cyber Cast Posts</span>
                </a>
            </li>
            @endif
            @if (Auth::user()->can("create-cybercastpost"))
            <li>
                <a href="{{ route('cyberCastPost.create')}}" class="{{ request()->routeIs('cyberCastPost.create')?'active':''}}">
                <i class="bi bi-circle"></i><span>Add New Cyber Cast Post</span>
                </a>
            </li>
            @endif
        </ul>
    </li>
    @endif

    {{-- --------------------------------Information--------------------------------- --}}
    @if (Auth::user()->can("view-information") || Auth::user()->can("create-information") || Auth::user()->can("edit-information") || Auth::user()->Can("remove-information"))
     <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('cyberCast*')?'':'collapsed'}}" data-bs-target="#components-Information" data-bs-toggle="collapse" href="#">
            <i class="fa fa-info-circle"></i><span>ESSENTIAL TRAVEL INFORMATION</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="components-Information" class="nav-content collapse {{ request()->routeIs('cyberCast*')?'show':''}}" data-bs-parent="#sidebar-nav">
            @if (Auth::user()->can("view-information"))
            <li>
                <a href="{{route('information.index')}}" class="{{ request()->routeIs('cyberCast.index')?'active':''}}">
                <i class="bi bi-circle"></i><span>All Information</span>
                </a>
            </li>
            @endif
            @if (Auth::user()->can("create-information"))
            <li>
                <a href="{{route('information.create')}}" class="{{ request()->routeIs('cyberCast.create')?'active':''}}">
                <i class="bi bi-circle"></i><span>Add New</span>
                </a>
            </li>
            @endif
        </ul>
    </li>
    @endif
    {{-- --------------------------------Information--------------------------------- --}}

    {{-- Cyber cast post --}}
    {{-- <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('cyberCastPost*')?'':'collapsed'}}" data-bs-target="#components-cyberCastPost" data-bs-toggle="collapse" href="#">
        <i class="bi bi-back"></i><span>Cyber Cast Posts</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>

        <ul id="components-cyberCastPost" class="nav-content collapse {{ request()->routeIs('cyberCastPost*')?'show':''}}" data-bs-parent="#sidebar-nav">

        </ul>
    </li> --}}
{{-- Cyber Cast Post --}}

       {{-- ----------------------------Hike----------------------------------- --}}
       @if (Auth::user()->can('view-hike') || Auth::user()->can('create-hike') || Auth::user()->can('edit-hike') || Auth::user()->can('remove-hike'))
       <li class="nav-item">
         <a class="nav-link {{ request()->routeIs('hike*')?'':'collapsed'}}" data-bs-target="#components-hike" data-bs-toggle="collapse" href="#">
             <i class="fa fa-users"></i><span>Community Support</span><i class="bi bi-chevron-down ms-auto"></i>
         </a>
         <ul id="components-hike" class="nav-content collapse {{ request()->routeIs('hike*')?'show':''}}" data-bs-parent="#sidebar-nav">
            @if (Auth::user()->can("view-hike"))
             <li>
                 <a href="{{ route('hike.index') }}" class="{{ request()->routeIs('hike.index')?'active':''}}">
                   <i class="bi bi-circle"></i><span>All Hike</span>
                 </a>
            </li>
            @endif
                @if (Auth::user()->can("create-hike"))
             <li>
             <a href="{{ route('hike.create')}}" class="{{ request()->routeIs('hike.create')?'active':''}}">
               <i class="bi bi-circle"></i><span>Add Hike</span>
             </a>
           </li>
           @endif
         </ul>
       </li>
       @endif
       {{-- ----------------------------/Hike----------------------------------- --}}



{{-- ------------------------------------------General Faqs------------------------------------- --}}
    @if (Auth::user()->can("view-generalfaq") || Auth::user()->can("create-generalfaq") || Auth::user()->can("edit-generalfaq") || Auth::user()->can("remove-general"))
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('generalFaq*')?'':'collapsed'}}" data-bs-target="#components-generalFaq" data-bs-toggle="collapse" href="#">
            <i class="fa fa-comment"></i><span>General FAQs</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="components-generalFaq" class="nav-content collapse {{ request()->routeIs('generalFaq*')?'show':''}}" data-bs-parent="#sidebar-nav">
            @if (Auth::user()->can("view-generalfaq"))
            <li>
                <a href="{{ route('generalFaq.index') }}" class="{{ request()->routeIs('generalFaq.index')?'active':''}}">
                  <i class="bi bi-circle"></i><span>All Items</span>
                </a>
              </li>
            @endif
            @if (Auth::user()->can("create-generalfaq"))
            <li>
            <a href="{{ route('generalFaq.create')}}" class="{{ request()->routeIs('generalFaq.create')?'active':''}}">
              <i class="bi bi-circle"></i><span>Add New</span>
            </a>
          </li>
          @endif

          @if (Auth::user()->can("create-generalfaq") || Auth::user()->can("edit-generalfaq"))
          <li>
          <a href="{{ route('faqDesign.index')}}" class="{{ request()->routeIs('faqDesign.edit')?'active':''}}">
            <i class="bi bi-circle"></i><span>Design FAQ</span>
          </a>
        </li>
        @endif
        </ul>
      </li>
      @endif
      {{-- ---------------------------Out Partners------------------------------ --}}
      @if (Auth::user()->can("create-partner") || Auth::user()->can("edit-partner") || Auth::user()->can("view-partner") || Auth::user()->can("remove-partner"))
      <li class="nav-item ">
        <a class="nav-link  {{ request()->routeIs('partner.index') || request()->routeIs('partner.edit')?'':'collapsed'}}" href="{{ route('partner.index') }}">
          <i class="fa fa-handshake"></i>
          <span>Our Partners</span>
        </a>
      </li>
      @endif
      {{-- end --}}

      @if (Auth::user()->can("create-about") || Auth::user()->can("edit-about"))
      <li class="nav-item ">
        <a class="nav-link  {{ request()->routeIs('all-messages.index') || request()->routeIs('all-messages.edit')?'':'collapsed'}}" href="{{ route('all-messages.index') }}">
          <i class="bi bi-file-earmark-person"></i>
          <span>All Messages</span>
        </a>
      </li>
      @endif

      {{-- ----------------------------About Us----------------------------------- --}}
      @if (Auth::user()->can("create-about") || Auth::user()->can("edit-about"))
        <li class="nav-item ">
          <a class="nav-link  {{ request()->routeIs('about.index') || request()->routeIs('about.edit')?'':'collapsed'}}" href="{{ route('about.index') }}">
            <i class="bi bi-file-earmark-person"></i>
            <span>About Us</span>
          </a>
        </li>
        @endif
      {{-- ----------------------------/About Us----------------------------------- --}}
  </aside>
