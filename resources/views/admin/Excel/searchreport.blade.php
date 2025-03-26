@php
    use App\Models\New_Customer;
@endphp
<table>
    <thead>
        <tr>
            <th><b>Searched By</b></th>
            <th><b>Keyword</b></th>
            <th><b>Browser</b></th>
            <th><b>System</b></th>
            <th><b>Search From</b></th>
            <th><b>Searched In</b></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($keywords as $row)
            <tr>
                <td>{{ $row->user->name ?? '' }}</td>
                <td>{{ $row->search_keyword }}</td>
                <td>{{ $row->browser }}</td>
                <td>{{ $row->system }}</td>
                <td>{{ $row->full_address }}</td>
                <td>{{ $row->created_at }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
