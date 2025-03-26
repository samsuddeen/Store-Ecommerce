@extends('frontend.layouts.app')
@section('title', env('DEFAULT_TITLE') . '|Completed Order')
@section('content')

    <div class="dashboard-wrapper mt mb">
        <div class="container">
            <div class="customer_dashboard_wrap">
                @include('frontend.customer.sidebar')
                <div class="dashboard-main-wrapper">
                    <div class="dash-toggle">
                        <i class="las la-bars"></i>
                    </div>
                    <div class="dashboard-main-col">
                        <div class="dashboard_contentArea">
                            <div class="dashboard_content table_wrapper">
                                <div class="dashboard-tables-head">
                                    <h3>Completed Order</h3>
                                </div>
                                <div class="wishlist_table completed_order_table">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="white-space: nowrap;">Product Images</th>
                                                    <th>Product Name</th>
                                                    <th>Total</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($orders as $order)
                                                    @foreach ($order->orderAssets as $product)
                                                    @if($product->product !=null)
                                                    
                                                        <tr>
                                                            <td>
                                                                <a
                                                                    href="{{ route('product.details', $product->product->slug) }}">
                                                                    <img src="{{ completedOrderImage($product->product->images) }}"
                                                                        alt="Img">
                                                                </a>
                                                            </td>
                                                            <td>
                                                                <div>
                                                                    <p>{{ $product->product_name }}</p>
                                                                </div>
                                                                <div>Quantity: {{ $product->qty }}</div>
                                                            </td>
                                                            <td>
                                                                <h5 style="display: inline">Rs </h5>
                                                                {{ $product->price }} /
                                                                Pcs
                                                            </td>

                                                            <td>
                                                                <div class="table_btn">
                                                                    @if (strtotime(date('Y-m-d', strtotime($product->created_at . '+' . $product->product->returnable_time . ' days'))) >=
                                                                            $current_date &&
                                                                            (int) $order->status === 5 &&
                                                                            $product->getReturnOrder === null)
                                                                        <button type="button" class="delete"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#exampleModaltest{{ $product->id }}"><i
                                                                                class="las la-exchange-alt"></i>
                                                                        </button>
                                                                    @endif
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    @endforeach
                                                @endforeach
                                            </tbody>
                                        </table>
                                        {!!$orders->links()!!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->

    @foreach ($orders as $order)
        @foreach ($order->orderAssets as $order_asset)
            {{-- $order_asset->product->return_policy --}}
            <form action="{{ route('return.product', $order_asset->id) }}" method="post">
                @csrf
                <div class="modal fade" id="exampleModaltest{{ $order_asset->id }}" tabindex="-1"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel"></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                @if ($order_asset->qty > 1)
                                    <div class="form-group">
                                        <label for="no_of_item">Number Items To Be Return ?</label>
                                        <input type="number" name="no_of_item" id="no_of_item"
                                            class="form-control form-control-sm" min=1 max={{ $order_asset->qty }}>
                                    </div>
                                @else
                                    <div class="form-group">
                                        <label for="no_of_item">Number Items To Be Return ?</label>
                                        <input type="number" value="1" name="no_of_item" id="no_of_item"
                                            class="form-control form-control-sm" min=1 max=1 disabled>
                                    </div>
                                @endif
                                <div class="form-group">
                                    <label for="reason">Why You Want to Return The Product ?</label>
                                    <textarea name="reason" id="reason" rows="3" class="form-control" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="comment">Explain More Info.</label>
                                    <textarea name="comment" id="comment" rows="5" class="form-control" required></textarea>
                                </div>
                                <div>
                                    <input type="checkbox" name="aggree" id="aggree" required>
                                    <label for="aggree">I am read and aggre to the <a
                                            href="{{ route('general', 'privacy-policy') }}" target="_blank"> Privacy Policy </a> of
                                        the Company,
                                        <a href="{{ route('general', 'term-condition') }}" target="_blank"> Terms &
                                            conditions </a> &
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal1"> Product
                                            Warranty Policy </a> </label>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-danger" id="modal-cancel"> Return Now
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>


            <div class="common-popup small-popup modal fade" id="exampleModal1" tabindex="-1"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Warranty Policy</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            {{ @$order_asset->product->warranty_policy }}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endforeach
@endsection
@push('script')
    <script></script>
@endpush
