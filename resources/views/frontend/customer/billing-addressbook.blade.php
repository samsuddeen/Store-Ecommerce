@extends('frontend.layouts.app')
@section('title', 'Customer |Billing Address')
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
                                    <h3>Billing Address</h3>
                                    <div class="round-btns">
                                        <button type="button" class="btns" data-bs-toggle="modal"
                                            data-bs-target="#addBillingAddress">
                                            Add Billing Address
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
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($billingAddresses as $billingAddress)
                                                        <tr>
                                                            <td>{{ @$billingAddress->name }}</td>
                                                            <td>{{ @$billingAddress->country }}</td>
                                                            <td>{{ @$billingAddress->province }}</td>
                                                            <td>{{ @$billingAddress->area }}</td>
                                                            <td>{{ @$billingAddress->state }}</td>
                                                            {{-- <td>{{ @$billingAddress->additional_address }}</td> --}}
                                                            <td>{{ @$billingAddress->phone }}</td>
                                                            <td>{{ @$billingAddress->zip }}</td>
                                                            <td>
                                                                <div class="table_btn">
                                                                    <a href="#" class="edit" title="Edit"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#editbillingaddress{{ $billingAddress->id }}"><i
                                                                            class="las la-edit"></i></a>
                                                                    <a href="{{ route('delete.billing.address', $billingAddress->id) }}"
                                                                        class="delete">
                                                                        <i class="las la-trash"></i>
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
{{-- edit billing --}}
@if (count($billingAddresses) > 0)
    @foreach ($billingAddresses as $billingAddress)
        <div class="common-popup medium-popup modal fade" id="editbillingaddress{{ $billingAddress->id }}"
            tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-center" id="exampleModalLabel">Edit Billing Address</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('updateBillingAddress', $billingAddress->id) }}" method="post"
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
                                            value="{{ $billingAddress->name ?? Auth::guard('customer')->user()->name }}">
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <label for="phone">Phone</label>
                                        <input type="text" name="phone" id="phone"
                                            value="{{ $billingAddress->phone ?? Auth::guard('customer')->user()->phone }}"
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
                                            value="{{ $billingAddress->email ?? Auth::guard('customer')->user()->email }}"
                                            class="form-control">
                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <label for="country">Country</label>
                                        <input type="country" name="country" id="country"
                                            value="{{ $billingAddress->country ?? Auth::guard('customer')->user()->country }}"
                                            class="form-control">
                                        @error('country')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {{ Form::label('province', 'Province/State') }}
                                        <input type="text" name="province" id="province" class="form-control" value="{{@$billingAddress->province}}" required>
                                        <span id="provinceErrorB" hidden class="text-danger"></span>
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
                                       <input type="text" name="area" id="area" class="form-control" value="{{@$billingAddress->area}}" required>
                                       <span id="areaErrorB" hidden class="text-danger"></span> 
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
                                        <input type="text" name="state" id="state"  class="form-control" value="{{@$billingAddress->state}}" required>
                                        <span id="stateErrorB" hidden class="text-danger"></span>
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
                                        <input type="number" class="form-control zip" id="zip" name="zip" value="{{ @$billingAddress->zip }}">
                                        {{-- <select name="zip" id="zip" class="form-control zip" disabled>
                                           
                                            @if($billingAddress->zip!=null)
                                            <option value="{{$billingAddress->zip}}">{{$billingAddress->zip}}</option>
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
                </div>
                </form>
            </div>
        </div>
        </div>
    @endforeach
@endif



{{-- Billing address modal --}}
{{-- ------------------------------Add Billing Address----------------------------------- --}}
<div class="common-popup medium-popup modal fade" id="addBillingAddress" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center" id="exampleModalLabel">Add Billing Address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="#" id="addBAddress">
                    @method('post')
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group">
                                {{ Form::label('name', 'Name') }}
                                {{ Form::text('name', '', ['class' => 'form-control form-control-sm ' . ($errors->has('name') ? 'is-invalid' : ''), 'placeholder' => 'Enter Your Name Here', 'required' => true]) }}
                                @error('name')
                                    <div class="invalid-feedback">
                                        <i class="bx bx-radio-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group">
                                {{ Form::label('email', 'Email') }}
                                {{ Form::email('email', '', ['class' => 'form-control form-control-sm ' . ($errors->has('email') ? 'is-invalid' : ''), 'required' => true, 'placeholder' => 'Enter Your Email Here']) }}
                                @error('email')
                                    <div class="invalid-feedback">
                                        <i class="bx bx-radio-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group">
                                {{ Form::label('phone', 'Phone') }}
                                {{ Form::text('phone', '', ['class' => 'form-control form-control-sm ' . ($errors->has('phone') ? 'is-invalid' : ''), 'required' => true, 'placeholder' => 'Enter Your Phone Num Here']) }}
                                @error('phone')
                                    <div class="invalid-feedback">
                                        <i class="bx bx-radio-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        {{-- <div class="col-lg-6 col-md-6">
                            <div class="form-group">
                                {{ Form::label('province', 'Province') }}
                                {{ Form::select('province', $provinces->pluck('eng_name', 'id'), [], ['class' => 'provinces form-control form-select ' . ($errors->has('province') ? 'is-invalid' : ''), 'placeholder' => '---Select Any One---', 'required' => true]) }}
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
                                {{ Form::select('district', [], [], ['class' => 'district form-control form-select ' . ($errors->has('district') ? 'is-invalid' : ''), 'placeholder' => '--Please Select Province Before District--', 'required' => true]) }}
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
                                {{ Form::select('area', [], [], ['class' => 'area form-control form-select ' . ($errors->has('area') ? 'is-invalid' : ''), 'placeholder' => '--Please Select District Before Area---', 'required' => true]) }}
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
                                {{ Form::label('country', 'Country') }}
                                <input type="text" name="country" id="country" class="form-control" required>
                                <span id="countryErrorB" hidden class="text-danger"></span>
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
                                <span id="provinceErrorB" hidden class="text-danger"></span>
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
                               <span id="areaErrorB" hidden class="text-danger"></span> 
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
                                <span id="stateErrorB" hidden class="text-danger"></span>
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
                                {{ Form::label('zip', 'Zip Code') }}
                                {{-- <select name="zip" id="zip" class="form-control zip">
                                    <option value="">--Your Zip Code---</option>
                                </select> --}}
                                <input type="text" class="form-control zip" name="zip" id="zip" placeholder="Zip Code" value="{{ @$s_address->zip }}">
                                @error('zip')
                                    <div class="invalid-feedback">
                                        <i class="bx bx-radio-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <button type="button" class=" btn" id="addBilling">Add Address</button>
                        </div>
                    </div>
                    {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
</div>
{{-- ----------------------------------/Add Billing Address------------------------------------- --}}

@endsection



@push('script')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
        $(document).ready(function() {
            $('.province').on('change', function() {
                var province_id = $('.province').val();
                $.ajax({
                    url: "{{ route('getDistrict') }}",
                    type: 'post',
                    data: {
                        province_id: province_id,
                    },
                    // dataType: 'JSON',
                    success: function(response) {
                        console.log(response);
                        $('.district').empty();
                        $('.local').empty();
                        $('.district').append('<option >Select District</option>');
                        $.each(response.districts, function(key, value) {
                            $('.district').append('<option value="' + value.id +
                                '">' + value.np_name + '</option>');
                        })
                    },
                    error: function(response) {}
                });
            })
        })
    </script>
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
                    toastr.options = {
                        "closeButton": true,
                        "progressBar": true
                    }
                    toastr.error("Something Is Wrong for Zip Code");
                }
                $('.zip_code_new').html(response.zip_code_is);
            }
        });
    });
