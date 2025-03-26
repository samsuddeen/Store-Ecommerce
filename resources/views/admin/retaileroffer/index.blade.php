@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Retailer Offers')
@section('content')
    <!-- brand start -->
    <section class="app-user-list">
        <div class="card">
            <x-cardHeader :href="route('retailer_offer.create')" name="Retailer Offer" />

            <div class="card-body border-bottom">
                <div class="card-datatable table-responsive pt-0">
                    <table class="location-list-table table">
                        <thead class="table-light text-capitalize">
                            <tr>
                                <th>S.N</th>
                                <th>Title</th>
                                <th>Offer</th>
                                <th>Total</th>
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
    <script src="{{ asset('admin/retailer-top-offer-list.js') }}" defer></script>
@endpush
