@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Transaction')
@section('content')
    <section class="app-user-list">
        <div class="card">
            <div class="card-header d-flex justify-space-between">
                <h4 class="card-title text-capitalize">Transaction</h4>
                <div class="div">
                </div>
            </div>
            <div class="card-body">
                <form action="#">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Year</label>
                                <input type="text" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Month</label>
                                <input type="text" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <button type="button" class="btn btn-primary mt-2">Filter</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body border-bottom">
                <div class="card-datatable table-responsive pt-0">
                    <table class="order-list-table table">
                        <thead class="table-light text-capitalize">
                            <tr>
                                <th>Date</th>
                                <th>S.N</th>
                                <th>Transaction No.</th>
                                <th>ref id</th>
                                <th>Customer</th>
                                <th>quantity</th>
                                <th>Discount</th>
                                <th>total</th>
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
                    <form class="row gy-1 gx-2 mt-75" method="post" action="{{ route('order.status') }}">
                        @csrf
                        @method('PATCH')
                        <div class="col-12" style="display: none" id="reason-box">
                            <label class="form-label" for="modalAddCardNumber">Reason/Remarks <span
                                    class="text-danger">*</span></label>
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
                if (type == "cancel" || type == "reject") {
                    $('#reason-box').css({
                        "display": "block"
                    });
                } else {
                    $('#reason-box').css({
                        "display": "none"
                    });
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
                ajax: "/datatables/transactions{{ $retrive_request }}",
                columns: [{
                        data: "DT_RowIndex",
                        render: (data, type) => `${data}`,
                        searchable: false,
                        orderable: false,
                    },
                    {
                        data: "delivery_date"
                    },
                    {
                        data: "transaction_no"
                    },
                    {
                        data: "ref_id"
                    },
                    {
                        data: "order_by"
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
