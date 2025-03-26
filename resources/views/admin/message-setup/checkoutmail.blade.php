@include('admin.message-setup.test')

<style>
    table, tr, th,td{
        border: 1px solid black;
    }
</style>
@if(count($products) >0)
<table class="table ">
    <thead>
        <tr>
            <th>#</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Product Discount</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $key=>$product)
        <tr>
            <td>{{ $key+1}}</td>
            <td>{{ @$product->product_name}}</td>
            <td>{{ @$product->qty}}</td>
            <td>{{ @$product->price}}</td>
            <td>{{ @$product->discount}}</td>
            <td>{{ @$product->sub_total_price}}</td>
        </tr>
        @endforeach
        <tr>
            <td>{{$key+2}}</td>
            <td>Total</td>
            <td>{{ $products->sum('qty')}}</td>
            <td>{{ $products->sum('price')}}</td>
            <td>{{ $products->sum('discount')}}</td>
            <td>{{ $products->sum('sub_total_price')}}</td>
        </tr>
    </tbody>
</table>
@endif