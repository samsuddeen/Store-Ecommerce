@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Product')

@section('content')
    <!-- brand start -->
    <section class="app-user-list">

        <div class="card">
            <x-cardHeader :href="route('page.create')" name="Page" />
            <div class="card-body border-bottom">
                <div class="card-datatable table-responsive pt-0">
                    <table class="banner-list-table table">
                        <thead class="table-light text-capitalize">
                            <tr>
                                <th>S.N</th>
                                <th>Title</th>
                                <th>Image</th>
                                <th>action</th>
                            </tr>
                        </thead>
                        <tbody class="text-capitalize">
                           @foreach($pages as $page)
                                <tr>
                                    <td>{{ $n}}</td>
                                    <td>{{ $page->title}}</td>
                                    <td>{{ $page->image}}</td>
                                    <td>
                                        {{-- <a href="javascript:;" class="btn btn-sm btn-danger me-1 delete-banner">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        </a> --}}
                                        <a href="{{ route('page.edit',$page->id)}}" class="btn btn-sm btn-primary me-1" title="Edit Page">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                        </a>

                                        {{-- <form action="{{route('page.destroy',$page->id)}}" class='delete-form' method="delete">
                                            @csrf
                                        </form> --}}


                                    </td>
                                    {{$n++}}
                                </tr>
                           @endforeach
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
    {{-- @include('admin.includes.datatables') --}}


@endpush
@push('script')
    <script src="{{ asset('admin/pages-list.js') }}" defer></script>

    {{-- <script>
        $(document).on('click', '.delete-banner', function(e) {
        e.preventDefault();

        let clicked = confirm('Are You Sure Want To Delete Banner !');

        if (clicked) {
            $(this).parent().find('form').submit();
        }
    });
    </script> --}}
@endpush
