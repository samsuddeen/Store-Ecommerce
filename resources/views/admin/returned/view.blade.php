@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Return Order')
@section('content')
    <section id="default-breadcrumb">
        <div class="row" id="table-bordered">
            <div class="col-12">
                <!-- list and filter start -->
                <div class="card">
                    <div class="card-header d-flex justify-space-between">
                        {{-- <h4 class="card-title text-capitalize">Review</h4> --}}
                    </div>
                    {{-- @dd($order) --}}
                    <div class="card-body border-bottom">
                        <div class="card-datatable table-responsive pt-0">
                            <h4>Reason</h4>
                            {{@$returnOrder->reason}}
                            <br>
                            {{@$returnOrder->comment}}
                            <a href="{{route('admin.viewOrder',@$order->ref_id)}}" class="btn btn-sm btn-success">View Order</a>
                            {{-- <table class="return-list-table table">
                                <thead class="table-light text-capitalize">
                                    <tr>
                                        <th>S.N</th>
                                        <th>Status</th>
                                        <th>Customer</th>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Qty</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="text-capitalize">
                                    @foreach($product_info as $product)

                                    @endforeach
                                </tbody>
                            </table> --}}
                        </div>
                    </div>
                </div>                
            </div>
        </div>

    </section>
@endsection