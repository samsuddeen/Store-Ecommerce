@foreach ($allDeliveries as $delivery)
<tr>
    <td>{{ $loop->iteration }}</td>
    <td>{{ $delivery->ref_id }}</td>
    <td>{{ $delivery->total_price }}</td>
    <td>{{ $delivery->area . ',' . $delivery->additional_address }}</td>
    @if(!Auth::user()->hasRole('delivery'))
        @php
            $user = App\Models\User::find($delivery->assigned_to);
        @endphp
    <td>{{ $user->name }}</td>
    @endif
    <td>{{ $delivery->t_status }}</td>
    <td>{{ $delivery->completed_at }}</td>
</tr>
@endforeach