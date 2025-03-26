@extends('layouts.app')
@section('content')
    <section class="app-user-view-account">
        <div class="row">
            <!-- User Sidebar -->
            <div class="col-12">
                <!-- User Card -->
                <div class="card">
                    <div class="card-body">
                        <div class="user-avatar-section">
                            <div class="d-flex align-items-center flex-column">
                                <img class="img-fluid rounded mt-3 mb-2" src="{{ auth()->guard('seller')->user()->photo }}"
                                    height="110" width="110" alt="Profile" />
                                <div class="user-info text-center">
                                    <h4>{{ $user->name }}</h4>
                                    <span
                                        class="badge bg-light-secondary">{{ implode(',', $user->roles?->pluck('name')->toArray()) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-around my-2 pt-75">
                            <div class="d-flex align-items-start me-2">
                                <span class="badge bg-light-primary p-75 rounded">
                                    <i data-feather="check" class="font-medium-2"></i>
                                </span>
                                <div class="ms-75">
                                    <h4 class="mb-0">{{ count($total_orders) }}</h4>
                                    <small>Total Orders</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-start">
                                <span class="badge bg-light-primary p-75 rounded">
                                    <i data-feather="briefcase" class="font-medium-2"></i>
                                </span>
                                <div class="ms-75">
                                    <h4 class="mb-0">{{ count($total_delivered) }}</h4>
                                    <small>Total Delivered</small>
                                </div>
                            </div>
                        </div>
                        <h4 class="fw-bolder border-bottom pb-50 mb-1">Details</h4>
                        <div class="info-container">
                            <ul class="list-unstyled">
                                <li class="mb-75">
                                    <span class="fw-bolder me-25">Seller ID:</span>
                                    <span>#{{ $user->id }}</span>
                                </li>
                                <li class="mb-75">
                                    <span class="fw-bolder me-25">Seller Name:</span>
                                    <span>{{ $user->name }}</span>
                                </li>
                                <li class="mb-75">
                                    <span class="fw-bolder me-25"> Email:</span>
                                    <span>{{ $user->email }}</span>
                                </li>
                                <li class="mb-75">
                                    <span class="fw-bolder me-25">Status:</span>
                                    {!! $user->status
                                        ? '<span class="badge bg-light-success">Active</span>'
                                        : ' <span class="badge bg-light-danger">Deactivated</span>' !!}
                                </li>
                                <li class="mb-75">
                                    <span class="fw-bolder me-25">Role:</span>
                                    <span>{{ implode(',', $user->roles?->pluck('name')->toArray()) }}</span>
                                </li>

                                <li class="mb-75">
                                    <span class="fw-bolder me-25">Contact:</span>
                                    <span>{{ $user->phone }}</span>
                                </li>

                                <li class="mb-75">
                                    <span class="fw-bolder me-25">Province :</span>
                                    <span>{{ $province->eng_name }}</span>
                                </li>

                                <li class="mb-75">
                                    <span class="fw-bolder me-25">District:</span>
                                    <span>{{ $district->np_name }}</span>
                                </li>

                                <li class="mb-75">
                                    <span class="fw-bolder me-25">Area:</span>
                                    <span>{{ @$user_area->local_name }}</span>
                                </li>

                                <li class="mb-75">
                                    <span class="fw-bolder me-25">Address:</span>
                                    <span>{{ @$user_address->title }}</span>
                                </li>
                            </ul>
                            <div class="d-flex justify-content-center pt-2">
                                <button type="button" class="btn btn-primary m-1" data-bs-toggle="modal"
                                    data-bs-target="#editProfile">
                                    Edit Profile
                                </button>


                                <button type="button" class="btn btn-danger m-1" data-bs-toggle="modal"
                                    data-bs-target="#changePassword">
                                    Change Password
                                </button>


                                {{-- <button type="button" class="btn btn-danger m-1" data-bs-toggle="modal"
                                    data-bs-target="#deleteAccount">
                                    Delete Account
                                </button> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /User Card -->

            </div>
        </div>


        {{-- for change Password --}}
        <!-- Modal -->
        <form action="{{ route('profile.changePassword', $user->id) }}" method="post">
            @csrf
            @method('PATCH');
            <!-- Modal -->
            <div class="modal fade" id="changePassword" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Change Password</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="current_password">Current Password</label>
                                <input type="password" name="current_password" id="current_password" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" name="password" id="password" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation">Confirm Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Change Now</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </section>

    {{-- modal for edit profile --}}
    <form action="{{ route('profile.update', $user->id) }}" method="post">
        @method('PATCH');
        @csrf
        <div class="modal fade" id="editProfile" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Update Profile</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="name">Name :</label>
                            <input type="name" name="name" id="name" class="form-control"
                                value="{{ auth()->guard('seller')->user()->name ?? '' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone :</label>
                            <input type="phone" name="phone" id="phone" class="form-control"
                                value="{{ auth()->guard('seller')->user()->phone ?? '' }}" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email :</label>
                            <input type="email" name="email" id="email" class="form-control"
                                value="{{ auth()->guard('seller')->user()->email ?? '' }}" required>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group">
                                    {{ Form::label('province', 'Province') }}
                                    {{ Form::select('province_id', $provinces->pluck('eng_name', 'id'), @$user->province_id, ['class' => 'provinces form-control form-select ' . ($errors->has('province') ? 'is-invalid' : ''), 'placeholder' => '---Select Any One---', 'required' => true]) }}
                                    @error('province')
                                        <div class="invalid-feedback">
                                            <i class="bx bx-radio-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group">
                                    {{ Form::label('district', 'District') }}
                                    {{ Form::select('district_id', [], @$user->district_id, ['class' => 'district form-control form-select ' . ($errors->has('district') ? 'is-invalid' : ''), 'placeholder' => '---Select Any One---', 'required' => true]) }}
                                    @error('district')
                                        <div class="invalid-feedback">
                                            <i class="bx bx-radio-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group">
                                    {{ Form::label('area', 'Area') }}
                                    {{ Form::select('area', [], [], ['class' => 'area form-control form-select ' . ($errors->has('area') ? 'is-invalid' : ''), 'placeholder' => '---Select Any One---', 'required' => true]) }}
                                    @error('area')
                                        <div class="invalid-feedback">
                                            <i class="bx bx-radio-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="form-group">
                                    {{ Form::label('additional_address', 'Additional Area') }}
                                    <input type="text" name="address" value="{{@$user->address}}" class = 'form-control form-select '>
                                    {{-- {{ Form::select('address', [], [], ['class' => 'additional_address form-control form-select ' . ($errors->has('addtional_address') ? 'is-invalid' : ''), 'placeholder' => '---Select Any One---']) }} --}}
                                    @error('additional_address')
                                        <div class="invalid-feedback">
                                            <i class="bx bx-radio-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                       
                        <div class="form-group mt-2" >
                            <div class="input-group">
                                <span class="input-group-btn">
                                  <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary">
                                    <i class="fa fa-picture-o"></i> Choose Profile Image
                                  </a>
                                </span>
                                <input id="thumbnail" class="form-control" type="text" name="photo" value="{{ auth()->guard('seller')->user()->photo ?? '' }}">
                              </div>                              
                        </div>

                        <div class="form-group">
                            <label for="password">Current Password</label>
                            <input type="password" name="password" id="password" placeholder="comnfirm password"
                                class="form-control" required>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Update Now</button>
                        </div>
                    </div>
                </div>
            </div>
    </form>

    {{-- modal for Delete profile --}}
    {{-- 
    <form action="{{ route('profile.destroy', $user->id) }}" method="post">
        @csrf
        @method('DELETE')
        <div class="modal fade" id="deleteAccount" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Delete Account</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <h2>Are You sure, you want to delete your account ?</h2>

                        <h3 class="text-danger"> You Can't retrive your Account.</h3>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form> --}}
@endsection

@push('script')
    <script>
        $('.provinces').change(function(e) {
            e.preventDefault();
            const province_id = $(this).val();
            const districtId = {{$user->district_id ?? 0}};
            $.ajax({
                url: "{{ route('show-province') }}",
                type: "get",
                data: {
                    province_id: province_id
                },
                success: function(response) {
                    if (typeof(response) != 'object') {
                        response = JSON.parse(response);
                    }
                    var dist_html =
                        "<option value=''>---Select Any One---</option>";
                    if (response.error) {
                        alert('something is wrong, please select one.');
                    } else {
                        if (response.data.child.length > 0) {
                            $.each(response.data.child, function(index, value) {
                                
                                dist_html += "<option value='" + value.id + "'";
                                if(districtId==value.id)
                                {
                                    dist_html+='selected';
                                }
                                dist_html += ">" + value.np_name + "</option>";
                            });
                        }
                    }
                    $('.district').html(dist_html);
                }
            });
        });
        @isset($user)
            $('.provinces').change();
        @endisset
    </script>


    {{-- district --}}
    <script>
        $(document).ready(function(){
            @isset($user)
            var district_ids={{$user->district_id ?? 0}};
            @else
            var district_ids=0;
            @endisset
            $('.district').change(function(e) {
                e.preventDefault();
                 district_id = $(this).val() ? $(this).val() : district_ids;
                const areaId = {{@$user->area ?? 0}};
                $.ajax({
                    url: "{{ route('show-district') }}",
                    type: "get",
                    data: {
                        district_id: district_id
                    },
                    success: function(response) {
                        if (typeof(response) != 'object') {
                            response = JSON.parse(response);
                        }
                        var area_html =
                            "<option value=''>---Select Any One---</option>";
                        if (response.error) {
                            alert('something is wrong, please select one.');
                        } else {
                            if (response.data.child.length > 0) {
                                $.each(response.data.child, function(index, value) {
                                    area_html += "<option value='" + value.id + "'";
                                    if(areaId==value.id)
                                    {
                                        area_html+='selected';
                                    }
                                    area_html += ">" + value.local_name + "</option>";
                                });
                            }
                        }
                        $('.area').html(area_html);
                    }
                });
            });
            @isset($user)
                $('.district').change();
            @endisset
        });
       
    </script>

    {{-- Additional address --}}

    <script>
        $(document).ready(function()
        {
            @isset($user)
            var areas_ids={{$user->area ?? 0}};
            @else
            var areas_ids=0;
            @endisset
            $('.area').change(function(e) {
            e.preventDefault();
            area_id = $(this).val() ? $(this).val() : areas_ids;
            const addressId={{$user->address ?? 0}};
            $.ajax({
                url: "{{ route('get-addtional-address') }}",
                type: "get",
                data: {
                    area_id: area_id
                },
                success: function(response) {
                    if (typeof(response) != 'object') {
                        response = JSON.parse(response);
                    }

                    var address_html =
                        "<option value=''>---Select Any Two---</option>";
                    if (response.error) {
                        alert(response.error);
                    } else {
                        if (response.data.child.length > 0) {
                            $.each(response.data.child, function(index, value) {
                                address_html += "<option value='" + value.id + "'";
                                if(addressId==value.id)
                                {
                                    address_html+='selected';
                                }
                                address_html += ">" + value.title + "</option>";
                            });

                        }
                    }
                    $('.additional_address').html(address_html);
                }
            });
        });
        $('.area').change();
        });
       
    </script>

    <script>
        $('#lfm').filemanager("image", {
            prefix: "/laravel-filemanager"
        });
    </script>
@endpush    