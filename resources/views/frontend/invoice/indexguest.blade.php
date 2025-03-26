@extends('frontend.layouts.app')
@section('title', 'Order Details')
@section('content')
    <div class="order-invoice mt mb">
        <div class="container">
            <p><b>Order Received.</b> Thank you !!! your order has been received.</p>
            <div class="row">
                <div class="col-lg-8 col-md-7">
                    <div class="order-invoice-main">
                        <strong>RefId:</strong><span> {{@$order->ref_id}}</span>
                        <br>
                        <strong>Payment Method:</strong><span> {{@$order->payment_with}}</span>
                        <br>
                        <div class="table-responsive">
                            <table class="table-bordered">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->orderAssets as $product)
                                        <tr>
                                            <td>{{ ucfirst(@$product->product_name) }}<span>x
                                                    {{ @$product->qty }}</span> 
                                                    @if($product->options)
                                                        @foreach (json_decode($product->options) as $option)
                                                            <span>({{ $option->title }}:{{ $option->value }})</span>
                                                        @endforeach
                                                    @endif
                                            </td>
                                            <td>Rs. {{ formattedNepaliNumber(@$product->sub_total_price+@$product->discount*$product->qty) }}</td>

                                        </tr>

                                    @endforeach

                                        @if($order->total_discount !=0)
                                        <tr>
                                            <td>Discount Price</td>
                                            <td>Rs. {{ formattedNepaliNumber(@$order->total_discount) }}</td>
                                        </tr>
                                        @endif
                                        @if($order->coupon_discount_price !=0)
                                        <tr>
                                            <td>Coupon Price</td>
                                            <td>Rs.{{ formattedNepaliNumber(@$order->coupon_discount_price) }}</td>
                                        </tr>
                                        @endif
                                    <tr>
                                        <td>Sub Total</td>
                                        
                                        <td>Rs.{{ formattedNepaliNumber(@$order->total_price - @$order->shipping_charge - @$order->material_charge -@$order->vat_amount) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Delivery Charge</td>
                                        <td>Rs.{{ formattedNepaliNumber(@$order->shipping_charge) }}</td>
                                    </tr>
                                    @if(@$order->material_charge)
                                    <tr>
                                        <td>Material Charge</td>
                                        <td>Rs.{{ formattedNepaliNumber(@$order->material_charge) }}</td>
                                    </tr>
                                    @endif
                                    @if($order->vat_amount !=0 && $order->vat_amount >0)
                                    <tr>
                                        <td>VAT Charge:</td>
                                        <td>Rs.{{ formattedNepaliNumber(@$order->vat_amount) }}</td>
                                    </tr>
                                    @else
                                    {{-- <tr>
                                        <td>VAT Charge:</td>
                                        <td>${{ formattedNepaliNumber(@$order->vat_amount) }}</td>
                                    </tr> --}}
                                    @endif
                                    {{-- <tr>
                                        <td>Payment Method</td>
                                        <td>{{ @$order->payment_with }}</td>
                                    </tr> --}}
                                    <tr>
                                        <td>Total</td>
                                        <td>Rs.{{ formattedNepaliNumber(@$order->total_price) }}</td>
                                    </tr>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {{-- @dd($order) --}}
                <div class="col-lg-4 col-md-5">
                    <div class="order-invoice-sidebar">
                        <div class="table-responsive">
                            <table>
                                <tbody>
                                    <tr>
                                        <td>Order Code:</td>
                                        <td class="text-end">{{ @$order->ref_id }}</td>
                                    </tr>
                                    <tr>
                                        <td>Date</td>
                                        <td class="text-end">{{ @date('d M Y', strtotime($order->created_at)) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Sub Total</td>
                                        <td class="text-end">Rs. {{ formattedNepaliNumber(@$order->total_price - @$order->shipping_charge -@$order->material_charge-@$order->vat_amount) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Delivery Charge:</td>
                                        <td class="text-end">Rs. {{ formattedNepaliNumber(@$order->shipping_charge) }}</td>
                                    </tr>
                                    @if(@$order->material_charge)
                                    <tr>
                                        <td>Material Charge:</td>
                                        <td class="text-end">Rs. {{ formattedNepaliNumber(@$order->material_charge) }}</td>
                                    </tr>
                                    @endif
                                    {{-- @if($order->vat_amount !=0 && $order->vat_amount >0)
                                    <tr>
                                        <td>VAT Charge:</td>
                                        <td class="text-end">$. {{ formattedNepaliNumber(@$order->vat_amount) }}</td>
                                    </tr>
                                    @else
                                    <tr>
                                        <td>VAT Charge:</td>
                                        <td class="text-end">$. {{ formattedNepaliNumber(@$order->vat_amount) }}</td>
                                    </tr>
                                    @endif --}}
                                    <tr>
                                        <td>Total:</td>
                                        <td class="text-end">Rs. {{ formattedNepaliNumber(@$order->total_price) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Payment Method:</td>
                                        <td class="text-end">{{ @$order->payment_with }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <a href="{{ route('user-order-detail-pdfs', @$order->ref_id) }}">Download Invoice</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
