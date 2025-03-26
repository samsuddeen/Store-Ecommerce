@extends('frontend.layouts.app')
@section('title', env('DEFAULT_TITLE') . '|Voucher')
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
                                    <h3>Vouchers</h3>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="voucher_wrapper table_wrapper">
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Vauchor Title</th>
                                                            <th>Promo Code</th>
                                                            <th>Valid From</th>
                                                            <th>Valid Untill</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($vauchers as $vaucher)
                                                            <tr>
                                                                <td>{{ $vaucher->title }}</td>
                                                                <td>{{ $vaucher->coupon_code }}</td>
                                                                <td> {{ $vaucher->from }} </td>
                                                                <td> {{ $vaucher->to }} </td>
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
    </div>
@endsection
