@extends('frontend.layouts.app')
@section('title', @$product->name)
@section('css')
<style>
.product-color.active{
        border: 1px solid #000000;
        /* display: flex; */
        padding: 3px;
    }    

    .appendFlex{
         display: flex;
    }
</style>
@endsection
@section('content')
    <?php
    if (!empty(session('detail_info_address'))) {
        $session_data = request()->session()->get('detail_info_address');
    }
    ?>
    <div class="review-popup common-popup">
        <div class="modal fade" id="reviews-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title" id="exampleModalLabel">Write a Reviews</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="write-review-form">
                            <form action="">
                                <div class="form-group">
                                    <label for="rating">Review Rating</label>
                                    <div class="rating-counts">
                                        <div class="ratingControl">
                                            <input id="score100" class="ratingControl__radio" type="radio" name="rating"
                                                value="100" />
                                            <label for="score100" class="ratingControl__star" title="Five Stars"></label>
                                            <input id="score90" class="ratingControl__radio" type="radio" name="rating"
                                                value="90" />
                                            <label for="score90" class="ratingControl__star"
                                                title="Four & Half Stars"></label>
                                            <input id="score80" class="ratingControl__radio" type="radio" name="rating"
                                                value="80" />
                                            <label for="score80" class="ratingControl__star" title="Four Stars"></label>
                                            <input id="score70" class="ratingControl__radio" type="radio" name="rating"
                                                value="70" />
                                            <label for="score70" class="ratingControl__star"
                                                title="Three & Half Stars"></label>
                                            <input id="score60" class="ratingControl__radio" type="radio" name="rating"
                                                value="60" />
                                            <label for="score60" class="ratingControl__star" title="Three Stars"></label>
                                            <input id="score50" class="ratingControl__radio" type="radio" name="rating"
                                                value="50" />
                                            <label for="score50" class="ratingControl__star"
                                                title="Two & Half Stars"></label>
                                            <input id="score40" class="ratingControl__radio" type="radio" name="rating"
                                                value="40" />
                                            <label for="score40" class="ratingControl__star" title="Two Stars"></label>
                                            <input id="score30" class="ratingControl__radio" type="radio" name="rating"
                                                value="30" />
                                            <label for="score30" class="ratingControl__star"
                                                title="One & Half Star"></label>
                                            <input id="score20" class="ratingControl__radio" type="radio" name="rating"
                                                value="20" />
                                            <label for="score20" class="ratingControl__star" title="One Star"></label>
                                            <input id="score10" class="ratingControl__radio" type="radio" name="rating"
                                                value="10" />
                                            <label for="score10" class="ratingControl__star" title="Half Star"></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="comments">Reviews</label>
                                    <textarea name="comments" class="form-control"></textarea>
                                </div>
                                <div class="common-btn">
                                    <button type="submit" class="btn">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Self Cart Modal  -->
    <div class="modal fade common-popup large-modal" id="selfcart" tabindex="-1" aria-labelledby="selfcartLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="selfcartLabel">Your Order Details</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="selfcart-wrap" id="modal_detail">
                        <div class="selfcart-head">
                            <div class="selfcart-head-left">
                                <p><i class="las la-check-circle"></i> 1 new item(s) have been added to your cart</p>
                                <div class="selfcart-product">
                                    <div class="selfcart-product-media">
                                        <a href="#" title="Brand Quality Earphone With Mic Hi Tech E535 3.5mm">
                                            <img src="https://static-01.daraz.com.np/p/4aaed3f84edee12fa5b6ed4dd050a494.jpg"
                                                alt="images">
                                        </a>
                                    </div>
                                    <div class="selfcart-product-info">
                                        <h3><a href="#">Brand Quality Earphone With Mic Hi Tech E535 3.5mm</a></h3>
                                        <span>Brand: Acer, Color: Black mix</span>
                                        <b>Rs. 250</b>
                                        <div class="selfcart-price">
                                            <div class="selfcart-price-left">
                                                <del>Rs. 500</del>
                                                <span>-50%</span>
                                            </div>
                                            <em>Qty: <b>1</b></em>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="selfcart-head-right">
                                <div class="selfcart-right-head">
                                    <h3>My Shopping Cart</h3>
                                    <span>(5 items)</span>
                                </div>
                                <ul>
                                    <li>
                                        <span>Subtotal</span>
                                        <b>Rs. 0</b>
                                    </li>
                                    <li>
                                        <span>Total</span>
                                        <b>Rs. 0</b>
                                    </li>
                                </ul>
                                <div class="btn-groups">
                                    <button type="submit" class="btns">Go To Cart</button>
                                    <button type="submit" class="btns">Checkout</button>
                                </div>
                            </div>
                        </div>
                        <div class="selfcart-body mt">
                            <div class="overall-product">
                                <div class="main-title">
                                    <h3>Just For You</h3>
                                </div>
                                <ul>
                                    <li>
                                        <div class="product-col">
                                            <div class="product-media">
                                                <a href="#"
                                                    title="Brand Quality Earphone With Mic Hi Tech E535 3.5mm">
                                                    <img src="https://test.jhigu.store/storage/photos/RAM/CORSAIR%20VENGEANC%208GB%20DDR4%203600MHZ/main-a.jpg"
                                                        alt="images">
                                                </a>
                                                <div class="product-off">
                                                    <span>Off <b>10%</b></span>
                                                </div>
                                            </div>
                                            <div class="product-content">
                                                <h3>
                                                    <a href="#">
                                                        Brand Quality Earphone With Mic Hi Tech E535 3.5mm
                                                    </a>
                                                </h3>
                                                <div class="price-group">
                                                    <div class="old-price-list">
                                                        <span class="price_list">$. 120,000</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Self Cart Modal End  -->
    {{-- @dd($productBarCode) --}}
    <!-- Breadcrumb  -->
    <nav aria-label="breadcrumb">
        <div class="container">
            <div class="breadcrumb-qr">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
                </ol>
                <div class="qr-download">
                    <li class="download-qr">
                        <a href="#"><i class="las la-qrcode"></i></a>
                        <div class="click-app-qr">
                            {{ QrCode::size(100)->encoding('UTF-8')->generate($productBarCode) }}
                        </div>
                    </li>
                </div>
            </div>

        </div>
    </nav>
    <!-- Breadcrumb End  -->

    <!-- Details Page  -->
    <section class="detail_page pb">
        <div class="container">
            <div class="details-top-part">
                <div class="row m-0">
                    <div class="col-lg-12 p-0">
                        <div class="details-page-left">
                            <div class="row">
                                <div class="col-lg-6 col-md-7">
                                    <div class="details-media">
                                        <div id="sliders" class="flexslider">
                                            <ul class="slides">
                                                @foreach ($product->images as $key => $photos)
                                                    <li>
                                                        <img src="{{ $photos->image }}" alt="images" class="images">
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <div id="carousels" class="flexslider">
                                            <ul class="slides" id="my_slide">
                                                @foreach ($product->images as $key => $photos)
                                                    <li>
                                                        <img src="{{ $photos->image }}" alt="images">
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-5">
                                    <div class="detail-page-info">
                                        <div class="rating-wrap">
                                            <div class="rating-star">
                                                @php
                                                    if ($totalRating == 0) {
                                                        $totalRating = 1;
                                                    }
                                                    $avgRating = round($totalStar / $totalRating);
                                                @endphp
                                                <span>
                                                    {{-- @for ($i = 0; $i < $product->rating; $i++) --}}
                                                        <i class="las la-star"></i>
                                                    {{-- @endfor --}}
                                                </span>
                                                <p style="font-size: 12px;">{{ formattedNepaliNumber((float) $product->rating, 0, '.', '') }} <span style="color:#898989;">|</span> {{ $totalRating }} reviews
                                                </p>
                                            </div>
                                            <a href="javascript:;" class="wishlist" data-id="{{ $product->id }}"
                                                title="Add to Wishlist">
                                                <i class="lar la-heart {{ $iswish != null ? 'las' : '' }}"
                                                    id="heart"></i>
                                            </a>
                                        </div>
                                        <h3>{{ $product->name }}</h3>
                                        
                                        <p class="product-desc">
                                            {!! @$product->short_description !!}
                                        </p>

                                        {{-- <div class="brand-wrappers">
                                            <div class="brand-price" id="stock_price" style="display:flex; gap:9px; align-items:center;">
                                                @if (@$final_data['selected_data']['offer_price'][0] != null)
                                                    <span class="offer_price">Rs
                                                        {{ formattedNepaliNumber($final_data['selected_data']['offer_price'][0]) }}
                                                    </span>
                                                    <div class="detail_discount">
                                                        <del class="original_price">Rs
                                                            {{ formattedNepaliNumber($final_data['selected_data']['original_price'][0]) }}

                                                        </del>

                                                    </div>
                                                @elseif(
                                                    @$final_data['selected_data']['offer_price'][0] == null &&
                                                        @$final_data['selected_data']['special_price'][0] != null)
                                                   
                                                    <span class="special_price">Rs
                                                        {{ formattedNepaliNumber($final_data['selected_data']['special_price'][0]) }}
                                                    </span>
                                                    <div class="detail_discount">
                                                        <del class="original_price">Rs
                                                            {{ formattedNepaliNumber(@$final_data['selected_data']['original_price'][0]) }}
                                                        </del>
                                                    </div>

                                                @else
                                                    <span class="original_price">Rs
                                                        {{ formattedNepaliNumber($final_data['selected_data']['original_price'][0]) }}
                                                    </span>
                                                @endif

                                            </div> --}}



                                            {{-- @dd($final_data); --}}
                                            <div class="brand-wrappers">
                                                <div class="brand-price" id="stock_price" style="display:flex; gap:9px; align-items:center;">
                                                    @if (!empty($final_data['price']))
                                                        <span class="offer_price">
                                                            Rs
                                                            {{ formattedNepaliNumber($final_data['price']) }}
                                                        </span>
                                                    @elseif (!empty($final_data['selected_data']['special_price'][0]))
                                                        <span class="special_price">Rs
                                                            {{ formattedNepaliNumber($final_data['selected_data']['special_price'][0]) }}
                                                        </span>
                                                        <div class="detail_discount">
                                                            <del class="original_price">Rs
                                                                {{ formattedNepaliNumber($final_data['selected_data']['original_price'][0]) }}
                                                            </del>
                                                        </div>
                                                    @else
                                                        <span class="original_price">Rs
                                                            {{ formattedNepaliNumber($final_data['selected_data']['original_price'][0]) }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="brand d-flex align-items-center">

                                                <span>Brand:</span>
                                                <div class="brand-name">
                                                    @if ($product->brand->name)
                                                        <a href="{{ route('brand-front.index', $product->brand->name) }}">
                                                            {{ $product->brand->name }}
                                                        @else
                                                            <a
                                                                href="{{ route('category.show', $product->category->slug) }}">
                                                                {{ $product->category->title }}
                                                    @endif
                                                    </a>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="product-list-infos">
                                            <form action="{{ route('cart.add-to-cart', $product->slug) }}" method="post"
                                                enctype="multipart/form-data" id="direct-checkout-form">
                                                @csrf
                                                <input type="hidden" id="product_id" name="product_id"
                                                    value="{{ $product->id }}">
                                                <input type="hidden" name="varient_id"
                                                    value="{{ $final_data['varient_id'] }}" class="varient_id_class">
                                                    <div id="attribute">
                                                        @php
                                                            $colorBlocks = '';
                                                            foreach ($attributeData['colorsFinalData'] as $colorKey=>$colors) {
                                                                $colorBlocks .=
                                                                    '<a href="javascript:;" style="margin-right:10px;font-size:20px; border-radius:100%;" class="changeColor product-color changeColorAttribute '.($colorKey==0 ? "active":"").'" data-colorCode="'.$colors['color_code'].'">
                                                                        <span title="' .
                                                                        $colors['title'] .
                                                                        '" class="product-color" style= "background-color:' .
                                                                        $colors['color_code'] .
                                                                        ';border:' .
                                                                        $colors['color_code'] .
                                                                        ';padding-left:20px; border-radius:100%;">
                                                                        </span>
                                                                    </a>';
                                                                }
                                                    
                                                            $attributeRows = '';
                                                                $attributeRows.= '<div class="d-flex justify-content-start" style="gap:40px; padding-top:20px;">';
                                                            foreach ($attributeData['stockKeys'] as $keys) {
                                                                $attributesForKey = array_filter($attributeData['finalAttribute'][$attributeData['colorsFinalData'][0]['color_code']]['attributes'], function($varient) use ($keys) {
                                                                    return $varient['title'] == $keys['title'];
                                                                });
                                                                $attributeCount = count($attributesForKey);
                                                    
                                                                $attributeRows .=
                                                                    '<div id="ul-relate-to-attribute">
                                                                        <div>
                                                                            <div>
                                                                                <div class="details-more-side text-center" style="text-transform:uppercase;font-weight:600;">
                                                                                    <input type="hidden" name="key_title[]" value="' .
                                                                                        $keys['title'] .
                                                                                    '">
                                                                                    <input type="hidden" name="key[]" value="' .
                                                                                        $keys['id'] .
                                                                                    '">
                                                                                    <p>' .
                                                                                    $keys['title'] .
                                                                                    '</p>
                                                                                </div>
                                                                            </div>
                                                                            <div>
                                                                            <div class="form-group single-prop-det">';
                                                    
                                                                            $firstVariantFound = false;
                                                                            foreach ($attributeData['finalAttribute'][$attributeData['colorsFinalData'][0]['color_code']]['attributes'] as $varient) {
                                                                                if ($keys['title'] == $varient['title']) {
                                                                                    $borderColor = !$firstVariantFound ? 'red' : 'black';
                                                                                    $firstVariantFound = true;
                                                    
                                                                                    if ($attributeCount > 1) {
                                                                                        $attributeRows .=
                                                                                        '<a href="javascript:;" data-isSame="'.$varient['is_same'].'" class="me-2 btn btn-link btn-fcs updateAttribute selecteField'.$varient['stock_id'].'" data-totalQuantity="'.$varient['totalQty'].'" data-productPrice="'.$varient['price'].'" data-productId="'.$productId.'" data-stockId="'.$varient['stock_id'].'" data-colorCode="'.$varient['color_code'].'" style="text-decoration: none; border: 2px solid ' .
                                                                                        $borderColor .
                                                                                        ';">
                                                                                            <span style="color:#000000; font-weight:500;">' .
                                                                                            $varient['value'] .
                                                                                            '</span>
                                                                                        </a>';
                                                                                    } else {
                                                                                        $attributeRows .=
                                                                                        '<span class="btn btn-link btn-fcs" style="border: 2px solid; color:#000000; font-weight:500; ' .
                                                                                        $borderColor .
                                                                                        ';">
                                                                                            ' .
                                                                                            $varient['value'] .
                                                                                            '
                                                                                        </span>';
                                                                                    }
                                                                                    $attributeRows .= '<input type="text" name="value[]" hidden="">';
                                                                                }
                                                                            }
                                                    
                                                                            $attributeRows .= '        </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>';
                                                            }
                                                            $attributeRows.= '</div>';
                                                        @endphp
                                                    
                                                        <div class="attributeColor">
                                                            <div class="attributeColor" style="display:{{count($attributeData['colorsFinalData']) > 1 ? 'flex appendFlex':''}};gap:20px">
                                                                {!! $colorBlocks !!}
                                                            </div>
                                                            
                                                            {!! $attributeRows !!}
                                                        </div>
                                                    </div>
                                                    

                                                <input type="text" class="price" name="price"
                                                    id="price-relate-to-attribute"
                                                    value="{{ $final_data['selected_data']['offer_price'] != null ? $final_data['selected_data']['offer_price'] : $final_data['selected_data']['price'] }}"
                                                    id="prices" hidden>
                                                @if (@$loginUser->wholeseller == '1')
                                                    Minimum Quanity:
                                                    @php
                                                        $minQtyWholeSeller = 1;
                                                    @endphp
                                                    @foreach ($product->stocks as $key => $stock)
                                                        @if ($key == 0)
                                                            @php
                                                                $quantity = $stock->quantity;
                                                                if (@$loginUser->wholeseller == '1') {
                                                                    $minQtyWholeSeller = (int) $stock->mimquantity;
                                                                }
                                                            @endphp
                                                        @endif
                                                    @endforeach
                                                    <span id="wholesellerminQty">
                                                        {{ @$minQtyWholeSeller }}
                                                    </span>
                                                @endif
                                                <div class="product_item_count">
                                                    <div class="container_box">

                                                        @php
                                                            $initialQty = 1;
                                                        @endphp
                                                        @foreach ($product->stocks as $key => $stock)
                                                            @if ($key == 0)
                                                                @php
                                                                    $quantity = $stock->quantity;
                                                                    if (@$loginUser->wholeseller == '1') {
                                                                        $initialQty = (int) $stock->mimquantity;
                                                                    }
                                                                @endphp
                                                            @endif
                                                        @endforeach
                                                        @php
                                                            if ($quantity == null) {
                                                                $quantity = 0;
                                                            }
                                                            if ($total_quantity_on_cart == null) {
                                                                $total_quantity_on_cart = 0;
                                                            }
                                                        @endphp

                                                        <div class="qtySelector">
                                                            <span class="decreaseQty btns-qty attributedec"
                                                                data-stockquantity="{{ $minQtyWholeSeller ?? 0 }}"><i
                                                                    class="las la-minus"></i></span>
                                                            <input type="text" hidden class="stock_qty"
                                                                value="{{ $quantity }}">
                                                            <input type="text" class="qtyValue " name="qty"
                                                                value="{{ $initialQty }}" min="{{ $initialQty }}"
                                                                max="{{ $quantity }}">
                                                            <span class="increaseQty btns-qty attribute"
                                                                data-id="{{ $quantity }}"
                                                                data-stockquantity="{{ $quantity ?? $minQtyWholeSeller }}"><i
                                                                    class="las la-plus"></i></span>
                                                        </div>
                                                        <p class="left-qty"><span class="item_show">
                                                            </span>

                                                            <span class="text-danger stock_out">

                                                            </span>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="product-button">


                                                    @if (auth()->guard('customer')->user() != null)
                                                        <button class="btn" type="submit"
                                                            data-productId="{{ $product->id }}">Add To
                                                            Cart</button>
                                                        <a href="javascript:;" class="buyss btn direct-checkout-btn">
                                                            Checkout
                                                        </a>
                                                    @else
                                                        <button class="btn ajax-add-to-cart" type="submit"
                                                            data-productId="{{ $product->id }}">Add To
                                                            Cart</button>
                                                        <a href="javascript:;" class="buyss btn guest-buy-now">
                                                            Checkout
                                                        </a>
                                                    @endif
                                                    {{-- <a href="{{route('customer.inquiry',$product->slug)}}" class="btn">
                                                        Inquiry
                                                    </a> --}}
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="col-lg-4 p-0">
                        <div class="detail_page-sidebar">=
                            <ul>
                                <li>
                                    <div class="details-list-icon">
                                        <i class="las la-map-marker-alt"></i>
                                    </div>
                                    <div class="details-list-info">
                                        <div class="details-list-head">

                                            <div class="default_address_field" style="display: flex;flex-wrap: wrap;">
                                                <span
                                                    id="currentLocation">{{ @$city->city_name ?? @$currentLocation->city_name }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="details-list-icon">
                                        <i class="las la-check-double"></i>
                                    </div>
                                    <div class="details-list-info">
                                        <div class="details-list-head">

                                            <div class="default_address_field" style="display: flex;flex-wrap: wrap;">
                                                @foreach ($product->city as $key => $city)
                                                    <span>{{ $city->city_name }}
                                                        @if ($key < count($product->city) - 1)
                                                            ,&nbsp;
                                                        @endif
                                                    </span>
                                                @endforeach
                                            </div>

                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="details-list-icon">
                                        <i class="las la-store-alt"></i>
                                    </div>
                                    <div class="details-list-info">
                                        <div class="details-list-head">
                                            <span>Fulfilled by
                                                Glass Pipe</span>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="details-list-icon">
                                        <i class="las la-truck"></i>
                                    </div>
                                    <div class="details-list-info">
                                        <div class="details-list-head">
                                            <span>Standard Delivery</span>
                                           
                                            <div class="stan-charge">
                                                <?php
                                                   if(!empty(session('detail_info_address')))
                                                   {
                                                    ?>
                                                <b>$.{{ $session_data['min_charge'] }}</b>
                                                <?php
                                                   }
                                                   else {
                                                       ?>
                                                <b>$.{{ @$default_charge }}</b>
                                                <?php
                                                   }
                                               ?>
                                            </div> 


                                        </div>
                                        <p>Enjoy free shipping with minimum spend of $. 5,000 in certain area from </p>
                                    </div>
                                </li>

                                <li>
                                    <div class="details-list-icon">
                                        <i class="las la-plane-departure"></i>
                                    </div>
                                    <div class="details-list-info">
                                        <div class="details-list-head">
                                            <span>Express Delivery</span>
                                            <b>$. 500</b>
                                        </div>
                                        <p>Enjoy free shipping with minimum spend of $. 5,000 in certain area from </p>
                                    </div>
                                </li>

                                <li>
                                    <div class="details-list-icon">
                                        <i class="las la-hand-holding-usd"></i>
                                    </div>
                                    <div class="details-list-info">
                                        <div class="details-list-head">
                                            <span>Cash on Delivery Available</span>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="details-list-icon">
                                        <i class="las la-ban"></i>
                                    </div>
                                    <div class="details-list-info">
                                        <div class="details-list-head">
                                            <span>Warranty: {{ @$product->warranty_type }}
                                                {{ @$product->warranty_period ? '/' . @$product->warranty_period . ',' : 'No Warranty' }}</span>
                                            <button type="button" class="btn" data-bs-toggle="modal"
                                                data-bs-target="#exampleModal">
                                                View Policy
                                            </button>
                                        </div>
                                    </div>

                                </li>
                                <li>
                                    <div class="details-list-icon">
                                        <i class="las la-ban"></i>
                                    </div>
                                    <div class="details-list-info">
                                        <div class="details-list-head">
                                            <span>Return Policy:</span>
                                            @if ($product->policy_data == 1)
                                            <button type="button" class="btn" data-bs-toggle="modal"
                                                data-bs-target="#exampleModal1">
                                                View Policy
                                            </button>
                                            @else
                                            <a href="{{ route('general', 'return-policy') }}" target="_blank">View Policy</a>
                                            @endif
                                        </div>
                                    </div>

                                </li>
                                <li>
                                    <div class="details-list-icon">
                                        <i class="las la-exchange-alt"></i>
                                    </div>
                                    <div class="details-list-info">
                                        <div class="details-list-head">
                                            <span>{{ @$product->returnable_time }} hr easy return</span>
                                            @if (@$seller != null)
                                                <a href="{{ route('seller', @$seller->slug) }}">Visit Store</a>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </section>
    <!-- Details Page End  -->

    <section class="details-cards">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="details-card-left">
                        <!-- Feature Section  -->
                        <div class="details-tabs">
                            <div class="details-tab-wrapper">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab"
                                            data-bs-target="#home" type="button" role="tab" aria-controls="home"
                                            aria-selected="true">Description</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="profile-tab" data-bs-toggle="tab"
                                            data-bs-target="#profile" type="button" role="tab"
                                            aria-controls="profile" aria-selected="false">Specifications</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="comments-tab" data-bs-toggle="tab"
                                            data-bs-target="#comments" type="button" role="tab"
                                            aria-controls="comments" aria-selected="false">Comments</button>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="home" role="tabpanel"
                                        aria-labelledby="home-tab">
                                        <div class="tab-content-wrapper">
                                            <p> {!! @$product->long_description !!}</p>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="profile" role="tabpanel"
                                        aria-labelledby="profile-tab">
                                        <div class="tab-content-wrapper">
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <tbody>
                                                        <tr>
                                                            <th>Brand</th>
                                                            <td>{{ $product->brand->name ?? $product->category->title }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Color</th>
                                                            <td>
                                                                @php
                                                                    $index = 1;
                                                                @endphp
                                                                @foreach ($final_data['colors'] as $key => $color)
                                                                    <span>{{ @$color['title'] }}</span>
                                                                    @if ($index < count($final_data['colors']))
                                                                        ,
                                                                        @php
                                                                            $index++;
                                                                        @endphp
                                                                    @endif
                                                                @endforeach
                                                            </td>
                                                        </tr>

                                                        @if (count(getSpecification($product)) > 0 && !empty(getSpecification($product)) && getSpecification($product) != null)
                                                            @foreach (getSpecification($product) as $product_data)
                                                                <tr>
                                                                    <th>{{ $product_data['title'] }}</th>
                                                                    <td>
                                                                        @foreach (array_unique($product_data['value']) as $key => $value)
                                                                            <span>{{ @$value }}</span>
                                                                            @if ($key < count(array_unique($product_data['value'])) - 1)
                                                                                ,
                                                                            @endif
                                                                        @endforeach
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="comments" role="tabpanel"
                                        aria-labelledby="reviews-tab">
                                        <div class="tab-content-wrapper">
                                            @if (auth()->guard('customer')->user() != null)
                                                <h3>Ask Something about Product</h3>
                                                <form action="javascript:;" method="post" id="questionAnswer">
                                                    {{-- {{ route('comment') }} --}}
                                                    @csrf
                                                    <input type="hidden" value="{{ $product->id }}" name="product_id"
                                                        id="typeCommentMessage">
                                                    <textarea class="form-control" id="comment" name="question_answer" rows="5" cols="100"
                                                        placeholder="Write Your Comment"></textarea>
                                                    <button type="submit" id="questionBtn"
                                                        class="btn">Submit</button>
                                                </form>
                                            @else
                                                <div class="btn-bulk">
                                                    <a href="{{ route('signup') }}">signUp</a>
                                                    <span>Or</span>
                                                    <a href="{{ route('Clogin') }}">login</a> to add your comment
                                                </div>
                                            @endif

                                            <div class="comments-list" id="comment-list-section">
                                                <div class="reply-comment">
                                                    @foreach ($questionAnswer as $comment)
                                                        {{-- @dd($comment) --}}
                                                        @if ($comment['question']->user != null)
                                                            <div class="comments-list-col">
                                                                <div class="comments-list-media">
                                                                    @if ($comment['question']->user && $comment['question']->user->photo)
                                                                        <img src="{{ Storage::url($comment['question']->user->photo) }}"
                                                                            alt="User Photo">
                                                                    @else
                                                                        <img src="{{ asset('frontend/images/review.png') }}"
                                                                            alt="images">
                                                                    @endif
                                                                </div>
                                                                {{-- {{ Storage::url(auth()->guard('customer')->user()->photo) }} --}}
                                                                <div class="comments-list-info">
                                                                    <b>By: {{ @$comment['question']->user->name }}</b>
                                                                    <span>
                                                                        {{ @$comment['question']->question_answer }}
                                                                    </span>
                                                                    @isset($comment['answer']->question_answer)
                                                                        <p>answer:
                                                                            {{ @$comment['answer']->question_answer }}</p>
                                                                    @endisset

                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Feature Section End -->

                        <!-- Reviews  -->
                        <div class="customer-review">
                            <div class="customer-reviews-cols">
                                <div class="reviews-single">
                                    <div class="review-single-head">
                                        <h3>Ratings & Reviews of {{ $product->name }}</h3>
                                    </div>
                                    <div class="rating_wrapper">
                                        <div class="rating_left_side">
                                            <p>{{ $avgRating === 0.0 ? 5 : $avgRating }}<span>/5</span></p>
                                            <div class="star_wrapper">
                                                @for ($i = 0; $i < $avgRating; $i++)
                                                    <i class="las la-star"></i>
                                                @endfor

                                            </div>
                                            <span class="space-span">{{ $totalRating }} Rating</span>
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
                                                    if ($totalRating == 0) {
                                                        $totalRating = 1;
                                                    }
                                                    $percentage = intval(($fiveStarRating / $totalRating) * 100);

                                                @endphp
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar"
                                                        style="width: {{ $percentage }}%" aria-valuenow="75"
                                                        aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <span class="reviews-counts">{{ $fiveStarRating }}</span>
                                            </div>
                                            <div class="line_on_">
                                                <span class="review-span">
                                                    <i class="las la-star"></i>
                                                    <i class="las la-star"></i>
                                                    <i class="las la-star"></i>
                                                    <i class="las la-star"></i>
                                                </span>
                                                @php
                                                    if ($totalRating == 0) {
                                                        $totalRating = 1;
                                                    }
                                                    $percentage = intval(($fourStarRating / $totalRating) * 100);
                                                @endphp
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar"
                                                        style="width: {{ $percentage }}%" aria-valuenow="75"
                                                        aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <span class="reviews-counts">{{ $fourStarRating }}</span>
                                            </div>
                                            <div class="line_on_">
                                                <span class="review-span">
                                                    <i class="las la-star"></i>
                                                    <i class="las la-star"></i>
                                                    <i class="las la-star"></i>
                                                </span>
                                                @php
                                                    if ($totalRating == 0) {
                                                        $totalRating = 1;
                                                    }
                                                    $percentage = intval(($threeStarRating / $totalRating) * 100);
                                                @endphp
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar"
                                                        style="width: {{ $percentage }}%" aria-valuenow=""
                                                        aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <span class="reviews-counts">{{ $threeStarRating }}</span>
                                            </div>
                                            <div class="line_on_">
                                                <span class="review-span">
                                                    <i class="las la-star"></i>
                                                    <i class="las la-star"></i>
                                                </span>
                                                @php
                                                    if ($totalRating == 0) {
                                                        $totalRating = 1;
                                                    }
                                                    $percentage = intval(($twoStarRating / $totalRating) * 100);
                                                @endphp
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar"
                                                        style="width: {{ $percentage }}%" aria-valuenow="75"
                                                        aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <span class="reviews-counts">{{ $twoStarRating }}</span>
                                            </div>
                                            <div class="line_on_">
                                                <span class="review-span">
                                                    <i class="las la-star"></i>
                                                </span>
                                                @php
                                                    if ($totalRating == 0) {
                                                        $totalRating = 1;
                                                    }
                                                    $percentage = intval(($oneStarRating / $totalRating) * 100);
                                                @endphp
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar"
                                                        style="width: {{ $percentage }}%" aria-valuenow="75"
                                                        aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <span class="reviews-counts">{{ $oneStarRating }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="comments-wrapper">
                                        <div class="comment-wrapper-head">
                                            <h2>Product Reviews</h2>
                                            <div class="comment-wrapper-filter">
                                                <div class="comment-wrapper-filter-list">
                                                    <div class="comment-wrapper-filter-label">
                                                        <i class="las la-sort"></i>
                                                        <span>Sort: </span>
                                                    </div>
                                                    <select name="" class="form-control form-select"
                                                        id="sortReview">
                                                        <option value="relevance" selected>Relevance</option>
                                                        <option value="recent">Recent</option>
                                                        <option value="high">Rating: High to Low</option>
                                                        <option value="low">Rating: Low to High</option>
                                                    </select>
                                                </div>
                                                <div class="comment-wrapper-filter-list">
                                                    <div class="comment-wrapper-filter-label">
                                                        <i class="las la-filter"></i>
                                                        <span>Filter: </span>
                                                    </div>
                                                    <select name="" class="form-control form-select"
                                                        id="starReview">
                                                        <option value="0" selected>All Stars</option>
                                                        <option value="5">5 Star</option>
                                                        <option value="4">4 Star</option>
                                                        <option value="3">3 Star</option>
                                                        <option value="2">2 Star</option>
                                                        <option value="1">1 Star</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="reviewSection">
                                            @if (isset($reviews))
                                                @foreach ($reviews as $review)
                                                    {{-- @dd($review->getReviewReply) --}}
                                                    @if (count($review->getReviewReply) > 0)
                                                        <div class="comment-col">
                                                            <div class="comment-col-head">
                                                                <div class="comment-col-left">
                                                                    <div class="comment-media">
                                                                        @if (@$review->user->photo != null)
                                                                            <img src="{{ @$review->user->photo }}"
                                                                                alt="">
                                                                        @elseif(@$review->user->social_avatar)
                                                                            <img src="{{ @$review->user->social_avater }}"
                                                                                alt="">
                                                                        @else
                                                                            <img src="{{ asset('frontend/images/avatar.png') }}"
                                                                                alt="">
                                                                        @endif
                                                                        {{-- @if (@$review->user->photo != null)
                                                                    <img src="{{ @$review->user->photo }}"
                                                                        alt="">
                                                                @else
                                                                    <img src="{{ asset('frontend/images/review.png') }}"
                                                                        alt="images">
                                                                @endif --}}
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
                                                                    <span>
                                                                        {{ date('d M Y', strtotime(@$review->created_at)) }}</span>
                                                                </div>

                                                            </div>
                                                            <p>{{ @$review->message }}</p>
                                                            <div class="customer-upload-img">
                                                                <ul class="review-gallery">
                                                                    @if (json_decode($review->image) != null)
                                                                        @foreach (json_decode($review->image) as $imageData)
                                                                            <li
                                                                                data-src="{{ asset('Uploads/review/' . $imageData->title) }}">
                                                                                <img src="{{ asset('Uploads/review/' . $imageData->title) }}"
                                                                                    alt="images">
                                                                            </li>
                                                                        @endforeach
                                                                    @endif
                                                                </ul>
                                                            </div>
                                                            {{-- <div class="review-attributes">
                                                        @if ($product->brand->name)
                                                       <p>Brand:{{@$product->brand->name}}</p>
                                                        @else
                                                            <p>Brand:{{@$product->category->title}}</p>
                                                         @endif
                                                        <p>Brand: Corsair, Color:Black, Speed: 3200MHz, DDR: 4, GB: 8GB </p>
                                                    </div> --}}

                                                            <div class="customer-review-likes">
                                                                <div class="customer-review-likes-left">
                                                                    <span><i class="las la-thumbs-up likeReview likeReviewData{{ $review->id }} {{ getUserReviewLike($review) ? 'like' : '' }}"
                                                                            data-id="{{ $review->id }}"></i> <span
                                                                            class="countLikeReviewData{{ $review->id }}">{{ reviewLikeCount($review) }}</span></span>
                                                                </div>
                                                                {{-- <div class="customer-review-likes-right">
                                                            <i class="las la-ellipsis-v"></i>
                                                            <div class="reviews-likes-action">
                                                                <ul>
                                                                    <li>Not Helpful</li>
                                                                    <li>Report Abuse</li>
                                                                </ul>
                                                            </div>
                                                        </div> --}}
                                                            </div>
                                                            @if (count($review->getReviewReply) > 0)
                                                                <div class="reviews-reply-card-col">
                                                                    @foreach ($review->getReviewReply as $reply)
                                                                        <div class="reviews-reply-card">
                                                                            <div class="reviews-reply-card-head">
                                                                                <svg style="vertical-align: bottom!important;"
                                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                                    fill="none" viewBox="0 0 24 22"
                                                                                    width="24" height="22">
                                                                                    <path fill="#528282"
                                                                                        d="M 18.6667 18.3333 H 10.6667 V 13.4445 H 9.33333 V 18.3333 H 5.33333 V 13.4445 H 4 V 18.3333 C 4 18.6575 4.14048 18.9684 4.39052 19.1976 C 4.64057 19.4268 4.97971 19.5556 5.33333 19.5556 H 18.6667 C 19.0203 19.5556 19.3594 19.4268 19.6095 19.1976 C 19.8595 18.9684 20 18.6575 20 18.3333 V 13.4445 H 18.6667 V 18.3333 Z">
                                                                                    </path>
                                                                                    <path fill="#528282"
                                                                                        d="M 22.5267 8.10946 L 19.8067 3.12279 C 19.6963 2.91916 19.5261 2.7478 19.3153 2.62796 C 19.1045 2.50813 18.8614 2.44458 18.6133 2.44446 H 5.38666 C 5.1386 2.44458 4.8955 2.50813 4.68469 2.62796 C 4.47387 2.7478 4.30371 2.91916 4.19332 3.12279 L 1.47332 8.10946 C 1.38045 8.28026 1.33251 8.46862 1.33332 8.65946 V 10.5417 C 1.33271 10.8273 1.44122 11.104 1.63999 11.3239 C 1.93376 11.6326 2.29659 11.8797 2.70381 12.0484 C 3.11103 12.2171 3.55311 12.3035 3.99999 12.3017 C 4.72917 12.3027 5.43611 12.0716 5.99999 11.6478 C 6.56386 12.0718 7.27065 12.3036 7.99999 12.3036 C 8.72933 12.3036 9.43612 12.0718 9.99999 11.6478 C 10.5639 12.0718 11.2706 12.3036 12 12.3036 C 12.7293 12.3036 13.4361 12.0718 14 11.6478 C 14.5639 12.0718 15.2706 12.3036 16 12.3036 C 16.7293 12.3036 17.4361 12.0718 18 11.6478 C 18.6294 12.1215 19.4347 12.3531 20.2466 12.2939 C 21.0584 12.2346 21.8136 11.8891 22.3533 11.33 C 22.5545 11.111 22.6654 10.8341 22.6667 10.5478 V 8.65946 C 22.6675 8.46862 22.6195 8.28026 22.5267 8.10946 Z M 20 11.0795 C 19.7152 11.0788 19.4348 11.0159 19.182 10.8957 C 18.9292 10.7756 18.7113 10.6018 18.5467 10.3889 L 18 9.70446 L 17.46 10.3889 C 17.2922 10.5986 17.0729 10.7691 16.8198 10.8866 C 16.5667 11.0041 16.287 11.0654 16.0033 11.0654 C 15.7196 11.0654 15.4399 11.0041 15.1869 10.8866 C 14.9338 10.7691 14.7145 10.5986 14.5467 10.3889 L 14 9.70446 L 13.46 10.3889 C 13.2922 10.5986 13.0729 10.7691 12.8198 10.8866 C 12.5667 11.0041 12.287 11.0654 12.0033 11.0654 C 11.7196 11.0654 11.4399 11.0041 11.1869 10.8866 C 10.9338 10.7691 10.7145 10.5986 10.5467 10.3889 L 9.99999 9.70446 L 9.45999 10.3889 C 9.29217 10.5986 9.07285 10.7691 8.81978 10.8866 C 8.56672 11.0041 8.28701 11.0654 8.00332 11.0654 C 7.71963 11.0654 7.43993 11.0041 7.18686 10.8866 C 6.93379 10.7691 6.71447 10.5986 6.54666 10.3889 L 5.99999 9.70446 L 5.45332 10.3889 C 5.28864 10.6018 5.07081 10.7756 4.81801 10.8957 C 4.5652 11.0159 4.28474 11.0788 3.99999 11.0795 C 3.74875 11.0823 3.49976 11.0357 3.26978 10.943 C 3.0398 10.8502 2.83415 10.7134 2.66666 10.5417 V 8.65946 L 5.38666 3.66668 H 18.6133 L 21.3333 8.65335 V 10.5233 C 21.1668 10.6974 20.9618 10.8371 20.732 10.933 C 20.5021 11.0288 20.2526 11.0788 20 11.0795 Z">
                                                                                    </path>
                                                                                </svg>
                                                                                <span>Respond from Store -
                                                                                    {{ @$reply->created_at->diffForHumans() }}</span>
                                                                            </div>
                                                                            <div class="reviews-reply-card-body">
                                                                                <p>
                                                                                    {{ @$reply->reply }}
                                                                                </p>
                                                                                <div class="customer-review-likes">
                                                                                    <div
                                                                                        class="customer-review-likes-left">
                                                                                        <span><i class="las la-thumbs-up sellerReviewLike sellerReviewLikeData{{ $reply->id }} {{ getUserReviewReplyLike($reply) ? 'like' : '' }}"
                                                                                                data-id="{{ $reply->id }}"></i><span
                                                                                                class="countLikeReviewReplyData{{ $reply->id }}">{{ reviewReplyLikeCount($reply) }}</span></span>
                                                                                    </div>
                                                                                    {{-- <div class="customer-review-likes-right">
                                                                        <i class="las la-ellipsis-v"></i>
                                                                        <div class="reviews-likes-action">
                                                                            <ul>
                                                                                <li>Not Helpful</li>
                                                                                <li>Report Abuse</li>
                                                                            </ul>
                                                                        </div>
                                                                    </div> --}}
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- Review End  -->

                    </div>
                </div>
                {{-- <div class="col-lg-3">
                    <div class="details-card-right">
                        <h2>From The Same Store</h2>
                        <div class="related-store-col">
                            @foreach ($sameProduct as $sameData)
                            <div class="related-store-wrap">
                                <div class="related-store-media">
                                    <a href="{{ route('product.details', $sameData->slug) }}" title="{{($sameData->name) }}">
                                        <img src="{{productDetailImage($sameData)}}" alt="images">
                                    </a>
                                </div>
                                <div class="related-store-info">
                                    <h3><a href="{{ route('product.details', $sameData->slug) }}">{{@$sameData->name}}</a></h3>
                                    @foreach ($sameData->stocks as $key => $stock)
                                                @if ($key == 0)
                                                    @php
                                                        $offer = getOfferProduct($sameData, $stock);
                                                    @endphp
                                                    @if ($offer != null)
                                                        <del class="enable-del">$. {{ formattedNepaliNumber($stock->price) }}</del>
                                                        @if ($stock->special_price != null)
                                                            <del>$. {{ formattedNepaliNumber($stock->special_price) }}</del>
                                                        @endif
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
                                    <div class="stores-rating">
                                        @php
                                                    $rating = intval($sameData->rating);
                                                @endphp
                                                @for ($i = 0; $i < $rating; $i++)
                                                    <i class="las la-star"></i>
                                                @endfor
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </section>






    <!-- Related Product  -->
    @if (count($related_products) > 0)
        <section class="related-product overall-product mt pb">
            <div class="container">
                <div class="main-title">
                    <h3>Related Product</h3>
                </div>
                <div class="owl-carousel owl-theme" id="related-product">
                    @foreach ($related_products as $related_product)
                        <div class="item">
                            <div class="product-col">
                                <div class="product-media">
                                    <a href="{{ route('product.details', $related_product->slug) }}"
                                        title="{{ $related_product->name }}">
                                        <img src="{{ $related_product->images->where('is_featured', true)->first()->image ?? null }}"
                                            alt="images">
                                    </a>
                                    @php
                                        echo getDiscountValue($related_product);
                                    @endphp
                                </div>
                                <div class="product-content">
                                    <h3>
                                        <a href="{{ route('product.details', $related_product->slug) }}">
                                            {{ Str::limit($related_product->name, 40, $end = '...') }}
                                        </a>
                                    </h3>
                                    <div class="price-group">
                                        <div class="old-price-list">
                                            {{-- @foreach ($related_product->stocks as $key => $stock)
                                            @if ($key == 0)
                                                <span class="price_list">$. {{ formattedNepaliNumber($stock->price) }}</span>
                                            @endif
                                        @endforeach --}}
                                            @foreach ($related_product->stocks as $key => $stock)
                                                @if ($key == 0)
                                                    @php
                                                        $offer = getOfferProduct($related_product, $stock);
                                                    @endphp
                                                    @if ($offer != null)
                                                        <del class="enable-del">Rs.
                                                            {{ formattedNepaliNumber($stock->price) }}</del>
                                                        {{-- @if ($stock->special_price != null)
                                                            <del class="enable-del">$. {{ formattedNepaliNumber($stock->special_price) }}</del>
                                                        @endif --}}
                                                        <p class="price_list">Rs. {{ formattedNepaliNumber($offer) }}</p>
                                                    @elseif($stock->special_price)
                                                        <del class="enable-del">Rs.
                                                            {{ formattedNepaliNumber($stock->price) }}</del>
                                                        <p class="price_list">Rs.
                                                            {{ formattedNepaliNumber($stock->special_price) }}
                                                        </p>
                                                    @else
                                                        <p class="price_list">Rs.
                                                            {{ formattedNepaliNumber($stock->price) }}</p>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="product_rating">
                                        @php
                                            $rating = intval($related_product->rating);
                                        @endphp
                                        @for ($i = 0; $i < $rating; $i++)
                                            <i class="las la-star"></i>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    <!-- Related Product End  -->

    <div class="common-popup small-popup modal fade" id="exampleModal" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Warranty Policy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{ @$product->warranty_policy }}
                </div>
            </div>
        </div>
    </div>
    <div class="common-popup small-popup modal fade" id="exampleModal1" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Return Policy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {!! @$product->return_policy != null ? @$product->return_policy : @$returnPolicy->content !!}
                </div>
            </div>
        </div>
    </div>
    {{-- Modal For session Address --}}

    {{-- Modal For Address --}}
    <div class="common-popup small-popup modal fade" id="exampleModal-address" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Change Adsress to Know Your Delivery Charge</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="#" method="post" id="default_address">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <div class="form-group">
                                    <label for="province">Choose Your Province</label>
                                    <select name="province" id="provinces" class="provinces form-control form-select">
                                        <option value="">--Please Select--</option>

                                        @foreach ($provinces as $province)
                                            <option value="{{ $province->id }}"
                                                @if (@$session_data != null) {{ $province->eng_name == @$session_data['province'] ? 'selected' : '' }}
                                            @else
                                                {{ $province->eng_name == @$default_province ? 'selected' : '' }} @endif>
                                                {{ $province->eng_name }}
                                            </option>
                                        @endforeach

                                    </select>
                                    @error('province')
                                        <div class="invalid-feedback">
                                            <i class="bx bx-radio-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12">
                                <div class="form-group">
                                    <label for="district">District</label>
                                    <select name="district" id="district" class="district form-control form-select">
                                        <option value="">---Choose Province Before District---</option>
                                        @foreach ($new_province->districts as $district)
                                            <option value="{{ $district->id }}"
                                                @if (@$session_data != null) {{ $district->np_name == @$session_data['district'] ? 'selected' : '' }}
                                            @else
                                                {{ $district->np_name == @$default_district ? 'selected' : '' }} @endif>
                                                {{ $district->np_name }} </option>
                                        @endforeach
                                    </select>
                                    @error('district')
                                        <div class="invalid-feedback">
                                            <i class="bx bx-radio-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12">
                                <div class="form-group">
                                    <label for="area"> Local Area </label>
                                    <select name="area" id="area" class="area form-control form-select">
                                        <option value="">--Please Select Your district Before It--</option>
                                        @foreach ($new_district->localarea as $local_area)
                                            <option value="{{ $local_area->id }}"
                                                @if (@$session_data != null) {{ $local_area->city_name == @$session_data['area'] ? 'selected' : '' }}
                                                @else
                                                {{ $local_area->city_name == @$default_area ? 'selected' : '' }} @endif>
                                                {{ $local_area->city_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('area')
                                        <div class="invalid-feedback">
                                            <i class="bx bx-radio-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12">
                                <button type="button" class="btn" id="default_address_button">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection
@push('script')
    @include('frontend.product.frontattributejs')
    <script>
        @isset(auth()->guard('customer')->user()->wholeseller)
            var userDataValue = "{{ auth()->guard('customer')->user()->wholeseller }}" ?? 0;
        @else
            var userDataValue = 0;
        @endisset

        $('#default_address_button').click(function() {
            $('#exampleModal-address').modal('hide');
            var form = document.getElementById('default_address');
            $.ajax({
                url: "{{ route('default_shipping_address') }}",
                type: "get",
                data: {
                    province: form['province'].value,
                    district: form['district'].value,
                    area: form['area'].value
                },
                success: function(response) {
                    if (response.error) {
                        swal({
                            title: "Sorry!",
                            text: response.msg,
                            icon: "error",
                        });
                        return false;
                    }
                    $('.default_address_field').html(response.address_html);
                    $('.stan-charge').html(response.charge);
                }
            });
        });

        $('.customer-review-likes-right i').click(function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).closest('.customer-review-likes').find('.reviews-likes-action').slideToggle('fast');
        });
        $('.reviews-likes-action').click(function(e) {
            e.stopPropagation();
        });
        $('body').click(function() {
            $('.reviews-likes-action').slideUp('fast');
        });
    </script>
    <script>
        var minVal = 1,
            maxVal = 1;

        $(".increaseQty").on('click', function() {
            var $parentElm = $(this).parents(".qtySelector");
            // var stock_qty = $parentElm.find('.stock_qty').val()
            // let stock_qty = $('.attribute').find(':selected').data('stockquantity');
            let stock_qty = $('.attribute').attr('data-stockquantity');
            maxVal = parseInt(stock_qty);
            $(this).addClass("clicked");
            setTimeout(function() {
                $(".clicked").removeClass("clicked");
            }, 100);
            var value = $parentElm.find(".qtyValue").val();
            if (value < maxVal) {
                value++;
            }
            $parentElm.find(".qtyValue").val(value);
        });

        $(".decreaseQty").on('click', function() {
            var $parentElm = $(this).parents(".qtySelector");
            $(this).addClass("clicked");
            setTimeout(function() {
                $(".clicked").removeClass("clicked");
            }, 100);
            var value = $parentElm.find(".qtyValue").val();
            // let mimquantityDec = $('.attribute').find(':selected').data('mimquantity');
            let mimquantityDec = $('.attributedec').data('stockquantity');
            if (userDataValue == '1') {
                if (value > mimquantityDec) {
                    value--;
                }
            } else {
                if (value > 1) {
                    value--;
                }
            }

            $parentElm.find(".qtyValue").val(value);
        });
    </script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
    </script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
        $(document).ready(function() {
            $('.wishlist').on('click', function() {
                let product_id = $(this).data('id');

                $.ajax({
                    url: "{{ route('addToWishlist') }}",
                    type: 'post',
                    data: {
                        product_id: product_id,
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#heart').addClass('las');
                            toastr.options = {
                                "closeButton": true,
                                "progressBar": true
                            }
                            toastr.success("Successfully added to wishlist !! ");

                        } else if (response.remove) {
                            $('#heart').removeClass('las');
                            toastr.options = {
                                "closeButton": true,
                                "progressBar": true
                            }
                            toastr.success("Successfully removed from wishlist !! ");
                        } else {
                            window.location = "{{ route('Clogin') }}";
                        }
                    },

                    error: function(response) {}
                });

            })
        })


        // to change attribute of the product
    </script>
    {{-- script for change address of delivery --}}
    <script>
        $('.provinces').change(function(e) {
            e.preventDefault();
            const province_id = $(this).val();
            $.ajax({
                url: "{{ route('show-province') }}",
                type: "get",
                data: {
                    province_id: province_id
                },
                success: function(response) {
                    if (typeof(response) != 'object') {
                        response = JSON.parse(response);
                    }
                    var dist_html =
                        "<option value=''>---Select Any One---</option>";
                    if (response.error) {
                        alert(response.error);
                    } else {
                        if (response.data.child.length > 0) {
                            $.each(response.data.child, function(index, value) {
                                dist_html += "<option value='" + value.id + "'";
                                dist_html += ">" + value.np_name + "</option>";
                            });
                        }
                    }
                    $('.district').html(dist_html);
                }
            });
        });
    </script>
    <script>
        $('.district').change(function(e) {
            e.preventDefault();
            $('.area').removeAttr('disabled');
            const district_id = $(this).val();
            $.ajax({
                url: "{{ route('show-district') }}",
                type: "get",
                data: {
                    district_id: district_id
                },
                success: function(response) {
                    if (typeof(response) != 'object') {
                        response = JSON.parse(response);
                    }
                    var area_html =
                        "<option value=''>---Select Any One---</option>";
                    if (response.error) {
                        alert(response.error);
                    } else {
                        if (response.data.child.length > 0) {
                            $.each(response.data.child, function(index, value) {
                                area_html += "<option value='" + value.id + "'";
                                area_html += ">" + value.city_name + "</option>";
                            });
                        }
                    }
                    $('.area').html(area_html);
                }
            });
        });
    </script>
    <script>
        $('.area').change(function(e) {
            e.preventDefault();
            const area_id = $(this).val();
            $.ajax({
                url: "{{ route('get-addtional-address') }}",
                type: "get",
                data: {
                    area_id: area_id
                },
                success: function(response) {
                    if (typeof(response) != 'object') {
                        response = JSON.parse(response);
                    }

                    var address_html =
                        "<option value=''>---Select Any Two---</option>";
                    if (response.error) {
                        alert(response.error);
                    } else {
                        if (response.data.child.length > 0) {
                            $.each(response.data.child, function(index, value) {
                                address_html += "<option value='" + value.id + "'";
                                address_html += ">" + value.title + "</option>";
                            });

                        }
                    }
                    $('.additional_address').html(address_html);
                }
            });
        });
    </script>
    <script src="{{ asset('frontend/js/sweetalert.min.js') }}"></script>
    @include('frontend.product.info-script')
    @include('frontend.product.guest-buy')
    <script>
        $(document).on('click', '#questionBtn', function() {
            var form = document.getElementById('questionAnswer');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route('comment') }}",
                type: "post",
                data: {
                    product_id: form['product_id'].value,
                    question_answer: form['question_answer'].value
                },
                success: function(response) {
                    if (response.validate) {
                        $.each(response.errors, function(index, value) {
                            if (value != "") {
                                toastr.options = {
                                    "closeButton": true,
                                    "progressBar": true
                                }
                                toastr.error(value);
                            }
                        });

                        return false;
                    }
                    if (response.error) {
                        toastr.options = {
                            "closeButton": true,
                            "progressBar": true
                        }
                        toastr.error(response.msg);
                        return false;
                    }

                    $('#comment-list-section').replaceWith(response);
                    toastr.options = {
                        "closeButton": true,
                        "progressBar": true
                    }
                    toastr.success("Question Sent Successfully !!");
                    $('#comment').val('');

                }
            });
        })
    </script>

    <script>
        $(document).on('change', '#sortReview,#starReview', function() {
            var sort = $('#sortReview').val();
            var star = $('#starReview').val();
            var productId = "{{ $productId ?? 0 }}";
            $.ajax({
                url: "{{ route('filter-review') }}",
                type: "get",
                data: {
                    sort: sort,
                    star: star,
                    productId: productId
                },
                success: function(response) {
                    $('#reviewSection').replaceWith(response);
                }
            });

        });
    </script>

    <script>
        $(document).ready(function() {
            $(document).on('click', '.likeReview', function() {
                const reviewId = $(this).data('id');
                $.ajax({
                    url: "{{ route('likeReview') }}",
                    type: "get",
                    data: {
                        reviewId: reviewId
                    },
                    success: function(response) {
                        if (response.login) {
                            window.location.href = response.url;
                        }
                        if (response.error) {
                            location.reload();
                        }

                        if (response.exist) {
                            $('.likeReviewData' + reviewId).removeClass('like');
                            $('.countLikeReviewData' + reviewId).text('');
                            $('.countLikeReviewData' + reviewId).text(response.count);
                            toastr.options = {
                                "closeButton": true,
                                "progressBar": true
                            }
                            toastr.success(response.msg);
                        } else {
                            $('.likeReviewData' + reviewId).addClass('like');
                            $('.countLikeReviewData' + reviewId).text('');
                            $('.countLikeReviewData' + reviewId).text(response.count);
                            toastr.options = {
                                "closeButton": true,
                                "progressBar": true
                            }
                            toastr.success(response.msg);
                        }



                    }
                });

            })
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.download-qr a').click(function() {
                $('.click-app-qr').toggleClass('showqr');
            });
        });
        $(document).ready(function() {
            $(document).on('click', '.sellerReviewLike', function() {
                const reviewReplyId = $(this).data('id');
                $.ajax({
                    url: "{{ route('likeReviewReply') }}",
                    type: "get",
                    data: {
                        reviewReplyId: reviewReplyId
                    },
                    success: function(response) {
                        if (response.login) {
                            window.location.href = response.url;
                        }
                        if (response.error) {
                            toastr.options = {
                                "closeButton": true,
                                "progressBar": true
                            }
                            toastr.error('Something Went Wrong !!');
                            location.reload();

                        }

                        if (response.exist) {
                            $('.sellerReviewLikeData' + reviewReplyId).removeClass('like');
                            $('.countLikeReviewReplyData' + reviewReplyId).text('');
                            $('.countLikeReviewReplyData' + reviewReplyId).text(response.count);
                            toastr.options = {
                                "closeButton": true,
                                "progressBar": true
                            }
                            toastr.success(response.msg);
                        } else {
                            $('.sellerReviewLikeData' + reviewReplyId).addClass('like');
                            $('.countLikeReviewReplyData' + reviewReplyId).text('');
                            $('.countLikeReviewReplyData' + reviewReplyId).text(response.count);
                            toastr.options = {
                                "closeButton": true,
                                "progressBar": true
                            }
                            toastr.success(response.msg);
                        }



                    }
                });

            })
        });
    </script>
@endpush
