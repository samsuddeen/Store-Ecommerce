@extends('frontend.layouts.app')
@section('title', 'Guest Checkout')
@section('content')
    @php
        $pid = Str::random(25) . rand(0000, 9999);
    @endphp
    <section id="checkOut_wrapper" class="mt mb">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="form_wrapper">
                        <div class="cart_table_head">
                            <h3>Delivery Details</h3>
                        </div>
                        <form action="{{ route('guest.allcheckout.orderSuccess') }}" id="myForm" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="text" name="pid_info" id="pid_info" value="{{ $pid }}" hidden>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="Name">Full Name:</label>
                                        <input type="text" name="name" id="name" class="form-control" required>
                                        <div class="full_name_error"></div>
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="Phone">Phone:</label>
                                        <input type="number" name="phone" id="phone" class="form-control" required>
                                        <div class="phone_num_error"></div>
                                        @error('phone')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="email">Email:</label>
                                        <input type="email" name="email" id="email" class="form-control" required>
                                        <div class="email_error"></div>
                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="province">Province:</label>
                                        <select name="province" id="province" class="form-control provinces" required>
                                            <option value="">--Please Select Your Province--</option>
                                            @foreach ($provinces as $province)
                                                <option value="{{ $province->id }}">{{ $province->eng_name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="province_error"></div>
                                        @error('province')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {{ Form::label('district', 'District') }}
                                        {{ Form::select('district', [], [], ['class' => 'district form-control form-control-sm ' . ($errors->has('district') ? 'is-invalid' : ''), 'placeholder' => '--Please Select Province Before District--', 'required' => true, 'disabled']) }}
                                        <div class="district_error"></div>
                                        @error('district')
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
                                        {{ Form::select('area', [], [], ['class' => 'area form-control form-control-sm ' . ($errors->has('area') ? 'is-invalid' : ''), 'placeholder' => '--Please Select District Before Area---', 'required' => true, 'disabled']) }}
                                        <div class="area_error"></div>
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
                                        {{ Form::select('additional_address', [], [], ['class' => 'additional_address form-control form-control-sm ' . ($errors->has('additional_address') ? 'is-invalid' : ''), 'placeholder' => '-----------Select Any One-----------', 'required' => true, 'disabled' => true]) }}
                                        <div class="additional_address_error"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <label for="zip">Zip Code</label>
                                        <select name="zip" id="zip" class="form-control" disabled>
                                            <option value="">--Your Zip Code---</option>
                                        </select>
                                        <div class="zip_error"></div>
                                        @error('zip')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>                  

                                <div class="col-lg-12">
                                    <h2 class="inner-title">
                                        Payment
                                    </h2>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment" value="COD"
                                            id="flexRadioDefault3" checked>
                                        <img src="{{ asset('frontend/cod.jpg') }}" alt=""
                                            class="img img-fluid img-thubnail" width="100px" height="100px">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment" value="khalti"
                                            id="flexRadioDefault2">
                                        <img src="{{ asset('frontend/logo1.png') }}" alt=""
                                            class="img img-fluid img-thubnail" width="100px" height="100px">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-check esewa-check">
                                        <input class="form-check-input" type="radio" name="payment" value="esewa">
                                        <img src="{{ asset('frontend/png-clipart-esewa-fonepay-pvt-ltd-logo-brand-cash-on-delivery-logo-text-logo.png') }}"
                                            alt="" class="img img-fluid img-thubnail" width="100px"
                                            height="100px">
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="col-lg-12">
                                        <div class="global-btns">
                                            <button type="button" class="btns checkout-all"> Continue Checkout </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="checkout_detail_wrap">
                        <div class="cart_table_head">
                            <h3>Total Product</h3>
                        </div>
                        <div class="pt-table-head">
                            <ul>
                                @if ($guest_cart_products != null)
                                    @foreach ($guest_cart_products as $product)
                                        <li>
                                            <div class="tp-left">
                                                <h3>
                                                    <a href="{{ route('product.details', $product['product_slug']) }}">
                                                        {{ $product['product_name'] }} <span>*
                                                            {{ $product['qty'] }}</span>
                                                    </a>
                                                </h3>
                                            </div>
                                            <div class="tp-right">
                                                <b>{{ $product['price'] }}</b>
                                            </div>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                        <div class="pt-totals">
                            <ul>
                                <li>
                                    <span>Prpoduct Qty</span>
                                    <b>{{ @$guest_cart_total_item }}</b>
                                </li>
                                <li>
                                    <span>Sub Total</span>
                                    <b>{{ @$guest_cart_total_amount }}</b>
                                    <input type="hidden" id="all_sub_total" value="">
                                </li>
                                <li>
                                    <span>Shipping Charge</span>
                                    <span class="shipping_charge">0</span>
                                </li>
                                <li>
                                    <span>Total</span>
                                    <p class="allTotal" data-totalAmount="{{ @$guest_cart_total_amount }}">
                                        {{ @$guest_cart_total_amount }}</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="" id="totalamountValue">
                        <input type="hidden" name="payment_amount" value="{{ @$guest_cart_total_amount }}"
                            data-payAmounts="{{ @$guest_cart_total_amount }}" id="payAmount">
                    </div>
                    <div class="promo_code">
                        <h2>Promo Code</h2>
                        <p>please login for use promo code.</p>
                        <p class="not_found text-danger"></p>
                        <p class="found text-success"></p>
                        <div class="copon_code">
                            <input type="text" name="coupon_code" value="" class="form-control coupon"
                                disabled>
                            <button class="btn coupon_code" disabled>Redeem</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal-esewa" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <form action="https://uat.esewa.com.np/epay/main" method="POST" id="esewaForm">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Pay With Esewa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input value="{{ @$guest_cart_total_amount }}" name="tAmt" type="hidden"
                            id="real_total_amount">
                        <input value=" {{ @$guest_cart_total_amount }}" name="amt" type="hidden"
                            id="sub_total_amount">
                        <input value="0" name="txAmt" type="hidden">
                        <input value="0" name="psc" type="hidden">
                        <input value="0" name="pdc" type="hidden" id="delivery_charge">
                        <input value="EPAYTEST" name="scd" type="hidden">
                        <input value="{{ $pid }}" name="pid" type="hidden">
                        <input value="{{ route('guest.checkoutEsewa.success') }}" type="hidden" name="su">
                        <input value="http://merchant.com.np/page/esewa_payment_failed?q=fu" type="hidden"
                            name="fu">
                        <input value="Confirm To Pay" type="submit" class="btn btn-danger form-control">
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- Modal End  -->

@endsection
@push('script')
    @include('frontend.cart.khalti-allproduct-script')

    <script>
        $('.provinces').change(function(e) {
            e.preventDefault();
            $('.district').removeAttr('disabled');
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
                        "<option value=''>--Select Any One--</option>";
                    if (response.error) {
                        alert(response.error);
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
            $('.area').removeAttr('disabled');
            const district_id = $(this).val();
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
                        "<option value=''>--Select Any One--</option>";
                    if (response.error) {
                        alert(response.error);
                    } else {
                        if (response.data.child.length > 0) {
                            $.each(response.data.child, function(index, value) {
                                area_html += "<option value='" + value.id + "'";
                                area_html += ">" + value.local_name + "</option>";
                            });
                        }
                    }
                    $('.area').html(area_html);
                }
            });
        });
    </script>

    <script>
        $('.area').change(function(e) {
            e.preventDefault();

            $('.additional_address').removeAttr('disabled');
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
                        alert(response.error);
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
                        alert('Something Is Wrong for shipping Charge');
                        $('.shipping_charge').html((parseInt(response.charge)));
                        var fixed_amount = $('#payAmount').val();
                        $('#totalamountValue').html(
                            '<input type="hidden" name="payment_amount" value="' + (parseInt(
                                fixed_amount)) + '" data-payAmounts="' + (parseInt(fixed_amount)) +
                            '" id="payAmount">');
                    }
                    $('.shipping_charge').html((parseInt(response.charge)));
                    var total_amount = $('.allTotal').attr('data-totalAmount');
                    $('.allTotal').html('Rs ' + (parseInt(total_amount) + parseInt(response.charge)));
                    $('#real_total_amount').val((parseInt(total_amount) + parseInt(response.charge)));
                    $('#delivery_charge').val(parseInt(response.charge));
                    var fixed_amount = $('#payAmount').val();
                    $('#totalamountValue').html('<input type="hidden" name="payment_amount" value="' + (
                            parseInt(fixed_amount) + parseInt(response.charge)) +
                        '" data-payAmounts="' + (parseInt(fixed_amount) + parseInt(response
                            .charge)) + '" id="payAmount">');
                    $('#zip').html(response.zip_code_is);
                }
            });
        });
    </script>


    <script>
        function esewaOrderInfoGuest() {
            const guestOrderForm = document.getElementById('myForm');
            var guest_name = guestOrderForm['name'].value;
            var phone = guestOrderForm['phone'].value;
            var email = guestOrderForm['email'].value;
            var province = guestOrderForm['province'].value;
            var district = guestOrderForm['district'].value;
            var area = guestOrderForm['area'].value;
            var additiona_address = guestOrderForm['additional_address'].value;
            var zip = guestOrderForm['zip'].value;
            var pid_info = guestOrderForm['pid_info'].value;

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('guest.storeInfo.allCheckout') }}",
                type: "post",
                data: {
                    name: guest_name,
                    phone: phone,
                    email: email,
                    province: province,
                    district: district,
                    area: area,
                    additional_address: additiona_address,
                    pid_info: pid_info,
                    zip: zip,
                },
                success: function(response) {
                    if (response.error) {
                        alert(response.msg);
                    }
                }
            });
        }
    </script>
@endpush
