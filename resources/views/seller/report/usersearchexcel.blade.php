<table>
    <thead>
        <tr>
            <th><b>S.No</b></th>
            <th><b>User Name</b></th>
            <th><b>Search Keyword</b></th>
            <th><b>Search Date</b></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($keywords as $key=>$row)
        <tr>
            <td>{{$key+1}}</td>
            <td>{{ ucfirst($row->user->name)}}</td>
            <td>{{$row->search_keyword}}</td>
            <td>{{($row->created_at->format('d M Y').'['.$row->created_at->format('H:i').']')}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