</script> --}}

{{-- <script>
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
</script> --}}

 
    
   


    {{-- Additikonal address zip code --}}
    {{-- <script>
        $('.additional_address').change(function(e) {
            e.preventDefault();
            let area_id = $(this).val();
            // console.log(area_id);
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

    {{-- Storing billing address --}}
    <script>
        $('#addBilling').click(function(e) {
            e.preventDefault();
            let form = document.getElementById('addBAddress');
            $('#nameErrorB').attr('hidden',true);
            $('#countryErrorB').attr('hidden',true);
            $('#emailErrorB').attr('hidden',true);
            $('#phoneErrorB').attr('hidden',true);
            $('#stateErrorB').attr('hidden',true);
            $('#provinceErrorB').attr('hidden',true);
            $('#areaErrorB').attr('hidden',true);
            $('#zipErrorB').attr('hidden',true);
            $('#nameErrorB').text('');
            $('#emailErrorB').text('');
            $('#phoneErrorB').text('');
            $('#stateErrorB').text('');
            $('#provinceErrorB').text('');
            $('#areaErrorB').text('');
            $('#zipErrorB').text('');
            $.ajax({
                url: "{{ route('add-billing-address') }}",
                type: "get",
                data: {
                    name: form['name'].value,
                    email: form['email'].value,
                    phone: form['phone'].value,
                    province: form['province'].value,
                    state: form['state'].value,
                    area: form['area'].value,
                    zip: form['zip'].value,
                    country: form['country'].value

                },
                success: function(response) {
                    if (typeof(response) != 'object') {
                        response = JSON.parse(response);
                    }
                    if(response.validate)
                    {
                        $.each(response.msg,function(index,value){
                            $(`#${index}ErrorB`).removeAttr('hidden');
                            $(`#${index}ErrorB`).text(value);
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

                    location.reload();
                    $('#addBillingAddress').modal('hide');
                }
            });
        });
    </script>
    {{-- <script>
        $('#addBilling').click(function(e) {
            e.preventDefault();
            let form = document.getElementById('addBAddress');

            $.ajax({
                url: "{{ route('add-billing-address') }}",
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
                        response = JSON.parse(response);
                    }

                    if (response.error) {
                        alert(response.error);
                    }

                    location.reload();
                    $('#addBillingAddress').modal('hide');
                }
            });


        });
    </script> --}}
@endpush
