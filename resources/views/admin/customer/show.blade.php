@extends('layouts.app')
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
                                @if($user->social_avatar)
                                <img class="img-fluid rounded mt-3 mb-2"
                                    src="{{@$user->social_avatar  }}" height="110"
                                    width="110" alt="User avatar" />
                                @else
                                <img class="img-fluid rounded mt-3 mb-2"
                                    src="{{$user->photo ? asset($user->photo) : asset('frontend/images/avatar.png')  }}" height="110"
                                    width="110" alt="User avatar" />
                                @endif
                                <div class="user-info text-center">
                                    <h4>{{ $user->name }}</h4>
                                    <span
                                        class="badge bg-light-secondary">{{ implode(',', $user->roles?->pluck('name')->toArray()) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-around my-2 pt-75">
                            <div class="d-flex align-items-start me-2">
                                <span class="badge bg-light-primary p-75 rounded">
                                    <i data-feather="check" class="font-medium-2 text-white"></i>
                                </span>
                                <div class="ms-75">
                                    <h4 class="mb-0">{{ $user->product_count ?? '0' }}</h4>
                                    <small>Total Products</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-start">
                                <span class="badge bg-light-primary p-75 rounded">
                                    <i data-feather="briefcase" class="font-medium-2 text-white"></i>
                                </span>
                                <div class="ms-75">
                                    <h4 class="mb-0">{{ @$order_count ?? '0'}}</h4>
                                    <small>Total Orders</small>
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
                                    <span>Customer</span>
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
                            <div class="d-flex justify-content-center pt-2">
                                <a href="{{ route('customer.edit', $user->id) }}" class="btn btn-primary me-1 ">
                                    Edit
                                </a>
                                <form action="{{ route('customer.destroy', $user->id) }}" method="POST" id="deleteUser">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger me-1 suspend-user ">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /User Card -->

            </div>
            <!--/ User Sidebar -->

            <!-- User Content -->
            <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
                <!-- User Pills -->
                <ul class="nav nav-pills mb-2">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">
                            <i data-feather="user" class="font-medium-3 me-50"></i>
                            <span class="fw-bold">Account</span></a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.edit', $user->id) }}">
                            <i data-feather="bookmark" class="font-medium-3 me-50"></i>
                            <span class="fw-bold">Update Details</span>
                        </a>
                    </li>

                </ul>
                <!--/ User Pills -->

                <!-- Project table -->
                <div class="card">
                    <h4 class="card-header">Customer's Order List</h4>
                    <div class="table-responsive">
                        <table class="table datatable-project">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Order ID</th>
                                    <th class="text-nowrap">Total Qty</th>
                                    <th>Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse (@$orders as $order)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $order->ref_id }}</td>
                                    <td>{{ $order->total_quantity }}</td>
                                    <td>{{ $order->total_price }}</td>

                                @empty
                                <tr>
                                    <td colspan="4">No orders has been made by the customer</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $orders->links() }}
                    </div>
                </div>
                <!-- /Project table -->

                <!-- Activity Timeline -->
                {{-- <div class="card">
                    <h4 class="card-header">User Activity Timeline</h4>
                    <div class="card-body pt-1">
                        <ul class="timeline ms-50">
                            @foreach ($user->activities as $activity)
                                <li class="timeline-item">
                                    <span class="timeline-point timeline-point-indicator"></span>
                                    <div class="timeline-event">
                                        <div class="d-flex justify-content-between flex-sm-row flex-column mb-sm-0 mb-1">
                                            <h6>{{ $activity->action }}</h6>
                                            <span class="timeline-event-time me-1">{{ $activity->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p>By {{ $activity->ip }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div> --}}
                <!-- /Activity Timeline -->


            </div>
            <!--/ User Content -->

        </div>
    </section>
@endsection

@push('script')
    <script src="{{ asset('dashboard/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
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
