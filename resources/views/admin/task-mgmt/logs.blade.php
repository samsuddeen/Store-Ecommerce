@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Tasks')
@section('content')
    <!-- users list start -->
    <section class="app-user-list">
        <!-- list and filter start -->
        <div class="card">
            <div class="card-body border-bottom">
                <div class="card-datatable table-responsive pt-0">
                    <table class="task-list-table table">
                        <thead class="table-light text-capitalize">
                            <tr>
                                <th width="5%"></th>
                                <th width="20%">Task Title</th>
                                <th width="10%">Assigned By</th>
                                <th width="10%">Assigned To</th>
                                <th width="10%">Assigned At</th>
                                <th width="15%">Reason</th>
                                <th width="15%">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-capitalize">
                            @foreach ($logs as $log)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $log->task->title }}</td>
                                    <td>{{ $log->reassignedBy->name }}</td>
                                    <td>{{ $log->assignedTo->name }}</td>
                                    <td>{{ $log->task->reassign_date_time}}</td>
                                    <td>{{ $log->reason }}</td>
                                    <td>
                                         <a>{{ $log->task->status }}</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $logs->links() }}
                </div>
            </div>

            {{-- MODAL --}}
            <div class="common-popup medium-popup modal fade" id="taskDeleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-center" id="exampleModalLabel">Confirm Delete</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to delete this item ?</p>
                            <form action="" id="deleteTask" method="POST">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="d-flex">
                                            <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">No, Go Back</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            {{-- MODAL END --}}
        </div>
        <!-- list and filter end -->
    </section>
    <!-- users list ends -->
@endsection

{{-- @push('script')
    <script src="{{ asset('admin/task-list.js') }}" defer></script>
    <script src="{{ asset('admin/togglestatus.js') }}" defer></script>

    <script>
        $(document).on("click", ".badge-status", function() {
            const id = $(this).attr("toggle-id");
            test = changeStatus('tasks', id, '.task-list-table')

        });
    </script>

    <script>
        $(document).ready(function(){
            $(document).on('click','.delete',function(){
                let task_id = $(this).data('id');
                $('#taskDeleteModal').modal('show');
                let deleteUrl = "{{ route('task.destroy',':id') }}";
                    deleteUrl = deleteUrl.replace(':id',task_id);
                $('#deleteTask').on('submit', function(e){
                    e.preventDefault();
                    $.ajax({
                        type:"POST",
                        url:deleteUrl,
                        data:{
                            task_id: task_id,
                            _method: 'DELETE',
                            _token: "{{ csrf_token() }}"
                        },
                        dataType:"json",
                        success:function(response)
                        {
                            location.reload();
                        }
                    });
                });
            });

            $(document).on('click','.status', function(){
                let task_id = $(this).data('task-id');
                let status = $(this).data('status');
                $('#statusChangeModal').modal('show');
                $('#status_dropdown').val(status).attr('selected');

                $('#changeStatusForm').on('submit', function(e){
                    e.preventDefault();
                    let updateUrl = "{{ route('task.update', ':id') }}";
                    updateUrl = updateUrl.replace(':id', task_id);
                    let newStatus = $('#status_dropdown').val();

                    $.ajax({
                        type:"POST",
                        url: updateUrl,
                        data:{
                            task_id: task_id,
                            status: newStatus,
                            _method: "PUT",
                            _token: "{{ csrf_token() }}"
                        },
                        dataType: "json",
                        success: function(response){
                            location.reload();
                        }
                    });
                });

            });

            $(document).on('click', '.reassign', function(){
                let task_id = $(this).data('id');
                $('#reassignModal').modal('show');
                $('#task_id_val').val(task_id);
            });

            // $(document).on('change','#filter_status',function(){
            //     let status = $(this).val();
            //     alert(status);
            // });
        });
    </script>
@endpush --}}
