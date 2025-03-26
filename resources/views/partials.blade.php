
{{-- CUP SECTION --}}
<div class="bulk-section-wrap" id="cupSection">
    <div class="owl-carousel owl-theme bulk-carousel">
        @foreach ($cups as $product)
            <div class="item">
                <div class="bulk-section-col">
                    <div class="bulk-section-media">
                        <a href="{{ route('product.details', $product->slug) }}"
                            title="{{ $product->name }}">
                            <img src="{{ $product->images->first()->image ?? asset('dummyimage.png') }}" alt="image" />
                        </a>
                        @php
                            echo getDiscountValue($product);
                        @endphp
                    </div>
                    <div class="bulk-section-content">
                        <h3><a
                                href="{{ route('product.details', $product->slug) }}">{{ Str::limit($product->name, 35, $end = '...') }}</a>
                        </h3>

                        @foreach ($product->stocks as $key => $stock)
                            @if ($key == 0)
                                @php
                                    $offer = getOfferProduct($product, $stock);
                                @endphp
                                @if ($offer != null)
                                    <del class="enable-del">$. {{ formattedNepaliNumber($stock->price) }}</del>
                                    {{-- @if ($stock->special_price != null)
                                        <del>$. {{ $stock->special_price }}</del>
                                    @endif --}}
                                    <p class="price_list">$. {{ formattedNepaliNumber($offer) }}</p>
                                @elseif($stock->special_price)
                                    <del class="enable-del">$. {{ formattedNepaliNumber($stock->price) }}</del>
                                    <p class="price_list">$. {{ formattedNepaliNumber($stock->special_price) }}</p>
                                @else
                                    <p class="price_list">$. {{ formattedNepaliNumber($stock->price) }}</p>
                                @endif
                            @endif
                        @endforeach
                        <div class="product_rating">
                            @php
                            $rating = intval($product->rating);
                            @endphp
                            @for ($i = 0; $i < $rating; $i++)
                                <i class="las la-star"></i>
                            @endfor
                        </div>
                        {{-- <p>Rs {{ $new_arrival->stocks->first()->price ?? '' }}</p> --}}
                        {{-- <span>{{ $new_arrival->stocks->first()->quantity ?? '' }} Pieces</span> --}}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

{{-- CONE SECTION --}}
<div class="bulk-section-wrap" id="coneSection">
    <div class="owl-carousel owl-theme bulk-carousel">
        @foreach ($cones as $product)
            <div class="item">
                <div class="bulk-section-col">
                    <div class="bulk-section-media">
                        <a href="{{ route('product.details', $product->slug) }}"
                            title="{{ $product->name }}">
                            <img src="{{ $product->images->first()->image ?? asset('dummyimage.png') }}" alt="image" />
                        </a>
                        @php
                            echo getDiscountValue($product);
                        @endphp
                    </div>
                    <div class="bulk-section-content">
                        <h3><a
                                href="{{ route('product.details', $product->slug) }}">{{ Str::limit($product->name, 35, $end = '...') }}</a>
                        </h3>

                        @foreach ($product->stocks as $key => $stock)
                            @if ($key == 0)
                                @php
                                    $offer = getOfferProduct($product, $stock);
                                @endphp
                                @if ($offer != null)
                                    <del class="enable-del">$. {{ formattedNepaliNumber($stock->price) }}</del>
                                    {{-- @if ($stock->special_price != null)
                                        <del>$. {{ $stock->special_price }}</del>
                                    @endif --}}
                                    <p class="price_list">$. {{ formattedNepaliNumber($offer) }}</p>
                                @elseif($stock->special_price)
                                    <del class="enable-del">$. {{ formattedNepaliNumber($stock->price) }}</del>
                                    <p class="price_list">$. {{ formattedNepaliNumber($stock->special_price) }}</p>
                                @else
                                    <p class="price_list">$. {{ formattedNepaliNumber($stock->price) }}</p>
                                @endif
                            @endif
                        @endforeach
                        <div class="product_rating">
                            @php
                            $rating = intval($product->rating);
                            @endphp
                            @for ($i = 0; $i < $rating; $i++)
                                <i class="las la-star"></i>
                            @endfor
                        </div>
                        {{-- <p>Rs {{ $new_arrival->stocks->first()->price ?? '' }}</p> --}}
                        {{-- <span>{{ $new_arrival->stocks->first()->quantity ?? '' }} Pieces</span> --}}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

