@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Tasks')
@section('content')
    <!-- users list start -->
    <section class="app-user-list">
        <!-- list and filter start -->
        <div class="card">
            <div class="card-header">
                <form action="{{ route('task.index') }}" method="GET">
                    <div class="form-group d-flex">
                        <select name="filter_status" id="filter_status" class="form-control filter_status">
                            <option value="All" {{ old('filter_status') == 'All' ? 'selected' : '' }}>All Tasks</option>
                            <option value="Assigned" {{ old('filter_status') == 'Assigned' ? 'selected' : '' }}>Assigned</option>
                            <option value="Pending" {{ old('filter_status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="In-Progress" {{ old('filter_status') == 'In-Progress' ? 'selected' : '' }}>In-Progress</option>
                            <option value="Completed" {{ old('filter_status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                            <option value="On Hold" {{ old('filter_status') == 'On-Hold' ? 'selected' : '' }}>On Hold</option>
                            <option value="Cancelled" {{ old('filter_status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        <button type="submit" class="btn btn-primary" style="width:120px;">Filter</button>
                    </div>
                </form>
            </div>
            <x-cardHeader :href="route('task.create')" name="tasks" />
            <div class="card-body border-bottom">
                <div class="card-datatable table-responsive pt-0">
                    <table class="task-list-table table">
                        <thead class="table-light text-capitalize">
                            <tr>
                                <th width="5%"></th>
                                <th width="20%">Title</th>
                                <th width="10%">Status</th>
                                <th width="10%">Priority</th>
                                <th width="15%">Created By</th>
                                <th width="15%">Assigned To</th>
                                <th width="25%">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-capitalize">
                            @foreach ($data as $task)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $task->title }}</td>
                                    <td>
                                        @if($task->status != 'Completed')
                                        <a href="javascript:void(0);" class="status" data-task-id="{{ $task->id }}" data-status="{{ $task->status }}">{{ $task->status }}</a>
                                        @else
                                        <a href="javascript:void(0);" class="">{{ $task->status }}</a>
                                        @endif
                                    </td>
                                    <td>{{ $task->priority }}</td>
                                    <td>{{ $task->createdBy->name }}</td>
                                    @php
                                        $assigns = $task->assigns;
                                    @endphp
                                    <td>@foreach ($assigns as $assign)
                                        <a href="{{ route('user.show',$assign->id) }}" target="_blank">{{ $assign->name }} ({{ $assign->roles->first()->name }})</a>
                                    @endforeach</td>
                                    <td>
                                        @if(Auth::user()->can('task-edit'))<a href="{{ route('task.edit', $task->id) }}" class="edit btn btn-warning btn-sm"><i data-feather="edit"></i></a>&nbsp;@endif
                                        <a href="{{ route('task.show', $task->id) }}" class="edit btn btn-primary btn-sm"><i data-feather="eye"></i></a>&nbsp;
                                        @if(Auth::user()->can('task-delete'))<a href="javascript:void(0);" class="delete btn btn-danger btn-sm" data-id="{{ $task->id }}"><i data-feather="trash"></i></a>@endif
                                        @if($task->status != 'Completed')
                                        <a href="javascript:void(0);" class="reassign btn btn-primary btn-sm" data-id="{{ $task->id }}">Re-assign</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $data->links() }}
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

                        {{-- MODAL --}}
                        <div class="common-popup medium-popup modal fade" id="reassignModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title text-center" id="exampleModalLabel">Re-assign Task</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('task_reassign') }}" id="reassignTask" method="POST">
                                            @csrf
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <input type="hidden" class="form-control" name="task_id" id="task_id_val">
                                                    <div class="form-group">
                                                        <label for="reason">Why do you want to reassign this task?</label>
                                                        <textarea name="reason" id="reason" class="form-control reason"></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="user_id">Assign to</label>
                                                        <select name="assigned_to" id="assigned_to" class="form-control assigned_to">
                                                            @foreach ($users as $user)
                                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="d-flex my-2">
                                                        <button type="submit" class="btn btn-danger">Submit</button>
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
@endpush
