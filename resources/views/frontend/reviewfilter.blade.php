<div id="reviewSection">
    @if (isset($reviews))
        @foreach ($reviews as $review)
            <div class="comment-col">
                <div class="comment-col-head">
                    <div class="comment-col-left">
                        <div class="comment-media">
                            @if (@$review->user->photo != null)
                                <img src="{{ @$review->user->photo }}"
                                    alt="">
                            @else
                                <img src="{{ asset('frontend/images/review.png') }}"
                                    alt="images">
                            @endif
                        </div>
                        <div class="comment-info">
                            <h3>
                                {{ @$review->user->name }}
                                <b>
                                    <img src="{{ asset('frontend/images/verify.png') }}"
                                        alt="images">
                                    Verified Purchase
                                </b>
                            </h3>
                            @for ($i = 0; $i < $review->rating; $i++)
                                <i class="las la-star"></i>
                            @endfor
                        </div>
                    </div>
                    <div class="comment-col-right">
                        <span> {{date('d M Y',strtotime(@$review->created_at))}}</span>
                    </div>

                </div>
                <p>{{ @$review->message }}</p>
                <div class="customer-upload-img">
                    <ul class="review-gallery">
                        @if($review->image)
                        @foreach(json_decode($review->image) as $imageData)
                        <li data-src="{{ asset('Uploads/review/'.$imageData->title) }}">
                            <img src="{{ asset('Uploads/review/'.$imageData->title) }}"
                                alt="images">
                        </li>
                        @endforeach
                        @endif
                    </ul>
                </div>
                {{-- <div class="review-attributes">
                    <p>Brand: Corsair, Color:Black, Speed: 3200MHz, DDR: 4, GB: 8GB </p>
                </div> --}}
                <div class="customer-review-likes">
                    <div class="customer-review-likes-left">
                        <span><i class="las la-thumbs-up"></i> 0</span>
                    </div>
                    <div class="customer-review-likes-right">
                        <i class="las la-ellipsis-v"></i>
                        <div class="reviews-likes-action">
                            <ul>
                                <li>Not Helpful</li>
                                <li>Report Abuse</li>
                            </ul>
                        </div>
                    </div>
                </div>
                @if(count($review->getReviewReply) >0)
                <div class="reviews-reply-card-col">
                    @foreach($review->getReviewReply as $reply)
                    <div class="reviews-reply-card">
                        <div class="reviews-reply-card-head">
                            <svg style="vertical-align: bottom!important;"
                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 22" width="24" height="22">
                                <path fill="#528282"
                                    d="M 18.6667 18.3333 H 10.6667 V 13.4445 H 9.33333 V 18.3333 H 5.33333 V 13.4445 H 4 V 18.3333 C 4 18.6575 4.14048 18.9684 4.39052 19.1976 C 4.64057 19.4268 4.97971 19.5556 5.33333 19.5556 H 18.6667 C 19.0203 19.5556 19.3594 19.4268 19.6095 19.1976 C 19.8595 18.9684 20 18.6575 20 18.3333 V 13.4445 H 18.6667 V 18.3333 Z">
                                </path>
                                <path fill="#528282"
                                    d="M 22.5267 8.10946 L 19.8067 3.12279 C 19.6963 2.91916 19.5261 2.7478 19.3153 2.62796 C 19.1045 2.50813 18.8614 2.44458 18.6133 2.44446 H 5.38666 C 5.1386 2.44458 4.8955 2.50813 4.68469 2.62796 C 4.47387 2.7478 4.30371 2.91916 4.19332 3.12279 L 1.47332 8.10946 C 1.38045 8.28026 1.33251 8.46862 1.33332 8.65946 V 10.5417 C 1.33271 10.8273 1.44122 11.104 1.63999 11.3239 C 1.93376 11.6326 2.29659 11.8797 2.70381 12.0484 C 3.11103 12.2171 3.55311 12.3035 3.99999 12.3017 C 4.72917 12.3027 5.43611 12.0716 5.99999 11.6478 C 6.56386 12.0718 7.27065 12.3036 7.99999 12.3036 C 8.72933 12.3036 9.43612 12.0718 9.99999 11.6478 C 10.5639 12.0718 11.2706 12.3036 12 12.3036 C 12.7293 12.3036 13.4361 12.0718 14 11.6478 C 14.5639 12.0718 15.2706 12.3036 16 12.3036 C 16.7293 12.3036 17.4361 12.0718 18 11.6478 C 18.6294 12.1215 19.4347 12.3531 20.2466 12.2939 C 21.0584 12.2346 21.8136 11.8891 22.3533 11.33 C 22.5545 11.111 22.6654 10.8341 22.6667 10.5478 V 8.65946 C 22.6675 8.46862 22.6195 8.28026 22.5267 8.10946 Z M 20 11.0795 C 19.7152 11.0788 19.4348 11.0159 19.182 10.8957 C 18.9292 10.7756 18.7113 10.6018 18.5467 10.3889 L 18 9.70446 L 17.46 10.3889 C 17.2922 10.5986 17.0729 10.7691 16.8198 10.8866 C 16.5667 11.0041 16.287 11.0654 16.0033 11.0654 C 15.7196 11.0654 15.4399 11.0041 15.1869 10.8866 C 14.9338 10.7691 14.7145 10.5986 14.5467 10.3889 L 14 9.70446 L 13.46 10.3889 C 13.2922 10.5986 13.0729 10.7691 12.8198 10.8866 C 12.5667 11.0041 12.287 11.0654 12.0033 11.0654 C 11.7196 11.0654 11.4399 11.0041 11.1869 10.8866 C 10.9338 10.7691 10.7145 10.5986 10.5467 10.3889 L 9.99999 9.70446 L 9.45999 10.3889 C 9.29217 10.5986 9.07285 10.7691 8.81978 10.8866 C 8.56672 11.0041 8.28701 11.0654 8.00332 11.0654 C 7.71963 11.0654 7.43993 11.0041 7.18686 10.8866 C 6.93379 10.7691 6.71447 10.5986 6.54666 10.3889 L 5.99999 9.70446 L 5.45332 10.3889 C 5.28864 10.6018 5.07081 10.7756 4.81801 10.8957 C 4.5652 11.0159 4.28474 11.0788 3.99999 11.0795 C 3.74875 11.0823 3.49976 11.0357 3.26978 10.943 C 3.0398 10.8502 2.83415 10.7134 2.66666 10.5417 V 8.65946 L 5.38666 3.66668 H 18.6133 L 21.3333 8.65335 V 10.5233 C 21.1668 10.6974 20.9618 10.8371 20.732 10.933 C 20.5021 11.0288 20.2526 11.0788 20 11.0795 Z">
                                </path>
                            </svg>
                            <span>Respond from Store - {{@$reply->created_at->diffForHumans()}}</span>
                        </div>
                        <div class="reviews-reply-card-body">
                            <p>
                                {{@$reply->reply}}
                            </p>
                            <div class="customer-review-likes">
                                <div class="customer-review-likes-left">
                                    <span><i class="las la-thumbs-up"></i> 0</span>
                                </div>
                                <div class="customer-review-likes-right">
                                    <i class="las la-ellipsis-v"></i>
                                    <div class="reviews-likes-action">
                                        <ul>
                                            <li>Not Helpful</li>
                                            <li>Report Abuse</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        @endforeach
    @endif
</div>