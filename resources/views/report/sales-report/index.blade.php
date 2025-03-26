@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Sales Report')
@push('style')
    <style>
        td img {
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

                    <div class="row me-2 ms-2">
                        <form action="#" id="filter-form" method="get">
                            <div class="row">
                                {{-- <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="Areas">Area:</label>
                                        <select name="area" id="type" class="form-control form-control-sm">
                                            <option value="">Please Select</option>
                                            @foreach($area as $areas)
                                            <option value="{{$areas->area}}" data-type="{{ $areas['area'] }}">{{$areas->area}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> --}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="users">Year:</label>
                                        <select name="year" id="year" class="form-control form-control-sm">
                                            <option value="">Please Select</option>
                                            @foreach ($years as $year)
                                                <option value="{{ $year }}" data-type="{{ $year }}" {{(@$filters['year'] == $year) ? 'selected' : ''}}>
                                                    {{ $year }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="users">Month:</label>
                                        <select name="month" id="month" class="form-control form-control-sm">
                                            <option value="">Please Select</option>
                                            @foreach ($months as $index => $month)
                                                <option value="{{ $month['value'] }}" data-type="{{ $month['value'] }}" {{(@$filters['month'] == $month['value']) ? 'selected' : ''}}>
                                                    {{ $month['title'] }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
        
        
        
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <button class="btn btn-primary btn-sm mt-2" id="filter-btn" type="button">Filter</button>
                                    </div>
                                </div>
        
                            </div>
                        </form>
                    </div>
            
                    {{-- <div class="card mt">
                        <div class="card-body mid-body">
                    <div class="btn-bulk">
                        <a href="{{route('salesreportexcel')}}" class="global-btn pdfbilled" style="margin-left:5px;">Export
                            (CSV)</a>
                    </div>
                        </div>
                         </div> --}}
              
                    

                    
                    <div class="card-body border-bottom">
                        <div class="card-datatable table-responsive pt-0">
                            <table class="orde-list-table table data-table">
                                <thead class="table-light text-capitalize">
                                    <tr>
                                        <th>S.N</th> 
                                        <th>Date</th>
                                        <th>Transaction No.</th>
                                        <th>ref id</th>
                                        <th>Customer</th>
                                        <th>quantity</th>
                                        <th>Discount</th>
                                        <th>total</th>
                                        {{-- <th>action</th> --}}
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


    <!-- Modal -->
    <div class="modal " id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('style')
    @include('admin.includes.datatables')
@endpush
@push('script')
    <script src="{{ asset('backend/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>

<script>
        var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('sales-report.index') }}",
            data:function(d){
                @foreach($filters as $index=>$filter)
                d.{{ $index }} = "{{ $filter }}",
                @endforeach
                d.year =  $("#year").val().toLowerCase(),
                d.month = $("#month").val().toLowerCase(),
                d.area = $("#type").val()
            }
        },
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
        },
    });

    
    $(document).ready(function(){
            $('#filter-btn').on('click', function(e){
            table.draw();
             
            });
        });

        
</script>



@endpush