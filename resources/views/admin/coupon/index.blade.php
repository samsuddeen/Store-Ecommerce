@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Coupon')
@section('content')
    <!-- brand start -->
    <section class="app-user-list">
        <div class="card">
            <x-cardHeader :href="route('coupon.create')" name="coupon" />
            <div class="card-body border-bottom">
                <div class="card-datatable table-responsive pt-0">
                    <table class="coupon-list-table table">
                        <thead class="table-light text-capitalize">
                            <tr>
                                <th>S.N</th>
                                <th>Title</th>
                                <th>Code</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Discount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-capitalize">

                        </tbody>
                    </table>
                </div>
            </div>


        </div>
        <!-- list and filter end -->
    </section>
    <!-- brand ends -->
@endsection
@push('style')
    @include('admin.includes.datatables')
@endpush
@push('script')
    <script src="{{ asset('admin/coupon-list.js') }}" defer></script>
@endpush
