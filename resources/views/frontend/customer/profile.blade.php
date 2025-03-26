@extends('frontend.layouts.app')
@section('title','Customer|Profile')
@section('content')
    
    <!-- Modal -->
    <div class="common-popup medium-popup modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Update Profile
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('editCProfile') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            @if ($errors->any())
                                @foreach ($errors->all() as $error)
                                    <div class="text-danger">
                                        {{ $error }}
                                    </div>
                                @endforeach
                            @endif
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleFormControlInput1" class="form-label">Full
                                        Name</label>
                                    <div class="input_field">
                                        <input type="text" name="name"
                                            value="{{ auth()->guard('customer')->user()->name }}" class="form-control"
                                            id="exampleFormControlInput1" placeholder="Name" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleFormControlInput1" class="form-label">Phone
                                        Number</label>
                                    <div class="input_field">
                                        <input type="text" name="phone"
                                            value="{{ auth()->guard('customer')->user()->phone }}" class="form-control"
                                            id="exampleFormControlInput1" placeholder="Phone Number" required>
                                    </div>
                                </div>
                            </div>
                            {{-- @dd(auth()->guard('customer')->user()) --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleFormControlInput1" class="form-label">Province</label>
                                    <div class="input_field">
                                        <select name="province" class="form-select form-control provinces" required>
                                           
                                            <option>---Select Any One----</option>
                                            @foreach ($provinces as $province)
                                                <option value="{{ $province->id }}" {{(auth()->guard('customer')->user()->province==$province->id) ? 'selected':''}}>
                                                    {{ $province->eng_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleFormControlInput1" class="form-label">District</label>
                                    <div class="input_field">
                                        <select name="district" class="form-select form-control district" required>
                                            
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleFormControlInput1" class="form-label">Area</label>
                                    <div class="input_field">
                                        <select name="area" class="form-select form-control area" required>
                                            
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleFormControlInput1" class="form-label">Additional Address</label>
                                    <div class="input_field">
                                        <input type="text" class="form-control" name="address" value="{{(auth()->guard('customer')->user()->address) ?? ''}}" required>
                                        {{-- <select name="additional_address" class="form-select form-control additional_address">
                                            
                                        </select> --}}
                                    </div>
                                </div>
                            </div>
                            {{-- @dd(auth()->guard('customer')->user()->photo) --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleFormControlInput1" class="form-label">Your
                                        Image</label>
                                    <div class="input_field">
                                        <input type="file" name="photo" accept="image/*">
                                    </div>
                                </div>

                                @if (isset(auth()->guard('customer')->user()->photo))
                                <img src="{{ Storage::url(auth()->guard('customer')->user()->photo) }}"
                                    alt="" height="100px" width='100px'>
                                @elseif(isset(auth()->guard('customer')->user()->social_avatar))
                                <img src="{{auth()->guard('customer')->user()->social_avatar}}" alt="" height="100px" width='100px'>
                                @endif

{{-- 
                                @isset(auth()->guard('customer')->user()->photo)
                                    <img src="{{ Storage::url(auth()->guard('customer')->user()->photo) }}" >
                                @endisset --}}
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn">Update</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end model -->

    <div class="dashboard-wrapper mt mb">
        <div class="container">
            <div class="customer_dashboard_wrap">
                <div class="toggle_menu_style">
                    <span>Menu</span>
                </div>
                @include('frontend.customer.sidebar')
                <div class="dashboard-main-wrapper">
                    <div class="dash-toggle">
                        <i class="las la-bars"></i>
                    </div>
                    <div class="dashboard-main-col">
                        <div class="dashboard_contentArea">
                            <div class="dashboard_content">
                                <div class="cs_profile">
                                    <div class="avatar_wrapper">
                                        <div class="cs_profile_picture">
                                            @if (isset(auth()->guard('customer')->user()->photo))
                                                <img src="{{ Storage::url(auth()->guard('customer')->user()->photo) }}"
                                                    alt="">
                                            @elseif(isset(auth()->guard('customer')->user()->social_avatar))
                                            <img src="{{auth()->guard('customer')->user()->social_avatar}}" alt="">
                                            @else
                                            <img src="{{ asset('frontend/images/avatar.png') }}" alt="">
                                            @endif
                                            
                                        </div>
                                        <div class="cs_profile_upload">
                                        </div>
                                    </div>
                                    <div class="cs_profile_detail">
                                        <ul>
                                            <li>
                                                <span>Name:</span>
                                                <span>{{ auth()->guard('customer')->user()->name }}</span>
                                            </li>
                                            <li>
                                                <span>email:</span>
                                                <span>{{ auth()->guard('customer')->user()->email }}</span>
                                            </li>
                                            <li>
                                                <span>Mobile Number:</span>
                                                <span>{{ auth()->guard('customer')->user()->phone }}</span>
                                            </li>
                                        </ul>
                                        <div class="round-btns">
                                            <button type="button" class="btns" data-bs-toggle="modal"
                                                data-bs-target="#exampleModal">
                                                Update Profile
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="cs_profile_list">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="cs_account_link">
                                                <div class="dash-inner-title">
                                                    <h4>Default Billing Address</h4>
                                                </div>
                                                <table class="table-bordered">
                                                    <tbody>
                                                        <tr>
                                                            <td>Name</td>
                                                            <td>{{ auth()->guard('customer')->user()->name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Address</td>
                                                            <td>{{ auth()->guard('customer')->user()->address }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Phone</td>
                                                            <td>
                                                                <a
                                                                    href="{{ auth()->guard('customer')->user()->phone }}">{{ auth()->guard('customer')->user()->phone }}</a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Area</td>
                                                            <td>{{ auth()->guard('customer')->user()->area }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script>
    $('.provinces').change(function(e) {
        e.preventDefault();
        const province_id = $(this).val();
        var dist="{{auth()->guard('customer')->user()->district ?? 0}}";
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
                var child_html =
                    "<option value=''>---Select Any One---</option>";
                if (response.error) {
                } else {
                    if (response.data.child.length > 0) {
                        $.each(response.data.child, function(index, value) {
                            child_html+="<option value='"+value.id+"'";
                                if(dist==value.id)
                                {
                                    child_html+='selected';
                                }

                                child_html+=">"+value.np_name+"</option>";
                        });
                    }
                }
                $('.district').html(child_html);
            }
        });
    });
    $('.provinces').change();
</script>

<script>
    $('.district').change(function(e) {
        e.preventDefault();
        const district_id = $(this).val() ?? "{{auth()->guard('customer')->user()->district}}";
        var area="{{auth()->guard('customer')->user()->area ?? 0}}";
        $.ajax({
            url: "{{ route('show-district') }}",
            type: "get",
            data: {
                district_id: district_id
            },
            success: function(response) {
                console.log(response);
                if (typeof(response) != 'object') {
                    response = JSON.parse(response);
                }
                var area_html =
                    "<option value=''>---Select Any One---</option>";
                if (response.error) {
                } else {
                    if (response.data.child.length > 0) {
                        $.each(response.data.child, function(index, value) {
                            area_html+="<option value='"+value.id+"'";
                                if(area==value.city_name)
                                {
                                    area_html+='selected';
                                }
                                area_html+=">"+value.city_name+"</option>";
                            
                        });
                    }
                }
                $('.area').html(area_html);
            }
        });
    });
    $('.district').change();
</script>

<script>
    $('.area').change(function(e) {
        e.preventDefault();
        const area_id = $(this).val() ?? "{{auth()->guard('customer')->user()->area ?? 0}}";
        var address="{{auth()->guard('customer')->user()->address ?? 0}}";
        $.ajax({
            url: "{{ route('get-addtional-customer') }}",
            type: "get",
            data: {
                area_id: area_id
            },
            success: function(response) {
                if (typeof(response) != 'object') {
                    response = JSON.parse(response);
                }

                var address_html =
                    "<option value=''>---Select Any---</option>";
                if (response.error) {
                } else {
                    if (response.data.child.length > 0) {
                        $.each(response.data.child, function(index, value) {
                            address_html+="<option value='"+value.id+"'";
                                if(address==value.title)
                                {
                                    address_html+='selected';
                                }
                                address_html+=">"+value.title+"</option>";
                        });

                    }
                }
                $('.additional_address').html(address_html);
            }


        });
    });
    $('.area').change();
</script>

@endpush
