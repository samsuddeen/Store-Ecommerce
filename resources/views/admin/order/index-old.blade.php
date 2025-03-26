@extends('layouts.app')
@section('content')
    <!-- order start -->
    <section class="app-user-list">       
        <div class="card">
            <div class="card-body border-bottom">
                <div class="card-datatable table-responsive pt-0">
                        <input type="checkbox" id="check-all" > Check All
                        <select name="action"  style="float:right" class="action">
                            <option>Select option </option>
                            <option value="delete">Delete</option>
                            <option value="approve">Approve</option>
                        </select>
                        <a href="javascript:;" style="float:right" class="apply">apply</a>
                    <table class="orde-list-table table">
                        <thead class="table-order text-capitalize">
                            <tr>
                                <th></th>
                                <th>S.N</th>
                                <th>ref_id</th>
                                <th>total_price</th>
                                <th>total_quantity</th>
                                <th>coupon_name</th>
                                <th>payment_status</th>
                                <th>
                                    approve
                                </th>
                                <th>Status</th>
                                <th>Action_Changes</th>
                                <th>action</th>
                            </tr>
                        </thead>
                        <tbody class="text-capitalize">
                            @foreach($orders as $key=>$order)
                     
                                <tr>
                                    <td> <input type="checkbox" class="check-to-all" value="{{$order->id}}" name="order"></td>
                                    <td>{{$key+1}}</td>
                                    <td>{{$order->ref_id}}</td>
                                    <td>{{$order->total_price}}</td>
                                    <td>{{$order->total_quantity}}</td>
                                    <td>{{$order->coupon_name}}</td>
                                    <td>{{$order->payment_status}}</td>
                                    <td>{{$order->admin_approve == 1 ? 'approved' : "not approved" }}</td>
                                    <td>{{$order->status == 1 ? "Seen" : "Pending"}}</td>

                                    <td>
                                        <a href="{{route('admin.viewOrder',$order->ref_id)}}" id="viewme">View</a><br>
                                        <a href="{{route('admin.cancelled',$order->ref_id)}}">Cancel Order</a><br>
                                        <a href="{{route('admin.readytoship',$order->ref_id)}}">Ready Shipping</a><br>
                                        <a href="{{route('admin.shipped',$order->ref_id)}}">Shipped</a><br>
                                        {{-- <a href="{{route('admin.delivered',$order->ref_id)}}">delivered</a><br> --}}
                                    </td>

                                    <td><div class="btn-group"><a href="javascript:;" class="btn btn-sm btn-primary me-1" title="view all order" data-bs-toggle="modal" data-id="{{$order->ref_id}}" data-bs-target="#orderData{{$order->id}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                        </a>    <div class="disabled-backdrop-ex">
                                            <!-- Button trigger modal -->
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#backdropId-{{$order->id}}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                            </button>
                                            <!-- Modal -->
                                            <div class="modal fade text-start" id="backdropId-{{$order->id}}" tabindex="-1" aria-labelledby="myModalLabel4" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                <form action="{{route('order.destroy',$order->id)}}" method="POST">
                                                    @csrf
                                                    @method("delete")
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title" id="myModalLabel4">Delete Record</h4>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>
                                                               This action is irreversiable. Are you sure??
                                                            </p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="reset" class="btn btn-warning" data-bs-dismiss="modal">cancel</button>
                                                            <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">delete</button>
                                                        </div>
                                                    </div>
                                                    </form>                                       
                                                </div>
                                            </div>



                                            <!-- model-->
                                <div class="modal fade" id="orderData{{$order->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" style="max-width:900px;">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        <h3 class="modal-title" id="exampleModalLabel">Ordered Details</h3>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <table class="table allOrderData">
                                
                                            </table>
                                            <h4 style="color:brown">Other Details</h4>
                                            <b>User Name</b> : {{$order->user->name}} <br><br>
                                            <b>Shipping Address</b> : {{$order->province}}/{{$order->district}}/{{$order->area}}  <br><br>
                                            <b>Billing Address</b> : {{$order->b_province}}/{{$order->b_district}}/{{$order->b_area}} <br><br>    
                                            <b>Seller Approve</b> : {{$order->seller_approve == '1' ? 'yes' : 'no'}}  <br><br>     
                                        </div>
                                        
                                        <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                        </div>
                                    </div>
                                </tr>                              

                            @endforeach                          
                        </tbody>
                        
                    </table>
                   
                </div>
                {{ $orders->links() }}
            </div>
            <div>
            </div>
        </div>


        

        <!-- list and filter end -->
    </section>
    <!-- brand ends -->
@endsection
@push('style')commit
    @include('admin.includes.datatables')
@endpush
@push('script')
{{-- <script>
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var url ="{{url('status', @$orders[0]['id'])}}";
    $('#viewme').on('click',function(){
        if(confirm('Are You Sure')){
        $('#data-form').submit();
        }

    });
  
    </script> --}}


    {{-- <script src="{{ asset('admin/order-list.js') }}" defer></script> --}}

    <script>
        $(document).ready(function(){
            $('.me-1').on('click',function(e){
                e.preventDefault();
                var ref_id = $(this).data('id');
                $.ajax({
                    url: "{{route('productDetail')}}",
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
    </script>
    <script>
        $('#check-all').on('click',function(e){
            if(this.checked){
                $('.check-to-all').each(function(){
                    this.checked = true;
                });
            }else{
                $('.check-to-all').each(function(){
                    this.checked = false;
                });
            }
        });
    </script>
    <script>
       
        $('.apply').on('click',function(){
            var array = [];
            var checkboxes = document.querySelectorAll('input[type=checkbox][name=order]:checked');

            for (var i = 0; i < checkboxes.length; i++) {
                array.push(checkboxes[i].value);
            }
            var action = $('.action') .val();
            $.ajax({     
                    url: "{{route('apply')}}",
                    type: "get",
                    data: {
                        order_ids: array,
                        action: action,
                    },
                    success: function (response) {
                        window.location = "{{route('order.index')}}";
                    },
                });
        });

       


    </script>
@endpush
