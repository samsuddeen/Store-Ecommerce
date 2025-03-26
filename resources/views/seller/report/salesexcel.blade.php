<table>
    <thead>
        <tr>
            <th><b>OrderBy</b></th>
            <th><b>Transaction No</b></th>
            <th><b>Total Quantity</b></th>
            <th><b>Total Discount</b></th>
            <th><b>Total Price</b></th>
            <th><b>Delivery Date</b></th>
            <th><b>Reference Id</b></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($sales as $row)
        <tr>
            <td>{{$row->order->user->name}}</td>
            <td>{{ $row->transaction_no}}</td>
            <td>{{$row->sellerOrder->qty}}</td>
            <td>{{$row->sellerOrder->total_discount}}</td>
            <td>{{$row->sellerOrder->total}}</td>
            <td>{{($row->created_at->format('d M Y').'['.$row->created_at->format('H:i').']')}}</td>
            <td>{{$row->order->ref_id}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
