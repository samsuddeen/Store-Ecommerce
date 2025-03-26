<table>
    <thead>
        <tr>
            <th><b>S.No</b></th>
            <th><b>Product Name</b></th>
            <th><b>Category</b></th>
            <th><b>Status</b></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($seller_product as $key=>$row)
        <tr>
            <td>{{$key+1}}</td>
            <td>{{ $row->name}}</td>
            <td>{{ $row->category->title}}</td>
            <td>{{ ($row->publishStatus==1)?'True':'False'}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
