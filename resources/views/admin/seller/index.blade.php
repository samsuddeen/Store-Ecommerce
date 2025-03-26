@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Sellers')
@section('content')
    <section id="default-breadcrumb">
        <div class="row" id="table-bordered">
            <div class="col-12">
                <!-- list and filter start -->
                <div class="card">
                    <x-cardHeader :href="route('seller.create')" name="product" />

                    <div class="card-body border-bottom">
                        <div class="card-datatable table-responsive pt-0">
                            <table class="seller-list-table table">
                                <thead class="table-light text-capitalize">
                                    <tr>
                                        <th></th>
                                        <th>name</th>
                                        <th>status</th>
                                        <th>phone</th>
                                        <th>address</th>
                                        <th>document</th>
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
            </div>
        </div>
    </section>



    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('seller-document.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <input type="text" name="seller_id" id="modal-seller_id" hidden>

                        <inputelement name="title" placeholder="PAN Certificate" value="{{ old('title') }}"
                            required="required" :errors="{{ json_encode($errors->toArray()) }}">
                        </inputelement>

                        <x-filemanager :value="''" :name="'document'" required="required">
                        </x-filemanager>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    
    <div class="modal fade" id="shareProject" tabindex="-1" aria-labelledby="shareProjectTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-sm-5 mx-50 pb-5">
                    <h1 class="text-center mb-1" id="addNewCardTitle">Are You Sure </h1>
                    <p class="text-center">Please Confirm</p>
                    <form class="row gy-1 gx-2 mt-75" method="post" action="{{ route('seller.status') }}">
                        @csrf
                        @method('PATCH')
                        <div class="col-12" style="display: none" id="reason-box">
                            <label class="form-label" for="modalAddCardNumber">Reason/Remarks <span
                                    class="text-danger">*</span></label>
                            <div class="input-group input-group-merge">
                                <textarea name="remarks" id="remarks" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <input type="hidden" name="status" id="status-input" hidden>
                        <input type="hidden" name="seller_id" id="seller_id">
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
        $(document).ready(function(){
            $(document).on('click', '.order-action', function(){
                let status = $(this).data('type');
                let seller_id = $(this).data('seller_id');
                $('#status-input').val(status);
                $('#seller_id').val(seller_id);
            });
        });
        // function reloadAction() {
        //     $('.order-action').on('click', function() {
        //         alert('clicked');
        //         var status=$(this).data('type');
        //         var seller_id = $(this).data('seller_id');
        //         console.log(seller_id, status);
        //         return;
        //         $('#customer_id-customer_id').val($(this).data('seller_id'));
        //         $('#status-input').val(status);
        //     });
        // }
    </script>
    <script src="{{ asset('admin/seller-list.js') }}" defer></script>
@endpush
