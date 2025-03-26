@extends('frontend.layouts.app')
@section('title', env('DEFAULT_TITLE') . '|' . 'Seller')
@section('css')
@endsection
@section('content')


    <!-- Banner  -->
    @include('frontend.seller.sellerfront.inc.banner')
    <!-- Banner End  -->

    <!-- Menu  -->
    @include('frontend.seller.sellerfront.inc.menu')
    <!-- Menu End  -->

    <!-- Mobile Categories -->
    <div id="mySidenav2" class="sidenav">
        <div class="stellarnav">
            <ul class="menu-lists">
                <li>
                    <div class="mobile-logo">
                        <h3>All Categories</h3>
                        <a href="javascript:void(0)" class="closebtn"><i class="las la-times"></i></a>
                    </div>
                </li>
                @foreach ($categories as $category)
                    <li>
                        <a href="{{ route('category.show', $category->slug) }}" class="has-arrow">
                            {{ $category->title }}

                        </a>
                        @if (count($category->children) > 0)
                            @foreach ($category->children as $secondChild)
                                <ul>
                                    <li>
                                        <a
                                            href="{{ route('category.show', $secondChild->slug) }}">{{ $secondChild->title }}</a>
                                        @if (count($secondChild->children) > 0)
                                            @foreach ($secondChild->children as $thirdChild)
                                                <ul>
                                                    <li>
                                                        <a href="{{ route('category.show', $thirdChild->slug) }}"
                                                            class="has-arrow">{{ $thirdChild->title }}</a>
                                                    </li>
                                                </ul>
                                            @endforeach
                                        @endif
                                    </li>
                                </ul>
                            @endforeach
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <!-- Mobile Categories End -->

    <!-- Store Page  -->
    <section class="store-page mt mb">
        <div class="container">
            @include('frontend.seller.sellerfront.inc.sellerpage')
            @if (!empty(session('seller_parent_category')))
                @foreach (session('seller_parent_category') as $parent_cat)
                    <div class="store-categories">
                        <div class="row m-0">
                            <div class="col-lg-3 p-0">
                                <div class="store-categories-main">
                                    <div class="store-cat-wrap">
                                        <div class="store-cat-img">
                                            <a href="{{ route('seller.products', [@$seller->slug, @$parent_cat['parent_cat']->slug]) }}"
                                                title="Laptop Components">
                                                <img src="{{ @$parent_cat['parent_cat']->image }}" alt="images">
                                            </a>
                                        </div>
                                        <div class="store-cat-info">
                                            <h3>
                                                <a
                                                    href="{{ route('seller.products', [@$seller->slug, @$parent_cat['parent_cat']->slug]) }}">{{ @$parent_cat['parent_cat']->title }}</a>
                                            </h3>
                                            <div class="store-cat-btn">
                                                <a
                                                    href="{{ route('seller.products', [@$seller->slug, @$parent_cat['parent_cat']->slug]) }}">View
                                                    More <i class="las la-long-arrow-alt-right"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-9 p-0">
                                <div class="store-categories-list">
                                    <div class="owl-carousel owl-theme seller_store">
                                        @if (count($parent_cat['product']) > 0)
                                            @foreach ($parent_cat['product'] as $product)
                                                {{-- @dd($product) --}}
                                                <div class="item">
                                                    <div class="store-product-wrap">
                                                        <div class="store-product-img">
                                                            <a href="{{ route('product.details', $product->slug) }}"
                                                                title="Shangrila 100% Cotton T-Shirt For Woman">
                                                                <img src="{{ @$product->images[0]->image }}"
                                                                    alt="images">
                                                            </a>
                                                        </div>
                                                        <div class="store-product-info">
                                                            <h3>
                                                                <a
                                                                    href="{{ route('product.details', $product->slug) }}">{{ @$product->name }}</a>
                                                            </h3>

                                                            @foreach ($product->stocks as $key => $stock)
                                                                @php
                                                                    $offer = getOfferProduct($product, $stock);
                                                                @endphp
                                                                @if ($key == 0)
                                                                    @php
                                                                        $offer = getOfferProduct($product, $stock);
                                                                    @endphp
                                                                    @if ($offer != null)
                                                                        <del>$.{{ formattedNepaliNumber($stock->price) }}</del><span
                                                                            class="price_list">$.{{ formattedNepaliNumber($offer) }}</span>
                                                                    @elseif($stock->special_price)
                                                                        <del>$.{{ formattedNepaliNumber($stock->price) }}</del><span
                                                                            class="price_list">$.{{ formattedNepaliNumber($stock->special_price) }}</span>
                                                                    @else
                                                                        <span class="price_list">$.{{ formattedNepaliNumber($stock->price) }}</span>
                                                                    @endif
                                                                @endif
                                                            @endforeach

                                                            {{-- @dd($product->rating) --}}
                                                            <div class="store-rating">
                                                                @if ($product->rating > 1)
                                                                    @for ($i = 0; $i < $product->rating; $i++)
                                                                        <i class="las la-star"></i>
                                                                    @endfor
                                                                @else
                                                                    @for ($i = 0; $i < 5; $i++)
                                                                        <i class="las la-star"></i>
                                                                    @endfor
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </section>
    <!-- Store Page End  -->

    <!-- Store Related Product  -->
    <section class="store-related-product overall-product mb">
        <div class="container">
            <div class="main-title">
                <h3>Just for You</h3>
            </div>
            <ul>
                @if (!empty(session('just_for_you_product')))
                    @foreach (session('just_for_you_product') as $product)
                        <li>
                            <div class="product-col">
                                <div class="product-media">
                                    <a href="{{ route('product.details', $product->slug) }}"
                                        title="Shangrila 100% Cotton T-Shirt For Woman">
                                        <img src="{{ @$product->images[0]->image }}" alt="images">
                                    </a>
                                </div>
                                <div class="product-content">
                                    <h3>
                                        <a href="{{ route('product.details', $product->slug) }}">{{ @$product->name }}</a>
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
                                                        <del>$.{{ formattedNepaliNumber($stock->price) }}</del><span
                                                            class="price_list">$.{{ formattedNepaliNumber($offer) }}</span>
                                                    @elseif($stock->special_price)
                                                        <del>$.{{ formattedNepaliNumber($stock->price) }}</del><span
                                                            class="price_list">$.{{ formattedNepaliNumber($stock->special_price) }}</span>
                                                    @else
                                                        <span class="price_list">$.{{ formattedNepaliNumber($stock->price) }}</span>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>
    </section>
    <!-- Store Related Product End  -->


@endsection
