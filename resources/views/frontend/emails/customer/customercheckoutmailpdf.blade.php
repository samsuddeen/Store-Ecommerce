<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        table , tr , td, th{
           width:100%;
        border-collapse:collapse;
        text-align:center;
        border:1px solid #00F;
        font-size:12px;
          }
        </style>
</head>
<body>
    <p>hello world</p>
    {{-- @if(isset($setting->site_logo))
        <img src="{{ public_path("uploads/settings/".$setting->site_logo) }}" height="90px" width="300px" style="margin-left:25%;"><br>
    @endif
    <p>  Thank you for shopping with {{ config('app.name') }}.</p>
    <p>  Your order has been received. Your order details are as follows:</p>

    Order Code: <strong> {{$payment->ref_id}}</strong><br>
    Date: <strong> {{date('M d, Y',strtotime($payment->created_at))}}</strong><br>
    Total Price To Pay: <strong> $. {{number_format($payment->total_price)}}</strong><br>
    Payment method:
    @if($payment->esewa == 1)<strong> Pay with Esewa</strong>
    @elseif($payment->khalti == 1)<strong> Pay with Khalti</strong>
    @elseif($payment->imepay == 1)<strong> Pay with IME Pay</strong>
    @elseif($payment->hbl_pay == 1)<strong> Pay with Himalayan Bank Ltd</strong>
    @else <strong> Pay on Delivery</strong>@endif
    <br><br>
    <table>
        <tr>
            <th>Product Name</th>
            <th>Product Quantity</th>
            <th>Price</th>

        </tr>
        @foreach($orders as $order)
        <tr>
            <td>
                {{$order->product_name}}
            </td>
            <td>{{$order->quantity}}</td>
            <td>
                <span><span>$. </span>{{number_format($order->product_original_price * $order->quantity)}}</span>
            </td>
        </tr>
        @endforeach
        <tr>
            <td>Subtotal</td>
            <td colspan="2">$. {{number_format($payment->total_price)}}</td>
        </tr>
        <tr>
            <td>Delivery Charge</td>
            <td  colspan="2">$. {{number_format($payment->delivery_cost)}}</td>
        </tr>
        <tr>
            <td>Discount Amount</td>
            <td  colspan="2">$. {{number_format($payment->discount_amount)}}</td>
        </tr>
        <tr>
            <th>Grand Total</th>
            <th  colspan="2">$. {{number_format($payment->total_price + $payment->delivery_cost)}}</th>
        </tr>


    </table>
    <br> --}}
</body>
</html>
