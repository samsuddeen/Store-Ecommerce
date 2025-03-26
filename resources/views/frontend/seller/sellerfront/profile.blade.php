@extends('frontend.layouts.app')
@section('title', env('DEFAULT_TITLE') . '|' . @$product->name)
@section('css')
@endsection
@section('content')


    <!-- Banner  -->
    @include('frontend.seller.sellerfront.inc.banner')
    <!-- Banner End  -->

    <!-- Menu  -->
    @include('frontend.seller.sellerfront.inc.menu')
    <!-- Menu End  -->

    <!-- Store All Product  -->
    <section class="store-profile mt mb">
        <div class="container">
            @include('frontend.seller.sellerfront.inc.sellerpage')
            <div class="seller-profile-page">
                <div class="customer-review">
                    <div class="customer-reviews-cols">
                        <div class="reviews-single">
                            <div class="review-single-head">
                                <h3>Average Seller Ratings</h3>
                            </div>
                            <?php
                                $avgRating = round($data['totalStar']  / $data['totalRating'] );
                            ?>
                            <input type="hidden" name='seller_id' id="seller_id" value="{{@$seller->id}}">
                            <div class="rating_wrapper">
                                <div class="rating_left_side">
                                    <p>{{$avgRating==0.0 ? 5 : $avgRating}}<span>/5</span></p>
                                    <div class="star_wrapper">
                                        @for($i=0;$i<$avgRating;$i++)
                                            <i class="las la-star"></i>
                                        @endfor
                                    </div>
                                    <span class="space-span">{{$data['totalRating']}}  Rating</span>
                                </div>
                                <div class="rating_right_side">
                                    <div class="line_on_">
                                        <span class="review-span">
                                            <i class="las la-star"></i>
                                            <i class="las la-star"></i>
                                            <i class="las la-star"></i>
                                            <i class="las la-star"></i>
                                            <i class="las la-star"></i>
                                        </span>
                                        @php
                                            if ($data['totalRating']== 0) {
                                                $data['totalRating']= 1;
                                            }
                                            $percentage = intval(($data['fiveStar'] / $data['totalRating']) * 100);
                                        @endphp
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%"
                                                aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <span class="reviews-counts">{{@$data['fiveStar']}}</span>
                                    </div>
                                    <div class="line_on_">
                                        <span class="review-span">
                                            <i class="las la-star"></i>
                                            <i class="las la-star"></i>
                                            <i class="las la-star"></i>
                                            <i class="las la-star"></i>
                                        </span>
                                        @php
                                            if ($data['totalRating']== 0) {
                                                $data['totalRating']= 1;
                                            }
                                            $percentage = intval(($data['fourStar'] / $data['totalRating']) * 100);
                                        @endphp
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%"
                                                aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <span class="reviews-counts">{{@$data['fourStar']}}</span>
                                    </div>
                                    <div class="line_on_">
                                        <span class="review-span">
                                            <i class="las la-star"></i>
                                            <i class="las la-star"></i>
                                            <i class="las la-star"></i>
                                        </span>
                                        @php
                                            if ($data['totalRating']== 0) {
                                                $data['totalRating']= 1;
                                            }
                                            $percentage = intval(($data['threeStar'] / $data['totalRating']) * 100);
                                        @endphp
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%"
                                                aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <span class="reviews-counts">{{@$data['threeStar']}}</span>
                                    </div>
                                    <div class="line_on_">
                                        <span class="review-span">
                                            <i class="las la-star"></i>
                                            <i class="las la-star"></i>
                                        </span>
                                        @php
                                        if ($data['totalRating']== 0) {
                                            $data['totalRating']= 1;
                                        }
                                        $percentage = intval(($data['twoStar'] / $data['totalRating']) * 100);
                                        @endphp
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%"
                                                aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    <span class="reviews-counts">{{@$data['twoStar']}}</span>
                                    </div>
                                    <div class="line_on_">
                                        <span class="review-span">
                                            <i class="las la-star"></i>
                                        </span>
                                        @php
                                        if ($data['totalRating']== 0) {
                                            $data['totalRating']= 1;
                                        }
                                        $percentage = intval(($data['oneStar'] / $data['totalRating']) * 100);
                                        @endphp
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%"
                                                aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    <span class="reviews-counts">{{@$data['oneStar']}}</span>
                                        
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        <div class="store-profile-response">
                            <ul>
                                <li>
                                    <i class="las la-comments"></i>
                                    <div class="profile-responsive-info">
                                        <span>Chat Response Rate</span>
                                        <p>95.05%</p>
                                    </div>
                                </li>
                                <li>
                                    <i class="las la-dot-circle"></i>
                                    <div class="profile-responsive-info">
                                        <span>Last Active</span>
                                        <p>1 Hours Ago</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="seller-reviews">
                        <h3>Seller Ratings and Reviews (283)</h3>
                        <ul class="nav nav-tabs" id="myTab5" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="sr1-tab" data-bs-toggle="tab"
                                    data-bs-target="#sr1-tab-pane" type="button" role="tab"
                                    aria-controls="sr1-tab-pane" aria-selected="true"><img
                                        src="{{ asset('frontend/images/emo1.png') }}" alt="Images"> Positive</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="sr2-tab" data-bs-toggle="tab"
                                    data-bs-target="#sr2-tab-pane" type="button" role="tab"
                                    aria-controls="sr2-tab-pane" aria-selected="false"><img
                                        src="{{ asset('frontend/images/emo2.png') }}" alt="Images"> Neutral</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="sr3-tab" data-bs-toggle="tab"
                                    data-bs-target="#sr3-tab-pane" type="button" role="tab"
                                    aria-controls="sr3-tab-pane" aria-selected="false"><img
                                        src="{{ asset('frontend/images/emo3.png') }}" alt="Images"> Negative</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent5">
                            <div class="tab-pane fade show active" id="sr1-tab-pane" role="tabpanel"
                                aria-labelledby="sr1-tab" tabindex="0">
                                <div class="seller-review-list">
                                    @foreach($seller_review as $review)
                                    @if($review->response==='1')
                                        <div class="seller-review-wrap">
                                            <div class="seller-review-media">
                                                <img src="{{ asset('frontend/images/emo1.png') }}" alt="Images">
                                            </div>
                                            <div class="seller-review-content">
                                                <h3>Positive</h3>
                                                <ul>
                                                    <li>{{ucfirst($review->user->name)}}</li>
                                                    <li>{{$review->created_at->diffForHumans()}}</li>
                                                    <li><img src="{{ asset('frontend/images/verify.png') }}" alt="Images">
                                                        Verified Purchase</li>
                                                    <li><i class="las la-thumbs-up"></i> 0</li>
                                                </ul>
                                            </div>
                                        </div>
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                            <div class="tab-pane fade" id="sr2-tab-pane" role="tabpanel" aria-labelledby="sr2-tab"
                                tabindex="0">
                                <div class="seller-review-list">
                                    @foreach($seller_review as $review)
                                    @if($review->response==='2')
                                        <div class="seller-review-wrap">
                                            <div class="seller-review-media">
                                                <img src="{{ asset('frontend/images/emo2.png') }}" alt="Images">
                                            </div>
                                            <div class="seller-review-content">
                                                <h3>Neutral</h3>
                                                <ul>
                                                    <li>{{ucfirst($review->user->name)}}</li>
                                                    <li>{{$review->created_at->diffForHumans()}}</li>
                                                    <li><img src="{{ asset('frontend/images/verify.png') }}" alt="Images">
                                                        Verified Purchase</li>
                                                    <li><i class="las la-thumbs-up"></i> 0</li>
                                                </ul>
                                            </div>
                                        </div>
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                            <div class="tab-pane fade" id="sr3-tab-pane" role="tabpanel" aria-labelledby="sr3-tab"
                                tabindex="0">
                                <div class="seller-review-list">
                                    @foreach($seller_review as $review)
                                        @if($review->response==='3')
                                        <div class="seller-review-wrap">
                                            <div class="seller-review-media">
                                                <img src="{{ asset('frontend/images/emo3.png') }}" alt="Images">
                                            </div>
                                            <div class="seller-review-content">
                                                <h3>Negative</h3>
                                                <ul>
                                                    <li>{{ucfirst($review->user->name)}}</li>
                                                    <li>{{$review->created_at->diffForHumans()}}</li>
                                                    <li><img src="{{ asset('frontend/images/verify.png') }}" alt="Images">
                                                        Verified Purchase</li>
                                                    <li><i class="las la-thumbs-up"></i> 0</li>
                                                </ul>
                                            </div>
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="seller-product-review">
                    <div class="seller-product-review-head">
                        <h3>Product Ratings and Reviews ({{@$data['totalRating']}})</h3>
                        <div class="seller-product-review-filter">
                            <div class="seller-product-review-filer-list">
                                <span><i class="las la-sort"></i> Sort By:</span>
                                <div class="form-group">
                                    <select name="page_value" class="form-control form-select " id="page_select">
                                        <option value="recent">Recent</option>
                                        <option value="increasing">Rating: High to Low</option>
                                        <option value="decreasing">Rating: Low to High</option>
                                    </select>
                                </div>
                            </div>
                            <div class="seller-product-review-filer-list">
                                <span><i class="las la-filter"></i> Filter:</span>
                                <div class="form-group">
                                    <select name="star_page" class="form-control form-select " id="star-select">
                                        <option value="all">All Stars</option>
                                        <option value="5">5 Star</option>
                                        <option value="4">4 Star</option>
                                        <option value="3">3 Star</option>
                                        <option value="2">2 Star</option>
                                        <option value="1">1 Star</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="seller-product-opinion" id="seller-filter-data">
                        @foreach($seller_review as $review)
                        
                            <div class="seller-product-opinion-list">
                                <div class="seller-product-opinion-media">
                                    <a href="{{ route('product.details', @$review->product->slug) }}"
                                        title="{{@$review->product->name}}">
                                        <img src="{{ @$review->product->images[0]->image}}" alt="Images">
                                    </a>
                                </div>
                                <div class="seller-product-opinion-content">
                                    <h3>
                                        <a href="{{ route('product.details', @$review->product->slug) }}">{{@$review->product->name}}</a>
                                    </h3>
                                    <div class="seller-product-opinion-rating">
                                        @for ($i=1;$i<=$review->rating;$i++)
                                            <i class="las la-star"></i>
                                        @endfor
                                        
                                    </div>
                                    <p>
                                        {{ $review->message}}
                                    </p>
                                    <div class="seller-product-opinion-thumbnails">
                                        <ul class="lightgallery">
                                            @if($review->image !=null && !empty($review->image))
                                                @foreach (json_decode($review->image) as $image)
                                                <li data-src="{{ asset('Uploads/review/'.$image->title) }}">
                                                    <img src="{{ asset('Uploads/review/'.$image->title) }}" alt="Images">
                                                </li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                    <div class="seller-review-content">
                                        <ul>
                                            <li>{{@$seller->company_name ?? null}}</li>
                                            <li>{{$review->created_at->diffForHumans()}}</li>
                                            <li><img src="{{ asset('frontend/images/verify.png') }}" alt="Images">
                                                Verified Purchase</li>
                                            <li><i class="las la-thumbs-up"></i> 0</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- StoreAll Product End  -->

@endsection

@push('script')
    
    <script>
        $('#page_select,#star-select').change(function(event)
        {
            var page_value=$('#page_select').val();
            var star_value=$('#star-select').val();
            var seller_id=$('#seller_id').val();
            
            $.ajax({
                url:"{{ route('seller.filter') }}",
                type:"get",
                data:{
                    page_value:page_value,
                    star_value:star_value,
                    seller_id:seller_id
                },
                success:function(response)
                {
                    if(response.error)
                    {
                        alert(response.msg);
                    }
                    $('#seller-filter-data').replaceWith(response);
                    
                }
            });
            
        });
    </script>

@endpush
