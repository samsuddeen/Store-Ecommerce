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
                            <h4>Details</h4>
                            
                            Customer Name:<strong>{{ $returnOrder->user->name }}</strong>
                            <br>
                            Order ID: <strong>{{ $returnOrder->returnOrder->orderAsset->order->ref_id }}</strong>
                            <br>
                            Product Qty: <strong>{{ $returnOrder->returnOrder->qty }}</strong>
                            <br>
                            Price: <strong>{{ $returnOrder->returnOrder->amount }}</strong>
                            <br>
                            Reason: <strong>{{ $returnOrder->returnOrder->reason }}</strong>
                            {{-- @foreach (json_decode($returnOrder->refund_details) as $key=>$order)
                                @foreach ($order as $index=>$value)
                                    <strong>{{@$index}}:</strong>{{@$value}}
                                    <br>
                                @endforeach
                            @endforeach --}}
                            <h4></h4>
                        </div>
                    </div>
                </div>                
            </div>
        </div>

    </section>
@endsection