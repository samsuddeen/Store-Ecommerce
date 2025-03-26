<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        table,
        th,
        td {
            border: 1px solid;
        }
    </style>
</head>

<body>
    <h4>Product Ordering (E-mail From Customer)</h4>
    <h5>Order Info</h5>
    <table class="table">
        <thead>
            <th>Ref ID</th>
            <th>Shipping Charge</th>
            <th>Quantity</th>
            <th>Discount</th>
            <th>Total Price</th>
            <th>Payment With</th>
            <th>Action</th>
        </thead>
        <tr>
            <td>{{ $ref_id }}</td>
            <td>{{ $shipping_charge }}</td>
            <td>{{ $total_quantity }}</td>
            <td>{{ $total_discount }}</td>
            <td>{{ $total_price }}</td>
            <td>{{ $payment_with }}</td>
            <td>
                <a href="{{ url(env('APP_URL') . '/admin/order') }}">
                    View More Info
                </a>
            </td>
        </tr>
    </table>
</body>

</html>
