@section('title')

Order Detail | {{env('APP_NAME')}}

@stop
@extends('layouts.app')
@push('style')
<style>
    td img{
        height: 70px !important;
        display: block;
        align-content: center;
        text-align: center;
    }
</style>
@endpush
@section('content')
<div class="page-heading">
<div class="page-content fade-in-up">
    <div class="ibox invoice" id="printpage">
        <div class="invoice-header">
            <div class="row">
                <div class="col-6">
                    <h1><center>Progress Bar Goes here</center></h1>
                </div>
            </div>
        </div>

        <table class="table table-striped no-margin table-invoice">
            <thead>
                <tr>
                    <th>S.N.</th>
                    <th>Product Name</th>
                    <th>Seller</th>
                    <th colspan="2">Unit Price</th>
                    <th>Quantity</th>
                    <th colspan="2">Total Price</th>
                </tr>
                <tr>
                    <th colspan="3"></th>
                    <th>Org</th>
                    <th>Discount</th>
                    <th></th>
                    <th>Org</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderAssets as $key => $row)
                <tr>
                    <td>{{ ++$key }}</td>
                    <td>
                        @if($row->product->featured_image() !== null)
                        <img src="{{$row->product->featured_image()}}" alt="image not found" class="img-fluid">
                        @endif
                        @foreach($row->options as $option)
                            <label for="">{{$option['title'] ?? null}} : {{$option['value'] ?? null}}</label><br>
                        @endforeach
                        <a href="#"> {{ substr($row->product_name, 0, 10).'.............'}}</a>
                    </td>
                    <td><a href="#"> {{ substr($row->seller->name ??  null, 0, 10).'.............'}}</a></td>
                    <td>{{ '$. '.$row->price + $row->discount }}</td>
                    <td>{{ '$. '.$row->discount }}</td>
                    <td>{{ $row->qty }}</td>
                    <td>{{ '$. '.($row->price + $row->discount) * $row->qty }}</td>
                    <td>{{ '$ '.$row->sub_total_price }}</td>
                </tr>
                @endforeach

            </tbody>
        </table>
        <table class="table no-border">
            <thead>
                <tr>
                    <th></th>
                    <th width="15%"></th>
                </tr>
            </thead>
            <tbody>
                <tr class="text-right">
                    <td>Subtotal:</td>
                    <td>{{'$ '. $order->total_price + $order->total_discount + $order->shipping_charge + $order->coupon_discount_price  }}</td>
                </tr>

                @if($order->total_discount > 0)
                <tr class="text-right">
                    <td>Discount:</td>
                    <td>{{' - $ '.$order->total_discount  }}</td>
                </tr>
                @endif

                @if($order->coupon_discount_price > 0)
                <tr class="text-right">
                    <td>Coupon Discount:</td>
                    <td>{{'- $ '.$order->coupon_discount_price  }}</td>
                </tr>
                @endif
                @if($order->shipping_charge > 0)
                <tr class="text-right">
                    <td>Coupon Discount:</td>
                    <td>{{'+ $ '.$order->shipping_charge  }}</td>
                </tr>
                @endif
                <tr class="text-right">
                    <td class="font-bold font-18">TOTAL:</td>
                    <td class="font-bold font-18">{{ '$. '.$order->total_price }}</td>
                </tr>
            </tbody>
        </table>
       
    </div>
    <style>
        .invoice {
            padding: 20px
        }

        .invoice-header {
            margin-bottom: 50px
        }

        .invoice-logo {
            margin-bottom: 50px;
        }

        .table-invoice tr td:last-child {
            text-align: right;
        }
    </style>

</div>
<!-- END PAGE CONTENT-->


@stop
@section('footer')
<script>
    function printDiv() {
        var value1 = document.getElementById('printpage').innerHTML;
        var value2 = document.body.innerHTML;
        document.body.innerHTML = value1;
        window.print();
        document.body.innerHTML = value2;
        location.reload();
    }
</script>
@endsection