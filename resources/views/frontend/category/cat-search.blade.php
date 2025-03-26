<div class="community-btn" id="grid_list">
    <div class="row margin">
        @if (count($products) > 0)
            @foreach ($products as $key => $product)
                <div class="col-lg-3 col-md-4 col-sm-6 padding">
                    <div class="product-col">
                        @foreach ($product->featuredSections as $feature)
                            @if ($feature->title == 'on_sale')
                                <div class="thumbnail_sellbadge">
                                    sale
                                </div>
                            @endif
                        @endforeach
                        <div class="product-media">
                            <a href="{{ route('product.details', $product->slug) }}" title="{{ $product->name }}"
                                data-id={{ $product->id }}>
                                <img src="{{ $product->images->first()->image ?? asset('dummyimage.png') }}" alt="">
                            </a>


                            <div class="add_cart_btn">
                                @if(auth()->guard()->user() !=null)
                                <a href="javascript:;" class="ajax-add-to-cart" data-product_id="{{ $product->id }}">
                                    <i class="las la-shopping-cart"></i>
                                </a>
                                @else
                                <a href="javascript:;" class="guestdirectajax-add-to-cart" data-productId="{{$new_arrival->id}}"
                                    <i class="las la-shopping-cart"></i>
                                </a>
                                @endif
                            </div>


                        </div>
                        <div class="product-content">
                            <h3>
                                <a href="{{ route('product.details', $product->slug) }}">
                                    {{ Str::limit($product->name, 35, $end = '...') }}
                                </a>
                            </h3>
                            <div class="price-group">
                                <div class="old-price-list">
                                    @foreach ($product->stocks as $key => $stock)
                                        @php
                                            $offer = getOfferProduct($product, $stock);
                                        @endphp
                                        @if ($key == 0)
                                            @php
                                                $offer = getOfferProduct($product, $stock);
                                            @endphp
                                            @if ($offer != null)
                                                <del>{{ formattedNepaliNumber($stock->price) }}</del><span
                                                    class="price_list">{{ formattedNepaliNumber($offer) }}</span>
                                            @elseif($stock->special_price)
                                                <del>{{ formattedNepaliNumber($stock->price) }}</del><span
                                                    class="price_list">{{ formattedNepaliNumber($stock->special_price) }}</span>
                                            @else
                                                <span class="price_list">{{ formattedNepaliNumber($stock->price) }}</span>
                                            @endif
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <h4 class="text-center">Sorry No Item Found!!</h4>
        @endif
    </div>
    <a class="btn btn-sm btn-danger load-button" data-last_id="<?php echo $product->id; ?>">Load More</a>
</div>
