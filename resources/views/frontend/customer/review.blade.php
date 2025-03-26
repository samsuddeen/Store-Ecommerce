@extends('frontend.layouts.app')
@section('title', 'Customer|Review')
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
                                <div class="review_table">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="white-space: nowrap;">Product Images</th>
                                                    <th>Product Details</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($order_products as $index => $product)
                                                    @if ($product->product != null)
                                                        <tr>
                                                            <td>
                                                                <a
                                                                    href="{{ route('product.details', $product->product->slug) }}">
                                                                    <img src="{{ completedOrderImage($product->product->images) }}"
                                                                        alt="Img">
                                                                </a>
                                                            </td>
                                                            <td>
                                                                {{ $product->product->name }}
                                                                <span style="display: block;margin-top:3px;">Color Family
                                                                    {{ ucfirst(@$product->getColor->title) }}</span>
                                                                <span
                                                                    style="display: block;margin-top:3px;">{{ $product->created_at }}</span>
                                                            </td>
                                                            <td>
                                                                <div class="more-btns">
                                                                    <a href="javascript:;" class="btns"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#reviewModel{{ $product->id + $index }}">Review</a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                        {!!$order_products->links()!!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @foreach ($order_products as $index => $product)
                    @php $starIndex = $product->id + $index @endphp
                    <div class="common-popup medium-popup modal fade" id="reviewModel{{ $starIndex }}" tabindex="-1"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Your Review and Ratings</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="{{ route('save.review') }}" method="post" id="reviewForm{{ $starIndex }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-group clearfix">
                                            <label>Your Rating</label>
                                            <div class="rate">
                                                <input type="radio" id="star5_{{ $starIndex }}" name="rating"
                                                    value="5" />
                                                <label for="star5_{{ $starIndex }}" title="text">5 stars</label>
                                                <input type="radio" id="star4_{{ $starIndex }}" name="rating"
                                                    value="4" />
                                                <label for="star4_{{ $starIndex }}" title="text">4 stars</label>
                                                <input type="radio" id="star3_{{ $starIndex }}" name="rating"
                                                    value="3" />
                                                <label for="star3_{{ $starIndex }}" title="text">3 stars</label>
                                                <input type="radio" id="star2_{{ $starIndex }}" name="rating"
                                                    value="2" />
                                                <label for="star2_{{ $starIndex }}" title="text">2 stars</label>
                                                <input type="radio" id="star1_{{ $starIndex }}" name="rating"
                                                    value="1"  />
                                                <label for="star1_{{ $starIndex }}" title="text">1 star</label>
                                            </div>
                                            <br>
                                            <div class="rating_error"> </div>
                                            @error('rating')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>Write your review</label>
                                            <input type="hidden" value="{{ @$product->product_id }}" name="product_id">
                                            <textarea cols="40" rows="4" name="message" class="form-control" placeholder="Write Something" required></textarea>
                                            <div class="review_error"> </div>
                                            @error('message')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Upload Image (can upload multiple) <span class="text-danger">( less than
                                                    2MB/Image
                                                    )</span></label>
                                            <input type="file" name="product_image[]" accept="image/png, image/jpeg"
                                                multiple >
                                            <div class="image_error"> </div>
                                            @error('product_image')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>How did you feel?</label>
                                            <div class="response-wrap">
                                                <div class="response-list">
                                                    <input type="radio" name="response" value="1">
                                                    <i class="las la-smile"></i>
                                                    Positive
                                                </div>
                                                <div class="response-list">
                                                    <input type="radio" name="response" value="2">
                                                    <i class="las la-meh"></i>
                                                    Neutral
                                                </div>
                                                <div class="response-list">
                                                    <input type="radio" name="response" value="3">
                                                    <i class="las la-angry"></i>
                                                    Negative
                                                </div>
                                            </div>
                                            <div class="response_error"> </div>
                                            @error('response')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <button type="button" class="btn btn-review">Submit your Review</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- 
                @foreach ($order_products as $index => $product)
                    <div class="common-popup medium-popup modal fade" id="reviewModel{{ $product->id + $index }}"
                        tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Your Review and Ratings</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="{{ route('save.review') }}" method="post"
                                    id="reviewForm{{ $product->id + $index }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-group clearfix">
                                            <label>Your Ratings</label>
                                            <div class="rate">
                                                <input type="radio" id="star5" name="rating" value="5" />
                                                <label for="star5" title="text">5 stars</label>
                                                <input type="radio" id="star4" name="rating" value="4" />
                                                <label for="star4" title="text">4 stars</label>
                                                <input type="radio" id="star3" name="rating" value="3" />
                                                <label for="star3" title="text">3 stars</label>
                                                <input type="radio" id="star2" name="rating" value="2" />
                                                <label for="star2" title="text">2 stars</label>
                                                <input type="radio" id="star1" name="rating" value="1"
                                                    required />
                                                <label for="star1" title="text">1 star</label>
                                            </div>
                                            <br>
                                            <div class="rating_error"> </div>
                                            @error('rating')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>Your Review</label>
                                            <input type="hidden" value="{{ @$product->product_id }}" name="product_id">
                                            <textarea cols="40" rows="4" name="message" class="form-control" placeholder="Write Something" required></textarea>
                                            <div class="review_error"> </div>
                                            @error('message')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Upload Multiple Images <span class="text-danger">( less than
                                                    2MB/Image
                                                    )</span></label>
                                            <input type="file" name="product_image[]" accept="image/png, image/jpeg"
                                                multiple required>
                                            <div class="image_error"> </div>
                                            @error('product_image')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Your Response</label>
                                            <input type="radio" name="response" value="1"><i
                                                class="las la-smile"></i> Positive
                                            <input type="radio" name="response" value="2"><i
                                                class="las la-smile"></i> Neutral
                                            <input type="radio" name="response" value="3"><i
                                                class="las la-smile"></i> Negative
                                            <div class="response_error"> </div>
                                            @error('response')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <button type="button" class="btn btn-review">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach --}}
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        @foreach ($order_products as $index => $product)
            $(document).ready(function() {
                $('.btn-review').on('click', function() {
                    var form = document.getElementById('reviewForm{{ $product->id + $index }}');
                    var rating = form['rating'].value;
                    var message = form['message'].value;
                    var response = form['response'].value;

                    if (!(rating)) {
                        $('.rating_error').replaceWith(
                            '<div class="rating_error"> <span class="text-danger">Rating Field is required</span> </div>'
                        );
                    } else if (!(message)) {
                        $('.review_error').replaceWith(
                            '<div class="review_error"> <span class="text-danger">Review Field is required</span> </div>'
                        );
                    } 
                     else if (!(response)) {
                        $('.response_error').replaceWith(
                            '<div class="response_error"> <span class="text-danger">Respnse Field is required</span> </div>'
                        );
                    } else {
                        $('#reviewForm{{ $product->id + $index }}').submit();
                    }
                });
            });
        @endforeach
    </script>
@endpush
