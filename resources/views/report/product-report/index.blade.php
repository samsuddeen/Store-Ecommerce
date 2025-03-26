@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Product Report')
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
                        <form action="{{route('product-report.index')}}" id="filter-form" method="get">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="sellers">Seller:</label>
                                        <select name="seller" id="seller" class="form-control form-control-sm">
                                            <option value="">Please Select</option>
                                            @foreach ($seller as $index => $sellers)
                                                <option value="{{ $sellers->id }}" data-type="{{ $sellers['name'] }}">
                                                    {{ $sellers->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <button class="btn btn-primary btn-sm mt-2" type="submit" id="filter-btn"
                                            type="button">Filter</button>
                                    </div>
                                </div>

                                {{-- <div class="card mt">
                                    <div class="card-body mid-body">
                                        <div class="btn-bulk">
                                            <a href="{{ route('productreportexcel') }}" class="global-btn pdfbilled"
                                                style="margin-left:5px;">Export
                                                (CSV)</a>
                                        </div>
                                    </div>
                                </div> --}}

                            </div>
                        </form>
                    </div>

                    <div class="card-body border-bottom">
                        <div class="card-datatable table-responsive pt-0">
                            <table class="orde-list-table table data-table">
                                <thead class="table-light text-capitalize">
                                    <tr>
                                        <th>S.N</th>
                                        <th>Product Name</th>
                                        <th>Category</th>
                                        <th>Stock</th>
                                        <th>publish</th>
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
                url: "{{ route('product-report.index') }}",
                data: function(d) {
                    @foreach ($filters as $index => $value)
                        d.{{ $index }} = "{{ $value }}",
                    @endforeach

                    d.seller = $("#seller").val()
                    // d.year =  $("#year").val().toLowerCase(),
                    // d.month = $("#month").val().toLowerCase(),
                },
            },
            columns: [{
                    data: "DT_RowIndex",
                    render: (data, type) => `${data}`,
                    searchable: false,
                    orderable: false,
                },
                {
                    data: "name_element"
                },
                {
                    data: "category"
                },
                {
                    data: 'stock'
                },
                {
                    data: 'publish'
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

        $(document).ready(function() {
            $('#filter-btn').on('click', function(e) {
                // alert();
                table.draw();

            });
        });
    </script>
@endpush
