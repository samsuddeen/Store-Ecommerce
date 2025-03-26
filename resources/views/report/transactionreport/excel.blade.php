<table>
    <thead>
        <tr>
            <th><b>SN</b></th>
            <th><b>Order Id</b></th>
            <th><b>Payment With</b></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($product as $key=>$row)        
        <tr>
            <td>{{$key++}}</td>
            <td>{{$row->id}}</td>
            <td>{{ $row->payment_with}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
