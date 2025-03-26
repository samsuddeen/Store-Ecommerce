@extends('frontend.layouts.app')
@section('content')
    <div class="track-orders mt mb">
        <div class="container">
            
            <div class="order-tracking-details">
                <div class="order-tracking-table">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="order-tracking-table-list">
                                <h3>Seller Details</h3>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Name</th>
                                            <td>{{ucfirst(@$seller_details->name)}}</td>
                                        </tr>
                                        <tr>
                                            <th>Address</th>
                                            <td>{{$seller_details->address}}</td>
                                        </tr>
                                        <tr>
                                            <th>Phone</th>
                                            <td>{{$seller_details->phone}}</td>
                                        </tr>
                                        <tr>
                                            <th>Email</th>
                                            <td>{{$seller_details->email}}</td>
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
                                            <td>{{@$seller_order->qty}}</td>
                                        </tr>
                                        <tr>
                                            <th>Amount</th>
                                            <td>{{@$seller_order->total}}</td>
                                        </tr>
                                        <tr>
                                            <th>Discount</th>
                                            <td>{{@$seller_order->total_discount}}</td>
                                        </tr>
                                        @if(@$admin)
                                        <tr>
                                            <th>Shipping Charge</th>
                                            <td>{{@$order->shipping_charge ?? null}}</td>
                                        </tr>
                                        <tr>
                                            <th>Total</th>
                                            <td>{{@$seller_order->subtotal + @$order->shipping_charge}}</td>
                                        </tr>
                                        @else
                                        <tr>
                                            <th>Total</th>
                                            <td>{{@$seller_order->subtotal}}</td>
                                        </tr>
                                        @endif

                                       
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p><b>Description:</b> @foreach ($seller_order_details as $key=>$product)
                        {{$product->getProduct->name}}
                        
                        @if($key < (count($seller_order_details)-1))
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
                                            <p><i class="las la-shopping-cart"></i> {{ count($order->orderAssets) ?? null}} Items</p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        @endforeach
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
