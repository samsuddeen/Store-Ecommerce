@extends('frontend.layouts.app')
@section('title','Customer|Shipping Address')
@section('content')

    <div class="dashboard-wrapper mt mb">
        <div class="container">
            <div class="customer_dashboard_wrap">
                @include('frontend.customer.sidebar')
                <div class="dashboard-main-wrapper">
                    <div class="dash-toggle">
                        <i class="las la-bars"></i>
                    </div>
                    <div class="dashboard-main-col">
                        <div class="dashboard_contentArea">
                            <div class="dashboard_content table_wrapper">
                                <div class="dashboard-tables-head">
                                    <h3>Add Shipping Address</h3>
                                    <div class="round-btns">
                                        <button type="button" class="btns" data-bs-toggle="modal"
                                            data-bs-target="#addShippingAddress">
                                            Add Shipping Address
                                        </button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="table-responsive">
                                            <table class="table addressBook_wrapper table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Full Name</th>
                                                        <th>Country</th>
                                                        <th>Province/State</th>
                                                        <th>Area</th>
                                                        <th>City</th>
                                                        {{-- <th>Additional Address</th> --}}
                                                        <th>Phone Number</th>
                                                        <th>Zip</th>
                                                        {{-- <th>Address</th> --}}
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @foreach ($shippingAddresses as $key => $shippingAddress)
                                                    {{-- @dd($shippingAddress) --}}
                                                    {{-- @dd($shippingAddress->getDistrict) --}}
                                                        <tr>
                                                            <td>{{ @$shippingAddress->name }}</td>
                                                            <td>{{ @$shippingAddress->country }}</td>
                                                            {{-- <td>{{ @$shippingAddress->getProvince->eng_name }}</td> --}}
                                                            <td>{{ @$shippingAddress->province }}</td>
                                                            <td>{{ @$shippingAddress->area }}</td>
                                                            <td>{{ @$shippingAddress->state }}</td>
                                                            {{-- <td>{{ @$shippingAddress->additional_address }}</td> --}}
                                                            <td>{{ @$shippingAddress->phone }}</td>
                                                            <td>{{ @$shippingAddress->zip }}</td>
                                                            {{-- <td>Default shipping Address</td> --}}
                                                            <td>
                                                                <div class="table_btn">
                                                                    <a href="#" class="edit" title="Edit"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#editshippingAddress{{ @$shippingAddress->id }}"><i
                                                                            class="las la-edit"></i></a>
                                                                    <a href="{{ route('delete.shipping.address', @$shippingAddress->id) }}"
                                                                        class="delete">
                                                                        <i class="la la-trash"></i>
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
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
{{-- ------------------------------Add Shippinbg Address----------------------------------- --}}
<div class="common-popup medium-popup modal fade" id="addShippingAddress" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center" id="exampleModalLabel">Add Shipping Address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="#" id="addSAddress">
                    @method('post')
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                {{ Form::label('name', 'Name') }}
                                {{ Form::text('name', '', ['class' => 'form-control form-control-sm ' . ($errors->has('name') ? 'is-invalid' : ''), 'placeholder' => 'Enter Your Name Here.....', 'required' => true]) }}
                                <span id="nameError" hidden class="text-danger"></span>
                                @error('name')
                                    <div class="invalid-feedback">
                                        <i class="bx bx-radio-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                {{ Form::label('email', 'Email') }}
                                {{ Form::email('email', '', ['class' => 'form-control form-control-sm ' . ($errors->has('email') ? 'is-invalid' : ''), 'required' => true, 'placeholder' => 'Enter Your Email Here.....']) }}
                                {{-- <div id="email" class="form-text">We'll never share your email with anyone
                                    else.
                                </div> --}}
                                <span id="emailError" hidden class="text-danger"></span>
                                @error('email')
                                    <div class="invalid-feedback">
                                        <i class="bx bx-radio-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                {{ Form::label('phone', 'Phone') }}
                                {{ Form::text('phone', '', ['class' => 'form-control form-control-sm ' . ($errors->has('phone') ? 'is-invalid' : ''), 'required' => true, 'placeholder' => 'Enter Your Phone Num Here.....']) }}
                                <span id="phoneError" hidden class="text-danger"></span>
                                @error('phone')
                                    <div class="invalid-feedback">
                                        <i class="bx bx-radio-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                       
                        <div class="col-lg-6">
                            <div class="form-group">
                                {{ Form::label('country', 'Country') }}
                                <input type="text" name="country" id="country" class="form-control" required>
                                <span id="countryError" hidden class="text-danger"></span>
                                @error('country')
                                    <div class="invalid-feedback">
                                        <i class="bx bx-radio-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                {{ Form::label('province', 'Province/State') }}
                                <input type="text" name="province" id="province" class="form-control" required>
                                <span id="provinceError" hidden class="text-danger"></span>
                                @error('province')
                                    <div class="invalid-feedback">
                                        <i class="bx bx-radio-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                {{ Form::label('area', 'Area') }}
                               <input type="text" name="area" id="area" class="form-control" required>
                               <span id="areaError" hidden class="text-danger"></span> 
                               @error('area')
                                    <div class="invalid-feedback">
                                        <i class="bx bx-radio-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                {{ Form::label('state', 'City') }}
                                <input type="text" name="state" id="state" value="" class="form-control" required>
                                <span id="stateError" hidden class="text-danger"></span>
                                @error('state')
                                    <div class="invalid-feedback">
                                        <i class="bx bx-radio-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        {{-- <div class="col-lg-6">
                            <div class="form-group">
                                {{ Form::label('additional_address', 'Additional Area') }}
                                <input type="text" class="form-control additional_address" name="additional_address" id="additional_address" value="{{ @$s_address->additional_address }}">
                                @error('additional_address')
                                    <div class="invalid-feedback">
                                        <i class="bx bx-radio-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div> --}}
                        <div class="col-lg-6">
                            <div class="form-group">
                                {{ Form::label('zip', 'Zip Code') }}
                                {{-- <select name="zip" class="form-control zip_code_new" disabled>
                                    <option value="">--Your Zip Code---</option>
                                </select> --}}
                                <input type="text" class="form-control zip" name="zip" id="zip" value="{{ @$s_address->zip }}" required>
                                <span id="zipError" hidden class="text-danger"></span>
                                @error('zip')
                                    <div class="invalid-feedback">
                                        <i class="bx bx-radio-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <button type="button" class=" btn" id="addShipping">Add Address</button>
                        </div>
                    </div>
                    {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
{{-- ----------------------------------/Add Shipping Address------------------------------------- --}}


{{-- edit billing --}}
@if (count($shippingAddresses) > 0)
    @foreach ($shippingAddresses as $shippingAddress)
        <div class="common-popup medium-popup modal fade" id="editshippingAddress{{ $shippingAddress->id }}"
            tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-center" id="exampleModalLabel">Edit Shipping Address</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('updateshippingaddress', $shippingAddress->id) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            @if ($errors->any())
                                @foreach ($errors->all() as $error)
                                    <div class="text-danger">{{ $error }}</div>
                                @endforeach
                            @endif
                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <label for="name">Full Name</label>
                                        <input type="text" name="name" id="name" class="form-control"
                                            value="{{ $shippingAddress->name ?? Auth::guard('customer')->user()->name }}">
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <label for="phone">Phone</label>
                                        <input type="text" name="phone" id="phone"
                                            value="{{ $shippingAddress->phone ?? Auth::guard('customer')->user()->phone }}"
                                            class="form-control">
                                        @error('phone')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" id="email"
                                            value="{{ $shippingAddress->email ?? Auth::guard('customer')->user()->email }}"
                                            class="form-control">
                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="country">Country</label>
                                        <input type="country" name="country" id="country"
                                            value="{{ $shippingAddress->country ?? Auth::guard('customer')->user()->country }}"
                                            class="form-control">
                                        @error('country')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                {{-- <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <label for="province">Province</label>
                                        <select name="province" id="province" class="form-control form-select provinces">
                                            @foreach ($provinces as $province)
                                                <option value="{{ $province->id }}"
                                                    {{ $province->id == $shippingAddress->province ? 'selected' : '' }}>
                                                    {{ $province->eng_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('province')
                                            <span class="text-danger">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <label for="district">District</label>
                                        <select name="district" id="district" class="form-control form-select district">
                                            @foreach ($districts as $district)
                                                <option value="{{ $district->id }}"
                                                    {{ $district->id == $shippingAddress->district ? 'selected' : '' }}>
                                                    {{ $district->np_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('district')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <label for="area">Area</label>
                                        <select name="area" id="area" class="form-control form-select area">
                                            @if($shippingAddress->area)
                                                <option value="{{ $shippingAddress->area }}" selected>{{ $shippingAddress->area }}</option>
                                            @else
                                            @foreach ($areas as $area)
                                                <option value="{{ $area->city_name }}">
                                                    {{ $area->city_name }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        @error('area')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <label for="additional_address">Additional Address</label>
                                        <input type="text" class="form-control additional_address" name="additional_address" id="additional_address" value="{{ @$shippingAddress->additional_area }}">
                                        
                                    </div>
                                </div> --}}
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {{ Form::label('province', 'Province/State') }}
                                        <input type="text" name="province" id="province" class="form-control" value="{{@$shippingAddress->province}}" required>
                                        <span id="provinceError" hidden class="text-danger"></span>
                                        @error('province')
                                            <div class="invalid-feedback">
                                                <i class="bx bx-radio-circle"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {{ Form::label('area', 'Area') }}
                                       <input type="text" name="area" id="area" class="form-control" value="{{@$shippingAddress->area}}" required>
                                       <span id="areaError" hidden class="text-danger"></span> 
                                       @error('area')
                                            <div class="invalid-feedback">
                                                <i class="bx bx-radio-circle"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {{ Form::label('state', 'City') }}
                                        <input type="text" name="state" id="state"  class="form-control" value="{{@$shippingAddress->state}}" required>
                                        <span id="stateError" hidden class="text-danger"></span>
                                        @error('state')
                                            <div class="invalid-feedback">
                                                <i class="bx bx-radio-circle"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                      
                                        <label for="zip">Zip Code</label>
                                        <input type="text" class="form-control zip" name="zip" id="zip" value="{{ @$shippingAddress->zip }}">
                                        {{-- <select name="zip" id="zip" class="form-control zip" disabled>
                                            
                                            @if($shippingAddress->zip!=null)
                                                <option value="{{$shippingAddress->zip}}">{{$shippingAddress->zip}}</option>
                                            @endif

                                        </select> --}}
                                        @error('zip')
                                            <span class="text-damger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12">
                                    <button type="submit" class="btn">Update</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endif
@endsection







@push('script')
   
    <script type="text/javascript">
        function valueChanged() {
            if ($('#flexCheckDefault').is(":checked"))
                $(".ans").hide();
            else
                $(".ans").show();
        }
    </script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
        $(document).ready(function() {
            $('.bprovince').on('change', function() {
                var province_id = $('.bprovince').val();
                $.ajax({
                    url: "{{ route('getDistrict') }}",
                    type: 'post',
                    data: {
                        province_id: province_id,
                    },
                    // dataType: 'JSON',
                    success: function(response) {
                        console.log(response);
                        $('.bdistrict').empty();
                        $('.blocal').empty();
                        $('.bdistrict').append('<option >Select District</option>');
                        $.each(response.districts, function(key, value) {
                            $('.bdistrict').append('<option value="' + value.id +
                                '">' + value.np_name + '</option>');
                        })
                    },
                    error: function(response) {}
                });
            })
        })
    </script>
    
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
        $(document).ready(function() {
            $('.bdistrict').on('change', function() {
                var district_id = $('.bdistrict').val();
                $.ajax({
                    url: "{{ route('getLocal') }}",
                    type: 'post',
                    data: {
                        district_id: district_id,
                    },
                    // dataType: 'JSON',
                    success: function(response) {
                        console.log(response);
                        $('.blocal').empty();
                        $('.blocal').append('<option >Select Local</option>');
                        $.each(response.locals, function(key, value) {
                            $('.blocal').append('<option value="' + value.id + '">' +
                                value.city_name + '</option>');
                        })
                    },
                    error: function(response) {}
                });
            })
        })
    </script>
<script>
    $('.provinces').change(function(e) {
        e.preventDefault();
        const province_id = $(this).val();
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
                } else {
                    if (response.data.child.length > 0) {
                        $.each(response.data.child, function(index, value) {
                            dist_html += "<option value='" + value.id + "'";
                            dist_html += ">" + value.np_name + "</option>";
                        });
                    }
                }
               
                $('.district').html(dist_html);
            }
        });
    });
</script>
<script>
    $('#addShipping').click(function(e) {
        e.preventDefault();
        var form = document.getElementById('addSAddress');
        $('#nameError').attr('hidden',true);
        $('#countryError').attr('hidden',true);
        $('#emailError').attr('hidden',true);
        $('#phoneError').attr('hidden',true);
        $('#stateError').attr('hidden',true);
        $('#provinceError').attr('hidden',true);
        $('#areaError').attr('hidden',true);
        $('#zipError').attr('hidden',true);
        $('#nameError').text('');
        $('#emailError').text('');
        $('#phoneError').text('');
        $('#stateError').text('');
        $('#provinceError').text('');
        $('#areaError').text('');
        $('#zipError').text('');
        $.ajax({
            url: "{{ route('add-shipping-address') }}",
            type: "get",
            data: {
                name: form['name'].value,
                email: form['email'].value,
                phone: form['phone'].value,
                state: form['state'].value,
                province: form['province'].value,
                area: form['area'].value,
                zip: form['zip'].value,
                country: form['country'].value
            },

            success: function(response) {
                if (typeof(response) != 'object') {
                    response.JSON.parse(response);
                }
                if(response.validate)
                {
                    $.each(response.msg,function(index,value){
                        $(`#${index}Error`).removeAttr('hidden');
                        $(`#${index}Error`).text(value);
                    });
                    return false;
                }
                if (response.error) {
                    toastr.options = {
                        "closeButton": true,
                        "progressBar": true
                    }
                    toastr.error(response.msg);
                    location.reload();
                    return false;
                }

                $('#addShippingAddress').modal('hide');
                location.reload();

            }
        });
    });
</script>
<script>
    $('.district').change(function(e) {
        e.preventDefault();
        const district_id = $(this).val();;
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
                } else {
                    if (response.data.child.length > 0) {
                        $.each(response.data.child, function(index, value) {
                            area_html += "<option value='" + value.id + "'";
                            area_html += ">" + value.city_name + "</option>";
                        });
                    }
                }
                $('.area').html(area_html);
            }
        });
    });
