@extends('layouts.app')
@section('content')
    <!-- brand start -->
    <section class="app-user-list">

        <div class="card">
            <div class="card-header d-flex justify-space-between">
                <h4 class="card-title text-capitalize">Logs</h4>
            </div>
            <div class="row me-2 ms-2">
                <form action="#" id="filter-form" method="get">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="users">User Type:</label>
                                <select name="type" id="type" class="form-control form-control-sm">
                                    <option value="">Please Select</option>
                                    @foreach ($user_types as $type)
                                        <option value="{{ $type['model'] }}" data-type="{{ $type['model'] }}" {{(@$filters['type'] == $type['model']) ? 'selected' : ''}}>
                                            {{ $type['name'] }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
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
            <div class="card-body border-bottom">
                <div class="card-datatable table-responsive pt-0">
                    <table class="tag-list-table table">
                        <thead class="table-light text-capitalize">
                            <tr>
                                <th>S.N</th>
                                <th>Title</th>
                                <th>Url</th>
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
    </section>
    <!-- brand ends -->
@endsection
@push('style')
    @include('admin.includes.datatables')
@endpush
@push('script')
    <script>
        var table = $('.tag-list-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/datatables/logs',
                data: function(d) {
                    @foreach ($filters as $index => $value)
                            d.{{ $index }} = "{{ $value }}",
                    @endforeach
                    d.year =  $("#year").val().toLowerCase(),
                    d.month = $("#month").val().toLowerCase(),
                    d.type = $("#type").val()
                },
            },
            columns: [{
                    data: "DT_RowIndex",
                    render: (data, type) => `${data}`,
                    searchable: false,
                    orderable: false,
                },
                {
                    data: "title"
                },
                {
                    data: "action"
                },
                {
                    data: "url"
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
                // alert();
                table.draw();
            });
        });

            
    </script>
@endpush
