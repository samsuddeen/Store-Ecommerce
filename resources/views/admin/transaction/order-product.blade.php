<table class="table">
    <thead>
      <tr>
        <th scope="col">S.N</th>
        <th scope="col">Image</th>
        <th scope="col">Name</th>
        <th scope="col">Seller Name</th>
        <th scope="col">Quantity</th>
        <th scope="col">Price</th>
      </tr>
    </thead>
    <tbody>
    @foreach($products as $key=>$product)
      <tr>
        <td scope="row">{{$key+1}}</td>
        <td>
        @foreach($product->product->images as $key=>$image)
            @if($key==0)
                <img src="{{$image->image}}" alt="productImage" height="50" width="50">
            @else
                @break
            @endif
        @endforeach
        </td>
        <td>{{$product->product->name}}</td>
        <td>{{$product->product->user->name}}</td>
        <td>{{$product->qty}}</td>
        @if($product->discount > 0)
            <td><del>{{$product->sub_total_price + $product->discount}}</del> {{$product->sub_total_price}}</td>
        @else
        <td>{{$product->price}}</td>
        @endif
      </tr>
    @endforeach
    </tbody>
</table>
