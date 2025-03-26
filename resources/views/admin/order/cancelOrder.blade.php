@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Return Order')
@section('content')
    <section id="default-breadcrumb">
        <div class="row" id="table-bordered">
            <div class="col-12">
                <!-- list and filter start -->
                <div class="card">
                    <div class="card-header d-flex justify-space-between">
                        <h4 class="card-title text-capitalize">Cancel Order</h4>
                    </div>
                    <div class="card-body border-bottom">
                        <div class="card-datatable table-responsive pt-0">
                            <table class="return-list-table table">
                                <thead class="table-light text-capitalize">
                                    <tr>
                                        <th>S.N</th>
                                        <th>Customer</th>
                                        <th>Reason</th>
                                        <th>Action</th>
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
@endsection
@push('style')
    @include('admin.includes.datatables')
@endpush
@push('script')
    <script>
        $(function() {
            ("use strict");
            var dtReturnTable = $(".return-list-table");
            dtReturnTable.DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                url: '{{route("cancelOrderRequest")}}',
                data: function(d) {
                        @foreach ($filters as $index => $value)
                                @if(!$loop->last)
                                        d.{{ $index }} = "{{ $value }}",
                                    @else
                                        d.{{ $index }} = "{{ $value }}"
                                @endif
                        @endforeach
                    },
                },
                columns: [{
                        data: "DT_RowIndex",
                        render: (data, type) => `${data}`,
                        searchable: false,
                        orderable: false,
                    },
                    {
                        data: "customer"
                    },
                    {
                        data: "reason"
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
                    render: function(data, type, full, meta) {
                        return "";
                    },
                }, ],
                fnDrawCallback: function(oSettings) {
                    feather.replace({
                        width: 14,
                        height: 14
                    });
                    reloadStatusAction();
                },
            });
        });
        function reloadStatusAction(){
            $('.order-action').on('click', function(e){
                let return_id = $(this).data('returned_id');
                let action_type = $(this).data('type');
                $('#return_id-input').val(return_id);
                $('#type-input').val(action_type);
                if(action_type == "REJECTED"){
                    $('#remarks-block').css({"display":"block"});
                }else{
                    $('#remarks-block').css({"display":"none"});
                }
            });
        }
    </script>
@endpush
