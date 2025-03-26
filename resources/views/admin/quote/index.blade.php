@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Quote Request')
@section('content')
    <!-- brand start -->
    <section class="app-user-list">

        <div class="card">
            <div class="card-header d-flex justify-space-between">
                <h4 class="card-title text-capitalize">Subscriber</h4>
            </div>
            <div class="card-body border-bottom">
                <div class="card-datatable table-responsive pt-0">
                    <table class="quote-list-table table">
                        <thead class="table-light text-capitalize">
                            <tr>
                                <th>S.N</th>
                                <th>Name</th>
                                <th>Quantity</th>
                                <th>Type</th>
                                <th>action</th>
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
    <script src="{{ asset('admin/quote-list.js') }}" defer></script>
@endpush
