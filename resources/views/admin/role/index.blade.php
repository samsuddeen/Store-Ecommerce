@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Role')   
@push('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/vendors/css/forms/select/select2.min.css') }}">
@endpush

@push('script')
    <script src="{{ asset('dashboard/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="{{ asset('dashboard/js/scripts/forms/form-select2.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#select2-basic').select2()
        })
    </script>
@endpush
@section('content')
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <h3>Roles List</h3>
            <p class="mb-2">
                A role provided access to predefined menus and features so that depending <br />
                on assigned role an administrator can have access to what he need
            </p>

            <!-- Role cards -->
            <div class="row">

                @foreach ($roles as $key => $role)
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <span>Total {{ $role->users_count }} users</span>
                                    <span>Total {{ $role->permissions_count }} Permissions</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-end mt-1 pt-25">
                                    <div class="role-heading">
                                        <h4 class="fw-bolder text-capitalize">{{ $role->name }}</h4>
                                        <a href="{{ route('role.edit',$role->id) }}" class="role-edit-modal">
                                            <small class="fw-bolder">Edit Role</small>
                                        </a>
                                    </div>
                                    <a href="javascript:void(0);" class="text-body"><i data-feather="copy"
                                            class="font-medium-5"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                @endforeach
            </div>
            <!--/ Role cards -->




        </div>
    </div>
@endsection
