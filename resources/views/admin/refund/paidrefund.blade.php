@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Refund')
@section('content')
    <section id="default-breadcrumb">
        <div class="row" id="table-bordered">
            <div class="col-12">
                <!-- list and filter start -->
                <div class="card">
                    <div class="card-header d-flex justify-space-between">
                        <h4 class="card-title text-capitalize">Refund Paid Request</h4>
                    </div>
                    <div class="card-body border-bottom">
                        <div class="card-datatable table-responsive pt-0">
                            <table class="return-list-table table">
                                <thead class="table-light text-capitalize">
                                    <tr>
                                        <th>S.N</th>
                                        <th>Status</th>
                                        <th>Customer</th>
                                        <th>Orders</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="text-capitalize">

                                </tbody>
                            </table>
                        </div>
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
                        <h1 class="text-center mb-1" id="addNewCardTitle">Are You Sure </h1>
                        <p class="text-center">Please Confirm</p>
                        <form class="row gy-1 gx-2 mt-75" method="post" action="{{route('refunddirect.status')}}">
                            @csrf
                            <input type="text" name="type" id="type-input" hidden>
                            <input type="text" name="refund_id" id="return_id-input" hidden>
                            <div class="col-md-12" style="display: none" id="remarks-block">
                                <div class="form-group">
                                    <label for="reason">Reson/Remarks <span class="text-danger">*</span>:</label>
                                    <textarea name="remarks" id="remarks" class="form-control" rows="4"></textarea>
                                </div>
                            </div>

                            <div class="col-md-12 payment-block" style="display: none">
                                <div class="form-group">
                                    <label for="payment-method">Payment Method <span class="text-danger">*</span>:</label>
                                    <select name="payment_method" id="payment-method" class="form-control">
                                        <option value="">Please Select</option>
                                        @foreach($payment_methods as $method)
                                            <option value="{{$method->id}}">{{$method->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 payment-block" style="display: none">
                                <div class="form-group">
                                    <label for="amount-to-paid">Amount <span class="text-danger">*</span>:</label>
                                    <input type="number" class="form-control" name="amount" id="amount-to-paid">
                                </div>
                            </div>

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
                url: '/datatables/refunds-paid',
                data: function(d) {
                    @foreach ($filters as $index => $value)
                        @if($loop->last)
                            d.{{ $index }} = "{{ $value }}"
                        @else
                            d.{{ $index }} = "{{ $value }}",
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
                        data: "status"
                    },
                    
                    {
                        data: "customer"
                    },

                    {
                        data: "order"
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
                let amount = $(this).data('amount');
                $('#return_id-input').val(return_id);
                $('#type-input').val(action_type);
                $('#amount-to-paid').val(amount);
                if(action_type == "REJECTED"){
                    $('#remarks-block').css({"display":"block"});
                    $('.payment-block').css({"display":"none"});
                }else{
                    $('#remarks-block').css({"display":"none"});
                    $('.payment-block').css({"display":"block"});
                }
            });
        }
    </script>
@endpush
