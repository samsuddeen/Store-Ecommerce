<header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="" class="logo d-flex align-items-center">
        {{-- <img src="{{ asset('backend/assets/img/ecohot.png') }}" alt="" > --}}
        <span class="d-none d-lg-block">Mega AdventureIntl</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

         <li class="nav-item dropdown">

          <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
            <i class="bi bi-bell"></i>
            <span class="badge bg-primary badge-number">{{$messages->count()}}</span>
          </a><!-- End Notification Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
            <li class="dropdown-header">
              You have {{$messages->count()}} new notifications
              <a href=""><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            @if($messages->count() >0)

            @foreach($messages as $msg)
              <li class="notification-item">

                <i class=" bi bi-check-circle  text-success"></i>
                <div>
                  <h4>{{ ucfirst($msg->full_name)}}</h4>
                  <h4><a href="mailto:{{$msg->email}}" target="_blank">{{ ($msg->email)}}</a></h4>
                  <p>{{ $msg->message}}</p>
                  <p>{{ $msg->contact}}</p>
                  <p>{{ $msg->created_at->diffForHumans()}}</p>
                </div>
              </li>
              <li>
                <hr class="dropdown-divider">
              </li>
            @endforeach
            @else
              <li>
                <div style="margin-left:40px;">
                <p style="color:red">"No New Message Yet...."</p>
              </div>
              </li>
            @endif


            <li class="dropdown-footer">
              <a href="">Show all notifications</a>
            </li>

          </ul><!-- End Notification Dropdown Items -->

        </li>

        <!-- End Notification Nav -->



        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            @if(auth()->user()->UserInfo && auth()->user()->UserInfo->image !=null)
            <img src="{{ asset('uploads/user/'.auth()->user()->UserInfo->image) }}" alt="Profile" class="rounded-circle">
            @else
            <img src="{{ asset('dummy.jpeg') }}" alt="Profile" class="rounded-circle">
            @endif
            <span class="d-none d-md-block dropdown-toggle ps-2">{{ ucfirst(auth()->user()->name ?? '')}}</span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6>{{ ucfirst(auth()->user()->name ?? '')}}</h6>
              <span>{{ ucfirst(auth()->user()->role ?? '')}}</span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="javascript:;" data-bs-toggle="modal" data-bs-target="#updateProfile">
                <i class="bi bi-person"></i>
                <span>Update Profile</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>


            <li>
              <a class="dropdown-item d-flex align-items-center" href="javascript:;" data-bs-toggle="modal" data-bs-target="#updatePassword">
                <i class="bi bi-key"></i>
                <span>Update Password</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>



            <li>
              <a class="dropdown-item d-flex align-items-center" href="javascript:;" onclick="event.preventDefault();document.getElementById('logout').submit();">
                {{ Form::open(['url'=>route('logout'),'id'=>'logout'])}}
                {{ Form::close()}}
                <i class="bi bi-box-arrow-right"></i>
                <span>Log Out</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header>


  {{-- -------------------------------------Update Profile---------------------------------------- --}}
  <div class="modal fade" id="updateProfile" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h5 class="modal-title text-white">Update Profile</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          {{ Form::open(['url'=>route('user.update-profile',auth()->user()->id),'files'=>true,'class'=>'row g-3'])}}
          @method('put')
            <div class="col-12">
              {{ Form::label('name','Name:')}}
              {{ Form::text('name',auth()->user()->name ?? '',['class'=>'form-control form-control-sm '.($errors->has('name') ?'is-invalid':''),'required'=>true,'placeholder'=>'Enter Profile Name Here.....'])}}
              @error('name')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>

            <div class="col-12">
              {{ Form::label('email','Email:')}}
              {{ Form::text('email',auth()->user()->email ?? '',['class'=>'form-control form-control-sm '.($errors->has('email') ?'is-invalid':''),'required'=>true,'placeholder'=>'Enter Email Here.....','readonly'=>'readonly'])}}
              @error('email')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>

            <div class="col-12">
              {{ Form::label('address','Address:')}}
              {{ Form::textarea('address',auth()->user()->UserInfo->address ?? '',['class'=>'form-control form-control-sm '.($errors->has('address') ?'is-invalid':''),'required'=>false,'placeholder'=>'Enter Address Here.....','rows'=>4,'style'=>'resize:none;'])}}
              @error('address')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>

            <div class="col-12">
              {{ Form::label('phone','Phone Num:')}}
              {{ Form::tel('phone',auth()->user()->UserInfo->phone ?? '',['class'=>'form-control form-control-sm '.($errors->has('phone') ?'is-invalid':''),'required'=>false,'placeholder'=>'Enter Phone Num Here.....'])}}
              @error('phone')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>

            <div class="col-12">
              {{ Form::label('image','Profile Image:')}}
              {{ Form::file('image',['class'=>($errors->has('image') ?'is-invalid':''),'required'=>false,'accept'=>'image/*'])}}
              @error('image')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>

            @if(auth()->user()->UserInfo && auth()->user()->UserInfo->image !=null)
            <div>
              <img src="{{ asset('uploads/users/'.auth()->user()->UserInfo->image)}}" alt="" class="img-fluid img-thumbnail">
            </div>
            @endif


            <div class="modal-footer">
              {{ Form::button('<i class="bi bi-x"></i> Reset',['class'=>'btn btn-sm btn-danger','type'=>'reset'])}}
              {{ Form::button('<i class="bi bi-send"></i> Update',['class'=>'btn btn-sm btn-success','type'=>'submit'])}}
            </div>
          {{ Form::close()}}
        </div>

      </div>
    </div>
  </div>
  {{-- -------------------------------------Update Profile---------------------------------------- --}}


  {{-- -------------------------------------Update Password---------------------------------------- --}}
  <div class="modal fade" id="updatePassword" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-danger">
          <h5 class="modal-title text-white">Update Password</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          {{ Form::open(['url'=>route('user.update-password',auth()->user()->id),'files'=>true,'class'=>'row g-3'])}}
          @method('put')
            <div class="col-12">
              {{ Form::label('old_password','Old Password:')}}
              {{ Form::password('old_password',['class'=>'form-control form-control-sm '.($errors->has('old_password') ?'is-invalid':''),'required'=>true,'placeholder'=>'Enter Old Password Here.....'])}}
              @error('old_password')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>
            <div class="col-12">
              {{ Form::label('password','New Password:')}}
              {{ Form::password('password',['class'=>'form-control form-control-sm '.($errors->has('password') ?'is-invalid':''),'required'=>true,'placeholder'=>'Enter New Password Here.....'])}}
              @error('password')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>

            <div class="col-12">
              {{ Form::label('password_confirmation','Re-Type Password:')}}
              {{ Form::password('password_confirmation',['class'=>'form-control form-control-sm '.($errors->has('password_confirmation') ?'is-invalid':''),'required'=>true,'placeholder'=>'Enter Password Again.....'])}}
              @error('password_confirmation')
                <span class="text-danger">{{ $message }}</span>
              @enderror
            </div>


            <div class="modal-footer">
              {{ Form::button('<i class="bi bi-x"></i> Reset',['class'=>'btn btn-sm btn-danger','type'=>'reset'])}}
              {{ Form::button('<i class="bi bi-send"></i> Update',['class'=>'btn btn-sm btn-success','type'=>'submit'])}}
            </div>
          {{ Form::close()}}
        </div>

      </div>
    </div>
  </div>
  {{-- -------------------------------------Update Password---------------------------------------- --}}

