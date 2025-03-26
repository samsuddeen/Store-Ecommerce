@extends('layouts.app')
@section('title', 'Comment')
@section('content')
    <section class="app-user-list">
        <div class="card">

            <div class="card-body border-bottom">
                <div class="card-datatable table-responsive pt-0">
                    <table class="order-list-table table">
                        <thead class="table-light text-capitalize">
                            <tr>
                                <th>S.N</th>
                                <th>Status</th>
                                <th>Product Name</th>
                                <th>Product Image</th>
                                <th>Received On</th>
                                <th>Action</th>
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
                    <h1 class="text-center mb-1" id="addNewCardTitle">Are You Sure....?</h1>
                    <p class="text-center">Please Confirm</p>
                    <form class="row gy-1 gx-2 mt-75" method="post" action="{{ route('comment.status') }}">
                        @csrf
                        @method('PATCH')
                        <div class="col-12" style="display: none" id="reason-box">
                            <label class="form-label" for="modalAddCardNumber">Reason/Remarks <span
                                    class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <textarea name="remarks" id="remarks" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <input type="text" name="type" id="type-input" hidden>
                        <input type="text" name="order_id" id="order_id-input" hidden>
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

    @foreach ($allcomment as $key => $data)
        <div class="modal fade seller-modal" id="viewComment{{ $data->id }}" aria-labelledby="exampleModalLabel"
            aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalScrollableTitle">Reply Comment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-primary">{{ $data->question_answer }}</p>

                        <br>
                        @if ($data->answer)
                            <p style="float:right">{{ $data->answer->question_answer }}</p>
                        @endif

                    </div>
                    <form action="javascript:;" method="post" id="send-comment{{ $data->id }}">
                        @csrf
                        <input type="hidden" name="comment_id" value="{{ $data->id }}">
                        <input type="text" class="form-control form-control-sm " style="margin-left:30px;width:80%"
                            name="message" placeholder="type Message Here....">

                        <div class="modal-footer">

                            <button type="reset" class="btn btn-danger">Reset</button>

                            @if ($data->answer)
                                <input type="hidden" name="seller_comment_id" value="{{ $data->answer->id }}">
                                <button type="submit" class="btn btn-success send-update-comment"
                                    data-id="{{ $data->id }}">Update</button>
                            @else
                                <button type="submit" class="btn btn-success send-comment-btn"
                                    data-id="{{ $data->id }}">Send</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
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
                if (type == "cancel" || type == "reject") {
                    $('#reason-box').css({
                        "display": "block"
                    });
                    // }else{
                    $('#reason-box').css({
                        "display": "none"
                    });
                }
                let order_id = $(this).data('order_id');
                $('#type-input').val(type);
                $('#order_id-input').val(order_id);
            });
        }
    </script>
    <script>
        $(function() {
            ("use strict");
            var dtBrandTable = $(".order-list-table");
            dtBrandTable.DataTable({
                processing: true,
                serverSide: true,
                ajax: "/datatables/allcomment",
                columns: [{
                        data: "DT_RowIndex",
                        render: (data, type) => `${data}`,
                        searchable: false,
                        orderable: false,
                    },
                    // { data: "all_action" },
                    {
                        data: "status"
                    },
                    {
                        data: "product_name"
                    },
                    {
                        data: "product_image"
                    },
                    {
                        data: "received_on"
                    },
                    {
                        data: "action"
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
                    reloadActionBtn();
                },
            });
        });
    </script>
    <script>
        $(document).on('click', '.send-comment-btn', function() {
            $('.seller-modal').hide('modal');
            var id = $(this).data('id');
            var form = document.getElementById('send-comment' + id);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{ route('seller-send.comment') }}",
                type: "post",
                data: {
                    comment_id: form['comment_id'].value,
                    message: form['message'].value
                },
                success: function(response) {
                    if (response.validate) {
                        alert(response.msg);
                        return false;
                    }

                    if (response.error) {
                        alert(response.msg);
                        return false;
                    }

                    if (response.error === false) {
                        toastr.success(response.msg, 'Success');
                    } else {
                        toastr.error(response.msg, 'Error');
                    }

                    // // Additional handling or page reload if needed
                    location.reload();
                }
            });
        });
    </script>
@endpush
