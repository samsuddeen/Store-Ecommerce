<table>
    <thead>
        <tr>
            <th><b>Order Id</b></th>
            <th><b>Reference ID</b></th>
            <th><b>Customer</b></th>
            <th><b>Total Quantity</b></th>
            <th><b>Shipping Charge</b></th>
            <th><b>Total Price</b></th>
            <th><b>Total Discount</b></th>
            <th><b>Coupon Name</b></th>
            <th><b>Coupon Code</b></th>
            <th><b>Coupon Discount Price</b></th>
            <th><b>Payment With</b></th>
            <th><b>Payment Date</b></th>
            <th><b>Name</b></th>
            <th><b>Email</b></th>
            <th><b>Phone</b></th>
            <th><b>Province</b></th>
            <th><b>District</b></th>
            <th><b>Local Body</b></th>
            <th><b>Address</b></th>
            <th><b>Postal Code</b></th>
            <th><b>Billing Name</b></th>
            <th><b>Billing Email</b></th>
            <th><b>Billing Phone</b></th>
            <th><b>Billing Province</b></th>
            <th><b>Billing District</b></th>
            <th><b>Billing Local Body</b></th>
            <th><b>Billing Address</b></th>
            <th><b>Billing Postal Code</b></th>
            <th><b>Order Placed Date Time</b></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($orders as $row)
        <tr>

            <td>{{ $row->id }}</td>
            <td>{{ $row->ref_id }}</td>
            <td>{{ $row->user->name }}</td>
            <td>{{ $row->total_quantity }}</td>
            <td>{{ $row->shipping_charge }}</td>
            <td>{{ $row->total_price }}</td>
            <td>{{ $row->total_discount }}</td>
            <td>{{ $row->coupon_name }}</td>
            <td>{{ $row->coupon_code }}</td>
            <td>{{ $row->coupon_discount_price }}</td>
            <td>{{ $row->payment_with }}</td>
            <td>{{ $row->payment_date }}</td>
            <td>{{ $row->name }}</td>
            <td>{{ $row->email }}</td>
            <td>{{ $row->phone }}</td>
            <td>{{ $row->province }}</td>
            <td>{{ $row->district }}</td>
            <td>{{ $row->area }}</td>
            <td>{{ $row->additional_address }}</td>
            <td>{{ $row->zip }}</td>
            <td>{{ $row->b_name }}</td>
            <td>{{ $row->b_email }}</td>
            <td>{{ $row->b_phone }}</td>
            <td>{{ $row->b_province }}</td>
            <td>{{ $row->b_district }}</td>
            <td>{{ $row->b_area }}</td>
            <td>{{ $row->b_additional_address }}</td>
            <td>{{ $row->b_zip }}</td>
            <td>{{ $row->created_at }}</td>
        </tr>
        
        @endforeach
    </tbody>

</table>
