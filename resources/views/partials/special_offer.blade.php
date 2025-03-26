{{-- LATEST PRODUCTS --}}

@foreach ($special_offers as $new_arrival)
<div class="item">
    <div class="bulk-section-col">  
        <div class="bulk-section-media">
            <a href="{{ route('product.details', $new_arrival->slug) }}"
                title="{{ $new_arrival->name }}">
                
                @if($new_arrival->images->first()->image)
                <img src="{{$new_arrival->images->first()->image}}" alt="{{ $new_arrival->slug }}" loading="lazy"/>
                @else
                <img src="{{ asset('frontend/img/folder.png') }}" alt="{{ $new_arrival->slug }}" loading="lazy"/>
                @endif
            </a>
            @php
                echo getDiscountValue($new_arrival);
            @endphp
        </div>
        <div class="bulk-section-content">
            <h3><a
                    href="{{ route('product.details', $new_arrival->slug) }}">{{ Str::limit($new_arrival->name, 35, $end = '...') }}</a>
            </h3>

            @foreach ($new_arrival->stocks as $key => $stock)
                @if ($key == 0)
                    @php
                        $offer = getOfferProduct($new_arrival, $stock);
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
                $rating = intval($new_arrival->rating);
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