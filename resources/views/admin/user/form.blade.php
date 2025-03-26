@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'User Create')
@section('content')
    <section class="app-user-view-account">
        <div class="row">
            <div class="card">

                <div class="card-body">
                    @if ($user->id)
                        <form class="row gy-1 pt-75" action="{{ route('user.update', $user->id) }}" method="post">
                            @method('PATCH')
                        @else
                            <form class="row gy-1 pt-75" action="{{ route('user.store') }}" method="post">
                    @endif
                    @csrf
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="name">Username</label>
                        <input type="text" id="name" name="name" class="form-control" required
                            value="{{ old('name', $user->name) }}" placeholder="john.doe.007" />
                        @error('name')
                            <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="email"> Email:</label>
                        <input type="email" id="email" name="email" class="form-control" required
                            value="{{ old('email', $user->email) }}" placeholder="example@domain.com" />
                        @error('email')
                            <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label" for="address">Address</label>
                        <input type="text" id="address" name="address" class="form-control" required
                            placeholder="Sundhara, New Road  , Kathmandu" value="{{ old('address', $user->address) }}" />
                        @error('address')
                            <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="phone">Contact</label>
                        <input type="number" id="phone" name="phone" class="form-control" required
                            value="{{ old('phone', $user->phone) }}" placeholder="9134622145" />
                        @error('phone')
                            <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                        @enderror
                    </div>

                    @if (!$user->id)
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="password"> password:</label>
                            <input type="password" id="password" name="password" class="form-control" required />
                            @error('password')
                                <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label" for="password_confirmation">password confirmation</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="form-control" />
                                @error('password_confirmation')
                                <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    

                    
                    @if(Auth::user()->hasRole('super admin'))
                    <div class="col-12 col-md-6">
                        <div class="d-flex align-items-center mt-1">
                            <div class="form-check form-switch form-check-primary">
                                <input type="checkbox" class="form-check-input" id="customSwitch10" name="status"
                                    {{ $user->status ? 'checked' : null }} />
                                <label class="form-check-label" for="customSwitch10">
                                    <span class="switch-icon-left"><i data-feather="check"></i></span>
                                    <span class="switch-icon-right"><i data-feather="x"></i></span>
                                </label>
                            </div>
                            <label class="form-check-label fw-bolder" for="customSwitch10">User Status</label>
                            @error('status')
                                <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    @else
                    <input type="hidden" class="form-control" name="status" value="{{ $user->status }}">
                    @endif
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            @if(Auth::user()->hasRole('super admin'))
                            <label for="">Role</label>
                            <select class="form-control" name="role_id">
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}"
                                        {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            @else
                                <input type="hidden" class="form-control" name="role_id" value="{{ @$userRole->id }}">
                            @endif
                        </div>
                    </div>
                    
                    <div class="col-12 col-md-6">
                        <div class="input-group">
                            <span class="input-group-btn">
                                <label for="photo">Profile Image</label>
                              <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary">
                                <i class="fa fa-picture-o"></i> Choose
                              </a>
                            </span>
                            <input id="thumbnail" class="form-control" type="text" name="photo">
                          </div>
                          <img id="holder" style="margin-top:15px;max-height:100px;">
                          @isset($user->photo)
                          <div class="col-md-2">
                              <img src="{{$user->photo}}" alt="" class="img img-fluid img-thumbnail">
                          </div>
                      @endisset
                    </div>
                   
                    <div class="col-12 mt-2 pt-50">
                        <button type="submit" class="btn btn-primary me-1">Submit</button>
                        <button type="reset" class="btn btn-outline-secondary" aria-label="Close">
                            Discard
                        </button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script>
         $('#lfm').filemanager('image');
    </script>
@endpush
