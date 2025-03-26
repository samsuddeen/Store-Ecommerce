@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Seller | Review')
@push('style')
<style>
    td img{
        height: 85px !important;
        display: block;
    }
</style>
@endpush
@section('content')
    <section id="default-breadcrumb">
        <div class="row" id="table-bordered">
            <div class="col-12">
                <!-- list and filter start -->
                <div class="card">

                    <div class="card-body border-bottom">
                        <div class="card-datatable table-responsive pt-0">
                            <table class="orde-list-table table data-table">
                                <thead class="table-light text-capitalize">
                                    <tr>
                                        <th>Id</th>
                                        <th>Product Name</th>
                                         <th>Product Image</th>
                                         <th>Recieved On</th>
                                        <th>action</th> 
                                    </tr>
                                </thead>
                                <tbody class="text-capitalize">

                                </tbody>
                            </table>
                        </div>
                    </div>


                </div>
                <!-- list and filter end -->
            </div>
        </div>
    </section>

   @foreach ($reviews as $key=>$data)
    <div class="modal fade seller-modal" id="staticBackdrop{{$data->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalScrollableTitle">Review Chat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @foreach (@$data->getReview as $msg)
                        @if($msg->parent_id !=null)
                            <p class="text-primary"  style="float:right">{{$msg->message}}</p>
                        @else
                            <p class="text-success">{{$msg->message}}</p>
                        @endif
                        
                        <br>
                    @endforeach
                </div>
                <form action="javascript:;" method="post" id="send-review{{$data->id}}">
                    @csrf
                    <input type="hidden" name="review_id" value="{{$data->id}}">
                    <input type="text" class="form-control form-control-sm " style="margin-left:30px;width:80%" name="message" placeholder="type Message Here....">
                
                    <div class="modal-footer">
                    
                    <button type="reset" class="btn btn-danger">Reset</button>
                    <button type="submit" class="btn btn-success send-review-btn" data-id="{{$data->id}}">Send</button>
                    
                    </div>
                </form>
            </div>
        </div>
    </div>
   @endforeach

@endsection
@push('style')
    @include('admin.includes.datatables')
@endpush
@push('script')
<script src="{{ asset('backend/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>   
<script src="{{ asset('frontend/js/sweetalert.min.js') }}"></script>
<script type="text/javascript">
   
</script>
<script type="text/javascript">
   $(function() {

        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('seller-review.view') }}",
            columns: [{
                    data: 'id',
                    name: 'id'
            },
            {
                data:'product_name',
                name:'product_name'
            },
            {
                data:'product_image',
                name:'product_image'
            },
            {
                data:'recieved_on',
                name:'recieved_on'
            },
            {
                data:'action',
                name:'action'
            },
            ]
        });

    });
</script>



<script>
    $(document).on('click','.send-review-btn',function()
    {
        $('.seller-modal').hide('modal');
        var id=$(this).data('id');
        var form=document.getElementById('send-review'+id);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url:"{{ route('seller-send.review')}}",
            type:"post",
            data:{
                review_id:form['review_id'].value,
                message:form['message'].value
            },
            success:function(response)
            {
                if(response.validate)
                {
                    $.each(response.msg,function(index,value)
                    {
                        alert(value);
                        
                    });
                    return false;
                }

                if(response.error)
                {
                    alert(response.msg);
                    return false;
                }

                swal({
                    title: "Thank You!",
                    text: response.msg,
                    icon: "success",
                });

                location.reload();
            }
        });
        
    })
</script>
@endpush
