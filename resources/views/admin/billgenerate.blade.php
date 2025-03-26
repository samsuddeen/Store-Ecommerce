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
        .barcode-modal-footer {
            display: flex;
            justify-content: space-between;
        }
        .barcode-modal-footer a {
            background: var(--secondary-color);
            color: var(--white-color);
            padding: 5px 10px;
            border-radius: var(--border-radius);
        }
    </style>
</head>

<body>
    
    <div class="modal-dialog" id="barcode_modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Bill Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="barcode-modal">
                    <div class="barcode-modal-col">
                        <div class="barcode-modal-left">
                            <img width="100px" height="100px" src="{{$data['logo']}}" alt="">
                        </div>
                        <div class="barcode-modal-right">
                            <ul>
                                <li>Standard</li>
                                <li>{{@$data['weight']}} Kg</li>
                                <li>Standard</li>
                                <li>{{@$data['payment_with']}}</li>
                                <li>{{formattedNepaliNumber(@$data['total']+@$data['order']->shipping_charge) }} NPR</li>
                            </ul>
                        </div>
                    </div>
                    <br>
                    <p class="text-center"><strong>{{@$data['ref_id']}}</strong></p>
                    <div class="barcode-modal-col second-barcode-modal">
                        <div class="barcode-modal-left">
                            {{ QrCode::size(100)->generate($bar_html) }}
                        </div>
                        <div class="barcode-modal-right">
                            <ul>
                                <li>
                                    Recipient:<br> Glass Pipe <br>
                                        Buyer Details:<br>
                                    {{$data['user_name']??''}}<br>
                                    {{$data['user_province'] ??''}}<br>
                                    {{$data['user_district'] ??''}}<br><br>
                                    {{$data['user_address'] ??''}}<br>
                                    {{$data['user_email'] ??''}}<br>
                                    +977{{@$data['user_phone']  ??''}}
                                </li>
                                <li>Quantity: {{@$data['qty']}}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="barcode-modal-footer">
                        <ul>
                            <li>Order Date: {{date('d M Y',strtotime(@$order->created_at))}}</li>    
                        </ul>
                        <a href="{{route('download-bill',$order->ref_id)}}" style="background:red">Download</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