{{-- BAR SECTION --}}
<div class="bulk-section-wrap" id="barSection">
    <div class="owl-carousel owl-theme bulk-carousel">
        @foreach ($bars as $product)
            <div class="item">
                <div class="bulk-section-col">
                    <div class="bulk-section-media">
                        <a href="{{ route('product.details', $product->slug) }}" title="{{($product->name) }}">
                            <img src="{{ asset($product->images->first()->image ?? asset('dummyimage.png')) }}"
                                alt="image" />
                        </a>
                        @php
                            echo getDiscountValue($product);
                        @endphp

                    </div>
                    
                    <div class="bulk-section-content {{(getOfferProduct($product, $product->stocks[0])!=null) ? 'show-del-price': (($product->stocks[0]->special_price!=null) ? 'show-del-price' : '')}}">
                        <h3><a
                                href="{{ route('product.details', $product->slug) }}">{{ Str::limit($product->name, 35, $end = '...') }}</a>
                        </h3>

                        @foreach ($product->stocks as $key => $stock)
                            @if ($key == 0)
                                @php
                                    $offer = getOfferProduct($product, $stock);
                                @endphp
                                @if ($offer != null)
                                    <del class="enable-del">$. {{ formattedNepaliNumber($stock->price) }}</del>
                                    {{-- @if ($stock->special_price != null)
                                        <del>$. {{ formattedNepaliNumber($stock->special_price) }}</del>
                                    @endif --}}
                                    <p class="price_list">$. {{ formattedNepaliNumber($offer) }}</p>
                                @elseif($stock->special_price)
                                    <del  class="enable-del">$. {{ formattedNepaliNumber($stock->price) }}</del>
                                    <p class="price_list">$.
                                        {{ formattedNepaliNumber($stock->special_price) }}</p>
                                @else
                                    <p class="price_list">$. {{ formattedNepaliNumber($stock->price) }}</p>
                                @endif
                            @endif
                        @endforeach
                        <div class="product_rating">
                            @php
                                $rating = intval($product->rating);
                            @endphp
                            @for ($i = 0; $i < $rating; $i++)
                                <i class="las la-star"></i>
                            @endfor
                        </div>
                        {{-- <p>Rs {{ $top_sell->stocks->first()->price ?? '' }}</p> --}}
                        {{-- <span>{{ $top_sell->stocks->first()->quantity ?? '' }} Pieces</span> --}}
                    </div>
                </div>
            </div>
        @endforeach

    </div>
</div>

{{-- FAMILY PACK SECTION --}}
<div class="bulk-section-wrap" id="familyPackSection">
    <div class="owl-carousel owl-theme bulk-carousel">
        @foreach ($family_packs as $product)
            <div class="item">
                <div class="bulk-section-col">
                    <div class="bulk-section-media">
                        <a href="{{ route('product.details', $product->slug) }}"
                            title="{{ $product->name }}">
                            <img src="{{ $product->images->first()->image ?? asset('dummyimage.png') }}" alt="image" />
                        </a>
                        @php
                            echo getDiscountValue($product);
                        @endphp
                    </div>
                    <div class="bulk-section-content">
                        <h3><a
                                href="{{ route('product.details', $product->slug) }}">{{ Str::limit($product->name, 35, $end = '...') }}</a>
                        </h3>

                        @foreach ($product->stocks as $key => $stock)
                            @if ($key == 0)
                                @php
                                    $offer = getOfferProduct($product, $stock);
                                @endphp
                                @if ($offer != null)
                                    <del class="enable-del">$. {{ formattedNepaliNumber($stock->price) }}</del>
                                    {{-- @if ($stock->special_price != null)
                                        <del>$. {{ $stock->special_price }}</del>
                                    @endif --}}
                                    <p class="price_list">$. {{ formattedNepaliNumber($offer) }}</p>
                                @elseif($stock->special_price)
                                    <del class="enable-del">$. {{ formattedNepaliNumber($stock->price) }}</del>
                                    <p class="price_list">$. {{ formattedNepaliNumber($stock->special_price) }}</p>
                                @else
                                    <p class="price_list">$. {{ formattedNepaliNumber($stock->price) }}</p>
                                @endif
                            @endif
                        @endforeach
                        <div class="product_rating">
                            @php
                            $rating = intval($product->rating);
                            @endphp
                            @for ($i = 0; $i < $rating; $i++)
                                <i class="las la-star"></i>
                            @endfor
                        </div>
                        {{-- <p>Rs {{ $new_arrival->stocks->first()->price ?? '' }}</p> --}}
                        {{-- <span>{{ $new_arrival->stocks->first()->quantity ?? '' }} Pieces</span> --}}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>