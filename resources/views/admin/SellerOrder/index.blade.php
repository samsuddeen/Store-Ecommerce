@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Seller | Order')
<style>
    .barcode-modal-col {
    display: flex;
    border: 2px solid #ebebeb;
    border-radius: 4px;
}

.barcode-modal-left {
    flex: 2;
    text-align: center;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.barcode-modal-right {
    flex: 3;
    border-left: 2px solid #ebebeb;
}

.barcode-modal-right ul {
    padding: 0;
    margin: 0;
    list-style: none;
}

.barcode-modal-left span {
    display: block;
    font-weight: 600;
    margin-top: 10px;
    font-size: 17px;
}

.barcode-modal-right ul li {
    font-size: 13px;
    font-weight: 600;
    text-transform: uppercase;
    padding: 8px 15px;
    text-align: center;
}

.barcode-modal-right ul li +li {
    border-top: 2px solid #ebebeb;
}
#barcode_modal .modal-body {
    padding: 20px;
}
#barcode_modal .modal-header{
    padding:10px 20px;
}
.barcode-modal-col +.barcode-modal-col{
    margin-top:20px;
}
.second-barcode-modal .barcode-modal-right ul li{
    text-align: left;
}
.barcode-modal-footer ul {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0;
    margin: 0;
}

.barcode-modal-footer ul li {
    list-style: none;
    font-weight: 600;
}

.barcode-modal-footer {
    margin-top: 15px;
}
</style>

@section('content')
    <section class="app-user-list">
        <div class="card">
            {{-- <x-cardHeader :href="route('seller-order-index')" name="order">

            </x-cardHeader> --}}
            <div class="card-body border-bottom">
                <div class="card-datatable table-responsive pt-0">
                    <table class="orde-list-table table data-table">
                        <thead class="table-light text-capitalize">
                            <tr>
                                <th>Status</th>
                                <th>Order By</th>
                                <th>Ref Id</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Payment Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-capitalize">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="shareProject" tabindex="-1" aria-labelledby="shareProjectTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-sm-5 mx-50 pb-5">
                    <h1 class="text-center mb-1" id="addNewCardTitle">Are You Sure....?</h1>
                    <p class="text-center">Please Confirm</p>
                    <form class="row gy-1 gx-2 mt-75" method="post"  action="{{route('seller-order-status.status')}}">
                        @csrf
                        @method("PATCH")
                        <div class="col-12" style="display: none" id="reason-box">
                            <label class="form-label" for="modalAddCardNumber">Reason/Remarks <span class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                               <textarea name="remarks" id="remarks" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <input type="text" name="type" id="type-input" hidden>
                        <input type="text" name="order_id" id="order_id-input" hidden>
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary me-1 mt-1 " >Submit</button>
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

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" id="barcode_modal">
       
        </div>
    </div>

    <div class="modal fade" id="reasonCancel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="reasonData">
              
            </div>
          </div>
        </div>
      </div>

@endsection
@push('style')
    {{-- @include('admin.includes.datatables') --}}
@endpush
@push('script')
    <script src="{{ asset('backend/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>   
    <script type="text/javascript">
        $(function() {
            reloadActionBtn();
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('seller-order-index') }}",
                columns: [
                    
                {
                    data:'status',
                    name:"status"
                },
                {
                        data: 'order_id',
                        name: 'order_id'
                    },
                    {
                        data:'ref_id',
                        name:'ref_id'
                    },
                    {
                        data:'qty_id',
                        name:'qty_id'
                    },
                    {
                        data:'total_price',
                        name:'total_price'
                    },
                    {
                        data:'payment_status',
                        name:'payment_status'
                    },
                    {
                        data:'action',
                        name:'action'
                    },
      
                ],
                fnDrawCallback: function(oSettings) {
                    feather.replace({
                        width: 14,
                        height: 14,
                    });
                    reloadActionBtn();
                },
            });

        });

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

    <script>
        $(document).on('click','.barcode_btn',function()
        {
            var order_id=$(this).attr('data-orderId');
            $.ajax({
                url:"{{ route('generateBarcode')}}",
                type:"get",
                data:{
                    seller_ordeId:parseInt(order_id)
                },
                success:function(response)
                {
                    $('#barcode_modal').replaceWith(response);
                    $('#exampleModal').modal('show');
                }
            });
        });
    </script>

<script>
    $(document).on('click','.cancelReason',function()
    {
        var reasonId=$(this).attr('data-orderId');
        $('#reasonData').html('');
            $.ajax({
                url:"{{route('sellergetReason')}}",
                type:"get",
                data:{
                    reasonId:reasonId
                },
                success:function(response)
                {
                    if(response.error)
                    {
                        $('#reasonData').html('');
                        return false;
                    }
                    $('#reasonData').html(response.data);
                }
            });
    });
</script>

@endpush
