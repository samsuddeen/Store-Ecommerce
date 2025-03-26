@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Payment Histories')
@section('content')
    <section class="app-user-list">
        <div class="card">
            <div class="card-body border-bottom">
                <div class="card-datatable table-responsive pt-0">
                    <div class="form mb-2">
                        <form action="#" method="get">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Year:</label>
                                        <select name="year" id="year" class="form-control form-control-sm">
                                            <option value="">Select</option>
                                            @foreach($years as $year)
                                             <option value="{{$year}}">{{$year}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
    
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Month:</label>
                                        <select name="month" id="month" class="form-control form-control-sm">
                                            <option value="">Select</option>
                                            @foreach($months as $month)
                                             <option value="{{$month['value']}}">{{$month['title']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
    
    
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Status:</label>
                                        <select name="status" id="status" class="form-control form-control-sm">
                                            <option value="">Select</option>
                                            <option value="0">Paid</option>
                                            <option value="1">Received</option>
                                       </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-primary btn-sm mt-2 filter">Filter</button>
                                    </div>
                                </div>
                                
                            </div>
                        </form>
                    </div>
                    <table class="orde-list-table table data-table">
                        <thead class="table-light text-capitalize">
                            <tr>
                                <th>SN.</th>
                                <th>Date</th>
                                <th>Paid By</th>
                                <th>Received By</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Method</th>
                                {{-- <th>Action</th> --}}
                            </tr>
                        </thead>
                        <tbody class="text-capitalize">

                        </tbody>
                    </table>
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
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                
                ajax: {
                    url: '/datatables/payment-histories',
                    data: function (d) {
                        d.year =  $("#year").val().toLowerCase(),
                        d.month = $("#month").val().toLowerCase(),
                        d.status = $("#status").val().toLowerCase()
                    },
                },

                columns: [{
                        data: "DT_RowIndex",
                        render: (data, type) => `${data}`,
                        searchable: false,
                        orderable: false,
                    },
                    {
                        data: "date"
                    },
                    {
                        data: "paid_by"
                    },
                    {
                        data: "received_by"
                    },
                    {
                        data: "amount"
                    },
                    {
                        data: "status"
                    },
                    {
                        data: "method"
                    },
                    // {
                    //     data: "action",
                    //     render: (data, type) => `<div class="btn-group">${data}</div>`,
                    // },
                ],
                fnDrawCallback: function(oSettings) {
                    feather.replace({
                        width: 14,
                        height: 14,
                    });
                },
            });

            $(document).ready(function(){
                $('.filter').on('click', function(){
                    table.draw();
                });
            });
    </script>
@endpush
