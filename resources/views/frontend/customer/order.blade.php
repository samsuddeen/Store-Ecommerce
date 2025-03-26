@extends('frontend.layouts.app')
@section('title', env('DEFAULT_TITLE') . '|' . 'customer|order')
@section('content')
    <div class="dashboard-wrapper mt mb">
        <div class="container">
            <div class="customer_dashboard_wrap">
                @include('frontend.customer.sidebar')
                <div class="dashboard-main-wrapper">
                    <div class="dash-toggle">
                        <i class="las la-bars"></i>
                    </div>
                    <div class="dashboard-main-col">
                        <div class="dashboard_contentArea">
                            <div class="dashboard_content">
                                <div class="customer_order_wrapper table_wrapper">
                                    <div class="dashboard-tables-head">
                                        <h3>My Order</h3>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table_style table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>S.N</th>
                                                    <th>Order ref no</th>
                                                    <th>Date</th>
                                                    <th>Total Price</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($orders as $key => $order)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ $order->ref_id }}</td>
                                                        <td>{{ $order->created_at }}</td>
                                                        <td>{{ formattedNepaliNumber($order->total_price) }}</td>
                                                        <td>
                                                            @if($order->is_new==0)
                                                                Pending
                                                            @else
                                                            {{ $order->status == 1 ? 'Seen' : ($order->status == 2 ? 'Ready To Ship' : ($order->status == 3 ? 'Dispatched' : ($order->status == 4 ? 'Shipped' : ($order->status == 5 ? 'Delivered' : ($order->status == 6 ? 'Cancelled':($order->status == 7 ? 'Rejected' : ''))))) ) }}
                                                            @endif
                                                            @if($order->status==7)
                                                                <a href="javascript:;"  data-bs-toggle="modal" data-bs-target="#rejectModal" data-rejectid="{{$order->id}}" class="btn btn-sm btn-success rejectReason">View</a>
                                                                @if($order->directrefund !=null)
                                                                    @if ($order->directrefund->status==3)
                                                                    <a href="javascript:;" id="rejectedData" class="btn btn-sm btn-danger" data-reason="{{@$order->directrefund->remarks}}">Rejected</a>
                                                                    @else
                                                                    <span class="btn btn-sm btn-success rejectedOrder" >{{(@$order->directrefund->status==1) ? 'Pending' :((@$order->directrefund->status==2)?'Paid' :'Rejected')}}</span>
                                                                    @endif
                                                                
                                                                @elseif($order->payment_status==1 && $order->directrefund==null)
                                                                    <button type="button" class="btn btn-sm btn-danger"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#refundOrder{{$order->id}}">Apply Refund
                                                                    </button>

                                                                @endif
                                                            @endif
                                                            @if($order->status==6)
                                                                <a href="javascript:;"  data-bs-toggle="modal" data-bs-target="#reasonModal" data-reasonid="{{$order->id}}" class="btn btn-sm btn-success cancellReason">View</a>
                                                                @if($order->directrefund !=null)
                                                                @if ($order->directrefund->status==3)
                                                                <a href="javascript:;" id="rejectedData" class="btn btn-sm btn-danger" data-reason="{{@$order->directrefund->remarks}}">Rejected</a>
                                                                @else
                                                                <span class="btn btn-sm btn-success rejectedOrder" >{{(@$order->directrefund->status==1) ? 'Pending' :((@$order->directrefund->status==2)?'Paid' :'Rejected')}}</span>
                                                                @endif
                                                                @elseif($order->payment_status==1 && $order->directrefund==null)
                                                                    <button type="button" class="btn btn-sm btn-danger"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#refundOrder{{$order->id}}">Apply Refund
                                                                    </button>
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="table_btn">
                                                                <a class="view" title="View" data-bs-toggle="modal"
                                                                    data-id="{{ $order->ref_id }}"
                                                                    data-bs-target="#orderData{{ $order->id }}"><i
                                                                        class="las la-eye"></i></a>
                                                                {{-- @if ($order->status == 5)
                                                                    <a href="{{ route('deleteOrderCompleted', $order->ref_id) }}"
                                                                        class="delete" title="Delete"><i class="las la-trash"></i></a>
                                                                @endif --}}
                                                                @if ($order->status == 1 || $order->status == 2 || $order->status == 3)
                                                                    <button type="button" class="edit"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#exampleModal{{ $order->id }}">
                                                                        <i class="las la-times"></i>
                                                                    </button>
                                                                @endif
                                                                {{-- <a href="{{route('order.productDetails',$order->id)}}" class="view" title="View">Detail</a></a> --}}
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        {!!$orders->links()!!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

     {{-- -----------------------------------------Refund--------------------------------------------------------- --}}
     @foreach ($orders as $returningData)
        @if($returningData->payment_status==1) 
         <div class="modal fade" id="refundOrder{{$returningData->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
             <div class="modal-dialog">
             <div class="modal-content">
                 <div class="modal-header bg-danger text-white">
                 <h5 class="modal-title text-center" id="exampleModalLabel">Apply For Refund</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                 </div>
                 <div class="modal-body">
                 {{Form::open(['url'=>route('refund.direct.request',$returningData->id),])}}
                 @method('post')
                     <div class="row">
                         <div class="col-md-12 refund-form">
                             <div class="cod">
                                 <input type="radio" id="cod" value="cod" class="refund" name="return type">
                                     <label for="cod" >Cash</label>
                             </div>
                             <br>
                             <div class="esewa">
                                 <input type="radio" id="esewa" value="esewa" class="refund" name="return type">
                                 <label for="esewa">Esewa</label>
                                 <div class="esewa-field"></div>
                             </div>
                             <br>
                             <div class="khalti">
                                 <input type="radio" id="khalti" value="khalti" class="refund" name="return type">
                                 <label for="khalti">Khalti</label>
                                 <div class="khalti-field"></div>
                             </div>
                             <br>
                             <div class="bank">
                                 <input type="radio" id="bank" value="bank" class="refund" name="return type">
                                 <label for="cod">Bank</label>
                                 <div class="bank-field"></div>
                             </div>
                         </div>
                     </div>
                 
                 </div>
                 <div class="modal-footer">
                     <button  class="btn btn-danger" type="reset">Reset</button>
                     <button class="btn btn-primary" type="submit">Apply</button>
                 </div>
                 {{ Form::close()}}
             </div>
             </div>
         </div>
        @endif
     @endforeach
     {{-- -----------------------------------------Refund--------------------------------------------------------- --}}

     

    <!-- Modal -->
    @foreach ($orders as $order)
        <form action="{{ route('cancel.order', $order->id) }}">
            <div class="common-popup medium-popup modal fade" id="exampleModal{{ $order->id }}" tabindex="-1"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Cancel Orders</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="order_id" name="order_id" id="order_id" value="{{ $order->id }}" hidden>
                            <div class="form-group">
                                <label for="reason">Why do you want to cancel the order ?</label>
                                <select name="reason" id="reason" class="form-control" required>
                                    <option value="">--Please Select Reason---</option>
                                    @foreach ($cancellation_reasons as $reason)
                                        <option value="{{ $reason->title }}">{{ $reason->title }}</option>
                                    @endforeach
                                </select>
                                @error('reason')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="additional_reason">Give your reason.</label>
                                <textarea name="additional_reason" id="additional_reason" rows="5" class="form-control" required></textarea>
                                @error('additional_reason')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group" style="display: flex;align-items:center;">
                                <input type="checkbox" name="aggree" id="aggree" required>
                                <label for="aggree" style="margin-left:7px;margin-bottom:0;">I have read and agree to the cancellation policy.</label>
                                @error('aggree')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit" class="btn" id="modal-cancel-button">Cancel Now</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @endforeach
    @foreach ($orders as $order)
        <div class="common-popup medium-popup modal fade" id="orderData{{ $order->id }}" tabindex="-1"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Order Products</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table allOrderData table-bordered">
                                <thead>
                                    <th>
                                        S.N.
                                    </th>
                                    <th>
                                        Image
                                    </th>
                                    <th>
                                        Name
                                    </th>
                                    <th>
                                        Quantity
                                    </th>
                                    <th>
                                        Price/Pcs
                                    </th>
                                </thead>
                                <tbody>
                                    @foreach ($order->orderAssets as $key => $asset)
                                        @php
                                            $key = $key + 1;
                                        @endphp
                                        <tr>
                                            <td>
                                                {{ $key }}
                                            </td>
                                            <td>
                                                @if ($asset->image ==null)
                                                <a href="{{route('product.details',$asset->product->slug ?? '')}}" target="_blank"><img src="{{ productImage($asset) }}" alt="Img"></a>
                                                @else
                                                <a href="{{route('product.details',$asset->product->slug ?? '')}}" target="_blank"><img src="{{ @$asset->image }}" alt="Img"></a>
                                                @endif
                                                

                                            </td>
                                            <td>
                                                {{ @$asset->product_name }}
                                            </td>
                                            <td>
                                                {{ @$asset->qty }}
                                            </td>
                                            <td> {{ formattedNepaliNumber(@$asset->sub_total_price) }} </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <h6 style="display:inline-block;">Shipping Charge Rs : {{ formattedNepaliNumber(@$order->shipping_charge) }}</h6>
                        <a style="float:right;text-decoration:none;" class="btn btn-sm btn-orange"
                            href="{{ route('order.productDetails', @$order->id) }}">View</a>
                        @if (@$order->coupon_discount_price > 0)
                            <h6>Discount Rs : {{ formattedNepaliNumber(@$order->coupon_discount_price) }}</h6>
                        @endif
                        <br>
                        <h6 style="display:inline-block;">Total Rs : {{ formattedNepaliNumber(@$order->total_price) }}</h6>

                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <div class="modal fade" id="reasonModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Reason</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="reasonBody">
              ...
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Reject Reason</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="rejectBody">
              ...
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="rejectDirectRefund" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Reject Reason</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="rejectBodyss">
              ...
            </div>
          </div>
        </div>
    </div>
    
@endsection






@push('script')

<script>
    $(document).on('click','#rejectedData',function()
    {
        var reason=$(this).attr('data-reason');
        
        $('#rejectBodyss').empty();
        $('#rejectBodyss').text(reason)
        $('#rejectDirectRefund').modal('show');
    });
</script>
<script>
    $('.refund').on('click',function()
    {
        var paramElem=$(this).parents('.refund-form');
        var value=$(this).val();
            if(value==='esewa')
            {
                $('.esewa-field').html('<label for="">UserName</label><input type="text" required placeholder="Enter Esewa Name Here....." name="name" class="form-control">@error("name"){{$message}}@enderror<label for="">Esewa Id</label><input type="text" placeholder="Enter Esewa Id Name Here....." required name="wallet_id" class="form-control">@error("wallet_id"){{$message}}@enderror<label for="contact_no">Contact Number</label><input type="number" name="contact_no" id="contact_no" required class="form-control">@error("contact_no"){{$message}}@enderror');
                $('.khalti-field').empty();
                $('.bank-field').empty();
            }
            else if(value==='khalti')
            {
                $('.khalti-field').html('<label for="">UserName</label><input type="text" required placeholder="Enter Khalti Name Here....." name="name" class="form-control">@error("name"){{$message}}@enderror<label for="">Khalti Id</label><input type="text" placeholder="Enter Khalti Name Here....." required name="wallet_id" class="form-control">@error("wallet_id"){{$message}}@enderror<label for="contact_no">Contact Number</label><input type="number" name="contact_no" id="contact_no" required class="form-control">@error("contact_no"){{$message}}@enderror');
                $('.esewa-field').empty();
                $('.bank-field').empty();
            }
            else if(value==='bank')
            {
                $('.bank-field').html('<label for="name">Account Holder Name</label><input type="text" placeholder="Enter Account Name Here....." name="name" id="name" required class="form-control">@error("name"){{$message}}@enderror<label for="payment_method">Bank Name</label><input type="text" placeholder="Enter Bank Name Here....." name="payment_method" required id="payment_method" class="form-control">@error("payment_method"){{$message}}@enderror<label for="branch">Branch</label><input type="text" placeholder="Enter Branch  Here....." name="branch" id="branch" required class="form-control">@error("branch"){{$message}}@enderror<label for="acc_no">Account Number</label><input type="text" placeholder="Enter Valid Account Number....." name="acc_no" id="acc_no" required class="form-control">@error("acc_no"){{$message}}@enderror<label for="contact_no">Contact Number</label><input type="number" name="contact_no" id="contact_no" required class="form-control">@error("contact_no"){{$message}}@enderror<label for="account_type">Account Type</label><select name="account_type" id="account_type" required class="form-control"><option value="" selected>-------Choose Account Type---------</option><option value="current">Current Account</option><option value="saving">Saving Account</option></select>@error("account_type"){{$message}}@enderror');
                $('.esewa-field').empty();
                $('.khalti-field').empty();
            }
            else
            {
                $('.esewa-field').empty();
                $('.khalti-field').empty();
                $('.bank-field').empty();
            }

    });
</script>

<script>
    $(document).ready(function()
    {
        $(document).on('click','.cancellReason',function()
        {
            $('#reasonBody').html('');
            var reasonId=$(this).data('reasonid');
            $.ajax({
                url:"{{route('getReason')}}",
                type:"get",
                data:{
                    reasonId:reasonId
                },
                success:function(response)
                {
                    if(response.error)
                    {
                        $('#reasonBody').html('');
                        return false;
                    }
                    $('#reasonBody').html(response.data);
                }
            });
        });

        $(document).on('click','.rejectReason',function()
        {
            $('#rejectBody').html('');
            var rejectId=$(this).data('rejectid');
            $.ajax({
                url:"{{route('getReject')}}",
                type:"get",
                data:{
                    rejectId:rejectId
                },
                success:function(response)
                {
                    if(response.error)
                    {
                        $('#rejectBody').html('');
                        return false;
                    }
                    $('#rejectBody').html(response.data);
                }
            });
        });
    })
</script>
    {{-- <script>
    $(document).ready(function(){
        $('.view').on('click',function(e){
            e.preventDefault();
            var ref_id = $(this).data('id');

            $.ajax({
                url: "{{route('allOrderProduct')}}",
                type: 'get',
                data: {
                    ref_id: ref_id,
                },
                success:function(response)
                {
                    $('.allOrderData').replaceWith(response);
                },
                error: function(response) {

                }
            });
        });
    });
</script> --}}
@endpush
