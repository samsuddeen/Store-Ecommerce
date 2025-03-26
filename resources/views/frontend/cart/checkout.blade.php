@extends('frontend.layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Checkout')
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
                            <h3>Delivery Detail</h3>
                        </div>
                        <form action="{{ route('cash-on-delivery') }}" method="post" id="myForm"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="text" name="newly_generated_pid" id="" value="{{ $pid }}"
                                hidden>
                            <div class="login-checkout-head">
                                <p>Please Select Shipping Address.
                                    <a href="{{ route('shipping.address.book') }}" class="">View All Shipping
                                        Address</a>
                                </p>
                                <div class="round-btns">
                                    <button type="button" class="btns" data-bs-toggle="modal"
                                        data-bs-target="#addShippingAddress">
                                        Add Shipping Address
                                    </button>
                                </div>
                            </div>
                            <div class="login-checkout-col">
                                <div class="row margin">
                                    @foreach ($shipping_address as $key => $s_address)
                                        <div class="col-lg-4 col-md-6 col-sm-6 padding">
                                            <div class="login-checkout-wrap">
                                                <input class="form-check-input shippingAddress" required
                                                    name="shipping_address" type="radio" value="{{ $s_address->id }}"
                                                    id="shippingAddress" {{ $key == 0 ? 'checked' : '' }}>
                                                <span>{{ $s_address->name }}</span>
                                                <p>{{ $s_address->phone }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="login-checkout-head mt-3">
                                <p>Please Select Billing Address
                                    <a href="{{ route('addressBook') }}" class="">View
                                        All Billing Address</a>
                                </p>
                                <div class="round-btns">
                                    <button type="button" class="btns" data-bs-toggle="modal"
                                        data-bs-target="#addBillingAddress">
                                        Add Billing Address
                                    </button>
                                </div>
                            </div>
                            <div class="login-checkout-col">
                                <div class="row margin">
                                    @foreach ($billing_address as $bkey => $b_address)
                                        <div class="col-lg-4 col-md-6 col-sm-6 padding">
                                            <div class="login-checkout-wrap">
                                                <input class="form-check-input billing_address" name="billing_address"
                                                    type="{{ $billing_address->count() > 1 ? 'radio' : 'checkbox' }}"
                                                    value="{{ $b_address->id }}" id="billingAddress">
                                                <span>{{ $b_address->name }}</span>
                                                <p>{{ $b_address->phone }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="shiping_address">
                                <div class="form-check">
                                    <input class="form-check-input same same_address" name="same_address" type="checkbox"
                                        value="1" id="flexCheckDefault">
                                    <label class="form-check-label same_address_text" for="flexCheckDefault">
                                        Billing address is the same as my shipping address
                                    </label>
                                </div>
                            </div>
                            <div class="payment-options">
                                <h2 class="inner-title">Payment</h2>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="d-flex align-items-start" style="gap: 10px;">

                                            
                                            <div class="">
                                                <div class="form-check">
                                                    <div class="d-flex justify-content-between align-items-center" style="gap: 10px;">
                                                        <div>
                                                            <input class="form-check-input paymentMethod" type="radio" name="payment" value="COD" id="flexRadioDefault1" checked>
                                                        </div>
                                                        <label for="flexRadioDefault1" class="form-check-label">
                                                            <img src="{{ asset('frontend/images/cash.png') }}" alt="Cash on Delivery" style="height: 40px;">
                                                            Cash on Delivery 
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="">
                                                <div class="form-check">
                                                    <div class="d-flex justify-content-between align-items-center" style="gap: 10px;">
                                                        <div>
                                                            <input class="form-check-input paymentMethod" type="radio" name="payment" value="QRCode" id="flexRadioDefault2">
                                                        </div>
                                                        <label for="flexRadioDefault2" class="form-check-label">
                                                            <img src="{{ asset('qrcodenew.jpg') }}" alt="QR Code Payment" style="height: 40px;">
                                                            QR Code Payment
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            
                                            
                                        </div>
                                        <div class="mt-3" id="cod-section">
                                            <div>
                                                <img src="{{ asset('qrcodenew.jpg') }}" alt="QR Code" class="qr-code" style="height: 280px;">
                                            </div>

                                            <div class="" id="upload-section">
                                                <div class="upload-section">
                                                    <label for="transactionImage" class="upload-label">
                                                        <i class="fas fa-upload"></i> Insert File
                                                        <input type="file" id="transactionImage" name="payment_proof" accept="image/*" required />
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                               
                            
                            


                                    {{-- <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-check">
                                            <input class="form-check-input paymentMethod" type="radio" name="payment" value="Fonepay"
                                                id="flexRadioDefault2">
                                            <label for="flexRadioDefault2" class="form-check-label"><img
                                                    src="{{ asset('frontend/fonepay.png') }}" alt=""></label>
                                        </div>
                                    </div> --}}
                                    
                                    {{-- <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-check">
                                            <input class="form-check-input paymentMethod" type="radio" name="payment" value="Hbl"
                                                id="flexRadioDefault2" checked>
                                            <label for="flexRadioDefault2" class="form-check-label"><img
                                                    src="{{ asset('frontend/vgjh.png') }}" alt=""></label>
                                        </div>
                                    </div> --}}

                                    {{-- <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-check">
                                            <input class="form-check-input paymentMethod " type="radio" name="payment" value="paypal"
                                                id="flexRadioDefault2">
                                            <label for="flexRadioDefault2" class="form-check-label paypal"><img
                                                    src="{{ asset('frontend/paypal.png') }}" alt=""></label>
                                        </div>
                                    </div> --}}

                                    
                                    {{-- <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment" value=""
                                                id="flexRadioDefault2">
                                            <label for="flexRadioDefault2" class="form-check-label"><img
                                                    src="{{ asset('frontend/images/esewa.png') }}" alt=""></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment" value=""
                                                id="flexRadioDefault3">
                                            <label for="flexRadioDefault3" class="form-check-label"><img
                                                    src="{{ asset('frontend/images/khalti.png') }}" alt=""></label>
                                        </div>
                                    </div> --}}
                                </div>
                                @php
                                    $consumer = auth()
                                        ->guard('customer')
                                        ->user();
                                    if ($consumer->UserShippingAddress != null) {
                                        if ($consumer->UserShippingAddress->future_use == 1) {
                                            $area_id = \App\Models\City::where('city_name', $consumer->UserShippingAddress->area)->value('id');
                                        } else {
                                            $area_id = \App\Models\City::where('city_name', $consumer->area)->value('id');
                                        }
                                    } else {
                                        $area_id = \App\Models\City::where('city_name', $consumer->area)->value('id');
                                    }
                                    $shipping_route = null;
                                    if ($shipping_route == null) {
                                        $shipping_charge = $default_shipping_charge;
                                    } else {
                                        $shipping_charge = $shipping_route->charge->branch_delivery;
                                    }
                                    
                                @endphp
                                <input type="hidden" class="coupoon_code" name="coupoon_code">
                                <input type="hidden" class="coupon_name" name="coupon_name">
                                <input type="hidden" class="coupon_code_name" name="coupon_code_name">
                                <div class="global-btns">
                                    <button class="btns" id="checkout" type="submit">Continue to Checkout</button>
                                </div>
                            </div>
                        </form>

                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('billingAddress') }}" method="post"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row">
                                                @if ($errors->any())
                                                    @foreach ($errors->all() as $error)
                                                        <div class="text-danger">{{ $error }}</div>
                                                    @endforeach
                                                @endif
                                                <div class="col-md-6">
                                                    <div class="mb-3 input_type_wrap">
                                                        <label for="exampleFormControlInput1" class="form-label">Full
                                                            Name</label>
                                                        <div class="input_field">
                                                            @if ($consumer->userBillingAddress != null)
                                                                @if ($consumer->userBillingAddress->future_use == 1)
                                                                    <input type="text" name="name"
                                                                        value="{{ $consumer->userBillingAddress->name }}"
                                                                        class="form-control" id="exampleFormControlInput1"
                                                                        placeholder="Name">
                                                                @else
                                                                    <input type="text" name="name"
                                                                        value="{{ auth()->guard('customer')->user()->name }}"
                                                                        class="form-control" id="exampleFormControlInput1"
                                                                        placeholder="Name">
                                                                @endif
                                                            @else
                                                                <input type="text" name="name"
                                                                    value="{{ auth()->guard('customer')->user()->name }}"
                                                                    class="form-control" id="exampleFormControlInput1"
                                                                    placeholder="Name">
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3 input_type_wrap">
                                                        <label for="exampleFormControlInput1" class="form-label">Email
                                                            address</label>
                                                        <div class="input_field">
                                                            @if ($consumer->userBillingAddress != null)
                                                                @if ($consumer->userBillingAddress->future_use == 1)
                                                                    <input type="email"
                                                                        value="{{ $consumer->userBillingAddress->email }}"
                                                                        name="email" class="form-control"
                                                                        id="exampleFormControlInput1"
                                                                        placeholder="name@example.com">
                                                                @else
                                                                    <input type="email"
                                                                        value="{{ auth()->guard('customer')->user()->email }}"
                                                                        name="email" class="form-control"
                                                                        id="exampleFormControlInput1"
                                                                        placeholder="name@example.com">
                                                                @endif
                                                            @else
                                                                <input type="email"
                                                                    value="{{ auth()->guard('customer')->user()->email }}"
                                                                    name="email" class="form-control"
                                                                    id="exampleFormControlInput1"
                                                                    placeholder="name@example.com">
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3 input_type_wrap">
                                                        <label for="exampleFormControlInput1" class="form-label">Phone
                                                            (Required)</label>
                                                        <div class="input_field">
                                                            @if ($consumer->userBillingAddress != null)
                                                                @if ($consumer->userBillingAddress->future_use == 1)
                                                                    <input type="number"
                                                                        value="{{ $consumer->userBillingAddress->phone }}"
                                                                        name="phone" class="form-control"
                                                                        id="exampleFormControlInput1"
                                                                        placeholder="9849736232">
                                                                @else
                                                                    <input type="number"
                                                                        value="{{ auth()->guard('customer')->user()->phone }}"
                                                                        name="phone" class="form-control"
                                                                        id="exampleFormControlInput1"
                                                                        placeholder="9849736232">
                                                                @endif
                                                            @else
                                                                <input type="number"
                                                                    value="{{ auth()->guard('customer')->user()->phone }}"
                                                                    name="phone" class="form-control"
                                                                    id="exampleFormControlInput1"
                                                                    placeholder="9849736232">
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3 input_type_wrap">
                                                        <label for="exampleFormControlInput1"
                                                            class="form-label">Province</label>
                                                        <div class="input_field">
                                                            <select name="province"
                                                                class="form-select bprovince form-control">
                                                                @if ($consumer->userBillingAddress != null)
                                                                    @if ($consumer->userBillingAddress->future_use == 1)
                                                                        @php
                                                                            $id = \App\Models\Province::where('eng_name', $consumer->UserBillingAddress->province)->value('id');
                                                                        @endphp
                                                                        <option value="{{ $id }}" selected>
                                                                            {{ $consumer->userBillingAddress->province }}
                                                                        </option>
                                                                    @else
                                                                        @php
                                                                            $id = \App\Models\Province::where(
                                                                                'eng_name',
                                                                                auth()
                                                                                    ->guard('customer')
                                                                                    ->user()->province,
                                                                            )->value('id');
                                                                        @endphp
                                                                        <option value="{{ $id }}" selected>
                                                                            {{ auth()->guard('customer')->user()->province }}
                                                                        </option>
                                                                    @endif
                                                                @elseif(auth()->guard('customer')->user()->province)
                                                                    @php
                                                                        $id = \App\Models\Province::where(
                                                                            'eng_name',
                                                                            auth()
                                                                                ->guard('customer')
                                                                                ->user()->province,
                                                                        )->value('id');
                                                                    @endphp
                                                                    <option value="{{ $id }}" selected>
                                                                        {{ auth()->guard('customer')->user()->province }}
                                                                    </option>
                                                                @else
                                                                    <option selected> Select Province </option>
                                                                @endif
                                                                @foreach ($provinces as $province)
                                                                    <option value="{{ $province->id }}">
                                                                        {{ $province->eng_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3 input_type_wrap">
                                                        <label for="exampleFormControlInput1"
                                                            class="form-label">District</label>
                                                        <div class="input_field">
                                                            <select name="district"
                                                                class="form-select bdistrict form-control">
                                                                @if ($consumer->userBillingAddress != null)
                                                                    @if ($consumer->userBillingAddress->future_use == 1)
                                                                        @php
                                                                            $id = \App\Models\District::where('np_name', $consumer->userBillingAddress->district)->value('dist_id');
                                                                        @endphp
                                                                        <option value="{{ $id }}" selected>
                                                                            {{ $consumer->userBillingAddress->district }}
                                                                        </option>
                                                                    @else
                                                                        @php
                                                                            $id = \App\Models\District::where(
                                                                                'np_name',
                                                                                auth()
                                                                                    ->guard('customer')
                                                                                    ->user()->district,
                                                                            )->value('dist_id');
                                                                        @endphp
                                                                        <option value="{{ $id }}" selected>
                                                                            {{ auth()->guard('customer')->user()->district }}
                                                                        </option>
                                                                    @endif
                                                                @elseif(auth()->guard('customer')->user()->district)
                                                                    @php
                                                                        $id = \App\Models\District::where(
                                                                            'np_name',
                                                                            auth()
                                                                                ->guard('customer')
                                                                                ->user()->district,
                                                                        )->value('dist_id');
                                                                    @endphp
                                                                    <option value="{{ $id }}" selected>
                                                                        {{ auth()->guard('customer')->user()->district }}
                                                                    </option>
                                                                @else
                                                                    <option selected> Select District </option>
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3 input_type_wrap">
                                                        <label for="exampleFormControlInput1"
                                                            class="form-label">Area</label>
                                                        <div class="input_field">
                                                            <select name="area"
                                                                class="form-select blocal form-control">
                                                                @if ($consumer->userBillingAddress != null)
                                                                    @if ($consumer->userBillingAddress->future_use == 1)
                                                                        @php
                                                                            $id = \App\Models\City::where('city_name', $consumer->userBillingAddress->area)->value('id');
                                                                        @endphp
                                                                        <option value="{{ $id }}" selected>
                                                                            {{ $consumer->userBillingAddress->area }}
                                                                        </option>
                                                                    @else
                                                                        @php
                                                                            $id = \App\Models\City::where(
                                                                                'city_name',
                                                                                auth()
                                                                                    ->guard('customer')
                                                                                    ->user()->area,
                                                                            )->value('id');
                                                                        @endphp
                                                                        <option value="{{ $id }}" selected>
                                                                            {{ auth()->guard('customer')->user()->area }}
                                                                        </option>
                                                                    @endif
                                                                @elseif(auth()->guard('customer')->user()->area)
                                                                    @php
                                                                        $id = \App\Models\City::where(
                                                                            'city_name',
                                                                            auth()
                                                                                ->guard('customer')
                                                                                ->user()->area,
                                                                        )->value('id');
                                                                    @endphp
                                                                    <option value="{{ $id }}" selected>
                                                                        {{ auth()->guard('customer')->user()->area }}
                                                                    </option>
                                                                @else
                                                                    <option selected> Select Area </option>
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="hidden" value="{{ auth()->guard('customer')->user()->id }}"
                                                    name="user_id">
                                                <div class="col-md-6">
                                                    <div class="mb-3 input_type_wrap">
                                                        <label for="exampleFormControlInput1" class="form-label">Address
                                                            (optional)</label>
                                                        <div class="input_field">
                                                            @if ($consumer->userBillingAddress != null)
                                                                @if ($consumer->userBillingAddress->future_use == 1)
                                                                    <input type="text" name="additional_address"
                                                                        value="{{ $consumer->userBillingAddress->additional_address }}"
                                                                        class="form-control" id="exampleFormControlInput1"
                                                                        placeholder="near machapokhari">
                                                                @else
                                                                    <input type="text" name="additional_address"
                                                                        value="{{ auth()->guard('customer')->user()->address }}"
                                                                        class="form-control" id="exampleFormControlInput1"
                                                                        placeholder="near machapokhari">
                                                                @endif
                                                            @else
                                                                <input type="text" name="additional_address"
                                                                    value="{{ auth()->guard('customer')->user()->address }}"
                                                                    class="form-control" id="exampleFormControlInput1"
                                                                    placeholder="near machapokhari">
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3 input_type_wrap">
                                                        <label for="exampleFormControlInput1"
                                                            class="form-label">zip</label>
                                                        <div class="input_field">
                                                            @if ($consumer->userBillingAddress != null)
                                                                @if ($consumer->userBillingAddress->future_use == 1)
                                                                    <input type="number" name="zip"
                                                                        value="{{ $consumer->userBillingAddress->zip }}"
                                                                        class="form-control" id="exampleFormControlInput1"
                                                                        placeholder="Phone Number">
                                                                @else
                                                                    <input type="number" name="zip"
                                                                        value="{{ auth()->guard('customer')->user()->zip }}"
                                                                        class="form-control" id="exampleFormControlInput1"
                                                                        placeholder="Phone Number">
                                                                @endif
                                                            @else
                                                                <input type="number" name="zip"
                                                                    value="{{ auth()->guard('customer')->user()->zip }}"
                                                                    class="form-control" id="exampleFormControlInput1"
                                                                    placeholder="Phone Number">
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
                        <!-- end of model -->
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="checkout_detail_wrap">
                        <div class="cart_table_head">
                            <h3>Your cart (Checking Out)</h3>
                            <div class="custom_badge">{{ $cart_item->sum('qty') }}</div>
                        </div>
                        <input type="hidden" class="sub_total" value="{{ $cart_item->sum('sub_total_price') }}"
                            name="sub_total">
                        <div class="pt-table-head">
                            <ul>
                                @foreach ($cart_item as $item)
                                {{-- @dd($item) --}}
                                    <li>
                                        <div class="tp-left">
                                            <h3>
                                                <a href="{{ route('product.details', $item->product->slug) }}">
                                                    {{ $item->product_name }} <span>* {{ $item->qty }}</span>
                                                </a>
                                            </h3>
                                            @if($item->options)
                                            @php
                                                $optionsArray = json_decode($item->options, true);
                                            @endphp
                                            @foreach ($optionsArray as $option)
                                            <span style="font-size:12px;">{{ $option['title'] }}:{{ $option['value'] }}</span>
                                            @endforeach
                                            @endif
                                        </div>
                                        <div class="tp-right">
                                            <b>Rs.{{ number_format($item->sub_total_price) }}</b>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="pt-totals">
                            <ul>
                                <li>
                                    <span>Sub Total</span>
                                    <input type="hidden" class="all_sub_total" name="all_sub_total"
                                        data-amountId="{{ @$total_amount }}" value="{{ @$total_amount }}">
                                    <b class="after_coupon">Rs.{{ number_format(@$total_amount) }}</b>
                                </li>
                                <li>
                                    <span>Shipping Charge</span>
                                    <b class="shipping_charge">Rs.{{ @$default_shipping_charge }}</b>
                                </li>
                                @if($default_material_charge > 0)
                                <li>
                                    <span>Material  Charge</span>
                                    <b class="shipping_charge">Rs.{{ @$default_material_charge }}</b>
                                </li>
                                @endif
                                <li>
                                    <span>Coupon Price</span>
                                    <b class="found">Rs.0</b>
                                </li>
                                <li>
                                    <span>Total</span>
                                    <p class="hidden_amount" hidden>{{ @$total_amount }}</p>
                                    <b class="allTotal" id="items_total_cost" data-totalAmount="{{ @$total_amount }}">Rs.{{ @$total_amount + @$default_shipping_charge + @$default_material_charge  }}</b>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="promo_code">
                        <h2>Promo Code</h2>
                        <p class="not_found text-danger"></p>
                        <p class="found text-success"></p>
                        <div class="copon_code">
                            <input type="text" name="coupon_code" value="" class="form-control coupon">
                            <button class="btn btn-danger coupon_code">Redeem</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="" id="totalamountValue">
            <input type="hidden" name="payment_amount" value="{{ @$total_amount }}"
                data-payAmounts="{{ @$total_amount }}" id="payAmount">
        </div>
        {{-- --------------------------Shipping Address--------------------- --}}
        <div class="common-popup modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">My Shipping Address</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            @foreach ($shipping_address as $key => $s_address)
                                <div class="col-md-3 message-box"
                                    style="margin-left:20px;margin-bottom:20px;width: 45%;height:180px;border-radius:10px;padding:20px;border:2px solid rgb(12, 99, 156)">
                                    <span>{{ $s_address->name }}</span>
                                    <a href="javascript:;" class="float-right" data-bs-toggle="modal"
                                        data-bs-target="#exampleModaleditshipping{{ $key }}">Edit</a>
                                    <p>{{ $s_address->phone }}</p>
                                    <p>{{ $s_address->province }},{{ $s_address->district }},{{ $s_address->area }}</p>
                                    <p>{{ $s_address->additional_address }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- ----------------------Shipping Address----------------------------------- --}}

        {{-- --------------------------Billing Address--------------------- --}}
        <div class="common-popup modal fade" id="exampleModalbilling" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">My Billing Address</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            @foreach ($billing_address as $bkey => $b_address)
                                <div class="col-md-3 message-box"
                                    style="margin-left:20px;margin-bottom:20px;width: 45%;height:180px;border-radius:10px;padding:20px;border:2px solid rgb(12, 99, 156)">
                                    <span>{{ $b_address->name }}</span>
                                    <a href="javascript:;" class="float-right" data-bs-toggle="modal"
                                        data-bs-target="#exampleModaleditbilling{{ $bkey }}">Edit</a>
                                    <p>{{ $b_address->phone }}</p>
                                    <p>{{ $b_address->province }},{{ $b_address->district }},{{ $b_address->area }}</p>
                                    <p>{{ $b_address->additional_address }}</p>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- ----------------------Billing Address----------------------------------- --}}

        {{-- --------------------------Edit Shipping Address--------------------- --}}
        @foreach ($shipping_address as $key => $s_address)
            <div class="common-popup modal fade" id="exampleModaleditshipping{{ $key }}" tabindex="-1"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-center" id="exampleModalLabel">Edit Shipping Address</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                {{-- {{ Form::open(['url'=>route('update-shipping-address',@$s_address->id)])}} --}}
                                <form action="#" id="editShippingAddress">
                                    <input type="number" name="shipping_id" value="{{ @$s_address->id }}" hidden>
                                    <div class="mb-3">
                                        {{ Form::label('name', 'Name') }}
                                        {{ Form::text('name', @$s_address->name, ['class' => 'form-control form-control-sm ' . ($errors->has('name') ? 'is-invalid' : ''), 'placeholder' => 'Enter Your Name Here.....', 'required' => true]) }}
                                        @error('name')
                                            <div class="invalid-feedback">
                                                <i class="bx bx-radio-circle"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        {{ Form::label('email', 'Email') }}
                                        {{ Form::email('email', @$s_address->email, ['class' => 'form-control form-control-sm ' . ($errors->has('email') ? 'is-invalid' : ''), 'required' => true, 'placeholder' => 'Enter Your Email Here.....']) }}
                                        <div id="email" class="form-text">We'll never share your email with anyone
                                            else.</div>
                                        @error('email')
                                            <div class="invalid-feedback">
                                                <i class="bx bx-radio-circle"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        {{ Form::label('phone', 'Phone') }}
                                        {{ Form::text('phone', @$s_address->phone, ['class' => 'form-control form-control-sm ' . ($errors->has('phone') ? 'is-invalid' : ''), 'required' => true, 'placeholder' => 'Enter Your Phone Num Here.....']) }}
                                        @error('phone')
                                            <div class="invalid-feedback">
                                                <i class="bx bx-radio-circle"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        {{ Form::label('province', 'Province') }}
                                        {{ Form::select('province', $provinces->pluck('eng_name', 'id'), [], ['class' => 'provinces form-control form-control-sm ' . ($errors->has('province') ? 'is-invalid' : ''), 'placeholder' => '-----------Select Any One-----------', 'required' => true]) }}
                                        @error('province')
                                            <div class="invalid-feedback">
                                                <i class="bx bx-radio-circle"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        {{ Form::label('district', 'District') }}
                                        {{ Form::select('district', [], [], ['class' => 'district form-control form-control-sm ' . ($errors->has('district') ? 'is-invalid' : ''), 'placeholder' => '-----------Select Any One-----------', 'required' => true]) }}
                                        @error('district')
                                            <div class="invalid-feedback">
                                                <i class="bx bx-radio-circle"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        {{ Form::label('area', 'Area') }}
                                        {{ Form::select('area', [], [], ['class' => 'area form-control form-control-sm ' . ($errors->has('area') ? 'is-invalid' : ''), 'placeholder' => '-----------Select Any One-----------', 'required' => true]) }}
                                        @error('area')
                                            <div class="invalid-feedback">
                                                <i class="bx bx-radio-circle"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        {{ Form::label('additional_address', 'Additional Area') }}
                                        {{ Form::select('additional_address', [], [], ['class' => 'additional_address form-control form-control-sm ' . ($errors->has('additional_address') ? 'is-invalid' : ''), 'placeholder' => '-----------Select Any One-----------', 'required' => true]) }}
                                        {{-- {{ Form::textarea('addtional_address',@$s_address->additional_address,['class'=>'form-control form-control-sm '.($errors->has('addtional_address') ?'is-invalid':''),'placeholder'=>'Enter Your Additional Address.....','rows'=>3,'style'=>'resize:none;'])}} --}}
                                        @error('additional_address')
                                            <div class="invalid-feedback">
                                                <i class="bx bx-radio-circle"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        {{ Form::label('zip', 'Zip Code') }}
                                        {{ Form::number('zip', @$s_address->zip, ['class' => 'form-control form-control-sm ' . ($errors->has('zip') ? 'is-invalid' : ''), 'placeholder' => 'zip Code .....', 'required' => true, 'min' => '1']) }}
                                        @error('zip')
                                            <div class="invalid-feedback">
                                                <i class="bx bx-radio-circle"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <button type="button" class=" btn btn-success updateShippingAddress">Update<i
                                            class="las la-edit"></i></button>
                                    {{ Form::close() }}

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        {{-- ----------------------Edit Shipping Address----------------------------------- --}}

        {{-- --------------------------Edit Billing Address--------------------- --}}
        @foreach ($billing_address as $bkey => $b_address)
            <div class="common-popup modal fade" id="exampleModaleditbilling{{ $bkey }}" tabindex="-1"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-center" id="exampleModalLabel">Edit Shipping Address</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                {{-- {{ Form::open(['url'=>route('update-billing-address',@$b_address->id)])}} --}}
                                <form action="" id="editBillingAddress">
                                    @method('post')
                                    <input type="number" name="billing_id" value="{{ @$b_address->id }}" hidden>
                                    <div class="mb-3">
                                        {{ Form::label('name', 'Name') }}
                                        {{ Form::text('name', @$b_address->name, ['class' => 'form-control form-control-sm ' . ($errors->has('name') ? 'is-invalid' : ''), 'placeholder' => 'Enter Your Name Here.....', 'required' => true]) }}
                                        @error('name')
                                            <div class="invalid-feedback">
                                                <i class="bx bx-radio-circle"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        {{ Form::label('email', 'Email') }}
                                        {{ Form::email('email', @$b_address->email, ['class' => 'form-control form-control-sm ' . ($errors->has('email') ? 'is-invalid' : ''), 'required' => true, 'placeholder' => 'Enter Your Email Here.....']) }}
                                        <div id="email" class="form-text">We'll never share your email with anyone
                                            else.</div>
                                        @error('email')
                                            <div class="invalid-feedback">
                                                <i class="bx bx-radio-circle"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        {{ Form::label('phone', 'Phone') }}
                                        {{ Form::text('phone', @$b_address->phone, ['class' => 'form-control form-control-sm ' . ($errors->has('phone') ? 'is-invalid' : ''), 'required' => true, 'placeholder' => 'Enter Your Phone Num Here.....']) }}
                                        @error('phone')
                                            <div class="invalid-feedback">
                                                <i class="bx bx-radio-circle"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        {{ Form::label('province', 'Province') }}
                                        {{ Form::select('province', $provinces->pluck('eng_name', 'eng_name'), [], ['class' => 'provinces form-control form-control-sm ' . ($errors->has('province') ? 'is-invalid' : ''), 'placeholder' => '-----------Select Any One-----------', 'required' => true]) }}
                                        @error('province')
                                            <div class="invalid-feedback">
                                                <i class="bx bx-radio-circle"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        {{ Form::label('district', 'District') }}
                                        {{ Form::select('district', [], [], ['class' => 'district form-control form-control-sm ' . ($errors->has('district') ? 'is-invalid' : ''), 'placeholder' => '-----------Select Any One-----------', 'required' => true]) }}
                                        @error('district')
                                            <div class="invalid-feedback">
                                                <i class="bx bx-radio-circle"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        {{ Form::label('area', 'Area') }}
                                        {{ Form::select('area', [], [], ['class' => 'area form-control form-control-sm ' . ($errors->has('area') ? 'is-invalid' : ''), 'placeholder' => '-----------Select Any One-----------', 'required' => true]) }}
                                        @error('area')
                                            <div class="invalid-feedback">
                                                <i class="bx bx-radio-circle"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        {{ Form::label('additional_address', 'Additional Area') }}
                                        {{ Form::select('additional_address', [], [], ['class' => 'additional_address form-control form-control-sm ' . ($errors->has('additional_address') ? 'is-invalid' : ''), 'placeholder' => '-----------Select Any One-----------', 'required' => true]) }}
                                        {{-- {{ Form::textarea('addtional_address',@$s_address->additional_address,['class'=>'form-control form-control-sm '.($errors->has('addtional_address') ?'is-invalid':''),'placeholder'=>'Enter Your Additional Address.....','rows'=>3,'style'=>'resize:none;'])}} --}}
                                        @error('additional_address')
                                            <div class="invalid-feedback">
                                                <i class="bx bx-radio-circle"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        {{ Form::label('zip', 'Zip Code') }}
                                        {{ Form::number('zip', @$b_address->zip, ['class' => 'form-control form-control-sm ' . ($errors->has('zip') ? 'is-invalid' : ''), 'placeholder' => 'zip Code .....', 'required' => true, 'min' => '1']) }}
                                        @error('zip')
                                            <div class="invalid-feedback">
                                                <i class="bx bx-radio-circle"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <button type="button" class=" btn btn-success" id="updateBillingAddress">Update<i
                                            class="las la-edit"></i></button>
                                    {{ Form::close() }}

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        {{-- ----------------------Edit Billing Address----------------------------------- --}}

        {{-- ------------------------------Add Shippinbg Address----------------------------------- --}}
        <div class="common-popup medium-popup modal fade" id="addShippingAddress" tabindex="-1"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
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
                                        {{ Form::label('area', 'Address') }}
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
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {{ Form::label('name', 'Name') }}
                                        {{ Form::text('name', '', ['class' => 'form-control form-control-sm ' . ($errors->has('name') ? 'is-invalid' : ''), 'placeholder' => 'Enter Your Name Here.....', 'required' => true]) }}
                                        <span id="nameErrorB" hidden class="text-danger"></span>
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
                                        <span id="emailErrorB" hidden class="text-danger"></span>
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
                                        {{ Form::label('area', 'Address') }}
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

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {{ Form::label('phone', 'Phone') }}
                                        {{ Form::text('phone', '', ['class' => 'form-control form-control-sm ' . ($errors->has('phone') ? 'is-invalid' : ''), 'required' => true, 'placeholder' => 'Enter Your Phone Num Here.....']) }}
                                        <span id="phoneErrorB" hidden class="text-danger"></span>
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
                                        {{ Form::label('zip', 'Zip Code') }}
                                       
                                        <input type="text" class="form-control zip" name="zip" id="zip">
                                        <span id="zipErrorB" hidden class="text-danger"></span> 
                                        @error('zip')
                                            <div class="invalid-feedback">
                                                <i class="bx bx-radio-circle"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <button type="button" class=" btn" id="addBilling">Add Address</button>
                                </div>
                            </div>
                            {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
        {{-- ----------------------------------/Add Billing Address------------------------------------- --}}
        <!-- Button trigger modal -->
        <!-- Modal -->
        <div class="common-popup modal fade" id="exampleModal-esewa" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <form action="https://esewa.com.np/epay/main" method="POST" id="esewaForm">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Pay With Esewa</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input value="{{ @$total_amount }}" name="tAmt" type="hidden" id="real_total_amount">
                            <input value="{{ @$total_amount }}" name="amt" type="hidden" id="sub_total_amount">
                            <input value="0" name="txAmt" type="hidden">
                            <input value="0" name="psc" type="hidden">
                            <input value="0" name="pdc" type="hidden" id="delivery_charge">
                            <input value="NP-ES-ULTRASOFT" name="scd" type="hidden">
                            <input value="{{ $pid }}" name="pid" type="hidden">
                            <input value="{{ route('esewa-payment-success') }}" type="hidden" name="su">
                            <input value="http://merchant.com.np/page/esewa_payment_failed?q=fu" type="hidden"
                                name="fu">
                            <input value="Confirm To Pay" type="submit" class="btn btn-success form-control">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
@push('script')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
        $(document).ready(function() {
            $('.local').on('change', function() {
                var area_id = $('.local').val();
                var all_sub_total = $('.all_sub_total').val();
                $.ajax({
                    url: "{{ route('getShippingCharge') }}",
                    type: 'post',
                    data: {
                        area_id: area_id,
                    },
                    success: function(response) {
                        console.log(response);
                        var total = parseInt(response.delivery_charge) + parseInt(
                            all_sub_total);
                        $('.shipping_charge').text(response.delivery_charge);
                        $('#shipping_charge').val(response.delivery_charge);
                        $('.allTotal').text(total);
                        $('#grandTotal').val(total);
                        if (response.error) {
                            toastr.options = {
                                "closeButton": true,
                                "progressBar": true
                            }
                            toastr.error(response.error);
                            $('.hide').hide();
                        } else {
                            $('.hide').show();
                        }
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
        var strRandom = function(length) {
            var result = '';
            var characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            var charactersLength = characte$.length;
            for (var i = 0; i < length; i++) {
                result += characte$.charAt(Math.floor(Math.random() * charactersLength));
            }
            return result;
        }
    </script>

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
        });


        $(document).ready(function() {
            $('#checkout').on('click', function(e) {
                // alert('checkout');
                // return false;
                e.preventDefault();
                if (!$('.shippingAddress').is(":checked")) {
                    toastr.options = {
                        "closeButton": true,
                        "progressBar": true
                    }
                    toastr.error("Plz Select Shipping Address");

                    return false;
                }

                let form = document.getElementById('myForm');
                var same = null;
                var coupoon_code = form['coupoon_code'].value;
                var coupon_name = form['coupon_name'].value;
                var coupon_code_name = form['coupon_code_name'].value;

                if ($('.billing_address').is(":checked")) {
                    var billing_address = form['billing_address'].value;
                } else {
                    var billing_address = null;
                }
                if ($('.same_address').is(":checked")) {
                    same = 1;
                } else {
                    same = null;
                };

                if (billing_address == null && same == null) {
                    toastr.options = {
                        "closeButton": true,
                        "progressBar": true
                    }
                    toastr.error("please select the billing address or same shipping addres");

                    return false;
                }
                let data = {
                    shipping_address: form['shippingAddress'].value,
                    billing_address: billing_address,
                    same: same,
                    coupoon_code: coupoon_code,
                    coupon_name: coupon_name,
                    coupon_code_name: coupon_code_name
                }
                var payment = $("input[name = 'payment']:checked").val();
                if (payment == 'khalti') {
                    $('#myForm').submit();
                } else if (payment == 'esewa') {
                    $('#myForm').submit();
                    // esewaOrderInfo();
                    // // $('#exampleModal-esewa').modal('show');
                    // $('#esewaForm').submit();
                } else {
                    $('#myForm').submit();
                }
            })
        })
    </script>
    <script>
        // var shiiping_id = null;
        // $('.shippingAddress').change(function(e) {
           
        //     e.preventDefault();
        //     const shipping_id = $(this).val();
            
        //     var charge = '';
            

        //     var actual_charge = parseInt(charge);
        //     $.ajax({
        //         url: "{{ route('get-shipping-charge') }}",
        //         type: "get",
        //         data: {
        //             shipping_id: shipping_id
        //         },
        //         success: function(response) {
        //             if (typeof(response) != 'object') {
        //                 response = JSON.parse(response);
        //             }
        //             if (response.error) {
        //                 alert(response.error);
        //             } else {
        //                 if (response.data.charge) {
        //                     shiiping_id = response.data.shiiping_id;
        //                     charge = response.data.charge;

        //                     // {{ session()->put('esewa_cart_shipping_id') }}
        //                 }
        //             }
        //             $('.shipping_charge').text(response.data.charge);

                    
                  
        //             localStorage.setItem('chcharge',charge);
        //             let  test = localStorage.getItem('chcharge');
        //             console.log('test value:', test);

        //             var total_amount = $('.allTotal').attr('data-totalAmount');
        //             $('.allTotal').text('Rs ' + (parseInt(total_amount) + parseInt(charge)));
        //             $('.found').empty();
        //             $('.coupon').val('');
        //             $('.not_found').hide();
        //             var payonlineAmount = parseInt(total_amount);
        //             var totalOnlineAmount = parseInt(payonlineAmount) + parseInt(charge);
        //             $('#totalamountValue').html('<input type="hidden" name="payment_amount" value="' +
        //                 parseInt(total_amount) + '" data-payAmounts="' + totalOnlineAmount +
        //                 '" id="payAmount">');
        //         }
        //     }); 
        // });
    </script>
    <script>
        $(document).ready(function() {

            var ship_charge;
            if(ship_charge!=null)
            {
                let  test = localStorage.getItem('chcharge');
                $('.shipping_charge').text(test);
                    console.log('test storage:', test);
            }
            else
            {
                localStorage.removeItem('chcharge');
            }

            if ($('input[name="shipping_address"]:checked').length > 0) {
            } else {
                localStorage.removeItem('chcharge');

            }

           
           
        });
        </script>

    {{-- Shipping address clicked, chagnged of amount value of shipping for esewa --}}
    <script>
        var shiiping_id = null;
        // $('.shippingAddress').change(function(e) {
        //     // $('#esewa-section').removeAttr('hidden');
        //     e.preventDefault();
        //     const shipping_id = $(this).val();
        //     var charge = '';
        //     var actual_charge = parseInt(charge);
        //     let totalAmount="{{@$total_amount}}" ?? 0;
        //     $.ajax({
        //         url: "{{ route('get-shipping-charge') }}",
        //         type: "get",
        //         data: {
        //             shipping_id: shipping_id,
        //             totalAmount:totalAmount
        //         },
        //         success: function(response) {
        //             if (typeof(response) != 'object') {
        //                 response = JSON.parse(response);
        //             }
        //             if (response.error) {
        //                 alert(response.error);
        //             } else {
        //                 if (response.data.charge) {
        //                     $('#delivery_charge').val(response.data.charge);
        //                     $('#real_total_amount').val({{ @$total_amount }} + response.data.charge);
        //                 }
        //             }

        //         }
        //     });
        // });
    </script>

    <script>
        var esewa_coupon = null;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function() {
            $('.coupon_code').on('click', function(e) {

                e.preventDefault();
                var coupon_code = $('.coupon').val();
                var total_amount = "{{ @$total_amount }}";
                var shipping_id =  $('input[name="shipping_address"]:checked').val();

                $.ajax({
                    url: "{{ route('verify-coupon') }}",
                    type: "get",
                    data: {
                        coupon_code: coupon_code,
                        total_amount: total_amount,
                        shipping_id: shipping_id,
                    },
                    success: function(response) {
                        if (typeof(response) != 'object') {
                            response = JSON.parse(response);
                        }
                        if (response.error) {
                            $('.not_found').text(response.msg);
                            var total_amount = $('.allTotal').attr('data-totalAmount');

                            if (response.data.shipping_charge) {
                                var shipping_charge = response.data.shipping_charge;
                            } else {
                                var shipping_charge = 0;
                            }
                            $('.coupoon_code').val(null);
                            $('.coupon_name').val(null);
                            $('.coupon_code_name').val(null);
                            $('#items_total_cost').text(total_amount);
                            $('.found').hide();
                            $('.not_found').show();
                            $('.allTotal').text('Rs ' + (parseInt(shipping_charge) + parseInt(
                                total_amount)));

                            var payonlineAmount = parseInt(total_amount);
                            var totalOnlineAmount = parseInt(payonlineAmount) + parseInt(
                                shipping_charge);
                            $('#totalamountValue').html(
                                '<input type="hidden" name="payment_amount" value="' +
                                parseInt(total_amount) + '" data-payAmounts="' +
                                totalOnlineAmount + '" id="payAmount">');
                        } else {
                            if (response.data) {
                                var amount = response.data.discount_amount;
                                var coupon_name = response.data.coupon_name;
                                var coupon_code_name = response.data.coupon_code_name;
                                var shipping_charge = response.data.shipping_charge;

                                var total_amount = $('.allTotal').attr('data-totalAmount');
                                $('.coupoon_code').val(amount);
                                $('.coupon_name').val(coupon_name);
                                $('.coupon_code_name').val(coupon_code_name);
                                $('.found').text('-Rs ' + amount);
                                $('.allTotal').text('Rs ' + (parseInt(shipping_charge) +
                                    parseInt(total_amount) - parseInt(amount)));
                                $('.not_found').hide();
                                $('.found').show();
                                $('#sub_total_amount').val(parseInt(total_amount) - parseInt(
                                    amount));
                                $('#real_total_amount').val(parseInt(shipping_charge) +
                                    parseInt(total_amount) - parseInt(amount));

                                var payonlineAmount = parseInt(total_amount);
                                var totalOnlineAmount = parseInt(payonlineAmount) - parseInt(
                                    amount) + parseInt(shipping_charge);
                                $('#totalamountValue').html(
                                    '<input type="hidden" name="payment_amount" value="' +
                                    parseInt(total_amount) + '" data-payAmounts="' +
                                    totalOnlineAmount + '" id="payAmount">');
                            }
                        }
                    }
                });
            });
        });
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
                        "<option value=''>---Select Any One---</option>";
                    if (response.error) {
                        alert(response.error);
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
                    $('.zip_code_new').html(response.zip_code_is);
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
                    country:form['country'].value

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

    <script>
        $('#addShipping').click(function(e) {
            e.preventDefault();
            var form = document.getElementById('addSAddress');
            $('#countryError').attr('hidden',true);
            $('#nameError').attr('hidden',true);
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
                    country:form['country'].value
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
        $('.billing_address').click(function() {
            $('.same_address').toggleClass('d-none');
            $('.same_address_text').toggleClass('d-none');
        });
        $('.same_address').click(function() {
            $('.billing_field').toggleClass('d-none');
        });
    </script>

    <script>
        function esewaOrderInfo() {
            var form = document.getElementById('myForm');
            var ship_id = form['shipping_address'].value;
            var coupan_code_price = form['coupoon_code'].value;
            var coupan_code_name = form['coupon_name'].value;
            var coupan_code_seriel = form['coupon_code_name'].value;
            var esewa_pid = form['newly_generated_pid'].value;
            var payment = "eSewa";

            if ($('.billing_address').is(":checked")) {
                var billing_address = form['billing_address'].value;
            } else {
                var billing_address = null;
            }
            if ($('.same_address').is(":checked")) {
                billing_address_sameAs_shipping = 1;
            } else {
                billing_address_sameAs_shipping = null;
            };

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('store-esewa-orders') }}",
                type: "post",
                data: {
                    ship_id: ship_id,
                    billing_address: billing_address,
                    billing_address_sameAs_shipping: billing_address_sameAs_shipping,
                    coupan_code_price: coupan_code_price,
                    coupan_code_name: coupan_code_name,
                    coupan_code_seriel: coupan_code_seriel,
                    esewa_pid: esewa_pid,
                    payment_option: payment,
                },
                success: function(response) {
                    if (typeof(response) != 'object') {
                        response = JSON.parse(response);
                    }
                    location.reload();
                }
            });
        }
    </script>
    <script>
        $(document).ready(function(){
            $('#cod-section').hide();
            $(document).on('change', '.paymentMethod', function() {
                let payment_method = $(this).val();
                // alert(payment_method);
                if(payment_method == 'QRCode')
                {
                    $('#cod-section').show();
                }
                else {
                    $('#cod-section').hide();
                }
            });
        });
    </script>
@endpush
