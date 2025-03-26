@extends('frontend.layouts.app')
@section('title','Order Return')
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
                        <div class="dashboard_content table_wrapper">
                            <div class="dashboard-tables-head">
                                <h3>Returning Product</h3>
                            </div>
                            <div class="wishlist_table completed_order_table">
                                <div class="table-responsive">
                                    <table class="table-bordered"> 
                                        <thead>
                                            <tr>
                                                <th style="white-space: nowrap;">First Image</th>
                                                <th>Product Name</th>
                                                <th>Quantity</th>
                                                <th>Price/Pcs</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($returningProducts as $returningData)
                                            {{-- @dd($returningData) --}}
                                            @if($returningData->getproduct && $returningData->getproduct->slug !=null)   
                                                <tr>   
                                                    <td>    
                                                                                                 
                                                        <a href="{{route('product.details', @$returningData->getproduct->slug)}}">                                                    
                                                            <img src="{{completedOrderImage($returningData->getproduct->images)}}" alt="Img">  
                                                        </a>  
                                                                                                 
                                                    </td>                                     
                                                    <td>
                                                        {{$returningData->getproduct->name}}                                           
                                                    </td>
                                                    <td>
                                                        {{$returningData->qty}}                                          
                                                    </td>
                                                    <td>
                                                        Rs. {{$returningData->amount}}/Pcs                                                
                                                    </td>
                                                    <td>
                                                        <div class="more-btns">
                                                            @if ((int)$returningData->status === 1)
                                                            <span class="btns btn-pending">Pending</span>
                                                            @elseif ((int)$returningData->status === 2)
                                                            <span class="btn btn-approved">Approved</span>
                                                            @elseif ((int)$returningData->status === 3)
                                                            <span class="btn btn-returned">Returned</span>
                                                            @elseif ((int)$returningData->status === 4)
                                                            <span class="btn btn-rejected rejectedOrder" data-id="{{$returningData->id}}" data-bs-toggle="modal"
                                                                data-bs-target="#exampleModalrejectedRequest">Rejected</span>
                                                            @elseif ((int)$returningData->status === 5)
                                                            <span class="btn btn-received">Received</span>
                                                            @endif

                                                            @if((int)$returningData->status === 5 && $returningData->refundData===null)
                                                            <button type="button" class="btn btn-refund"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#exampleModal{{$returningData->id}}">Apply Refund
                                                            </button>
                                                            @elseif((int)$returningData->status === 5 && $returningData->refundData!=null && $returningData->refundData->status==='1')
                                                            <button type="button" class="btn btn-pending">Refund Pending
                                                            </button>
                                                            @elseif((int)$returningData->status === 5 && $returningData->refundData!=null && $returningData->refundData->status==='2')
                                                            <button type="button" class="btn btn-paid">Refund Paid
                                                            </button>
                                                            @elseif((int)$returningData->status === 5 && $returningData->refundData!=null && $returningData->refundData->status==='3')
                                                                {{-- @dd($returningData->refundData->orderStatus->remarks) --}}
                                                            <button type="button" class="btn btn-rejected rejectedMessage" data-id="{{$returningData->id}}" data-bs-toggle="modal"
                                                            data-bs-target="#exampleModalrejectedmessage">Refund Rejected
                                                            </button>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                    {!!$returningProducts->links()!!}
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
    @foreach ($returningProducts as $returningData)  
    <!-- Modal -->
        <div class="modal fade" id="exampleModal{{$returningData->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                <h5 class="modal-title text-center" id="exampleModalLabel">Apply For Refund</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                {{Form::open(['url'=>route('refund.request',$returningData->id),])}}
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
    @endforeach

    {{-- -----------------------------------------Refund--------------------------------------------------------- --}}


    {{-- ---------------------------------------------------Rejected Message----------------------- --}}
    <div class="modal fade" id="exampleModalrejectedmessage" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
            <h5 class="modal-title text-center" id="exampleModalLabel">Rejected Message</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="rejectBody">
            </div>
        </div>
        </div>
    </div>
    {{-- ---------------------------------------------------/Rejected Message----------------------- --}}

     {{-- ---------------------------------------------------Rejected Message----------------------- --}}
     <div class="modal fade" id="exampleModalrejectedRequest" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
            <h5 class="modal-title text-center" id="exampleModalLabel">Rejected Message</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="rejectOrderBody">
            </div>
        </div>
        </div>
    </div>
    {{-- ---------------------------------------------------/Rejected Message----------------------- --}}
@endsection
@push('script')
        <script>
            $(document).on('click','.rejectedOrder',function(){
                var orderId=$(this).data('id');
                $('#rejectOrderBody').text('');
                $.ajax({
                    url:"{{route('getreturnrejected')}}",
                    type:"get",
                    data:{
                        orderId:orderId
                    },
                    success:function(response)
                    {
                        $('#rejectOrderBody').text(response.data);
                    }
                });
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
            $(document).on('click','.rejectedMessage',function()
            {
                var returnId=$(this).data('id');
                $('#rejectBody').text('');
                $.ajax({
                    url:"{{route('getRejectedReason')}}",
                    type:"get",
                    data:{
                        returnId:returnId
                    },
                    success:function(response)
                    {
                        if(response.error)
                        {
                            return false;
                        }
                        $('#rejectBody').text(response.data);
                    }
                });
            })
        </script>
@endpush