</script>

<script>
    $('.additional_address').change(function(e) {
        e.preventDefault();
        const area_id = $(this).val();
        $.ajax({
            url: "{{ route('guestShipping_charge') }}",
            type: "get",
            data: {
                area_id: area_id
            },

            success: function(response) {
                if (typeof(response) != 'object') {
                    response = JSON.parse(response);
                }
                if (response.error) {
                    toastr.options = {
                        "closeButton": true,
                        "progressBar": true
                    }
                    toastr.error("Something Is Wrong for Zip Code");
                }
                $('.zip').html(response.zip_code_is);
            }
        });
    });
</script>

<script>
    $('.area').change(function(e) {
        e.preventDefault();
        const area_id = $(this).val();
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
                } else {
                    if (response.data.child.length > 0) {
                        $.each(response.data.child, function(index, value) {
                            address_html += "<option value='" + value.id + "'";
                            address_html += ">" + value.title + "</option>";
                        });

                    }
                }
                $('.additional_address').html(address_html);
            }


        });
    });
</script>

 
    
   


    {{-- Additikonal address zip code --}}
    {{-- <script>
        $('.additional_address').change(function(e) {
            e.preventDefault();
            const area_id = $(this).val();
            $.ajax({
                url: "{{ route('guestShipping_charge') }}",
                type: "get",
                data: {
                    area_id: area_id
                },

                success: function(response) {
                    if (typeof(response) != 'object') {
                        response = JSON.parse(response);
                    }
                    if (response.error) {
                        alert('Something Is Wrong for Zip Code');
                    }
                    $('.zip').html(response.zip_code_is);
                    $('.zip').removeAttr('disabled');
                }
            });
        });
    </script> --}}




    {{-- add Shipping address --}}
    {{-- <script>
        $('#addShipping').click(function(e) {
            e.preventDefault();
            var form = document.getElementById('addSAddress');
            $.ajax({
                url: "{{ route('add-shipping-address') }}",
                type: "get",
                data: {
                    name: form['name'].value,
                    email: form['email'].value,
                    phone: form['phone'].value,
                    province: form['province'].value,
                    district: form['district'].value,
                    area: form['area'].value,
                    additional_address: form['additional_address'].value,
                    zip: form['zip'].value
                },

                success: function(response) {
                    if (typeof(response) != 'object') {
                        response.JSON.parse(response);
                    }

                    if (response.error) {
                        alert(response.msg);
                        return false;
                    }
                    $('#addShippingAddress').modal('hide');
                    location.reload();
                }
            });
        });
    </script> --}}
@endpush
