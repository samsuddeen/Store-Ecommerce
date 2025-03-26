<table>
    <thead>
        <tr>
            <th><b>Name</b></th>
            <th><b>Email</b></th>
            <th><b>Phone</b></th>
            <th><b>Address</b></th>
            <th><b>Created At</b></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($customer as $row)
        <tr>
            <td>{{$row->name}}</td>
            <td>{{$row->email}}</td>
            <td>{{ $row->phone}}</td>
            <td>{{$row->address}}</td>
            <td>{{($row->created_at->format('d M Y').'['.$row->created_at->format('H:i').']')}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
