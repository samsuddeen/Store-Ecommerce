@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Order')
@section('content')

    <section class="app-user-list">
        <div class="card">
            <x-cardHeader :href="route('order.index')" name="order">

            </x-cardHeader>
            <div class="card-body border-bottom">
                <div class="card-datatable table-responsive pt-0">
                    <table class="order-list-table table">
                        <thead class="table-light text-capitalize">
                            <tr>
                                <th>S.N</th>
                                <th>Status</th>
                                <th>Order By</th>
                                <th>ref id</th>
                                <th>quantity</th>
                                <th>Discoount</th>
                                <th>total</th>
                                <th>payment status</th>
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
                    <form class="row gy-1 gx-2 mt-75" method="post" action="{{route('order.status')}}">
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
@endsection
@push('style')
    @include('admin.includes.datatables')
@endpush
@push('script')
    <script>
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
    <script>
          $(function() {
            ("use strict");
            var dtBrandTable = $(".order-list-table");
            dtBrandTable.DataTable({
                processing: true,
                serverSide: true,
                ajax: "/datatables/inhouse-orders"+'{{$retrive_request}}',
                columns: [{
                        data: "DT_RowIndex",
                        render: (data, type) => `${data}`,
                        searchable: false,
                        orderable: false,
                    },
                    // { data: "all_action" },
                    {
                        data: "status"
                    },
                    {
                        data: "order_by"
                    },
                    {
                        data: "ref_id"
                    },
                    {
                        data: "total_quantity"
                    },
                    {
                        data: "total_discount"
                    },
                    {
                        data: "total_price"
                    },
                    {
                        data: "payment_status"
                    },
                    {
                        data: "action"
                    },
                ],
                columnDefs: [{
                    // For Responsive
                    className: "control",
                    orderable: false,
                    responsivePriority: 2,
                    targets: 0,
                    render: function(data, type, full, meta) {
                        return "";
                    },
                }, ],
                fnDrawCallback: function(oSettings) {
                    feather.replace({
                        width: 14,
                        height: 14,
                    });
                    reloadActionBtn();
                },
            });
        });
    </script>
@endpush
