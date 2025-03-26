@extends('frontend.layouts.app')
@section('content')
    <div class="container">
        <div class="customer_dashboard_wrap">
            <div class="toggle_menu_style">
                <span>Menu</span>
            </div>
            @include('frontend.customer.sidebar')
            <div class="dashboard_contentArea">
                <div class="dashboard_content table_wrapper">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table addressBook_wrapper">
                                    <thead>
                                        <tr>
                                            <th>Full Name</th>
                                            <th>Province</th>
                                            <th>District</th>
                                            <th>Area</th>
                                            <th>Additional Address</th>
                                            <th>Phone Number</th>
                                            <th>Zip</th>
                                            <th>Address</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{$shippingAddress->name}}</td>
                                            <td>{{$shippingAddress->province}}</td>
                                            <td>{{$shippingAddress->district}}</td>
                                            <td>{{$shippingAddress->area}}</td>
                                            <td>{{$shippingAddress->additional_address}}</td>
                                            <td>{{$shippingAddress->phone}}</td>
                                            <td>{{$shippingAddress->zip}}</td>
                                            <td>Default shipping Address</td>
                                            <td>
                                                <div class="table_btn">
                                                    <a href="#" class="edit" title="Edit"><i class="las la-edit" data-bs-toggle="modal" data-bs-target="#editshippingaddress"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{$billingAddress->name}}</td>
                                            <td>{{$billingAddress->province}}</td>
                                            <td>{{$billingAddress->district}}</td>
                                            <td>{{$billingAddress->area}}</td>
                                            <td>{{$billingAddress->additional_address}}</td>
                                            <td>{{$billingAddress->phone}}</td>
                                            <td>{{$billingAddress->zip}}</td>
                                            <td>Default billing Address</td>
                                            <td>
                                                <div class="table_btn">
                                                    <a href="#" class="edit" title="Edit" data-bs-toggle="modal" data-bs-target="#editbillingaddress"><i class="las la-edit"></i></a>
                                                </div>
                                            </td>
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
@endsection



