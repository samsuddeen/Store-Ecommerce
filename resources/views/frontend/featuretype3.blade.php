@foreach ($data as $product)
<div class="item">
    <div class="product-col">
        <div class="product-media">
            <a href="{{ route('product.details', $product->slug) }}"
                title="{{ $product->name }}">
                @foreach ($product->images as $im => $image)
                    @if ($im == 0)
                        <img src="{{ $image->image  ?? asset('dummyimage.png')}}" alt="{{ $product->title }}">
                    @endif
                @endforeach
            </a>
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
                        @if ($key == 0)
                            @php
                                $offer = getOfferProduct($product, $stock);
                            @endphp
                            @if ($offer != null)
                                <del>Npr {{ formattedNepaliNumber($stock->price) }}</del>
                                <del>{{ formattedNepaliNumber($stock->special_price) }}</del>
                                <span class="price_list">Npr {{ formattedNepaliNumber($offer) }}</span>
                            @elseif($stock->special_price)
                                <del>Npr {{ formattedNepaliNumber($stock->price) }}</del>
                                <span class="price_list">Npr
                                    {{ formattedNepaliNumber($stock->special_price) }}</span>
                            @else
                                <span class="price_list">Npr
                                    {{ formattedNepaliNumber($stock->price) }}</span>
                            @endif
                        @endif
                    @endforeach
                </div>
                <div class="new-price">

                </div>
            </div>
        </div>
    </div>
</div>
@endforeach