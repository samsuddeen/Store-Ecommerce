@extends('frontend.layouts.app')
@section('title', 'Customer Dashboard')
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
                            <div class="row">
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="visit_graph first">
                                        <div class="graph_art">
                                            <i class="las la-shopping-cart"></i>
                                        </div>
                                        <div class="in_dp_flex">
                                            <h5>Total Products</h5>
                                            <span>{{ @$total_products }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="visit_graph second">
                                        <div class="graph_art">
                                            <i class="las la-comment-dollar"></i>
                                        </div>
                                        <div class="in_dp_flex">
                                            <h5>Total Discount</h5>
                                            <span> - {{ @$coupan_discount }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="visit_graph third">
                                        <div class="graph_art">
                                            <i class="las la-dollar-sign"></i>
                                        </div>
                                        <div class="in_dp_flex">
                                            <h5>Shipping Charge</h5>
                                            <span> + {{ formattedNepaliNumber(@$shipping_amount) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="visit_graph fourth">
                                        <div class="graph_art">
                                            <i class="las la-funnel-dollar"></i>
                                        </div>
                                        <div class="in_dp_flex">
                                            <h5>Total Amount</h5>
                                            <span>{{ formattedNepaliNumber(@$total_amount) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="dashboard_content">
                                <div class="data_tables">
                                    <div class="table-responsive">
                                        <table class="table table_wrapper table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Ref Id</th>
                                                    <th>Total Price</th>
                                                    <th>Total Qty</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($total_orders as $order)
                                                    <tr>
                                                        <td>{{ @$order->ref_id}}</td>
                                                        <td style="white-space:nowrap;">{{ formattedNepaliNumber(@$order->total_price) }}</td>
                                                        <td style="white-space:nowrap;">{{ @$order->total_quantity }}</td>
                                                        <td style="white-space:nowrap;">{{ @$order->created_at }}
                                                            &nbsp;
                                                            &nbsp;
                                                            <a href="{{ route('order.productDetails', @$order->id) }}"><i class="lar la-eye"></i></a></td>
                                                        
                                                    </tr>
                                                @endforeach
                                                
                                            </tbody>
                                           
                                        </table>
                                        {!!$total_orders->links()!!}
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
