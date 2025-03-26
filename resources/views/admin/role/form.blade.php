@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Role')   
@push('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/vendors/css/forms/select/select2.min.css') }}">
@endpush

@push('script')
    <script>
        $('#selectAll').on('change', function() {
            var value = $(this).is(':checked');
            $('input[name="permission[]"]').prop('checked', value);

        })
        $('.group-check').on('change', function() {
            var value = $(this).is(':checked');
            $(this).closest('tr').find('input[type="checkbox"]').prop('checked', value);
        })
    </script>
@endpush

@section('content')
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
        </div>
        <div class="content-body">


            <!-- Role cards -->
            <div class="row">

                <div class="card">
                    <div class="card-body">
                        <form id="addRoleForm" class="row" method="POST"
                            action="{{ route('role.update', $role->id) }}">
                            @method('PATCH')
                            @csrf
                            <div class="col-12">
                                <label class="form-label" for="modalRoleName">Role Name</label>
                                <input type="text" id="modalRoleName" name="name" value="{{ $role->name }}" required
                                    class="form-control" placeholder="Enter role name" tabindex="-1"
                                    data-msg="Please enter role name" {{ $role->id == 1 ? 'readonly' : '' }} />
                                @error('name')
                                    <p class="form-text text-danger">

                                        {{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-12">
                                <h4 class="mt-2 pt-50">Role Permissions</h4>
                                @error('permission')
                                    <p class="form-text text-danger">
                                        {{ $message }}</p>
                                @enderror
                                <!-- Permission table -->
                                <div class="table-responsive">
                                    <table class="table table-flush-spacing">
                                        <tbody>
                                            <tr>
                                                <td class="text-nowrap fw-bolder">
                                                    Administrator Access
                                                    <span data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="Allows a full access to the system">
                                                        <i data-feather="info"></i>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="selectAll" />
                                                        <label class="form-check-label" for="selectAll"> Select
                                                            All
                                                        </label>

                                                    </div>
                                                </td>
                                            </tr>

                                            @foreach ($permissions as $key => $item)
                                                <tr>
                                                    <td class="text-nowrap fw-bolder text-capitalize">{{ $key }}
                                                        <input class="form-check-input group-check ms-2" type="checkbox" />
                                                    </td>
                                                    <td>
                                                        <div class="d-flex">

                                                            @foreach ($item as $permission)
                                                                <div class="form-check me-3 me-lg-5">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        name="permission[]" value="{{ $permission->id }}"
                                                                        {{ in_array($permission->id, $selectedPermissions) ? 'checked' : null }} />
                                                                    <label class="form-check-label" for="payrollRead">
                                                                        {{ $permission->name }}
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- Permission table -->
                            </div>
                            <div class="col-12 text-center mt-2">
                                <button type="submit" class="btn btn-primary me-1">Submit</button>
                                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    Discard
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--/ Role cards -->

            <!-- Add Role Modal -->

            <!--/ Add Role Modal -->

        </div>
    </div>
@endsection
