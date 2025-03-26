@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Contact Setting')   
@section('content')
    <!-- brand start -->
    <section class="app-user-list">

        <div class="card">
            <x-cardHeader :href="route('contact-setting.create')" name="Contact" />
            <div class="card-body border-bottom">
                <div class="card-datatable table-responsive pt-0">
                    <table class="tag-list-table table">
                        <thead class="table-light text-capitalize">
                            <tr>
                                <th>S.N</th>
                                <th>Type</th>
                                <th>Contact No</th>
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
    </section>

    <div class="modal fade" id="shareProject" tabindex="-1" aria-labelledby="shareProjectTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-sm-5 mx-50 pb-5">
                    <h1 class="text-center mb-1" id="addNewCardTitle">Are You Sure </h1>
                    <p class="text-center">Please Confirm</p>
                    <form class="row gy-1 gx-2 mt-75" method="post" action="{{route('contact-setting.status')}}">
                        @csrf
                        @method("PATCH")
                        <input type="text" name="status" id="status-input" hidden>
                        <input type="text" name="contact_setting_id" id="payment_id-input" hidden>
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary me-1 mt-1">Submit</button>
                            <button type="reset" class="btn btn-outline-secondary mt-1" data-bs-dismiss="modal"
                                aria-label="Close">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection
@push('style')
    @include('admin.includes.datatables')
@endpush
@push('script')
<script>
    reloadActionBtn();
    function reloadActionBtn() {
        $('.order-action').on('click', function(e) {
            e.preventDefault();
            let type = $(this).data('type');
            let order_id = $(this).data('order_id');
            $('#status-input').val(type);
            $('#payment_id-input').val(order_id);
        });
    }
</script>
    <script src="{{ asset('admin/contact-setting-list.js') }}" defer></script>
@endpush
