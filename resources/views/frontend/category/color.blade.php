<div class="tab-content" id="nav-tabContent">
    <div class="tab-pane show active" id="grid" role="tabpanel" aria-labelledby="grid-tab"
        tabindex="0">
        <div class="grid-view" id="product">
            <div class="row margin">
                @if(count($products) >0)
                @foreach ($products as $product)
                    <div class="col-lg-3 col-md-4 col-sm-6 padding">
                        <div class="product-col">
                            {{-- @foreach ($product->featuredSections as $feature)
                                @if ($feature->title == 'on_sale')
                                    <div class="thumbnail_sellbadge">
                                        sale
                                    </div>
                                @endif
                            @endforeach --}}
                            <div class="product-media">
                                <a href="{{ route('product.details', $product->slug) }}"
                                    title="{{ $product->name }}" data-id={{ $product->id }}>
                                    <img src="{{ $product->images->first()->image ?? asset('dummyimage.png') }}"
                                        alt="">
                                </a>
                                    
                                <div class="add_cart_btn">
                                    @if(auth()->guard('customer')->user() !=null)
                                    <a href="javascript:;" class="ajax-add-to-cart"
                                        data-product_id="{{ $product->id }}" title="Add To Cart">
                                        <i class="las la-shopping-cart"></i>
                                    </a>
                                    @else
                                    <a href="javascript:;" class="guestdirectajax-add-to-cart" data-productId="{{$product->id}}"
                                        data-product_id="{{ $product->id }}" title="Add To Cart">
                                        <i class="las la-shopping-cart"></i>
                                    </a>
                                    @endif
                                </div>                                  
                                    @php
                                        echo getDiscountValue($product);
                                    @endphp

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
                                                    <del class="enable-del">Rs.{{ formattedNepaliNumber($stock->price) }}</del><span
                                                        class="price_list">Rs.{{ formattedNepaliNumber($offer) }}</span>
                                                @elseif($stock->special_price)
                                                    <del class="enable-del">Rs.{{ formattedNepaliNumber($stock->price) }}</del><span
                                                        class="price_list">Rs.{{ formattedNepaliNumber($stock->special_price) }}</span>
                                                @else
                                                    <span
                                                        class="price_list">Rs.{{ formattedNepaliNumber($stock->price) }}</span>
                                                @endif
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                <div class="product_rating">
                                    @php
                                    $rating = intval($product->rating);
                                    @endphp
                                    @for ($i = 0; $i < $rating; $i++)
                                        <i class="las la-star"></i>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                @else
                    <h4 class="text-center">Sorry No Item Found!!</h4>
                @endif
                
            </div>
        </div>
    </div>
    <div class="tab-pane" id="list" role="tabpanel" aria-labelledby="list-tab"
        tabindex="0">
        <div class="list-view-category product-view" id="list_view">
            <div class="row margin">
                @if(count($products) >0)
                @foreach ($products as $product)
                    <div class="col-lg-6 col-md-6 padding">
                        <div class="product-col">
                            {{-- @foreach ($product->featuredSections as $feature)
                                @if ($feature->title == 'on_sale')
                                    <div class="thumbnail_sellbadge">
                                        sale
                                    </div>
                                @endif
                            @endforeach --}}
                            <div class="product-media">
                                <a href="{{ route('product.details', $product->slug) }}"
                                    title="{{ $product->name }}" data-id={{ $product->id }}>
                                    <img src="{{ $product->images->first()->image ?? asset('dummyimage.png') }}"
                                        alt="">
                                </a>                                
                                    @php
                                        echo getDiscountValue($product);
                                    @endphp
                                <div class="add_cart_btn">
                                    @if(auth()->guard('customer')->user() !=null)
                                    <a href="javascript:;" class="ajax-add-to-cart"
                                        data-product_id="{{ $product->id }}">
                                        <i class="las la-shopping-cart"></i>
                                    </a>
                                    @else
                                    <a href="javascript:;" class="guestdirectajax-add-to-cart" data-productId="{{$product->id}}"
                                        data-product_id="{{ $product->id }}">
                                        <i class="las la-shopping-cart"></i>
                                    </a>
                                    @endif
                                </div>
                            </div>
                            <div class="product-content">
                                <h3>
                                    <a href="{{ route('product.details', $product->slug) }}">
                                        {{ Str::limit($product->name, 55, $end = '...') }}
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
                                                    <del class="enable-del">Rs.{{ formattedNepaliNumber($stock->price) }}</del><span
                                                        class="price_list">Rs.{{ formattedNepaliNumber($offer) }}</span>
                                                @elseif($stock->special_price)
                                                    <del class="enable-del">Rs.{{ formattedNepaliNumber($stock->price) }}</del><span
                                                        class="price_list">Rs.{{ formattedNepaliNumber($stock->special_price) }}</span>
                                                @else
                                                    <span
                                                        class="price_list">Rs.{{ formattedNepaliNumber($stock->price) }}</span>
                                                @endif
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                <div class="product_rating">
                                    @php
                                    $rating = intval($product->rating);
                                    @endphp
                                    @for ($i = 0; $i < $rating; $i++)
                                        <i class="las la-star"></i>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                @else
                <h4 class="text-center">Sorry No Item Found!!</h4>
                @endif
            </div>
        </div>
    </div>
    {!! $products->links()!!}
</div>



<script>
    if ($('.enable-del').hasClass('enable-del')) {
        $('.enable-del').closest('#grid').addClass('show-del');
    } else {
        $('.enable-del').closest('#grid').removeClass('show-del');
    }

    if ($('.enable-del').hasClass('enable-del')) {
        $('.enable-del').closest('#list').addClass('show-del');
    } else {
        $('.enable-del').closest('#list').removeClass('show-del');
    }

</script>


