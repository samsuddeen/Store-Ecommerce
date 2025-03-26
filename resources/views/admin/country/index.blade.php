@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'country')
@section('content')
    <!-- brand start -->
    <section class="app-user-list">

        <div class="card">
            <x-cardHeader :href="route('countries.create')" name="color" />
            <div class="card-body border-bottom">
                <div class="card-datatable table-responsive pt-0">
                    <table class="country-list-table table">
                        <thead class="table-light text-capitalize">
                            <tr>
                                <th>S.N</th>
                                <th>Name</th>
                                <th>Zip Code</th>
                                <th>Flag</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-capitalize">
                            {{-- @foreach ($countries as $key=>$data)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$data->name}}</td>
                                    <td>{{$data->country_zip}}</td>
                                    <td>
                                        <img width="20px" height="20px" src="{{$data->flags}}" alt="">
                                    </td>
                                    <td>{{$data->status}}</td>
                                    <td>
                                        <a href="" class=""></a>
                                    </td>
                                </tr>
                            @endforeach --}}
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
    <script src="{{ asset('admin/country-list.js') }}" defer></script>
    <script>
        $(function() {
            ("use strict");
            var dtBrandTable = $(".country-list-table");
            dtBrandTable.DataTable({
                processing: true,
                serverSide: true,
                ajax: "/datatables/country_data",
                columns: [{
                        data: "DT_RowIndex",
                        render: (data, type) => `${data}`,
                        searchable: false,
                        orderable: false,
                    },
                    { data: "name" },
                    { data: "zipcode" },
                    {data:"flag",
                        render: (data, type) => `<img width="20px" height="20px" src="${data}"></img>`,},
                    {data:"status"},
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
                        height: 14,
                    });
                },
            });
        });

    </script>
@endpush
