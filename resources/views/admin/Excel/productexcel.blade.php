<table>
    <thead>
        <tr>
            <th><b>Name</b></th>
            <th><b>Category</b></th>
            <th><b>Stock</b></th>
            <th><b>Seller</b></th>
        
        </tr>
    </thead>
    <tbody>
        @foreach ($product as $row)        
        <tr>
            <td>{{$row->name}}</td>
            <td>{{$row->category->title}}</td>
            @foreach($row->stocks ?? [] as $stock)
                @php $qty = 0;
                $qty = $qty + $stock->quantity;
                @endphp
                <td>{{$qty}}</td>
            @endforeach
            <td>{{ $row->seller->name}}</td>

        </tr>
        @endforeach
    </tbody>
</table>
