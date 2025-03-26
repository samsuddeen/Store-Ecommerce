@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Districts')
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
                ajax: "{{ route('districts.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'province',
                        name: 'province'
                    },
                    {
                        data: 'district',
                        name: 'district'
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
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            $(document).ready(function() {
                $('.change-status').click(function() {
                    var status;
                    var parent = $(this);
                    let id = $(this).data('id');

                    $.ajax({
                        url: "{{ route('district.change.status') }}",
                        type: 'post',
                        data: {
                            district_id: id,
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
