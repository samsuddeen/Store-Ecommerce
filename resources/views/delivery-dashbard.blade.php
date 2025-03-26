<div class="card card-company-table">
    <div class="card-body p-0">
        <div class="table-responsive">
            <h4 class="p-2">Order Delivery Assigned to Me</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>SN.</th>
                        <th>Title</th>
                        <th>Order ID</th>
                        <th>City</th>
                        <th>Qty</th>
                        <th>Discount</th>
                        <th>Total</th>
                        <th>Payment Status</th>
                        <th>Status</th>
                        <th>Progress</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($myTasks as $index => $task)
                        <tr>
                            <td>
                                {{ $index + 1 }}
                            </td>
                            <td>
                                <a href="{{ route('task.show', $task->id ?? '') }}">{{ $task->title }}</a>
                            </td>
                            <td>
                                <a href="{{ route('admin.viewOrder', $task->order ? $task->order->ref_id : '1') }}">{{ $task->order ? $task->order->ref_id : '-' }}
                                </a>
                            </td>
                            <td>
                                {{ @$task->order->area }}
                            </td>
                            <td>
                                {{ @$task->order->total_quantity }}
                            </td>
                            <td>
                                {{ '$. ' . formattedNepaliNumber(@$task->order->total_discount) }}
                            </td>
                            <td>
                                {{ '$. ' . formattedNepaliNumber(@$task->order->total_price) }}
                            </td>
                            <td>
                                {{ (int) @$task->order->payment_status == 1 ? 'Paid' : 'Not Paid' }}
                            </td>
                            <td class="text-nowrap">
                                {!! (new \App\Datatables\OrderDatatables())->getStatus(@$task->order->status) !!}
                            </td>
                            <td>
                                @if($task->status == 'Completed')
                                <a href="javascript:void(0);" class="">{{ $task->status }}</a>
                                @else
                                <a href="javascript:void(0);" class="status" data-task-id="{{ $task->id }}"
                                    data-status="{{ $task->status }}">{{ $task->status }}</a>
                                @endif
                                </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $myTasks->links() }}
        </div>
    </div>
</div>

{{-- STATUS MODAL --}}
<div class="common-popup medium-popup modal fade" id="statusChangeModal" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center" id="exampleModalLabel">Change Task Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this item ?</p>
                <form action="" id="changeStatusForm" method="POST">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <select name="status" id="status_dropdown" class="form-control">
                                    <option value="Assigned">Assigned</option>
                                    <option value="Pending">Pending</option>
                                    <option value="In-Progress">In-Progress</option>
                                    <option value="Completed">Completed</option>
                                    <option value="On Hold">On Hold</option>
                                    <option value="Cancelled">Cancelled</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="d-flex form-group my-1">
                                <button type="submit" class="btn btn-danger">Change Status</button>
                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- STATUS MODAL END --}}

@push('script')
    <script src="{{ asset('admin/task-list.js') }}" defer></script>
    <script src="{{ asset('admin/togglestatus.js') }}" defer></script>

    <script>
        $(document).on("click", ".badge-status", function() {
            const id = $(this).attr("toggle-id");
            test = changeStatus('tasks', id, '.task-list-table')

        });
    </script>

    <script>
        $(document).ready(function() {

            $(document).on('click', '.status', function() {
                let task_id = $(this).data('task-id');
                let status = $(this).data('status');
                $('#statusChangeModal').modal('show');
                $('#status_dropdown').val(status).attr('selected');

                $('#changeStatusForm').on('submit', function(e) {
                    e.preventDefault();
                    let updateUrl = "{{ route('task.update', ':id') }}";
                    updateUrl = updateUrl.replace(':id', task_id);
                    let newStatus = $('#status_dropdown').val();

                    $.ajax({
                        type: "POST",
                        url: updateUrl,
                        data: {
                            task_id: task_id,
                            status: newStatus,
                            _method: "PUT",
                            _token: "{{ csrf_token() }}"
                        },
                        dataType: "json",
                        success: function(response) {
                            location.reload();
                        }
                    });
                });

            });
        });
    </script>
@endpush
