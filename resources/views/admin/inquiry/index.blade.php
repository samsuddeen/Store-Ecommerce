@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Inquiry')
@section('content')
    <!-- users list start -->
    <section class="app-user-list">
        <!-- list and filter start -->
        <div class="card">
            {{-- <x-cardHeader :href="route('task.create')" name="tasks" /> --}}
            <div class="card-body border-bottom">
                <div class="card-datatable table-responsive pt-0">
                    <table class="task-list-table table">
                        <thead class="table-light text-capitalize">
                            <tr>
                                <th width="5%"></th>
                                <th width="20%">Name</th>
                                <th width="10%">Email</th>
                                <th width="10%">Contact</th>
                                <th width="15%">Title</th>
                                <th width="25%">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="">
                            @foreach ($data as $inquiry)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $inquiry->full_name }}</td>
                                    <td>{{ $inquiry->email }}</td>
                                    <td>{{ $inquiry->contact }}</td>
                                    <td>{{ $inquiry->title }}</td>
                                    <td>
                                        <a href="{{ route('inquiry.show', $inquiry->id) }}" class="edit btn btn-primary btn-sm"><i data-feather="eye"></i></a>&nbsp;
                                        <a href="javascript:void(0);" class="delete btn btn-danger btn-sm" data-id="{{ $inquiry->id }}"><i data-feather="trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $data->links() }}
                </div>
            </div>

            {{-- MODAL --}}
            <div class="common-popup medium-popup modal fade" id="deleteInquiryModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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

@push('script')
    <script src="{{ asset('admin/task-list.js') }}" defer></script>
    <script src="{{ asset('admin/togglestatus.js') }}" defer></script>
    <script>
        $(document).ready(function(){
            $(document).on('click','.delete',function(){
                let inquiry_id = $(this).data('id');
                $('#deleteInquiryModal').modal('show');
                let deleteUrl = "{{ route('inquiry.destroy',':id') }}";
                    deleteUrl = deleteUrl.replace(':id',inquiry_id);
                $('#deleteTask').on('submit', function(e){
                    e.preventDefault();
                    $.ajax({
                        type:"POST",
                        url:deleteUrl,
                        data:{
                            inquiry_id: inquiry_id,
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
        });
    </script>
@endpush
