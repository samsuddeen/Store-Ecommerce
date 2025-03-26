@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Delivery Route')
@section('content')
    <!-- brand start -->
    <section class="app-user-list">

        <div class="card">
            {{-- <x-cardHeader :href="route('delivery-route.create')" name="Delivery route" /> --}}
            <div class="card-body border-bottom">
                <div class="card-datatable table-responsive pt-0">
                    <table class="delivery-route-list-table table">
                        <thead class="table-light text-capitalize">
                            <tr>
                                <th>S.N</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Charge</th>
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
    <script src="{{ asset('admin/delivery-route-list.js') }}" defer></script>
@endpush
