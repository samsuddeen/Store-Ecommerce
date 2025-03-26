{{-- LATEST PRODUCTS --}}

@foreach ($cones as $product)
<div class="item">
    <div class="bulk-section-col">  
        <div class="bulk-section-media">
            <a href="{{ route('product.details', $product->slug) }}"
                title="{{ $product->name }}">
                @if($product->images->first()->image)
                <img src="{{$product->images->first()->image}}" alt="{{ $product->slug }}" loading="lazy"/>
                @else
                <img src="{{ asset('frontend/img/folder.png') }}" alt="{{ $product->slug }}" loading="lazy"/>
                @endif
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
            {{-- <p>Rs {{ $product->stocks->first()->price ?? '' }}</p> --}}
            {{-- <span>{{ $product->stocks->first()->quantity ?? '' }} Pieces</span> --}}
        </div>
    </div>
</div>
@endforeach