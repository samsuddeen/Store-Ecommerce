<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
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
                            {{-- <span>0-105-E09</span> --}}
                        </div>
                        
                        <div class="barcode-modal-right">
                            <ul>
                                <li>Standard</li>
                                <li>{{@$data['weight']}} Kg</li>
                                <li>Standard</li>
                                <li>{{@$data['payment_with']}}</li>
                                <li>{{formattedNepaliNumber(@$data['total']+@$seller_order['total_vat_amount'])}} NPR</li>
                            </ul>
                        </div>
                    </div>
                    <br>
                    <br>
                    
                    <p class="text-center"><strong>{{@$data['ref_id']}}</strong></p>
                    <br>
                    <br>
                    <div class="barcode-modal-col second-barcode-modal">
                        <div class="barcode-modal-left">
                            {{ QrCode::size(100)->generate($bar_html) }}
                            {{-- <span>0-105-E09</span> --}}
                        </div>
                        <div class="barcode-modal-right">
                            <ul>
                                <li>
                                    Recipient: {{@$data['seller']['company_name']}} <br>
                                    {{$data['seller']['province_id']}}<br>
                                    {{$data['seller']['district_id']}}<br><br>
                                    {{$data['seller']['address']}}<br>
                                    {{$data['seller']['email']}}<br>
                                    {{-- +977{{@$data['seller']['phone']}} --}}
                                </li>
                                <li>Sender: {{@$data['seller']['company_name']}}</li>
                                <li>Quantity: {{@$data['qty']}}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="barcode-modal-footer">
                        <ul>
                            {{-- <li>Print Date: 2023-01-22</li> --}}
                            <li>Order Date: {{date('d M Y',strtotime(@$seller_order->created_at))}}</li>    
                        </ul>
                        {{-- <a href="{{route('download-bill',$order->ref_id)}}">Download</a> --}}
                        <a href="{{route('seller.download-bill',[$seller_order->id,$order->ref_id])}}">Download</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