<div class="modal fade" id="editbillingaddress" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <form action="{{route('billingAddress')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="row">
                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="text-danger">{{$error}}</div>
                        @endforeach
                    @endif
                    <div class="col-md-6">
                        <div class="mb-3 input_type_wrap">
                            <label for="exampleFormControlInput1" class="form-label">Full Name</label>
                            <div class="input_field">
                                @if($consumer->userBillingAddress != null)
                                    @if($consumer->userBillingAddress->future_use == 1)
                                        <input type="text" name="name" value="{{$consumer->userBillingAddress->name}}" class="form-control" id="exampleFormControlInput1" placeholder="Name">
                                    @else
                                        <input type="text" name="name" value="{{auth()->guard('customer')->user()->name}}" class="form-control" id="exampleFormControlInput1" placeholder="Name">
                                    @endif
                                @else
                                    <input type="text" name="name" value="{{auth()->guard('customer')->user()->name}}" class="form-control" id="exampleFormControlInput1" placeholder="Name">
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3 input_type_wrap">
                            <label for="exampleFormControlInput1" class="form-label">Email address</label>
                            <div class="input_field">
                                @if($consumer->userBillingAddress != null)
                                @if($consumer->userBillingAddress->future_use == 1)
                                    <input type="email" value="{{$consumer->userBillingAddress->email}}" name="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                                @else
                                    <input type="email" value="{{auth()->guard('customer')->user()->email}}" name="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                                @endif
                                @else
                                    <input type="email" value="{{auth()->guard('customer')->user()->email}}" name="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3 input_type_wrap">
                            <label for="exampleFormControlInput1" class="form-label">Phone (Required)</label>
                            <div class="input_field">
                                @if($consumer->userBillingAddress != null)
                                @if($consumer->userBillingAddress->future_use == 1)
                                    <input type="number" value="{{$consumer->userBillingAddress->phone}}" name="phone" class="form-control" id="exampleFormControlInput1" placeholder="9849736232">
                                @else
                                    <input type="number" value="{{auth()->guard('customer')->user()->phone}}" name="phone" class="form-control" id="exampleFormControlInput1" placeholder="9849736232">
                                @endif
                                @else
                                    <input type="number" value="{{auth()->guard('customer')->user()->phone}}" name="phone" class="form-control" id="exampleFormControlInput1" placeholder="9849736232">
                                @endif
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-md-6">
                        <div class="mb-3 input_type_wrap">
                            <label for="exampleFormControlInput1" class="form-label">Country/Region</label>
                            <div class="input_field">
                                <select name="country" class="form-select form-control">
                                    @if(auth()->user()->country != null)
                                    <option value="{{auth()->user()->country}}" selected> {{auth()->user()->country}} </option>
                                    @else
                                        <option selected> Select Country </option>
                                    @endif
                                    @foreach($countries as $country)
                                        <option value="{{$country->name}}">{{$country->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div> --}}
                    <div class="col-md-6">
                        <div class="mb-3 input_type_wrap">
                            <label for="exampleFormControlInput1" class="form-label">Province</label>
                            <div class="input_field">
                                <select name="province" class="form-select bprovince form-control">
                                    @if($consumer->userBillingAddress != null)
                                        @if($consumer->userBillingAddress->future_use == 1)
                                            @php
                                                $id = \App\Models\Province::where('eng_name',$consumer->UserBillingAddress->province)->value('id');
                                            @endphp
                                            <option value="{{$id}}" selected> {{$consumer->userBillingAddress->province}} </option>
                                        @else
                                            @php
                                                $id = \App\Models\Province::where('eng_name',auth()->guard('customer')->user()->province)->value('id');
                                            @endphp
                                            <option value="{{$id}}" selected> {{auth()->guard('customer')->user()->province}} </option>
                                        @endif
                                    @elseif(auth()->guard('customer')->user()->province)
                                            @php
                                                $id = \App\Models\Province::where('eng_name',auth()->guard('customer')->user()->province)->value('id');
                                            @endphp
                                        <option value="{{$id}}" selected> {{auth()->guard('customer')->user()->province}} </option>
                                    @else
                                        <option selected> Select Province </option>
                                    @endif
                                    @foreach($provinces as $province)
                                        <option value="{{$province->id}}">{{$province->eng_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3 input_type_wrap">
                            <label for="exampleFormControlInput1" class="form-label">District</label>
                            <div class="input_field">
                                <select name="district" class="form-select bdistrict form-control">
                                    @if($consumer->userBillingAddress != null)
                                        @if($consumer->userBillingAddress->future_use == 1)
                                            @php
                                                $id = \App\Models\District::where('np_name',$consumer->userBillingAddress->district)->value('dist_id');
                                            @endphp
                                            <option value="{{$id}}" selected> {{$consumer->userBillingAddress->district}} </option>
                                        @else
                                            @php
                                                $id = \App\Models\District::where('np_name',auth()->guard('customer')->user()->district)->value('dist_id');
                                            @endphp
                                            <option value="{{$id}}" selected> {{auth()->guard('customer')->user()->district}} </option>
                                        @endif
                                    @elseif(auth()->guard('customer')->user()->district)
                                        @php
                                            $id = \App\Models\District::where('np_name',auth()->guard('customer')->user()->district)->value('dist_id');
                                        @endphp
                                        <option value="{{$id}}" selected> {{auth()->guard('customer')->user()->district}} </option>
                                    @else
                                        <option selected> Select District </option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3 input_type_wrap">
                            <label for="exampleFormControlInput1" class="form-label">Area</label>
                            <div class="input_field">
                                <select name="area" class="form-select blocal form-control">
                                    @if($consumer->userBillingAddress != null)
                                        @if($consumer->userBillingAddress->future_use == 1)
                                            @php
                                                $id = \App\Models\City::where('city_name',$consumer->userBillingAddress->area)->value('local_level_id');
                                            @endphp
                                            <option value="{{$id}}" selected> {{$consumer->userBillingAddress->area}} </option>
                                        @else
                                            @php
                                                $id = \App\Models\City::where('city_name',auth()->guard('customer')->user()->area)->value('local_level_id');
                                            @endphp
                                            <option value="{{$id}}" selected> {{auth()->guard('customer')->user()->area}} </option>
                                        @endif
                                    @elseif(auth()->guard('customer')->user()->area)
                                        @php
                                            $id = \App\Models\City::where('city_name',auth()->guard('customer')->user()->area)->value('local_level_id');
                                        @endphp
                                        <option value="{{$id}}" selected> {{auth()->guard('customer')->user()->area}} </option>
                                    @else
                                        <option selected> Select Area </option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" value="{{auth()->guard('customer')->user()->id}}" name="user_id">
                    <div class="col-md-6">
                        <div class="mb-3 input_type_wrap">
                            <label for="exampleFormControlInput1" class="form-label">Address (optional)</label>
                            <div class="input_field">
                                @if($consumer->userBillingAddress != null)
                                    @if($consumer->userBillingAddress->future_use == 1)
                                        <input type="text" name="address" value="{{$consumer->userBillingAddress->additional_address}}" class="form-control" id="exampleFormControlInput1" placeholder="near machapokhari">
                                    @else
                                        <input type="text" name="address" value="{{auth()->guard('customer')->user()->address}}" class="form-control" id="exampleFormControlInput1" placeholder="near machapokhari">
                                    @endif
                                @else
                                    <input type="text" name="address" value="{{auth()->guard('customer')->user()->address}}" class="form-control" id="exampleFormControlInput1" placeholder="near machapokhari">
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3 input_type_wrap">
                            <label for="exampleFormControlInput1" class="form-label">zip</label>
                            <div class="input_field">
                                @if($consumer->userBillingAddress != null)
                                    @if($consumer->userBillingAddress->future_use == 1)
                                        <input type="number" name="zip" value="{{$consumer->userBillingAddress->zip}}" class="form-control" id="exampleFormControlInput1" placeholder="Phone Number">
                                    @else
                                        <input type="number" name="zip" value="{{auth()->guard('customer')->user()->zip}}" class="form-control" id="exampleFormControlInput1" placeholder="Phone Number">
                                    @endif
                                @else
                                    <input type="number" name="zip" value="{{auth()->guard('customer')->user()->zip}}" class="form-control" id="exampleFormControlInput1" placeholder="Phone Number">
                                @endif
                                </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </form>
        </div>
    </div>
</div>


<div class="modal fade" id="editshippingaddress" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <form action="{{route('updateshippingaddress')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="row">
                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="text-danger">{{$error}}</div>
                        @endforeach
                    @endif
                    <div class="col-md-6">
                        <div class="mb-3 input_type_wrap">
                            <label for="exampleFormControlInput1" class="form-label">Full Name</label>
                            <div class="input_field">
                                @if($consumer->userShippingAddress != null)
                                    @if($consumer->userShippingAddress->future_use == 1)
                                        <input type="text" name="name" value="{{$consumer->userShippingAddress->name}}" class="form-control" id="exampleFormControlInput1" placeholder="Name">
                                    @else
                                        <input type="text" name="name" value="{{auth()->guard('customer')->user()->name}}" class="form-control" id="exampleFormControlInput1" placeholder="Name">
                                    @endif
                                @else
                                    <input type="text" name="name" value="{{auth()->guard('customer')->user()->name}}" class="form-control" id="exampleFormControlInput1" placeholder="Name">
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3 input_type_wrap">
                            <label for="exampleFormControlInput1" class="form-label">Email address</label>
                            <div class="input_field">
                                @if($consumer->userShippingAddress != null)
                                @if($consumer->userShippingAddress->future_use == 1)
                                    <input type="email" value="{{$consumer->userShippingAddress->email}}" name="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                                @else
                                    <input type="email" value="{{auth()->guard('customer')->user()->email}}" name="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                                @endif
                                @else
                                    <input type="email" value="{{auth()->guard('customer')->user()->email}}" name="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3 input_type_wrap">
                            <label for="exampleFormControlInput1" class="form-label">Phone (Required)</label>
                            <div class="input_field">
                                @if($consumer->userShippingAddress != null)
                                @if($consumer->userShippingAddress->future_use == 1)
                                    <input type="number" value="{{$consumer->userShippingAddress->phone}}" name="phone" class="form-control" id="exampleFormControlInput1" placeholder="9849736232">
                                @else
                                    <input type="number" value="{{auth()->guard('customer')->user()->phone}}" name="phone" class="form-control" id="exampleFormControlInput1" placeholder="9849736232">
                                @endif
                                @else
                                    <input type="number" value="{{auth()->guard('customer')->user()->phone}}" name="phone" class="form-control" id="exampleFormControlInput1" placeholder="9849736232">
                                @endif
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-md-6">
                        <div class="mb-3 input_type_wrap">
                            <label for="exampleFormControlInput1" class="form-label">Country/Region</label>
                            <div class="input_field">
                                <select name="country" class="form-select form-control">
                                    @if(auth()->user()->country != null)
                                    <option value="{{auth()->user()->country}}" selected> {{auth()->user()->country}} </option>
                                    @else
                                        <option selected> Select Country </option>
                                    @endif
                                    @foreach($countries as $country)
                                        <option value="{{$country->name}}">{{$country->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div> --}}
                    <div class="col-md-6">
                        <div class="mb-3 input_type_wrap">
                            <label for="exampleFormControlInput1" class="form-label">Province</label>
                            <div class="input_field">
                                <select name="province" class="form-select bprovince form-control">
                                    @if($consumer->userShippingAddress != null)
                                        @if($consumer->userShippingAddress->future_use == 1)
                                            @php
                                                $id = \App\Models\Province::where('eng_name',$consumer->userShippingAddress->province)->value('id');
                                            @endphp
                                            <option value="{{$id}}" selected> {{$consumer->userShippingAddress->province}} </option>
                                        @else
                                            @php
                                                $id = \App\Models\Province::where('eng_name',auth()->guard('customer')->user()->province)->value('id');
                                            @endphp
                                            <option value="{{$id}}" selected> {{auth()->guard('customer')->user()->province}} </option>
                                        @endif
                                    @elseif(auth()->guard('customer')->user()->province)
                                            @php
                                                $id = \App\Models\Province::where('eng_name',auth()->guard('customer')->user()->province)->value('id');
                                            @endphp
                                        <option value="{{$id}}" selected> {{auth()->guard('customer')->user()->province}} </option>
                                    @else
                                        <option selected> Select Province </option>
                                    @endif
                                    @foreach($provinces as $province)
                                        <option value="{{$province->id}}">{{$province->eng_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3 input_type_wrap">
                            <label for="exampleFormControlInput1" class="form-label">District</label>
                            <div class="input_field">
                                <select name="district" class="form-select bdistrict form-control">
                                    @if($consumer->userShippingAddress != null)
                                        @if($consumer->userShippingAddress->future_use == 1)
                                            @php
                                                $id = \App\Models\District::where('np_name',$consumer->userShippingAddress->district)->value('dist_id');
                                            @endphp
                                            <option value="{{$id}}" selected> {{$consumer->userShippingAddress->district}} </option>
                                        @else
                                            @php
                                                $id = \App\Models\District::where('np_name',auth()->guard('customer')->user()->district)->value('dist_id');
                                            @endphp
                                            <option value="{{$id}}" selected> {{auth()->guard('customer')->user()->district}} </option>
                                        @endif
                                    @elseif(auth()->guard('customer')->user()->district)
                                        @php
                                            $id = \App\Models\District::where('np_name',auth()->guard('customer')->user()->district)->value('dist_id');
                                        @endphp
                                        <option value="{{$id}}" selected> {{auth()->guard('customer')->user()->district}} </option>
                                    @else
                                        <option selected> Select District </option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3 input_type_wrap">
                            <label for="exampleFormControlInput1" class="form-label">Area</label>
                            <div class="input_field">
                                <select name="area" class="form-select blocal form-control">
                                    @if($consumer->userShippingAddress != null)
                                        @if($consumer->userShippingAddress->future_use == 1)
                                            @php
                                                $id = \App\Models\City::where('city_name',$consumer->userShippingAddress->area)->value('local_level_id');
                                            @endphp
                                            <option value="{{$id}}" selected> {{$consumer->userShippingAddress->area}} </option>
                                        @else
                                            @php
                                                $id = \App\Models\City::where('city_name',auth()->guard('customer')->user()->area)->value('local_level_id');
                                            @endphp
                                            <option value="{{$id}}" selected> {{auth()->guard('customer')->user()->area}} </option>
                                        @endif
                                    @elseif(auth()->guard('customer')->user()->area)
                                        @php
                                            $id = \App\Models\City::where('city_name',auth()->guard('customer')->user()->area)->value('local_level_id');
                                        @endphp
                                        <option value="{{$id}}" selected> {{auth()->guard('customer')->user()->area}} </option>
                                    @else
                                        <option selected> Select Area </option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" value="{{auth()->guard('customer')->user()->id}}" name="user_id">
                    <div class="col-md-6">
                        <div class="mb-3 input_type_wrap">
                            <label for="exampleFormControlInput1" class="form-label">Address (optional)</label>
                            <div class="input_field">
                                @if($consumer->userShippingAddress != null)
                                    @if($consumer->userShippingAddress->future_use == 1)
                                        <input type="text" name="address" value="{{$consumer->userShippingAddress->additional_address}}" class="form-control" id="exampleFormControlInput1" placeholder="near machapokhari">
                                    @else
                                        <input type="text" name="address" value="{{auth()->guard('customer')->user()->address}}" class="form-control" id="exampleFormControlInput1" placeholder="near machapokhari">
                                    @endif
                                @else
                                    <input type="text" name="address" value="{{auth()->guard('customer')->user()->address}}" class="form-control" id="exampleFormControlInput1" placeholder="near machapokhari">
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3 input_type_wrap">
                            <label for="exampleFormControlInput1" class="form-label">zip</label>
                            <div class="input_field">
                                @if($consumer->userShippingAddress != null)
                                    @if($consumer->userShippingAddress->future_use == 1)
                                        <input type="number" name="zip" value="{{$consumer->userShippingAddress->zip}}" class="form-control" id="exampleFormControlInput1" placeholder="Phone Number">
                                    @else
                                        <input type="number" name="zip" value="{{auth()->guard('customer')->user()->zip}}" class="form-control" id="exampleFormControlInput1" placeholder="Phone Number">
                                    @endif
                                @else
                                    <input type="number" name="zip" value="{{auth()->guard('customer')->user()->zip}}" class="form-control" id="exampleFormControlInput1" placeholder="Phone Number">
                                @endif
                                </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </form>
        </div>
    </div>
</div>

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
                        $.each(response.districts, function (key, value) {
							$('.district').append('<option value="'+ value.dist_id +'">'+ value.np_name +'</option>');
						})
                },
                error: function(response) {
                }
            });
        })
    })
</script>
<script type="text/javascript">
    function valueChanged()
    {
        if($('#flexCheckDefault').is(":checked"))
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
                        $.each(response.districts, function (key, value) {
							$('.bdistrict').append('<option value="'+ value.dist_id +'">'+ value.np_name +'</option>');
						})
                },
                error: function(response) {
                }
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
        $('.district').on('change', function() {
            var district_id = $('.district').val();
            $.ajax({
                url: "{{ route('getLocal') }}",
                type: 'post',
                data: {
                    district_id: district_id,
                },
                // dataType: 'JSON',
                success: function(response) {
                        console.log(response);
                        $('.local').empty();
                        $('.local').append('<option >Select Local</option>');
                        $.each(response.locals, function (key, value) {
							$('.local').append('<option value="'+ value.id +'">'+ value.local_name +'</option>');
						})
                },
                error: function(response) {
                }
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
                        $.each(response.locals, function (key, value) {
							$('.blocal').append('<option value="'+ value.id +'">'+ value.local_name +'</option>');
						})
                },
                error: function(response) {
                }
            });
        })
    })
</script>
@endpush
