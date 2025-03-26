@extends('layouts.app')
@section('content')
    <!-- brand start -->
    <section class="app-user-list">

        <div class="card">
            <x-cardHeader :href="route('delivery-charge.create')" name="Delivery Charge" />
            <div class="card-body border-bottom">
                <div class="card-datatable table-responsive pt-0">
                    <table class="delivery-charge-list-table table">
                        <thead class="table-light text-capitalize">
                            <tr>
                                <th>S.N</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Branch</th>
                                <th>Door</th>
                                <th>Branch Express</th>
                                <th>Branch Normal</th>
                                <th>Door Express</th>
                                <th>Door Normal</th>
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
    <script src="{{ asset('admin/delivery-charge-list.js') }}" defer></script>
@endpush
