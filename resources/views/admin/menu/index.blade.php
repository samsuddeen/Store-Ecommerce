@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Menu')
@section('content')
    <!-- brand start -->
    <section class="app-user-list">

        <div class="card">
            <x-cardHeader :href="route('menu.create')" name="Menu">     
                                                     
                {{-- <a name="" id="" class="btn btn-info ms-1" href="{{ route('view.menu.sortable') }}" role="button">                    
                    Organize Category
                </a> --}}
            </x-cardHeader>

            <div class="card-body border-bottom">


                <div class="card-datatable table-responsive pt-0">                
                    <table class="category-list-table table">
                        <thead class="table-light ">
                            <tr>
                                <th>S.N</th>
                                <th>Order</th>
                                <th>name</th>
                                <th>banner image</th>
                                <th>Type</th>
                                <th>Show On</th>
                                <th>Status</th>
                                <th>children count</th>
                                <th>action</th>
                            </tr>
                        </thead>
                        <tbody id="sortable">                                                                          
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
    <script src="{{ asset('admin/menu-list.js') }}" defer></script>
    <script src="{{ asset('js/sortablejs/jquery-ui.min.js') }}"></script>
<script>
    $( function() {
        $( "#sortable" ).sortable({
            stop: function(event, ui) {
                var data = $(this).sortable('toArray', { attribute: 'data-id' });
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{ route('menu-order.update') }}",
                    data: {
                        order: data
                    },
                    success: function(response) {
                        toastr.success("Menu Position Updated Successfully !!")
                    }
                });
            }
        });
    });
</script>
@endpush
