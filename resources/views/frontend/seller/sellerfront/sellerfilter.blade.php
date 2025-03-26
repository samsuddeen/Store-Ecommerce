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