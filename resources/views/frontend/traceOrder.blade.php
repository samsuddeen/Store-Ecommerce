@extends('frontend.layouts.app')
@section('title', env('DEFAULT_TITLE') . '/' . 'Traceorder')
@section('content')
    <div class="track-orders mt mb">
        <div class="container">
            {{-- <div class="customer_dashboard_wrap">
                <div class="toggle_menu_style">
                    <span>Order</span>
                </div>
                @include('frontend.customer.sidebar')
                <div class="dashboard_contentArea">
                    <div class="dashboard_content">
                        <div class="customer_order_wrapper table_wrapper">
                            <div class="row">
                                <div class="col-lg-6 col-6">
                                    <h3>My Order</h3>
                                </div>
                                <div class="col-lg-6 col-6 text-end">
                                    <span class="conting_items"><span></span>Your Order</span>
                                </div>
                                <div class="col-lg-12">
                                    <div class="table-responsive">
                                        <table class="table table_style">
                                            <thead>
                                                <tr>
                                                    <th>S.N</th>
                                                    <th>Order ref no</th>
                                                    <th>Date</th>
                                                    <th>Order Total</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>{{ $order->ref_id }}</td>
                                                    <td>{{ $order->created_at }}</td>
                                                    <td>{{ $order->total_price }}</td>
                                                    <td>{{ $order->pending == 1 ? 'pending' : '' }}{{ $order->ready_to_ship == 1 ? 'ready to ship' : '' }}{{ $order->cancelled == 1 ? 'cancelled' : '' }}{{ $order->shipped == 1 ? 'shipped' : '' }}{{ $order->delivered == 1 ? 'delivered' : '' }}{{ $order->failed_delivery == 1 ? 'fail delivery' : '' }}
                                                    </td>
                                                    <td>
                                                        <div class="table_btn">
                                                            <a class="view" title="View" data-bs-toggle="modal"
                                                                data-id="{{ $order->ref_id }}"
                                                                data-bs-target="#orderData"><i class="las la-eye"></i></a>
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
            </div> --}}
            <div class="order-tracking-details">
                {{-- <div class="order-tracking-search">
                    <input type="text" class="form-control" placeholder="Enter your track number" required>
                    <button type="submit" class="btns">Search</button>
                </div> --}}
                <div class="order-tracking-table">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="order-tracking-table-list">
                                <h3>Contact Person</h3>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Name</th>
                                            <td>{{@$data['name']}}</td>
                                        </tr>
                                        <tr>
                                            <th>Address</th>
                                            <td>{{$data['address']}}</td>
                                        </tr>
                                        <tr>
                                            <th>Phone</th>
                                            <td>{{$data['phone']}}</td>
                                        </tr>
                                        <tr>
                                            <th>Email</th>
                                            <td>{{$data['email']}}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="order-tracking-table-list">
                                <h3>Order Details</h3>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Reference No.</th>
                                            <td>{{@$order->ref_id}}</td>
                                        </tr>
                                        <tr>
                                            <th>Quantity</th>
                                            <td>{{@$order->total_quantity}}</td>
                                        </tr>
                                        <tr>
                                            <th>Amount</th>
                                            <td>{{@$order->total_price-@$order->shipping_charge}}</td>
                                        </tr>
                                        <tr>
                                            <th>Discount</th>
                                            <td>{{@$order->total_discount}}</td>
                                        </tr>
                                        <tr>
                                            <th>Shipping Charge</th>
                                            <td>{{@$order->shipping_charge}}</td>
                                        </tr>
                                        <tr>
                                            <th>Total</th>
                                            <td>{{@$order->total_price}}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p><b>Description:</b> @foreach ($order->orderAssets as $key=>$product)
                        {{$product->product_name}}
                        @if($key < (count($order->orderAssets)-1))
                            ,
                        @endif
                    @endforeach</p>
                </div>
                <div class="tracking-infos">
                    <div class="tracking-wrap">
                        @foreach($order_status as $status)
                        <div class="timeline">
                            <span>{{ date('d M Y',strtotime($status['created_at']))}}</span>
                            <ul>
                                <li>
                                    <i class="las la-sitemap"></i>
                                    <div class="timeline-items">

                                        <div class="timeline-repeat">
                                            <b>{{ date('h:i a',strtotime($status['created_at']))}} |
                                                {{ @$status['status_value']}}
                                            </b>
                                                {{-- Received At Sundhara, Kathmandu, Nepal</b> --}}
                                            {{-- <p><i class="las la-map-marker-alt"></i> Kathmandu, Nepal
                                            </p> --}}
                                            <p><i class="las la-shopping-cart"></i> {{ count($order->orderAssets) ?? null}} Items</p>
                                        </div>
                                        {{-- <div class="timeline-repeat">
                                            <b>04:53 pm |
                                                Invoice Generated (Order Information Received)</b>
                                            <p><i class="las la-map-marker-alt"></i> Kathmandu, Nepal
                                            </p>
                                            <p><i class="las la-shopping-cart"></i> 3 Items</p>
                                        </div> --}}
                                    </div>
                                </li>
                            </ul>
                        </div>
                        @endforeach
                        {{-- <div class="timeline">
                            <span>13 Jun 2022</span>
                            <ul>
                                <li>
                                    <i class="las la-sitemap"></i>
                                    <div class="timeline-items">

                                        <div class="timeline-repeat">
                                            <b>03:55 pm |
                                                Received At Sundhara, Kathmandu, Nepal</b>
                                            <p><i class="las la-map-marker-alt"></i> Kathmandu, Nepal
                                            </p>
                                            <p><i class="las la-shopping-cart"></i> 2 Items</p>
                                        </div>
                                        <div class="timeline-repeat">
                                            <b>04:53 pm |
                                                Invoice Generated (Order Information Received)</b>
                                            <p><i class="las la-map-marker-alt"></i> Kathmandu, Nepal
                                            </p>
                                            <p><i class="las la-shopping-cart"></i> 3 Items</p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<div class="modal fade" id="orderData" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Order Products</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table allOrderData">

                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


@push('script')
    <script>
        $(document).ready(function() {
            $('.view').on('click', function(e) {
                e.preventDefault();
                var ref_id = $(this).data('id');

                $.ajax({
                    url: "{{ route('allOrderProduct') }}",
                    type: 'get',
                    data: {
                        ref_id: ref_id,
                    },
                    success: function(response) {
                        $('.allOrderData').replaceWith(response);
                    },
                    error: function(response) {

                    }
                });
            });
        });
    </script>
@endpush
