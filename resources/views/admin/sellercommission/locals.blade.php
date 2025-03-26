@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Locals')
@section('content')
    <!-- brand start -->
    <section class="app-user-list">

        <div class="card">
            <div class="card-body border-bottom">
                <div class="card-datatable table-responsive pt-0">
                    <table class="vat-tax-list-table table data-table">
                        <thead class="table-light text-capitalize">
                            <tr>
                                <th>S.N</th>
                                <th>Province</th>
                                <th>District</th>
                                <th>Area</th>
                                <th>Publish</th>
                                <th>action</th>
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
    <script src="{{ asset('backend/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
@endpush

@push('script')
    <script>
        $(function() {
            var table = $('.data-table').DataTable({
                processing: true,
                stateSave: true,
                serverSide: true,
                ajax: "{{ route('locals.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'province',
                        name: 'province'
                    }, {
                        data: 'district',
                        name: 'district'
                    }, {
                        data: 'area',
                        name: 'area'
                    },
                    {
                        data: 'publish',
                        name: 'publish'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ],

                fnDrawCallback: function(oSettings) {
                    feather.replace({
                        width: 14,
                        height: 14
                    });
                    reloadActionStatus();
                },

            });
        });
    </script>


    <script>
        function reloadActionStatus() {
            $(document).ready(function() {
                $('.change-publishStatus').click(function() {
                    // var status;
                    var parent = $(this);
                    var local_id = $(this).data('id');

                    $.ajax({
                        url: "{{ route('local.change.status') }}",
                        type: "get",
                        data: {
                            local_id: local_id,
                        },

                        success: function(response) {
                            parent.text(response.data);
                        },
                        error: function(response) {}
                    });
                });
            });

        }
    </script>
@endpush
