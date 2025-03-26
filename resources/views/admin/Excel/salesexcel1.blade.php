<table>
    <thead>
        <tr>
            <th>S.N</th> 
            <th>Log From</th>
            <th>Name</th>
        </tr>
    </thead>
    <tbody>
        @php
            $count=0;
        @endphp
        @foreach ($sales as $row)
        <tr>
            <td>{{$count}}</td>
            <td>{{($row->guard=='web') ? 'Admin' :$row->guard;}}</td>
            <td>{{logAction($row)}}</td>
        </tr>
        @php
            $count++;
        @endphp
        @endforeach
    </tbody>
</table>
