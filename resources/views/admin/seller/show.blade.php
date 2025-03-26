@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Sellers')
@section('content')
    <section class="app-user-view-account">
        <div class="row">
            <!-- User Sidebar -->
            <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
                <!-- User Card -->
                <div class="card">
                    <div class="card-body">
                        <div class="user-avatar-section">
                            <div class="d-flex align-items-center flex-column">
                                <img class="img-fluid rounded mt-3 mb-2"
                                    src="{{ $user->photo?? asset('dashboard/images/portrait/small/avatar-s-2.jpg') }}" height="110"
                                    width="110" alt="User avatar" />
                                <div class="user-info text-center">
                                    <h4>{{ $user->name }}</h4>
                                    <span
                                        class="badge bg-light-secondary">{{ implode(',', $user->roles?->pluck('name')->toArray()) }}</span>
                                </div>
                            </div>
                        </div>
                        <h4 class="fw-bolder border-bottom pb-50 mb-1">Details</h4>
                        <div class="info-container">
                            <ul class="list-unstyled">
                                <li class="mb-75">
                                    <span class="fw-bolder me-25">User ID:</span>
                                    <span>#{{ $user->id }}</span>
                                </li>
                                <li class="mb-75">
                                    <span class="fw-bolder me-25">Username:</span>
                                    <span>{{ $user->name }}</span>
                                </li>
                                <li class="mb-75">
                                    <span class="fw-bolder me-25">Billing Email:</span>
                                    <span>{{ $user->email }}</span>
                                </li>
                                <li class="mb-75">
                                    <span class="fw-bolder me-25">Status:</span>
                                    {!! $user->status ? '<span class="badge bg-light-success">Active</span>' : ' <span class="badge bg-light-danger">Deactivated</span>' !!}
                                </li>
                                <li class="mb-75">
                                    <span class="fw-bolder me-25">Role:</span>
                                    <span>{{ implode(',', $user->roles?->pluck('name')->toArray()) }}</span>
                                </li>

                                <li class="mb-75">
                                    <span class="fw-bolder me-25">Contact:</span>
                                    <span>{{ $user->phone }}</span>
                                </li>

                                <li class="mb-75">
                                    <span class="fw-bolder me-25">Address:</span>
                                    <span>{{ $user->address }}</span>
                                </li>
                            </ul>
                            
                        </div>
                    </div>
                </div>
                <!-- /User Card -->

            </div>
            <!--/ User Sidebar -->

            <!-- User Content -->
          
            <!--/ User Content -->

        </div>
    </section>

     <!-- Modal -->
    
        <!-- Modal -->
     
    
@endsection

@push('script')
    <script src="{{ asset('dashboard/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>

    <script>
        $('#updatePasswordBtn').on('click',function()
        {
            var form=document.getElementById('update-adminPassword');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url:"{{route('admin.updatePassword')}}",
                type:'put',
                data:{
                    current_password:form['current_password'].value,
                    password:form['password'].value,
                    password_confirmation:form['password_confirmation'].value,
                },
                success:function(res)
                {
                    if(res.validation)
                    {
                        $.each(res.msg,function(index,value)
                        {
                            $('.'+index).text(value);
                        });
                        return false;
                    }
                    if(res.error)
                    {
                        Swal.fire({
                            title: 'Error',
                            text: res.msg,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Ok',
                            customClass: {
                                confirmButton: 'btn btn-primary',
                                cancelButton: 'btn btn-outline-danger ms-1'
                            },
                            buttonsStyling: false
                        })
                        return false;
                    }
                    if(res.old_password)
                    {
                        $('.current_password').text(res.msg);
                        return false;
                    }

                   if(res.success)
                   {
                    Swal.fire({
                            title: 'Success ',
                            text: res.msg,
                            icon: 'success',
                            showCancelButton: true,
                            confirmButtonText: 'Ok',
                            customClass: {
                                confirmButton: 'btn btn-primary',
                                cancelButton: 'btn btn-outline-danger ms-1'
                            },
                            buttonsStyling: false
                        })
                    $('#changePassword').modal('hide');
                   }
                   
                }
            });
        });
    </script>
    <script>
        var URL = "{{ route('user.destroy', $user->id) }}";
        var token = "{{ csrf_token() }}";
        $(document).ready(function() {
            const suspendUser = document.querySelector('.suspend-user');

            // Suspend User javascript
            if (suspendUser) {
                suspendUser.onclick = function(e) {
                    e.preventDefault();
                    Swal.fire({
                            title: 'Are you sure?',
                            text: "You won't be able to revert user!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, Delete user!',
                            customClass: {
                                confirmButton: 'btn btn-primary',
                                cancelButton: 'btn btn-outline-danger ms-1'
                            },
                            buttonsStyling: false
                        })

                        .then(function(result) {
                            if (result.value) {
                                $('#deleteUser').submit();
                            } else if (result.dismiss === Swal.DismissReason.cancel) {
                                Swal.fire({
                                    title: 'Cancelled',
                                    text: 'Cancelled Deletion :)',
                                    icon: 'error',
                                    customClass: {
                                        confirmButton: 'btn btn-success'
                                    }
                                });
                            }
                        })

                };
            }
        })
    </script>
@endpush
