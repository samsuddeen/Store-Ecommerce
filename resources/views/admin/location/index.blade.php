@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Location')
@section('content')
    <!-- brand start -->
    <section class="app-user-list">
        <div class="card">
            <x-cardHeader :href="route('location.create')" name="location" />
            <div class="bulk-action">
                <a href="{{ route('location-sample.export') }}" class="btn btn-sm btn-info float-end me-1">Export Sample</a>
                <a href="{{ route('location.export') }}" class="btn btn-sm btn-success float-end me-1">Export</a>
                <a href="{{ route('location.get-import') }}" class="btn btn-sm btn-primary float-end me-1">Import</a>
            </div>
            <div class="card-body border-bottom">
                <div class="card-datatable table-responsive pt-0">
                    <table class="location-list-table table">
                        <thead class="table-light text-capitalize">
                            <tr>
                                <th>S.N</th>
                                <th>Title</th>
                                <th>Belongs To</th>
                                <th>Near To</th>
                                <th>Charge</th>
                                <th>Status</th>
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
    <script src="{{ asset('admin/location-list.js') }}" defer></script>
@endpush
