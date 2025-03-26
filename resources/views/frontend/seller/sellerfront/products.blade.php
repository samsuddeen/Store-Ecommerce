@extends('frontend.layouts.app')
@section('title', env('DEFAULT_TITLE') . '|' . @$product->name)
@section('css')
@endsection
@section('content')


    <!-- Banner  -->
    @include('frontend.seller.sellerfront.inc.banner')
    <!-- Banner End  -->

    <!-- Menu  -->
    @include('frontend.seller.sellerfront.inc.menu')
    <!-- Menu End  -->

    <!-- Store All Product  -->
    <section class="store-all-product mt mb">
        <div class="container">
            @include('frontend.seller.sellerfront.inc.sellerpage')
            <div>
                <input type="hidden" name="slug" id="slug" value="{{ @$slug }}">
                <input type="hidden" name="slug" id="seller_id" value="{{ @$seller->id }}">
            </div>
            <div class="store-all-product-list">
                <div class="category-head">
                    <div class="category_product_number">
                        <p><span>{{ count(@$all_product) }}</span> items found in Jhingu Store</p>
                    </div>
                    <div class="category-top-section">
                        <div class="in_flex_box">
                            <div class="wrap-col">
                                <div class="wrap_select">
                                    <p>Sort by :</p>
                                    <select class="form-select for_space form-control" id="data_sort"
                                        aria-label="Default select example">
                                        <option selected>Select option</option>
                                        <option value="ASC">A to Z</option>
                                        <option value="DESC">Z to A</option>
                                        <option value="increasing">Low to high</option>
                                        <option value="decreasing">high to low</option>
                                        <option value="recent">recent</option>
                                        <option value="old">old</option>
                                    </select>
                                </div>
                                <div class="wrap_select">
                                    <p>Show :</p>
                                    <select class="form-select form-control" id='paginate'
                                        aria-label="Default select example">
                                        <option value="all" selected>Select option</option>
                                        <option value="10">show 10</option>
                                        <option value="20">show 20</option>
                                        <option value="30">show 30</option>
                                        <option value="all">show All</option>
                                    </select>
                                </div>
                            </div>
                            <div class="wrap-nav">
                                <nav>
                                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                        <button class="nav-link active" id="grid-tab" data-bs-toggle="tab"
                                            data-bs-target="#grid" type="button" role="tab" aria-controls="grid"
                                            aria-selected="true" title="Grid View"><i
                                                class="las la-th-large"></i></button>
                                        <button class="nav-link" id="list-tab" data-bs-toggle="tab"
                                            data-bs-target="#list" type="button" role="tab" aria-controls="list"
                                            aria-selected="false" title="List View"><i class="las la-list"></i></button>
                                    </div>
                                </nav>
                                <div class="mobile-filters" title="Filter">
                                    Filter <i class="las la-filter"></i>
                                </div>
                            </div>
                        </div>
                        <div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-3 cat-filter">
                        <div class="cat-close" title="close">
                            <i class="las la-times-circle"></i>
                        </div>
                        <div class="cat_sideBar_wrap">
                            <div class="filter-list">
                                <h3>Filter By Colors</h3>
                                <div class="color_filtergroup">
                                    @foreach ($colors as $key => $color)
                                        @if ($key < 10)
                                            <div class="form-check">
                                                <input class="color_id form-check-input" name="color" type="checkbox"
                                                    value="{{ $color->id }}" id="color">
                                                {{-- <div class="color_indicator" style="background-color: {{ $color->colorCode }}">
                                                </div> --}}
                                                <label class="form-check-label" for="flexCheckDefault">
                                                    {{ $color->title }}
                                                </label>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                                <div class="moretext">
                                    @foreach ($colors as $key => $color)
                                        @if ($key > 9)
                                            <div class="form-check">
                                                <input class="color_id form-check-input" name="color" type="checkbox"
                                                    value="{{ $color->id }}" id="color">
                                                {{-- <div class="color_indicator" style="background-color: {{ $color->colorCode }}">
                                                </div> --}}
                                                <label class="form-check-label" for="flexCheckDefault">
                                                    {{ $color->title }}
                                                </label>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                                <a class="moreless-button btns" href="javascript:void(0)">More Colors</a>
                            </div>
                            <div class="filter-list">
                                <h3>Filter By Price</h3>
                                <form class="price_filterGroup">
                                    <input type="number" name="min" id="min_price" min="0"
                                        class="form-control min" placeholder="Min">
                                    <span>-</span>
                                    <input type="number" name="max" id="max_price" min="0"
                                        class="form-control max" placeholder="Max">
                                    <button type='button' class="submit" id="submit"><i
                                            class="las la-search"></i></button>
                                </form>
                            </div>
                            <div class="filter-list">
                                <h3>Filter By Brands</h3>
                                <div id="brandsection">
                                    <div class="brandarticle">
                                        <div>
                                            @foreach ($brands as $key => $brand)
                                                @if ($key < 10)
                                                    <div class="form-check">
                                                        <input class="form-check-input brand_id" name="brand"
                                                            type="checkbox" value="{{ $brand->id }}" id="brand">
                                                        <label class="form-check-label" for="flexCheckDefault">
                                                            {{ $brand->name }}
                                                        </label>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                        <div class="morebrand">
                                            @foreach ($brands as $key => $brand)
                                                @if ($key > 9)
                                                    <div class="form-check">
                                                        <input class="form-check-input brand_id" name="brand"
                                                            type="checkbox" value="{{ $brand->id }}" id="brand">
                                                        <label class="form-check-label" for="flexCheckDefault">
                                                            {{ $brand->name }}
                                                        </label>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                    <a class="brandmoreless-button btns" href="javascript:void(0)">More Brands</a>
                                </div>
                            </div>
                            <div class="cat-submit">
                                <button type="submit">Apply Filter</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-9">
                        <div class="category-main">
                            <div class="tab-content" id="seller-nav-tabContent">
                                <div class="tab-pane show active" id="grid" role="tabpanel"
                                    aria-labelledby="grid-tab" tabindex="0">
                                    <div class="grid-view" id="product">
                                        <div class="row margin">
                                            @if(count($all_product) >0)
                                            @foreach ($all_product as $product)
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
                                                            <a href="{{ route('product.details', $product->slug) }}"
                                                                title="{{ $product->name }}" data-id={{ $product->id }}>
                                                                <img src="{{ $product->images->first()->image }}"
                                                                    alt="">
                                                            </a>


                                                            <div class="add_cart_btn">
                                                                <a href="javascript:;" class="ajax-add-to-cart"
                                                                    data-product_id="{{ $product->id }}">
                                                                    <i class="las la-shopping-cart"></i>
                                                                </a>
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
                                                                                <del>$.{{ formattedNepaliNumber($stock->price) }}</del><span
                                                                                    class="price_list">$.{{ formattedNepaliNumber($offer) }}</span>
                                                                            @elseif($stock->special_price)
                                                                                <del>$.{{ formattedNepaliNumber($stock->price) }}</del><span
                                                                                    class="price_list">$.{{ formattedNepaliNumber($stock->special_price) }}</span>
                                                                            @else
                                                                                <span
                                                                                    class="price_list">$.{{ formattedNepaliNumber($stock->price) }}</span>
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
                                            <h4 class="text-center">Sorry No Item Found !!</h4>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="list" role="tabpanel" aria-labelledby="list-tab"
                                    tabindex="0">
                                    <div class="list-view-category product-view" id="list_view">
                                        <div class="row margin">
                                            @if(count($all_product) >0)
                                            @foreach ($all_product as $product)
                                                <div class="col-lg-6 col-md-6 padding">
                                                    <div class="product-col">
                                                        @foreach ($product->featuredSections as $feature)
                                                            @if ($feature->title == 'on_sale')
                                                                <div class="thumbnail_sellbadge">
                                                                    sale
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                        <div class="product-media">
                                                            <a href="{{ route('product.details', $product->slug) }}"
                                                                title="{{ $product->name }}" data-id={{ $product->id }}>
                                                                <img src="{{ $product->images->first()->image }}"
                                                                    alt="">
                                                            </a>

                                                            <div class="add_cart_btn">
                                                                <a href="javascript:;" class="ajax-add-to-cart"
                                                                    data-product_id="{{ $product->id }}">
                                                                    <i class="las la-shopping-cart"></i>
                                                                </a>
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
                                                                                <del>$.{{ formattedNepaliNumber($stock->price) }}</del><span
                                                                                    class="price_list">$.{{ formattedNepaliNumber($offer) }}</span>
                                                                            @elseif($stock->special_price)
                                                                                <del>$.{{ formattedNepaliNumber($stock->price) }}</del><span
                                                                                    class="price_list">$.{{ formattedNepaliNumber($stock->special_price) }}</span>
                                                                            @else
                                                                                <span
                                                                                    class="price_list">$.{{ formattedNepaliNumber($stock->price) }}</span>
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
                                            <h4 class="text-center">Sorry No Item Found !!</h4>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- StoreAll Product End  -->

@endsection
@push('script')
@include('frontend.seller.sellerfront.seller-filter-js')
@endpush