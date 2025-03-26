@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Sub Tasks')
@section('content')
    <!-- users list start -->
    <section class="app-user-list">
        <!-- list and filter start -->
        <div class="card">
            <x-cardHeader :href="route('subtask.create')" name="users" />
            <div class="card-body border-bottom">
                <div class="card-datatable table-responsive pt-0">
                    <table class="task-list-table table">
                        <thead class="table-light text-capitalize">
                            <tr>
                                <th></th>
                                <th>Task</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Priority</th>
                                <th>Created By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-capitalize">
                            @foreach ($data as $task)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $task->task->title }}</td>
                                    <td>{{ $task->title }}</td>
                                    <td><a href="javascript:void(0);" class="status" data-task-id="{{ $task->id }}" data-status="{{ $task->status }}">{{ $task->status }}</a></td>
                                    <td>{{ $task->priority }}</td>
                                    <td>{{ $task->createdBy->name }}</td>
                                    <td>
                                        <a href="{{ route('subtask.edit', $task->id) }}" class="edit btn btn-warning btn-sm"><i data-feather="edit"></i></a>&nbsp;
                                        <a href="{{ route('subtask.show', $task->id) }}" class="edit btn btn-primary btn-sm"><i data-feather="eye"></i></a>&nbsp;
                                        <a href="javascript:void(0);" class="delete btn btn-danger btn-sm" data-id="{{ $task->id }}"><i data-feather="trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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


            
            {{--STATUS MODAL --}}
            <div class="common-popup medium-popup modal fade" id="statusChangeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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

        </div>
        <!-- list and filter end -->
    </section>
    <!-- users list ends -->
@endsection
@push('style')
    @include('admin.includes.datatables')
@endpush
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
        $(document).ready(function(){
            $(document).on('click','.delete',function(){
                let task_id = $(this).data('id');
                $('#taskDeleteModal').modal('show');
                let deleteUrl = "{{ route('subtask.destroy',':id') }}";
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
                    let updateUrl = "{{ route('subtask.update', ':id') }}";
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
        });
    </script>
@endpush
