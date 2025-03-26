@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Customers')
@section('content')

    <!-- brand start -->
    <section class="app-user-list">

        <div class="card">
            <x-cardHeader :href="route('customer.create')" name="customer" />
            <div class="card-body border-bottom">
                <div class="card-datatable table-responsive pt-0">
                    <table class="customer-list-table table">
                        <thead class="table-light text-capitalize">
                            <tr>
                                <th></th>
                                <th>name</th>
                                <th>email</th>
                                <th>phone</th>
                                <th>status</th>
                                <th>Type</th>
                                <th>address</th>
                                <th>created_at</th>
                                <th>action</th>
                            </tr>
                        </thead>
                        <tbody class="">

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
                    <h1 class="text-center mb-1" id="addNewCardTitle">Are You Sure </h1>
                    <p class="text-center">Please Confirm</p>
                    <form class="row gy-1 gx-2 mt-75" method="post" action="{{ route('customer.action.status') }}">
                        @csrf
                        @method('PATCH')
                        <div class="col-12" style="display: none" id="reason-box">
                            <label class="form-label" for="modalAddCardNumber">Reason/Remarks <span
                                    class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <textarea name="remarks" id="remarks" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <input type="text" name="status" id="status-input" hidden>
                        <input type="text" name="customer_id" id="customer_id-input" hidden>
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
        function reloadAction() {
            $('.order-action').on('click', function() {
                $('#customer_id-input').val($(this).data('order_id'));
                $('#status-input').val($(this).data('type'));
            });
        }
    </script>
    {{-- <script src="{{ asset('admin/customer-list.js') }}" defer></script> --}}
    <script>
        $(function() {
            ("use strict");
            var dtBrandTable = $(".customer-list-table");
            dtBrandTable.DataTable({
                processing: true,
                serverSide: true,
                // ajax: "/datatables/customers",
                ajax: {
                    url: '/datatables/customers',
                    data: function (d) {
                        @foreach($filters as $index=>$filter)
                        d.{{$index}} = '{{$filter}}';
                        @endforeach
                    },
                },
                columns: [
                    {
                        data: "DT_RowIndex",
                        render: (data, type) => `${data}`,
                        searchable: false,
                        orderable: false,
                    },
                    // columns according to JSON
                    
                    {
                        data: "name"
                    },
                    {
                        data: "email"
                    },
                    {
                        data: "phone"
                    },
                    {
                        data: "status",

                    },
                    {
                        data: "type",

                    },
                    {
                        data: "address",
                    },
                    {
                        data: "created_at"
                    },
                    {
                        data: "action",
                        render: (data, type) => `<div class="btn-group">${data}</div>`,
                    },
                ],
                columnDefs: [{
                    // For Responsive
                    className: "control",
                    orderable: false,
                    responsivePriority: 2,
                    targets: 0,
                    render: function(data, type, full , meta) {
                        return "";
                    },
                }, ],
                fnDrawCallback: function(oSettings) {
                    feather.replace({
                        width: 14,
                        height: 14,
                    });
                    reloadAction();
                },
            });
        });
    </script>
    <script src="{{ asset('admin/togglestatus.js') }}" defer></script>
@endpush
