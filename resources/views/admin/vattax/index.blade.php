@extends('layouts.app')
@section('title','Vat')
@section('content')
    <!-- brand start -->
    <section class="app-user-list">

        <div class="card">
            {{-- <x-cardHeader :href="route('vat-tax.create')" name="VatTax" /> --}}
            <div class="card-body border-bottom">
                <div class="card-datatable table-responsive pt-0">
                    <table class="vat-tax-list-table table data-table">
                        <thead class="table-light text-capitalize">
                            <tr>
                                <th>S.N</th>
                                <th>Vat Percent</th>
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
    <script src="{{ asset('backend/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
@endpush

@push('script')
   <script>
        $(function() {
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('vat-taxes') }}",
            columns: [{
                    data: 'id',
                    name: 'id'
            },
            {
                data:'vat_per',
                name:'vat_per'
            },
            {
                data:'action',
                name:'action'
            },
            ]
        });
    });
   </script>
@endpush
