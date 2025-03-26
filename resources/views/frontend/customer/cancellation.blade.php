@extends('frontend.layouts.app')
@section('title','Order Cancellation')
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
                                    <h3>Cancelled Order</h3>
                                </div>
                                <div class="wishlist_table cancellation_wrapper">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Product Images</th>
                                                    <th>Product Details</th>
                                                    <th>Quantity</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($orders as $order)
                                                    @foreach ($order->orderAssets as $product)
                                                        <tr>
                                                            <td>
                                                                @php
                                                                    $pro = \App\Models\Product::where('id', $product->product_id)->first();
                                                                @endphp
                                                                @if($pro && $pro->images !=null)
                                                                @foreach ($pro->images as $key => $image)
                                                                    @if ($key == 0)
                                                                        @continue
                                                                    @elseif($key == 1)
                                                                        <img src="{{ $image->image }}" alt="">
                                                                    @else
                                                                    @break
                                                                @endif
                                                            @endforeach
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <p style="margin-bottom:0;">{{ @$pro->name }}</p>
                                                            <p style="margin-bottom:0;margin-top:3px;">Color Family:Red</p>
                                                        </td>
                                                        <td>
                                                            {{ @$product->qty }}
                                                        </td>
                                                        <td style="vertical-align: middle;">
                                                            @if (($order->delivered == 1 && $order->pending == 0) || ($order->cancelled == 1 && $order->pending == 0))
                                                                Cancelled
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        </tbody>
                                    </table>
                                    {!!$orders->links()!!}
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
