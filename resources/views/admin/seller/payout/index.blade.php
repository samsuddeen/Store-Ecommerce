@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Seller Payout')
@section('content')
    <section class="app-user-list">
        <div class="card">
            <x-cardHeader :href="route('seller-order-index')" name="order">

            </x-cardHeader>
            <div class="card-body border-bottom">
                <div class="card-datatable table-responsive pt-0">
                    <table class="orde-list-table table data-table">
                        <thead class="table-light text-capitalize">
                            <tr>
                                <th>S.N</th>
                                <th>Seller Name</th>
                                <th>Seller Order </th>
                                <th>Status</th>
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
                    <form class="row gy-1 gx-2 mt-75" method="post" action="{{route('seller-payout-action-status')}}">
                        @csrf
                        <input type="text" name="type" id="type-input" hidden>
                        <input type="text" name="payout_id" id="return_id-input" hidden>
                        <div class="col-md-12" style="display: none" id="remarks-block">
                            <div class="form-group">
                                <label for="reason">Reson/Remarks <span class="text-danger">*</span>:</label>
                                <textarea name="remarks" id="remarks" class="form-control" rows="4"></textarea>
                            </div>
                        </div>

                        <div class="col-md-12 payment-block" style="display: none">
                            <div class="form-group">
                                <label for="payment-method">Payment Method <span class="text-danger">*</span>:</label>
                                <select name="payment_method" id="payment-method" class="form-control">
                                    <option value="">Please Select</option>
                                    @foreach($payment_methods as $method)
                                        <option value="{{$method->id}}">{{$method->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 payment-block" style="display: none">
                            <div class="form-group">
                                <label for="amount-to-paid">Amount <span class="text-danger">*</span>:</label>
                                <input type="number" class="form-control" name="amount" id="amount-to-paid">
                            </div>
                        </div>

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
    {{-- @include('admin.includes.datatables') --}}
@endpush
@push('script')
    <script src="{{ asset('backend/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>   
    <script type="text/javascript">
        $(function() {
            reloadActionBtn();
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                //    url: "{{ route('seller-payouts') }}",
                //    data: function (d) {
                //         @foreach($filters as $index=>$filter)
                //         d.{{$index}} = '{{$filter}}';
                //         @endforeach
                //     },
                },
                columns: [
                {
                    data:'id',
                    name:'id'
                },
                {
                    data: 'seller_id',
                    name: 'seller_id'
                },
                {
                    data:'seller_order_id',
                    name:'seller_order_id'
                },
                {
                    data:'status',
                    name:'status'                                                                                                                                                                 
                },

      
                ],
                fnDrawCallback: function(oSettings) {
                    feather.replace({
                        width: 14,
                        height: 14,
                    });
                    reloadActionBtn();
                },
            });

        });

        function reloadActionBtn() {
            $('.order-action').on('click', function(e) {
                e.preventDefault();

                let order_id = $(this).data('order_id');
                let action_type = $(this).data('type');
                let amount = $(this).data('amount');
                
                console.log(amount);

                $('#return_id-input').val(order_id);
                $('#type-input').val(action_type);
                $('#amount-to-paid').val(amount);

                let type = $(this).data('type');
                if(type=="cancel" || type=="reject"){
                    $('#reason-box').css({"display":"block"});
                }else{
                    $('#reason-box').css({"display":"none"});
                }

                if(type=='PAID'){
                    $('.payment-block').css({"display":"block"});
                }else{
                    $('.payment-block').css({"display":"none"});
                }
                $('#type-input').val(type);
                $('#order_id-input').val(order_id);
            });
        }

    </script>

@endpush
