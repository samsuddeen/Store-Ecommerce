@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Order')
@push('style')
    <style>
        td img {
            height: 70px !important;
            display: block;
            align-content: center;
            text-align: center;
        }
    </style>
@endpush
@section('content')
    <div class="page-heading">
        <div class="page-content fade-in-up">
            <div class="ibox invoice" id="printpage">
                <table class="table table-striped no-margin table-invoice">
                    <thead>
                        <tr>
                            <th>S.N.</th>
                            <th>Product Name</th>
                            {{-- <th>Seller</th> --}}
                            <th colspan="2">Unit Price</th>
                            <th>Quantity</th>
                            <th colspan="2">Total Price</th>
                        </tr>
                        <tr>
                            <th colspan="3"></th>
                            <th>Discount</th>
                            {{-- <th>Discount</th> --}}
                            <th>Org</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->orderAssets as $key => $row)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>
                                    @if($row->product && null !== $row->product->featured_image())
                                        <img src="{{ $row->product->featured_image() }}" alt="image not found"
                                            class="img-fluid">
                                    @endif
                                    @if($row->option && $row->option !=null)
                                    @foreach ($row->options as $option)
                                        <label for="">{{ $option['title'] ?? null }} :
                                            {{ $option['value'] ?? null }}</label><br>
                                    @endforeach
                                    @endif
                                    <a href="#"> {{$row->product_name, 0, 10 }}</a>
                                    @if($row->options)

                                    @foreach (json_decode($row->options) as $option)
                                   <span>({{ $option->title }}:{{ $option->value }})</span>
                                    @endforeach
                                    @endif
                                </td>
                                {{-- <td><a href="#"> {{ substr($row->product->seller->name ?? null, 0, 10) . '.............' }}</a>
                                </td> --}}
                                <td>{{ 'Rs. ' . formattedNepaliNumber($row->price) }}</td>
                                <td>{{ 'Rs. ' . formattedNepaliNumber($row->discount) }}/per piece</td>
                                <td>{{ $row->qty }}</td>
                                <td>{{ 'Rs. ' . formattedNepaliNumber($row->sub_total_price-@$row->vatamountfield )}}</td>
                                @if($row->cancelStatus)
                                <td>
                                    @if($row->cancelStatus->cancel_status=='0')
                                    <a class="btn btn-sm btn-info order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="cancel" data-order_id="{{$row->cancelStatus->id}}" href="#"><i data-feather="target" class="me-50"></i><span>Approve Cancel Request</span></a><a class="btn btn-sm btn-danger order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="reject" data-order_id="{{$row->cancelStatus->id}}" href="#"><i data-feather="target" class="me-50"></i><span>Reject</span></a><a href="javascript:;" class="btn btn-sm btn-primary sellerCancelReason" data-id="{{$row->cancelStatus->id}}" data-bs-toggle="modal" data-bs-target="#exampleModal" >View Reason</a>
                                    @elseif($row->cancelStatus->cancel_status=='1')
                                    <a href="javascript:;" class="btn btn-sm btn-success">Order Cancel Approved</a><a href="javascript:;" class="btn btn-sm btn-primary sellerCancelReason" data-id="{{$row->cancelStatus->id}}" data-bs-toggle="modal" data-bs-target="#exampleModal" >View Reason</a>
                                    @elseif($row->cancelStatus->cancel_status=='2') 
                                    <a href="javascript:;" class="btn btn-sm btn-danger">Order Cancel Rejected</a><a href="javascript:;" class="btn btn-sm btn-primary sellerCancelReason" data-id="{{$row->cancelStatus->id}}" data-bs-toggle="modal" data-bs-target="#exampleModal" >View Reason</a>
                                    @endif
                                   
                                </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <table class="table no-border">
                    <thead>
                        <tr>
                            <th></th>
                            <th width="15%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @dd($order) --}}
                        <tr class="text-right">
                            <td>Subtotal:</td>
                            <td>{{ 'Rs. ' . formattedNepaliNumber(@$order->total_price - @$order->shipping_charge-@$order->vat_amount)  }}
                            </td>
                        </tr>

                        {{-- @if ($order->total_discount > 0)
                            <tr class="text-right">
                                <td>Discount:</td>
                                <td>{{ ' - $ ' . $order->total_discount }}</td>
                            </tr>
                        @endif --}}
                        {{-- @dd($cancelOrderAsset) --}}
                        <?php
                            $cancelValue=0;
                        ?>
                        @if(count($cancelOrderAsset) >0)
                          
                            @foreach($cancelOrderAsset as $cancelData)
                            <?php
                                $cancelValue+=$cancelData->sub_total_price;
                            ?>
                            @endforeach
                            
                            <tr class="text-right">
                                <td>Cancel Product Price:</td>
                                <td>-{{$cancelValue}}</td>
                            </tr>
                        @endif

                        @if ($order->coupon_discount_price > 0)
                            <tr class="text-right">
                                <td>Coupon Discount:</td>
                                <td>{{ '- Rs. ' . formattedNepaliNumber($order->coupon_discount_price) }}</td>
                            </tr>
                        @endif
                        @if ($order->shipping_charge > 0)
                            <tr class="text-right">
                                <td>Shipping Charge:</td>
                                <td>{{ '+ Rs. ' . formattedNepaliNumber($order->shipping_charge) }}</td>
                            </tr>
                        @endif
                        <tr class="text-right">
                            <td>Material Charge:</td>
                            <td>{{ '+ Rs. ' . formattedNepaliNumber($order->material_charge??'0') }}</td>
                        </tr>
                        @if ($order->vat_amount > 0 && $order->vat_amount!=0)
                            <tr class="text-right">
                                <td>VAT:</td>
                                <td>{{ '+ Rs. ' . formattedNepaliNumber($order->vat_amount) }}</td>
                            </tr>
                        @endif

                        <tr class="text-right">
                            <td class="font-bold font-18">TOTAL:</td>
                            <td class="font-bold font-18">{{ 'Rs. ' . formattedNepaliNumber($order->total_price-$cancelValue) }}</td>
                        </tr>
                    </tbody>
                </table>

                <div>
                    <h3>Customer Info</h3>
                </div>
                <table class="table">
                    <th>
                        <tr>
                            <td> <b>Type</b> </td>
                            <td> <b>User Type </b> </td>
                            <td> <b>Name</b> </td>
                            <td> <b>Address</b> </td>
                            <td> <b> Payment With </b></td>
                            <td> <b>Contact</b> </td>
                            <td> <b>Email</b> </td>
                        </tr>
                    </th>
                    <tbody>
                        <tr>
                            <td> Shipping Address </td>
                            <td>
                                @if ($order->user_id == 1)
                                    Guest
                                @else
                                    Customer
                                @endif
                            </td>
                            <td> {{ $order->name }} </td>
                            <td> {{ $order->area }}, {{ $order->province }},  {{$order->district}}</td>
                            <td>{{ $order->payment_with }}</td>
                            <td> {{ $order->phone }} </td>
                            <td> {{ $order->email }} </td>
                        </tr>

                        <tr>
                            <td> Billing Address </td>
                            <td>
                                @if ($order->user_id == 1)
                                    Guest
                                @else
                                    Customer
                                @endif
                            </td>
                            <td> {{ $order->b_name }} </td>
                            <td> {{ $order->b_area }}, {{ $order->b_province }},  {{$order->b_district}}</td>
                            <td>{{ $order->payment_with }}</td>
                            <td> {{ $order->b_phone }} </td>
                            <td> {{ $order->b_email }} </td>
                        </tr>

                    </tbody>
                </table>

            </div>

            <!-- Reason -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Cancel Reason</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="reasonBody">
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="shareProject" tabindex="-1" aria-labelledby="shareProjectTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-sm-5 mx-50 pb-5">
                <h1 class="text-center mb-1" id="addNewCardTitle">Are You Sure....?</h1>
                <p class="text-center">Please Confirm</p>
                <form class="row gy-1 gx-2 mt-75" method="post" action="{{route('ordercancelrequest.confirm')}}">
                    @csrf
                    @method("POST")
                    <input type="text" name="type" id="type-input" hidden>
                    <input type="text" name="seller_order_cancel_id" id="order_id-input" hidden>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary me-1 mt-1">Submit</button>
                        <button type="reset" class="btn btn-outline-secondary mt-1" data-bs-dismiss="modal"
                            aria-label="Close">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
            <style>
                .invoice {
                    padding: 20px
                }

                .invoice-header {
                    margin-bottom: 50px
                }

                .invoice-logo {
                    margin-bottom: 50px;
                }

                .table-invoice tr td:last-child {
                    text-align: right;
                }
            </style>

        </div>
        <!-- END PAGE CONTENT-->


    @stop
    @section('footer')
        <script>
            function printDiv() {
                var value1 = document.getElementById('printpage').innerHTML;
                var value2 = document.body.innerHTML;
                document.body.innerHTML = value1;
                window.print();
                document.body.innerHTML = value2;
                location.reload();
            }
        </script>
        
    @endsection

    @push('script')
    <script>
        $(document).on('click','.sellerCancelReason',function()
        {
            $('#reasonBody').html('');
            var sellerOrderCancelId=$(this).data('id');
            $.ajax({
                url:"{{route('getSellerReason')}}",
                type:"get",
                data:{
                    sellerOrderCancelId:sellerOrderCancelId
                },
                success:function(response)
                {
                    $('#reasonBody').html(response.data);
                }
            });
        });
    </script>



<script type="text/javascript">
    reloadActionBtn();
     function reloadActionBtn() {
            $('.order-action').on('click', function(e) {
                e.preventDefault();
                let type = $(this).data('type');
                if(type=="cancel" || type=="reject"){
                    $('#reason-box').css({"display":"block"});
                }else{
                    $('#reason-box').css({"display":"none"});
                }
                let order_id = $(this).data('order_id');
                $('#type-input').val(type);
                $('#order_id-input').val(order_id);
            });
        }
    </script>
    @endpush