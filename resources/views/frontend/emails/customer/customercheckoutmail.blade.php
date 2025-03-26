@component('mail::layout')
{{-- Header --}}
@slot('header')
    @component('mail::header', ['url' => config('app.url')])
        @php
            $setting = \App\Models\Setting::first();
        @endphp
        @if(isset($setting->value))
            <img src="{{$setting->value}}" height="90px" width="300px"><br>
        @endif
    @endcomponent
@endslot

{{-- Body --}}
<style>
    table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    td,
    th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
    }

        tr:nth-child(even) {
        background-color: #dddddd;
    }
</style>
# Thank you for shopping with {{ config('app.name') }}.
Your order has been received.
Your order details are as follows:

Order Code: <strong> {{$info['ref_id']}}</strong><br>
@php
    $order = \App\Models\Order::where('ref_id',$info['ref_id'])->first();
@endphp
Date: <strong> {{date('M d, Y',strtotime($order->created_at))}}</strong><br>
Total Price To Pay: <strong> $. {{$order->total_price}}</strong><br>
Payment method:
 <strong> {{$info['payment_method']}}</strong>
<br>

<table>
    @foreach($order->orderAssets as $product)
        <tr>
            <td>{{$product->product_name}}<strong> × {{$product->qty}}</strong></td>
            <td><span><span>$. </span>{{number_format($product->sub_total_price)}}</span></td>
        </tr>
    @endforeach
    <tr>
        <td>Subtotal</td>
        <td>$. {{$order->total_price}}</td>
    </tr>
    <tr>
        <td>Delivery Charge</td>
        <td>$. 0</td>
    </tr>
    <tr>
        <td>Total</td>
        <td>$. {{$order->total_price}}</td>
    </tr>
</table>
<br>

{{-- Subcopy --}}
@slot('subcopy')
    @component('mail::subcopy')
    @php
        $setting = \App\Models\Setting::findOrFail(2);
    @endphp
        Regards,<br>
        @if(isset($setting->value)) {{$setting->value}} @else {{ config('app.name') }} @endif
    @endcomponent
@endslot


{{-- Footer --}}
@slot('footer')
    @component('mail::footer')
        <div class="copyright">Copyright &copy; {{date('Y')}} <a href="{{route('index')}}">{{$setting->value}}</a>. All rights reserved.
    @endcomponent
@endslot
@endcomponent
