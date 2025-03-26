@extends('frontend.layouts.app')
@section('title', ucfirst(@$selected_cat->title))
@section('content')

    <!-- Breadcrumb  -->
    <nav aria-label="breadcrumb" class="nav_breadcrumb">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                @if (count($main_category) > 0)
                    @foreach ($main_category as $cat)
                        <li class="breadcrumb-item">
                            {{ $cat->title }}
                        </li>
                    @endforeach
                @endif
            </ol>
        </div>
    </nav>
    <!-- Breadcrumb End -->

    <!-- Category Page  -->
    <section class="category-page mb">
        <div class="container">
            <div class="category-head">
                <div class="category_product_number">
                    <p><span>{{ @$count }}</span> items found in {{ $cat->title }}</p>
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
                                <select class="form-select form-control" id='paginate' aria-label="Default select example">
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
                                        aria-selected="true" title="Grid View"><i class="las la-th-large"></i></button>
                                    <button class="nav-link" id="list-tab" data-bs-toggle="tab" data-bs-target="#list"
                                        type="button" role="tab" aria-controls="list" aria-selected="false"
                                        title="List View"><i class="las la-list"></i></button>
                                </div>
                            </nav>
                            <div class="mobile-filters" title="Filter">
                                Filter <i class="las la-filter"></i>
                            </div>
                        </div>
                    </div>
                    <div>
                        <input type="hidden" name="slug" id="slug" value="{{ $slug }}">
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
                                <input type="number" name="min" id="min_price" min="0" class="form-control min"
                                    placeholder="Min">
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
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane show active" id="grid" role="tabpanel" aria-labelledby="grid-tab"
                                tabindex="0">
                                <div class="grid-view" id="product">
                                    <div class="row margin">
                                        @if (count($products) > 0)
                                            @foreach ($products as $product)
                                            {{-- @dd($product) --}}
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
                                                                @php
                                                                    echo getDiscountValue($product);
                                                                @endphp

                                                            <div class="add_cart_btn">
                                                                @if(auth()->guard('customer')->user() !=null)
                                                                <a href="javascript:;" class="ajax-add-to-cart"
                                                                    data-product_id="{{ $product->id }}"
                                                                    title="Add To Cart">
                                                                    <i class="las la-shopping-cart"></i>
                                                                </a>
                                                                @else
                                                                <a href="javascript:;"class="guestdirectajax-add-to-cart" data-productId="{{$product->id}}"
                                                                    data-product_id="{{ $product->id }}"
                                                                    title="Add To Cart">
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
                                                                                <del class="enable-del">Rs.{{ formattedNepaliNumber($stock->price) }}</del><span
                                                                                    class="price_list">Rs.{{ formattedNepaliNumber($offer) }}</span>
                                                                            @elseif($stock->special_price)
                                                                                <del class="enable-del">Rs.{{ formattedNepaliNumber($stock->price) }}</del><span
                                                                                    class="price_list">Rs.{{ formattedNepaliNumber($stock->special_price) }}</span>
                                                                            @else
                                                                                <span class="price_list">Rs.
                                                                                    {{ formattedNepaliNumber($stock->price) }}</span>
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
                                        @if (count($products) > 0)
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

                                                            <div class="add_cart_btn">
                                                                @if(auth()->guard('customer')->user() !=null)
                                                                <a href="javascript:;" class="ajax-add-to-cart"
                                                                    data-product_id="{{ $product->id }}"
                                                                    title="Add To Cart">
                                                                    <i class="las la-shopping-cart"></i>
                                                                </a>
                                                                @else
                                                                <a href="javascript:;" class="guestdirectajax-add-to-cart" data-productId="{{$product->id}}"
                                                                    data-product_id="{{ $product->id }}"
                                                                    title="Add To Cart">
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
                                                                                <span class="price_list">Rs.
                                                                                    {{ formattedNepaliNumber($stock->price) }}</span>
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
                            {!! $products->links() !!}
                        </div>
                    </div>
                </div>
            </div>
    </section>

    <!-- Category Page End  -->

@endsection


@push('script')
    <script>
        $('.moreless-button').click(function() {
            $('.moretext').slideToggle();
            if ($('.moreless-button').text() == "View Less") {
                $(this).text("View More")
            } else {
                $(this).text("View Less")
            }
        });
    </script>

    <script>
        $('.brandmoreless-button').click(function() {
            $('.morebrand').slideToggle();
            if ($('.brandmoreless-button').text() == "View Less") {
                $(this).text("View More")
            } else {
                $(this).text("View Less")
            }
        });
    </script>
    <script>
        $ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        window.onunload = function() {
            sessionStorage.clear();
        }

        $(document).ready(function() {
            var color_ids = [];
            var brand_ids = [];
            var slug = document.getElementById("slug").value;
        });
        paginateData();
    </script>
    @include('frontend.category.category-filter')
@endpush
