@push('style')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('dashboard/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('dashboard/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('dashboard/vendors/css/tables/datatable/rowGroup.bootstrap5.min.css') }}">
@endpush
@push('script')
    <script src="{{ asset('dashboard/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('dashboard/vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('dashboard/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('dashboard/vendors/js/tables/datatable/responsive.bootstrap5.js') }}"></script>
    <script src="{{ asset('dashboard/vendors/js/tables/datatable/datatables.buttons.min.js') }}"></script>
    <script src="{{ asset('dashboard/vendors/js/tables/datatable/jszip.min.js') }}"></script>
    <script src="{{ asset('dashboard/vendors/js/tables/datatable/pdfmake.min.js') }}"></script>
    <script src="{{ asset('dashboard/vendors/js/tables/datatable/vfs_fonts.js') }}"></script>
    <script src="{{ asset('dashboard/vendors/js/tables/datatable/buttons.print.min.js') }}"></script>
@endpush
