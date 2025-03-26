<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order PDF</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 13px;
        }

        table {
            border-collapse: collapse;
        }

        .pdf-tables table th {
            white-space: nowrap;
            background: #39afb2;
            color:#fff;
        }

        .pdf-tables table th,
        .pdf-tables table td {
            padding: 7px 10px;
            vertical-align: top;
            line-height: 1.2;
            text-align: left;
        }
        .pdf-tables-head table th, 
        .pdf-tables-head table td{
            padding:3px 0;
        }
        ul{
            margin:0;
            padding:0;
            padding-left:15px;
        }
        li{
            font-size: 13px;
        }
        li+li{
            margin-top:5px;
        }
        h3{
            font-size: 22px;
            text-align: center;
        }
        h4{
            margin-bottom:10px;
        }
        .pdf-tables-head td{
            vertical-align: top;
        }
        .logo{
            text-align: center;
            margin-bottom:20px;
        }
        .logo img{
            height: 80px;
            width: auto;
        }
    </style>
</head>

<body>
    
    <div class="logo">
        <img src="{{$tick}}" alt="Logo">
    </div>
    <h3>Delivery Order Details</h3>
    <div class="pdf-tables-head">
        <table width="100%" style="margin-bottom:20px;">
            <tbody>
                <tr>
                    <td style="width: 50%">
                        <table>
                            <tbody>
                                <tr>
                                    <td style="padding-right:10px;">Ref No.:</td>
                                    <td><strong>{{@$refId}}</strong></td>
                                </tr>
                                <tr>
                                    <td style="padding-right:10px;">Requested By:</td>
                                    <td>{{@$customer->name}}</td>
                                </tr>
                                <tr>
                                    <td style="padding-right:10px;">Order Date:</td>
                                    <td>{{ date('d M Y',strtotime(@$order->created_at))}}</td>
                                </tr>
                                <tr>
                                    <td style="padding-right:10px;">Payment With:</td>
                                    <td>{{@$order->payment_with}}</td>
                                </tr>
                                
                            </tbody>
                        </table>
                    </td>
                    <td style="width: 50%">
                        <table style="float:right;">
                            <tbody>
                                <tr>
                                    <td style="padding-right:10px;">Address:</td>
                                    <td>{{$order->province.','.$order->district.','.$order->area.','.$order->additional_address.',Nepal'}}</td>
                                </tr>
                                <tr>
                                    <td style="padding-right:10px;">Email:</td>
                                    <td>{{@$order->email}}</td>
                                </tr>
                                <tr>
                                    <td style="padding-right:10px;">Phone:</td>
                                    <td>{{@$customer->phone ? @$customer->phone :((@$order->phone) ? @$order->phone:@$order->b_phone)}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="pdf-tables">
        @if (count($data) > 0)
            <table class="table " border='1' width="100%" class="pdf-tables">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Product Discount</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $totalDiscount=0;    
                    ?>
                    {{-- @dd($data) --}}
                    @foreach ($data as $key => $product)
                    @dd($product)
                    <?php
                        $totalDiscount=$totalDiscount+($product->discount*@$product->qty);
                    ?>
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td style="text-transform: capitalize;">{{ @$product->product_name }}</td>
                            <td>{{ @$product->qty }}</td>
                            <td>{{ @$product->price }}</td>
                            <td>{{ @$product->discount*@$product->qty }}</td>
                            <td>{{ @$product->sub_total_price-@$product->vatamountfield }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td></td>
                        <td><b>Total</b></td>
                        {{-- <td>{{ $data->sum('qty') }}</td>
                        <td>{{ $data->sum('price') }}</td>
                        <td>{{ @$totalDiscount }}</td> --}}
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><b>{{ $data->sum('sub_total_price')-@$data->sum('vatamountfield') }}</b></td>
                    </tr>
                    <tr>
                        <td rowspan="3" colspan="4"></td>
                        <td>Shipping Charge:</td>
                        <td><strong>{{@$order->shipping_charge}}</strong></td>
                    </tr>
                    @if($order->vat_amount !=0 && $order->vat_amount >0)
                    <tr>
                        <td>Vat Charge:</td>
                        <td><strong>{{@$order->vat_amount}}</strong></td>
                    </tr>
                    @endif
                    {{-- @dd($order) --}}
                    <tr>
                        <td>Grand Total:</td>
                        <td><strong>{{ @$order->total_price }}</strong></td>
                    </tr>
                </tbody>
            </table>
        @endif
    </div>

    <div class="pdf-terms">
        <h4>Terms & Conditions</h4>
        <ul>
            <li>The policy of an invoice bill typically includes:</li>
            <li><strong>Payment terms:</strong> This outlines the payment due date, the payment methods accepted, and any penalties or late fees that may apply if payment is not received by the due date.</li>
            <li><strong>Delivery terms:</strong> This outlines the delivery method, shipping costs, and any other terms related to the delivery of the products or services.</li>
            <li><strong>Invoice format:</strong> This outlines the format and structure of the invoice, including any specific requirements for including certain information or details.</li>
            <li><strong>Tax requirements:</strong> This outlines any tax requirements related to the sale, including the tax rate, any exemptions, and how the tax should be calculated and included on the invoice.</li>
            <li><strong>Dispute resolution:</strong> This outlines the process for resolving any disputes related to the invoice, such as discrepancies in the products or services provided or payment amounts.</li>
            <li>Overall, the policy of an invoice bill is designed to ensure that the transaction is conducted fairly and efficiently, and that both the buyer and seller understand their rights and responsibilities.</li>
            
        </ul>
    </div>
</body>

</html>
