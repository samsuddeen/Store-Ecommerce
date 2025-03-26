@extends('frontend.layouts.app')
@section('title', env('DEFAULT_TITLE') . '|' . 'customer|order')
@section('content')
    <!-- Customer Order Details  -->
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
                            <div class="dashboard_content">
                                <div class="customer_order_wrapper table_wrapper">
                                    <div class="dashboard-tables-head">
                                        <h3>Order Details</h3>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table allOrderData table-bordered">
                                            <thead>
                                                <th>
                                                    S.N.
                                                </th>
                                                <th>
                                                    Image
                                                </th>
                                                <th>
                                                    Name
                                                </th>
                                                <th>
                                                    Quantity
                                                </th>
                                                <th>
                                                    Price/Pcs
                                                </th>
                                                <th>

                                                </th>
                                            </thead>
                                            <tbody>
                                                @foreach($order_asset as $key=>$product)
                                                <tr>
                                                    <td>
                                                        {{ $key+1}}
                                                    </td>
                                                    <td class="pds-img">
                                                        @if($product->image==null)
                                                        <a href="{{route('product.details',$product->product->slug ?? '')}}" target="_blank">
                                                        <img src="{{productImage($product)}}"
                                                            alt="Img">
                                                        </a>
                                                        @else
                                                        <a href="{{route('product.details',$product->product->slug ?? '')}}" target="_blank">
                                                        <img src="{{$product->image}}"
                                                        alt="Img">
                                                        </a>
                                                        @endif
                                                        
                                                    </td>
                                                    <td>
                                                        {{ ucfirst(@$product->product_name)}}
                                                    </td>
                                                    <td>
                                                        {{ @$product->qty}}
                                                    </td>
                                                    <td>{{formattedNepaliNumber(@$product->sub_total_price)}}</td>
                                                    <td>
                                                    </td>
                                                </tr>
                                                @endforeach
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                    <h6>Shipping Charge Rs : {{formattedNepaliNumber(@$order->shipping_charge)}}</h6>
                                    <h6>Total Rs : {{formattedNepaliNumber(@$order->total_price)}}</h6>
                                </div>
                                @if(!$feedback)
                                <div class="text-center">
                                    <a href="javascript:void(0);" class="btn giveFeedbackBtn" style="background-color:#39afb2;color: #fff;" data-id="{{$order->id}}">Tell us your experience</a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="reviewModal" tabindex="-1" role="dialog" aria-labelledby="reviewModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reviewModalLabel">Review your Latest Order</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Include the review form here -->
                    <form method="post" action="{{ route('post_delivery_feedback') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <input type="hidden" name="order_id" value="{{ @$latestOrder->id }}" class="form-control order_id">
                                    <label for="rating">Rating: (out of 5)</label>
                                    <input type="number" name="rating" class="form-control" min="1" max="5" required value="{{ old('rating') }}">
                                    @error('rating')
                                        <span class="text text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="comment">Message:</label>
                                    <textarea name="message" class="form-control">{!! old('message') !!}</textarea>
                                    @error('message')
                                        <span class="text text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="comment">Image:</label>
                                    <input type="file" class="form-control" name="image[]" multiple accept=".jpg,.jpeg,.png,.gif">
                                    @error('message')
                                        <span class="text text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn mt-1" style="background-color:#39afb2;color: #fff;">Submit Review</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Customer Order Details End  -->
@endsection
@push('script')
<script>
    $(document).ready(function() {
        $(document).on('click','.giveFeedbackBtn',function(){
            let order_id = $(this).data('id');
            $('#reviewModal').modal('show');
            $('.order_id').val(order_id);
        });
    });
</script>
@endpush