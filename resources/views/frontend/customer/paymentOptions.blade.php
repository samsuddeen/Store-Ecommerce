@extends('frontend.layouts.app')
@section('title', env('DEFAULT_TITLE') . '|Payment Option')
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
                            <div class="dashboard_content">
                                <div class="dashboard-tables-head">
                                    <h3>We accept different payment method for our client support</h3>
                                </div>
                                <div class="payment_option_wrap">
                                    <ul>
                                        <img src="{{ asset('frontend/images/cash.png') }}" alt="">
                                        {{-- <li>
                                            <img src="{{ asset('frontend/hbl.png') }}" alt="">
                                        </li>
                                        <li>
                                            <img src="{{ asset('frontend/paypal.png') }}" alt="">
                                        </li> --}}
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
