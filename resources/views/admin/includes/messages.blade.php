@if (request()->session()->has('success'))

    <script>
        toastr.success("{{ request()->session()->get('success') }}")
    </script>
@endif

@if (request()->session()->has('error'))
    <script>
        toastr.error("{{ request()->session()->get('error') }}")
    </script>
@endif
