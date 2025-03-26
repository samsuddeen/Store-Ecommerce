@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Actions')
@section('content')
    <!-- users list start -->
    <section class="app-user-list">
        <!-- list and filter start -->
        <div class="card">
            <x-cardHeader :href="route('task-action.create')" name="tasks" />
            <div class="card-body border-bottom">
                <div class="card-datatable table-responsive pt-0">
                    <table class="user-list-table table">
                        <thead class="table-light text-capitalize">
                            <tr>
                                <th></th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Default</th>
                                @if(Auth::user()->can('task-action-edit') || Auth::user()->can('task-action-delete'))<th>Action</th>@endif
                            </tr>
                        </thead>
                        <tbody class="text-capitalize">
                            @foreach ($actions as $key=> $action)
                                <tr>
                                    <th>{{$loop->iteration }}</th>
                                    <td>{{ $action->title }}</td>
                                    <td>{{ $action->status == true ? 'Published' : 'Draft' }}</td>
                                    <td>{{ $action->is_default == true ? 'Yes' : 'No' }}</td>
                                    @if(Auth::user()->can('task-action-edit') || Auth::user()->can('task-action-delete'))
                                    <td>
                                        @if(Auth::user()->can('task-action-edit'))
                                        <a href="{{ route('task-action.edit',$action->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        @endif
                                        @if(Auth::user()->can('task-action-delete'))
                                        <a href="javascript:void(0);" class="btn btn-danger btn-sm deleteBtn" data-toggle="modal" data-id="{{ $action->id }}">Delete</a>
                                        @endif
                                    </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- MODAL --}}
            <div class="common-popup medium-popup modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-center" id="exampleModalLabel">Confirm Delete</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to delete this item ?</p>
                            <form action="" id="deleteForm" method="POST">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="d-flex">
                                            <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">No, Go Back</button>
                                        </div>
                                    </div>
                                </div>
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
{{-- @push('style')
    @include('admin.includes.datatables')
@endpush --}}
@push('script')
    <script src="{{ asset('admin/user-list.js') }}" defer></script>
    <script src="{{ asset('admin/togglestatus.js') }}" defer></script>

    <script>
        $(document).on("click", ".badge-status", function() {
            const id = $(this).attr("toggle-id");
            test = changeStatus('users', id, '.user-list-table')

        });
    </script>

    <script>
        $(document).ready(function(){
            $(document).on('click','.deleteBtn',function(){
                let action_id = $(this).data('id');
                $('#deleteModal').modal('show');
                let deleteUrl = "{{ route('task-action.destroy',':id') }}";
                    deleteUrl = deleteUrl.replace(':id',action_id);
                $('#deleteForm').on('submit', function(e){
                    e.preventDefault();
                    $.ajax({
                        type:"POST",
                        url:deleteUrl,
                        data:{
                            action_id: action_id,
                            _method: 'DELETE',
                            _token: "{{ csrf_token() }}"
                        },
                        dataType:"json",
                        success:function(response)
                        {
                            $('#deleteModal').modal('hide');
                            location.reload();
                        }
                    });
                });
            });
        });
    </script>
@endpush
