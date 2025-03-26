@extends('frontend.layouts.app')
@section('title', env('DEFAULT_TITLE') . '|' . 'Checkout')
@section('content')

    @php
        $pid = Str::random(25) . rand(0000, 9999);
    @endphp

    <section id="checkOut_wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="form_wrapper">
                        <h2>Delivery Detail</h2>
                        <form action="{{ route('cash-on-delivery') }}" method="post" id="myForm"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="text" name="newly_generated_pid" id="" value="{{ $pid }}"
                                hidden>
                            <div class="row">
                                <h5>Please Select Shipping Address. <a href="{{ route('shipping.address.book') }}"
                                        class="">View All Shipping Address</a></h5>
                                <div class="card new" style="width: 18rem;height:100px">
                                    <div class="card-body">
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#addShippingAddress">
                                            Add Shipping Address
                                        </button>
                                    </div>
                                </div>
                                @foreach ($shipping_address as $key => $s_address)
                                    <div class="col-md-3 message-box"
                                        style="margin-left:20px;margin-bottom:20px;width: 30%;height:80px;border-radius:10px;padding:20px;border:2px solid rgb(12, 99, 156)">
                                        <input class="form-check-input shippingAddress" required name="shipping_address"
                                            type="radio" value="{{ $s_address->id }}" id="shippingAddress">
                                        <span>{{ $s_address->name }}</span>
                                        {{-- <a href="javascript:;" class="float-right" data-bs-toggle="modal" data-bs-target="#exampleModaleditshipping{{$key}}">Edit</a> --}}
                                        <p>{{ $s_address->phone }}</p>
                                    </div>
                                @endforeach
                                <h5>Please Select Billing Address <a href="{{ route('addressBook') }}" class="">View
                                        All Billing Address</a></h5>
                                <div class="card new" style="width: 18rem;height:100px">
                                    <div class="card-body">
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#addBillingAddress">
                                            Add Billing Address
                                        </button>
                                    </div>
                                </div>
                                @foreach ($billing_address as $bkey => $b_address)
                                    <div class="col-md-3 message-box billing_field"
                                        style="margin-left:20px;margin-bottom:20px;width: 30%;height:80px;border-radius:10px;padding:20px;border:2px solid rgb(12, 99, 156)">
                                        <input class="form-check-input billing_address" name="billing_address"
                                            type="{{ $billing_address->count() > 1 ? 'radio' : 'checkbox' }}"
                                            value="{{ $b_address->id }}" id="billingAddress">
                                        <span>{{ $b_address->name }}</span>
                                        {{-- <a href="javascript:;" class="float-right" data-bs-toggle="modal" data-bs-target="#exampleModaleditbilling{{$bkey}}">Edit</a> --}}
                                        <p>{{ $b_address->phone }}</p>
                                    </div>
                                @endforeach
                            </div>
                            <div class="shiping_address">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-check">
                                            <input class="form-check-input same same_address" name="same_address"
                                                type="checkbox" value="1" id="flexCheckDefault">
                                            <label class="form-check-label same_address_text" for="flexCheckDefault">
                                                Billing address is the same as my shipping address
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-12">
                                    <h2 class="checkout_header">Payment</h2>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment" value="khalti"
                                            id="flexRadioDefault2" checked>
                                        <label class="form-check-label" for="flexRadioDefault2">
                                            khalti
                                        </label>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment" value="COD"
                                            id="flexRadioDefault3">
                                        <label class="form-check-label" for="flexRadioDefault3">
                                            Cash On Delivery
                                        </label>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-check" id="esewa-section" hidden>
                                        <input class="form-check-input" type="radio" name="payment" value="esewa"
                                            id="flexRadioDefault1" data-bs-toggle="modal"
                                            data-bs-target="#exampleModal-esewa">
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            esewa
                                        </label>
                                    </div>
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
                                        $shipping_charge = 0;
                                    } else {
                                        $shipping_charge = $shipping_route->charge->branch_delivery;
                                    }
                                    
                                @endphp
                                <input type="hidden" class="coupoon_code" name="coupoon_code">
                                <input type="hidden" class="coupon_name" name="coupon_name">
                                <input type="hidden" class="coupon_code_name" name="coupon_code_name">
                            </div>
                            <div class="row">
                                <div class="col-lg-12"><button class="btn btn-danger" id="checkout"
                                            type="submit">Continue to Checkout</button>
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
                                                {{-- <div class="col-md-6">
                                            <div class="mb-3 input_type_wrap">
                                                <label for="exampleFormControlInput1" class="form-label">Country/Region</label>
                                                <div class="input_field">
                                                    <select name="country" class="form-select form-control">
                                                        @if (auth()->user()->country != null)
                                                        <option value="{{auth()->user()->country}}" selected> {{auth()->user()->country}} </option>
                                                        @else
                                                            <option selected> Select Country </option>
                                                        @endif
                                                        @foreach ($countries as $country)
                                                            <option value="{{$country->name}}">{{$country->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div> --}}
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
                                                                            $id = \App\Models\City::where('city_name', $consumer->userBillingAddress->area)->value('local_level_id');
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
                                                                            )->value('local_level_id');
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
                                                                        )->value('local_level_id');
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
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="sidebar_chekout">
                                    <h2>Your cart (Checking Out)</h2>
                                    <div class="custom_badge">{{ $cart_item->sum('qty') }}</div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" class="sub_total" value="{{ $cart_item->sum('sub_total_price') }}"
                            name="sub_total">
                        <div class="row">
                            <div class="col-lg-12">
                                <ul>
                                    @foreach ($cart_item as $item)
                                        <li>
                                            <div>
                                                <h6>{{ $item->product_name }} * <span>{{ $item->qty }}</span></h6>
                                                <a href="{{ route('product.details', $item->product->slug) }}"><small>Brief
                                                        description</small></a>
                                            </div>
                                            <span>Rs {{ $item->price }}</span>
                                        </li>
                                    @endforeach

                                    <li>
                                        <div>
                                            <h6>Sub Total</h6>
                                        </div>
                                        <input type="hidden" class="all_sub_total" name="all_sub_total"
                                            data-amountId="{{ $total_amount }}" value="{{ $total_amount }}">
                                        <small>Rs <span class="after_coupon">{{ $total_amount }}</span></small>
                                    </li>
                                    <li>
                                        <div class="d-flex justify-content-between " style="width:100%">
                                            <div>
                                                <h6>Shipping Charge</h6>
                                            </div>
                                            <small>Rs <span class="shipping_charge" id=#scharge"></span></small>
                                        </div>
                                    </li>
                                    <li>
                                        <div>
                                            <p>Total</p>
                                        </div>
                                        <p class="hidden_amount" hidden>{{ $total_amount }}</p>
                                        <small class="allTotal" id="items_total_cost"
                                            data-totalAmount="{{ $total_amount }}">Rs {{ $total_amount }}</small>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
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
            </div>
        </div>

        {{-- --------------------------Shipping Address--------------------- --}}
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
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
        <div class="modal fade" id="exampleModalbilling" tabindex="-1" aria-labelledby="exampleModalLabel"
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
            <div class="modal fade" id="exampleModaleditshipping{{ $key }}" tabindex="-1"
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
            <div class="modal fade" id="exampleModaleditbilling{{ $bkey }}" tabindex="-1"
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
        <div class="modal fade" id="addShippingAddress" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-center" id="exampleModalLabel">Add Shipping Address</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <form action="#" id="addSAddress">
                                {{-- {{ Form::open()}} --}}
                                @method('post')
                                <div class="mb-3">
                                    {{ Form::label('name', 'Name') }}
                                    {{ Form::text('name', '', ['class' => 'form-control form-control-sm ' . ($errors->has('name') ? 'is-invalid' : ''), 'placeholder' => 'Enter Your Name Here.....', 'required' => true]) }}
                                    @error('name')
                                        <div class="invalid-feedback">
                                            <i class="bx bx-radio-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    {{ Form::label('email', 'Email') }}
                                    {{ Form::email('email', '', ['class' => 'form-control form-control-sm ' . ($errors->has('email') ? 'is-invalid' : ''), 'required' => true, 'placeholder' => 'Enter Your Email Here.....']) }}
                                    <div id="email" class="form-text">We'll never share your email with anyone else.
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback">
                                            <i class="bx bx-radio-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    {{ Form::label('phone', 'Phone') }}
                                    {{ Form::text('phone', '', ['class' => 'form-control form-control-sm ' . ($errors->has('phone') ? 'is-invalid' : ''), 'required' => true, 'placeholder' => 'Enter Your Phone Num Here.....']) }}
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
                                    {{ Form::select('additional_address', [], [], ['class' => 'additional_address form-control form-control-sm ' . ($errors->has('addtional_address') ? 'is-invalid' : ''), 'placeholder' => '-----------Select Any One-----------', 'required' => true]) }}
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
                                    {{ Form::number('zip', '', ['class' => 'form-control form-control-sm ' . ($errors->has('zip') ? 'is-invalid' : ''), 'placeholder' => 'zip Code .....', 'required' => true, 'min' => '1']) }}
                                    @error('zip')
                                        <div class="invalid-feedback">
                                            <i class="bx bx-radio-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <button type="button" class=" btn btn-success" id="addShipping">Add Address<i
                                        class="las la-edit"></i></button>
                                {{ Form::close() }}

                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- ----------------------------------/Add Shipping Address------------------------------------- --}}

        {{-- ------------------------------Add Billing Address----------------------------------- --}}
        <div class="modal fade" id="addBillingAddress" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-center" id="exampleModalLabel">Add Billing Address</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <form action="#" id="addBAddress">
                                {{-- {{ Form::open()}} --}}
                                @method('post')
                                <div class="mb-3">
                                    {{ Form::label('name', 'Name') }}
                                    {{ Form::text('name', '', ['class' => 'form-control form-control-sm ' . ($errors->has('name') ? 'is-invalid' : ''), 'placeholder' => 'Enter Your Name Here.....', 'required' => true]) }}
                                    @error('name')
                                        <div class="invalid-feedback">
                                            <i class="bx bx-radio-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    {{ Form::label('email', 'Email') }}
                                    {{ Form::email('email', '', ['class' => 'form-control form-control-sm ' . ($errors->has('email') ? 'is-invalid' : ''), 'required' => true, 'placeholder' => 'Enter Your Email Here.....']) }}
                                    <div id="email" class="form-text">We'll never share your email with anyone else.
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback">
                                            <i class="bx bx-radio-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    {{ Form::label('phone', 'Phone') }}
                                    {{ Form::text('phone', '', ['class' => 'form-control form-control-sm ' . ($errors->has('phone') ? 'is-invalid' : ''), 'required' => true, 'placeholder' => 'Enter Your Phone Num Here.....']) }}
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
                                    {{ Form::select('additional_address', [], [], ['class' => 'additional_address form-control form-control-sm ' . ($errors->has('addtional_address') ? 'is-invalid' : ''), 'placeholder' => '-----------Select Any One-----------', 'required' => true]) }}
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
                                    {{ Form::number('zip', '', ['class' => 'form-control form-control-sm ' . ($errors->has('zip') ? 'is-invalid' : ''), 'placeholder' => 'zip Code .....', 'required' => true, 'min' => '1']) }}
                                    @error('zip')
                                        <div class="invalid-feedback">
                                            <i class="bx bx-radio-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <button type="button" class=" btn btn-success" id="addBilling">Add Address<i
                                        class="las la-edit"></i></button>
                                {{ Form::close() }}

                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- ----------------------------------/Add Billing Address------------------------------------- --}}
        <!-- Button trigger modal -->
        <!-- Modal -->
        <div class="modal fade" id="exampleModal-esewa" tabindex="-1" aria-labelledby="exampleModalLabel"
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
                            Please Choose Your Billing Address & Shipping Address Before Confirm to pay.
                            else Your payment & order not be success.
                            <hr>
                            <input value="{{ $total_amount }}" name="tAmt" type="hidden" id="real_total_amount">
                            <input value="{{ $total_amount }}" name="amt" type="hidden" id="sub_total_amount">
                            <input value="0" name="txAmt" type="hidden">
                            <input value="0" name="psc" type="hidden">
                            <input value="0" name="pdc" type="hidden" id="delivery_charge">
                            <input value="EPAYTEST" name="scd" type="hidden">
                            <input value="{{ $pid }}" name="pid" type="hidden">
                            <input value="{{ route('esewa-payment-success') }}" type="hidden" name="su">
                            {{-- <input value="http://merchant.com.np/page/esewa_payment_success?q=su" type="hidden" name="su"> --}}
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
    <script src="https://khalti.s3.ap-south-1.amazonaws.com/KPG/dist/2020.12.17.0.0.0/khalti-checkout.iffe.js"></script>
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
                    // dataType: 'JSON',
                    success: function(response) {
                        console.log(response);
                        var total = parseInt(response.delivery_charge) + parseInt(
                        all_sub_total);
                        $('.shipping_charge').text(response.delivery_charge);
                        $('#shipping_charge').val(response.delivery_charge);
                        $('.allTotal').text(total);
                        $('#grandTotal').val(total);
                        if (response.error) {
                            alert(response.error);
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
            var charactersLength = characters.length;
            for (var i = 0; i < length; i++) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
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
                            $('.district').append('<option value="' + value.dist_id +
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
        // function khalti(data){
        //         var refId = "Jhigu-" + strRandom(9);
        //         // var total = document.getElementById("grandTotal").value;
        //         var total = 20;
        //         alert(total);
        //         var config = {
        //             // replace the publicKey with yours
        //             "publicKey": "test_public_key_44b86e960bc84f0c9376abfd4dd1e13f",
        //             "productIdentity": refId,
        //             "productName": refId,
        //             "productUrl": "{{ route('cart.checkout') }}",
        //             "paymentPreference": [
        //                 "KHALTI",
        //                 "EBANKING",
        //                 "MOBILE_BANKING",
        //                 "CONNECT_IPS",
        //                 "SCT",
        //                 ],
        //             "eventHandler": {
        //                 onSuccess (payload) {
        //                     // hit merchant api for initiating verfication
        //                     if(payload.idx){
        //                         $.ajaxSetup({
        //                             headers: {
        //                                 'X-CSRF-TOKEN': '{{ csrf_token() }}'
        //                             }
        //                         })
        //                         $.ajax({
        //                             url: "{{ route('khalti.order') }}",
        //                             type: 'post',
        //                             data: {
        //                                 data: data,
        //                                 payload: payload,
        //                                 token: payload.token,
        //                                 amount: payload.amount,
        //                                 oid: payload.product_identity,
        //                             },
        //                             // dataType: 'JSON',
        //                             success:function(response)
        //                             {
        //                               alert('success'); 
        //                             },
        //                             error: function(response) {
        //                             }
        //                         });
        //                     }

        //                 },
        //                 onError (error) {
        //                     console.log(error);
        //                 },
        //                 onClose () {
        //                     console.log('widget is closing');
        //                 }
        //             }
        //         };

        //     var checkout = new KhaltiCheckout(config);
        //     var btn = document.getElementById("payment-button");
        //         // minimum transaction amount must be 10, i.e 1000 in paisa.
        //     checkout.show({amount: total * 100});
        // }

        function khalti(data) {
            var refId = "Jhigu-" + strRandom(9);
            // var total = document.getElementById("grandTotal").value;
            var total = 20;
            alert(total);
            var config = {
                // replace the publicKey with yours
                "publicKey": "test_public_key_44b86e960bc84f0c9376abfd4dd1e13f",
                "productIdentity": refId,
                "productName": refId,
                "productUrl": "{{ route('cart.checkout') }}",
                "paymentPreference": [
                    "KHALTI",
                    "EBANKING",
                    "MOBILE_BANKING",
                    "CONNECT_IPS",
                    "SCT",
                ],
                "eventHandler": {
                    onSuccess(payload) {
                        if (payload) {
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })

                            $.ajax({
                                url: "{{ route('khalti.order') }}",
                                type: "post",
                                data: {
                                    shipping_address: data.shipping_address,
                                    billing_address: data.billing_address,
                                    same: data.same,
                                    coupoon_code: data.coupoon_code,
                                    coupon_name: data.coupon_name,
                                    coupon_code_name: data.coupon_code_name,
                                    payload: payload,
                                    token: payload.token,
                                    amount: payload.amount,
                                    oid: payload.product_identity,
                                },

                                success: function(res) {
                                    window.location.href = "{{ route('Corder') }}";
                                },
                                error() {},
                                onClose() {
                                    console.log('widget is closing');
                                }
                            });
                        }
                    },
                    onError(error) {
                        alert(error);
                    },
                    onClose() {
                        console.log('widget is closing');
                    }
                }
            };

            var checkout = new KhaltiCheckout(config);
            var btn = document.getElementById("payment-button");
            // minimum transaction amount must be 10, i.e 1000 in paisa.
            checkout.show({
                amount: total * 100
            });
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
                e.preventDefault();
                
                let form = document.getElementById('myForm');
                var same = null;
                var coupoon_code = form['coupoon_code'].value;
                var coupon_name = form['coupon_name'].value;
                var coupon_code_name = form['coupon_code_name'].value;
                if($('.billingAddress').is(":checked"))
                {
                    var billing_address=form['billingAddress'].value;
                }
                else
                {
                    var billing_address=null;
                }
                if ($('.same_address').is(":checked")) {
                    same = 1;
                } else {
                    same = null;
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
                    khalti(data);
                } else {
                    $('#myForm').submit();
                }
            })
        })
    </script>


    <script>
        var shiiping_id = null;
        $('.shippingAddress').change(function(e) {
            e.preventDefault();
            const shipping_id = $(this).val();
            var charge = $('.shipping_charge').text();

            var actual_charge = parseInt(charge);
            $.ajax({
                url: "{{ route('get-shipping-charge') }}",
                type: "get",
                data: {
                    shipping_id: shipping_id
                },
                success: function(response) {
                    if (typeof(response) != 'object') {
                        response = JSON.parse(response);
                    }
                    if (response.error) {
                        alert(response.error);
                    } else {
                        if (response.data.charge) {
                            shiiping_id = response.data.shiiping_id;
                            charge = response.data.charge;

                            // {{ session()->put('esewa_cart_shipping_id') }}
                        }
                    }
                    $('.shipping_charge').text(charge);
                    var total_amount = $('.allTotal').attr('data-totalAmount');

                    $('.allTotal').text('Rs ' + (parseInt(total_amount) + parseInt(charge)));
                    $('.found').empty();
                    $('.coupon').val('');
                    $('.not_found').hide();
                }
            });
        });
    </script>

    <script>
        $('#flexRadioDefault1').click(function() {
            $('#checkout').hide();
        });

        $('#flexRadioDefault2').click(function() {
            $('#checkout').show();
        });

        $('#flexRadioDefault3').click(function() {
            $('#checkout').show();
        });
    </script>
    {{-- Shipping address clicked, chagnged of amount value of shipping for esewa --}}
    <script>
        var shiiping_id = null;
        $('.shippingAddress').change(function(e) {
            $('#esewa-section').removeAttr('hidden');
            e.preventDefault();
            const shipping_id = $(this).val();
            var charge = $('.shipping_charge').text();
            var actual_charge = parseInt(charge);
            $.ajax({
                url: "{{ route('get-shipping-charge') }}",
                type: "get",
                data: {
                    shipping_id: shipping_id
                },
                success: function(response) {
                    if (typeof(response) != 'object') {
                        response = JSON.parse(response);
                    }
                    if (response.error) {
                        alert(response.error);
                    } else {
                        if (response.data.charge) {
                            $('#delivery_charge').val(response.data.charge);
                            $('#real_total_amount').val({{ $total_amount }} + response.data.charge);
                        }
                    }

                }
            });
        });
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
                var total_amount = "{{ $total_amount }}";
                $.ajax({
                    url: "{{ route('verify-coupon') }}",
                    type: "get",
                    data: {
                        coupon_code: coupon_code,
                        total_amount: total_amount,
                        shipping_id: shiiping_id
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
                        "<option value=''>----------------Select Any One--------------------</option>";
                    if (response.error) {
                        alert(response.error);
                    } else {
                        if (response.data.child.length > 0) {
                            $.each(response.data.child, function(index, value) {
                                dist_html += "<option value='" + value.np_name + "'";
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
                        "<option value=''>----------------Select Any One--------------------</option>";
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
                        "<option value=''>--------------Select Any Two--------------</option>";
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
    </script>

    <script>
        addSAddress
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
                        alert(response.error);
                    }

                    $('#addShippingAddress').modal('hide');
                    location.reload();

                }
            });
        });
    </script>

    <script>
        $('.updateShippingAddress').click(function(e) {
            e.preventDefault();
            var form = document.getElementById('editShippingAddress');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('update-shipping-address') }}",
                type: "post",
                data: {
                    shipping_id: form['shipping_id'].value,
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
                    location.reload();
                }
            });
        });
    </script>

    <script>
        $('#updateBillingAddress').click(function(e) {
            e.preventDefault();
            var form = document.getElementById('editBillingAddress');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('update-billing-address') }}",
                type: "post",
                data: {
                    billing_id: form['billing_id'].value,
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
        $('#esewa-section').click(function(e) {
            e.preventDefault();
            var form = document.getElementById('myForm');
            var ship_id = form['shipping_address'].value;
            var billing_address = form['billing_address'].value;
            var billing_address_sameAs_shipping = form['same_address'].value;
            var coupan_code_price = form['coupoon_code'].value;
            var coupan_code_name = form['coupon_name'].value;
            var coupan_code_seriel = form['coupon_code_name'].value;
            var esewa_pid = form['newly_generated_pid'].value;
            var payment = "eSewa";

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
        })
    </script>   
@endpush
