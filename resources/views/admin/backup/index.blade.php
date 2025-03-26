@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Backup')
@section('content')
    <!-- brand start -->
    <section class="app-user-list">

        <div class="card">

            <div class="card-header d-flex justify-space-between">
                <h4 class="card-title text-capitalize">Backup List</h4>
                <div class="div">
                    <a id="create_new" class="btn btn-primary" href="{{ route('backup.create') }}" role="button">
                        create New
                    </a>
                </div>
            </div>


            <div class="card-body border-bottom">
                <div class="card-datatable table-responsive pt-0">
                    <table class="tag-list-table table">
                        <thead class="table-light text-capitalize">
                            <tr>
                                <th>S.N</th>
                                <th>Date</th>
                                <th>File Name</th>
                                <th>Manual</th>
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

    <div class="modal fade" id="shareProject" tabindex="-1" aria-labelledby="shareProjectTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-sm-5 mx-50 pb-5">
                    <h1 class="text-center mb-1" id="addNewCardTitle">Are You Sure </h1>
                    <p class="text-center">Please Confirm</p>
                    <form class="row gy-1 gx-2 mt-75" method="post" action="{{ route('backup-period.status') }}">
                        @csrf
                        @method('PATCH')
                        <input type="text" name="status" id="status-input" hidden>
                        <input type="text" name="payout_setting_id" id="payment_id-input" hidden>
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
        reloadActionBtn();

        function reloadActionBtn() {
            $('.order-action').on('click', function(e) {
                e.preventDefault();
                let type = $(this).data('type');
                let order_id = $(this).data('order_id');
                $('#status-input').val(type);
                $('#payment_id-input').val(order_id);
            });
        }
    </script>
    <script>
        var dtBrandTable = $(".tag-list-table");
        let table = dtBrandTable.DataTable({
            processing: true,
            serverSide: true,
            ajax: "/datatables/backup",
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
                    data: "file_name"
                },
                {
                    data: "is_manual"
                },
                {
                    data: "action",
                    render: (data, type) => `<div class="btn-group">${data}</div>`,
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
                reloadActionBtn();
            },
        });


        $(document).on('click', '#create_new', function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('backup.store') }}",
                type: "post",
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                success: function(response) {
                    toastr.success('Successfully created');
                    console.log(response);
                    table.draw();
                    window.location = "/storage/backup/" + response.file_name;
                },
                error: function(error) {
                    toastr.error(error.responseText);
                    console.log(error);
                }
            });

        });
    </script>
@endpush
