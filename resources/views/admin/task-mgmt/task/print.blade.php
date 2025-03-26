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
            padding:5px 0;
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
        .clear{
            position: relative;
        }
        .clear::after{
            display: block;
            content: '';
            clear: both;
        }
    </style>
</head>

<body>
    
    <div class="logo">
        <img src="{{ $setting ?? asset('celermart-logo.png') }}" alt="Logo">
    </div>
    <h3>Task Details</h3>
    <div class="pdf-tables-head">
        <table width="100%" style="margin-bottom:20px;">
            <tbody>
                <tr>
                    <td style="width: 50%">
                        <table>
                            <tbody>
                                <tr>
                                    <td style="padding-right:10px;">Task ID:</td>
                                    <td><strong>{{@$task->id}}</strong></td>
                                </tr>
                                <tr>
                                    <td style="padding-right:10px;">Created By:</td>
                                    {{-- <td>{{@$customer->name}}</td> --}}
                                    <td>{{@$task->createdBy->name}}</td>
                                </tr>
                                <tr>
                                    <td style="padding-right:10px;">Created Date:</td>
                                    <td>{{ date('d M Y',strtotime(@$task->created_at))}}</td>
                                </tr>
                                <tr>
                                    <td style="padding-right:10px;">Assigned To:</td>
                                    <td>{{ ucfirst(@$task->assigns->first()->name) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td style="width: 50%" class="clear">
                        <table style="float:right;">
                            <tbody>
                                <tr>
                                    <td style="padding-right:10px;">Title:</td>
                                    <td>{{ @$task->title }}</td>
                                </tr>
                                <tr>
                                    <td style="padding-right:10px;">Priority:</td>
                                    <td>{{@$task->priority}}</td>
                                </tr>
                                <tr>
                                    <td style="padding-right:10px;">Action:</td>
                                    <td>{{ @$task->action->title ?? '-'}}</td>
                                </tr>
                                <tr>
                                    <td  style="padding-right:10px;">Start/Due Date</td>
                                    <td>{{ @$task->start_date }} - {{ @$task->due_date }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="description">
        <p>{!! @$task->description !!}</p>
    </div>
    <div class="pdf-terms">
        <h4>Detail</h4>
        <ul>
            <li><strong>Order:</strong>  {{ @$task->order->ref_id }}</li>
            <li><strong>Order Qty:</strong>  {{ @$task->order->total_quantity }}</li>
            <li><strong>Total Price:</strong>  {{ @$task->order->total_price }}</li>
            <li><strong>Delivery Location:</strong>    {{ @$task->order->area }}</li>
            <li><strong>Customer Name:</strong>    {{ @$task->order->name }}</li>
            <li><strong>Customer Phone:</strong>    {{ @$task->order->phone }}</li>
            <li><strong>Customer Email:</strong>    {{ @$task->order->email }}</li>
            <li><strong>Payment Method:</strong>    {{ @$task->order->payment_with }}</li>
            <li><strong>Payment Status:</strong>    {{ @$task->order->payment_status == '1' ? 'Paid' : 'Not Paid' }}</li>
                        
            
        </ul>
        @if(@$task->subTasks->isNotEmpty())
            <h4>Sub Tasks</h4>
            <ul>
                @foreach ($task->subTasks as $sub)
                    <li>{{ $sub->title }}</li>
                @endforeach
            </ul>
        @endif
    </div>
</body>

</html>
