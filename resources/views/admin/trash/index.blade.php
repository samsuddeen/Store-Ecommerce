@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Trash')
@section('content')
    <section class="app-user-list">
        <div class="card">
            <div class="card-body border-bottom">
                <div class="card-datatable table-responsive pt-0">
                    <table class="tag-list-table table">
                        <thead class="table-light text-capitalize">
                            <tr>
                                <th>S.N</th>
                                <th>Title</th>
                                <th>Name</th>
                                <th>Deleted At</th>
                                <th>action</th>
                            </tr>
                        </thead>
                        <tbody class="text-capitalize">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <form action="#" method="post" id="action-form" hidden>
        @csrf
        <input type="text" name="_method" id="method-action" hidden>
        <input type="text" name="id" id="action-id" hidden>
    </form>
@endsection
@push('style')
    @include('admin.includes.datatables')
@endpush
@push('script')
    <script>
        function reloadRestore() {
            $('.restore').on('click', function(e) {
                e.preventDefault();
                let action_type = $(this).data('action_type');
                $('#method-action').val(action_type);
                $('#action-id').val($(this).data('id'));
                $('#action-form').attr('action', $(this).data('action'));
                if (confirm("Are You sure..?")) {
                    $('#action-form').submit();
                }
            });
        }
    </script>
    <script src="{{ asset('admin/trash-list.js') }}" defer></script>
@endpush
