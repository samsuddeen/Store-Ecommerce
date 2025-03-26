@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Brand')
@section('content')
    <!-- brand start -->
    <section class="app-user-list">

        <div class="card">
            <x-cardHeader :href="route('brand.create')" name="Brand" />
            <div class="card-body border-bottom">

                <div class="card-datatable table-responsive pt-0">
                    <table class="brand-list-table table">
                        <thead class="table-light text-capitalize">
                            <tr>
                                <th>S.N</th>
                                <th>name</th>
                                <th>logo</th>
                                <th>Status</th>
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
    <script src="{{ asset('admin/brand-list.js') }}" defer></script>
@endpush
