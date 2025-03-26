@extends('layouts.app')
@section('content')
    <!-- brand start -->
    <section class="app-user-list">

        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{@$title}}</h4>
            </div>
            {{-- <x-cardHeader :href="route('customer.create')" name="customer" /> --}}
            <div class="card-body border-bottom">
                <div class="card-datatable table-responsive pt-0">
                    <table class="customer-list-table data-table">
                        <thead class="table-light text-capitalize">
                            <tr>
                                <th>S.N</th>
                                <th>name</th>
                                <th>email</th>
                                <th>phone</th>
                                <th>status</th>
                                <th>address</th>
                                <th>created_at</th>
                                {{-- <th>action</th> --}}
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

@endsection
@push('style')
    @include('admin.includes.datatables')
@endpush
@push('script')
<script src="{{ asset('backend/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script>
$(function(){
 
    var table = $('.data-table').DataTable({
        processing :true,
        serverSide :true,
        ajax : "{{route('blocked.customer')}}",
        columns:[
            {
            data:'id',
            name:'id'
        },
        {
            data:'name',
            name:'name'
        },
        {
            data:'email',
            name:'email'
        },
        {
            data:'phone',
            name:'phone'
        },
        {
            data:'status',
            name:'status'
        },
        {
            data:'address',
            name:'address'
        },
        {
            data:'created_at',
            name:'created_at'
        },
    
    ]
        
    });

});  
</script>

@endpush
