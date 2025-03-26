@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Cancel Reasons')
@section('content')
    <!-- brand start -->
    <section class="app-user-list">

        <div class="card">
            <x-cardHeader :href="route('cancel-reason.create')" name="faq" />
            <div class="card-body border-bottom">
                <div class="card-datatable table-responsive pt-0">
                    <table class="faq-list-table table">
                        <thead class="table-light text-capitalize">
                            <tr>
                                <th>S.N</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>action</th>
                            </tr>
                        </thead>
                        <tbody class="text-capitalize">
                            @foreach ($cancellationReason as $reason)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><a href="javascript:void(0);" class="addValue" data-id="{{ $reason->id }}">{{ $reason->title }}</a></td>
                                    <td>{{ $reason->status == 1 ? 'Active' : 'Inactive' }}</td>
                                    <td>
                                        <a href="{{ route('cancel-reason.edit',$reason->id) }}" class="btn btn-warning btn-sm editBtn">Edit</a>
                                        <a href="javascript:void(0);" class="btn btn-danger btn-sm deleteBtn"
                                            data-toggle="modal" data-id="{{ $reason->id }}">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>


        </div>
        {{-- MODAL --}}
        <div class="common-popup medium-popup modal fade" id="attrValue" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-center" id="exampleModalLabel">Change Value</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" id="changeAttributeValueForm" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-lg-12">
                                    <label for="value">Value</label>
                                    <input type="text" class="form-control" id="attr_value" name="value">
                                </div>
                                <div class="col-lg-12">
                                    <label for="status">Status</label>
                                    <select name="status" id="attr_status" class="form-control">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                                <div class="col-lg-12">
                                    <div class="d-flex">
                                        <button type="submit" class="btn btn-danger">Save</button>
                                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Go
                                            Back</button>

                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                    <div class="modal-footer">
                        <form action="" id="deleteAttributeValue" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete Value</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{-- MODAL END --}}
                {{-- MODAL --}}
                <div class="common-popup medium-popup modal fade" id="addValueModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-center" id="exampleModalLabel">Add New Value</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="" id="addValueForm" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-12">
                                        <input type="hidden" class="form-control" id="attr_cat_id" name="attr_cat_id">
                                        <label for="value">Value</label>
                                        <input type="text" class="form-control" id="attr_value" name="value">
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="d-flex">
                                            <button type="submit" class="btn btn-danger">Save</button>
                                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
    
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
        <div class="common-popup medium-popup modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-center" id="exampleModalLabel">Edit Attribute</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" id="editAttributeCategoryForm" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-lg-12">
                                    <label for="value">Title</label>
                                    <input type="text" class="form-control" id="attr_title" name="title">
                                </div>
                                <div class="col-lg-12">
                                    <label for="status">Status</label>
                                    <select name="publish" id="publish" class="form-control">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                                <div class="col-lg-12">
                                    <div class="d-flex">
                                        <button type="submit" class="btn btn-danger">Save</button>
                                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Go
                                            Back</button>

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
        <div class="common-popup medium-popup modal fade" id="deleteModal" tabindex="-1"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-center" id="exampleModalLabel">Confirm Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this item ?</p>
                        <form action="" id="faqDeleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="d-flex">
                                        <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">No, Go
                                            Back</button>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- MODAL END --}}

        <!-- list and filter end -->
    </section>
    <!-- brand ends -->
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $(document).on('click', '.deleteBtn', function() {
                let faq_id = $(this).data('id');
                $('#deleteModal').modal('show');
                let deleteUrl = "{{ route('cancel-reason.destroy', ':id') }}";
                deleteUrl = deleteUrl.replace(':id', faq_id);
                $('#faqDeleteForm').attr('action', deleteUrl);

            });
        });
    </script>
@endpush
