@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Category')
@section('content')
    <!-- brand start -->
    <section class="app-user-list">

        <div class="card">
            <x-cardHeader :href="route('category.create')" name="category">
                <a name="" id="" class="btn btn-info ms-1" href="{{ route('view.category.sortable') }}" role="button">
                    Organize Category
                </a>
            </x-cardHeader>

            <div class="card-body border-bottom">
                <categorysearch></categorysearch>
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
    <script src="{{ asset('admin/category-list.js') }}" defer></script>
@endpush
