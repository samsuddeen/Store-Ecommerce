@extends('frontend.layouts.app')
@section('title', ucfirst(@$tag[0]->title) ?? 'Tag')
@section('content')

    <!-- Breadcrumb  -->
    <nav aria-label="breadcrumb" class="nav_breadcrumb">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                <li class="breadcrumb-item">{{ @$tag[0]->title }}</li>
            </ol>
        </div>
    </nav>
    <!-- Breadcrumb End  -->

    <section class="category-page mb">
        <div class="container">
            <div class="category-head">
                <div class="category_product_number">
                    @if(@$tag[0]!=null)
                    <p><span>{{ count(@$tag[0]->products) ?? 0 }}</span> items found in {{ $tag[0]->title }}</p>
                    @endif
                </div>
                <div class="category-top-section">
                    <div class="in_flex_box">
                        <div class="wrap-col">
                            <div class="wrap_select">
                                <p>Sort by :</p>
                                <select class="form-select form-control for_space" id="data_sort"
                                    aria-label="Default select example">
                                    <option selected>Select option</option>
                                    <option value="ASC">A to Z</option>
                                    <option value="DESC">Z to A</option>
                                    <option value="increasing">Low to high</option>
                                    <option value="decreasing">high to low</option>
                                    <option value="recent">Recent</option>
                                    <option value="old">Old</option>
                                </select>
                            </div>
                            <div class="wrap_select">
                                <p>Show :</p>
                                <select class="form-select form-control" id='paginate' aria-label="Default select example">
                                    <option value="all" selected>Select option</option>
                                    <option value="10">show 10</option>
                                    <option value="20">show 20</option>
                                    <option value="30">show 30</option>
                                    <option value="all">show all</option>
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
                                                    {{-- flexCheckDefault  --}}
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
                                                    {{-- flexCheckDefault  --}}
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
                                                @foreach ($products as $pro)
                                                    <div class="col-lg-3 col-md-4 col-sm-6 padding">
                                                        <div class="product-col">
                                                            @if ($pro->on_sale == 1)
                                                                <div class="thumbnail_sellbadge">
                                                                    sale
                                                                </div>
                                                            @endif
                                                            <div class="product-media">
                                                                @foreach ($pro->images as $key => $photos)
                                                                    @if ($key == 0)
                                                                        <a href="{{ route('product.details', $pro->slug) }}"
                                                                            title="{{ $pro->name }}">
                                                                            <img src="{{ $photos->image }}"
                                                                                alt="">
                                                                        </a>
                                                                    @endif
                                                                @endforeach
                                                                <div class="add_cart_btn">
                                                                    <a href="javascript:;" class="ajax-add-to-cart"
                                                                        data-product_id="{{ $pro->id }}"
                                                                        title="Add To Cart">
                                                                        <i class="las la-shopping-cart"></i>
                                                                    </a>
                                                                </div>
                                                                @php
                                                                    echo getDiscountValue($pro);
                                                                @endphp
                                                            </div>
                                                            <div class="product-content">
                                                                <h3>
                                                                    <a href="{{ route('product.details', $pro->slug) }}">
                                                                        {{ Str::limit($pro->name, 35, $end = '...') }}
                                                                    </a>
                                                                </h3>
                                                                <div class="price-group">
                                                                    <div class="old-price-list">
                                                                        @foreach ($pro->stocks as $key => $stock)
                                                                            @if ($key == 0)
                                                                                @php
                                                                                    $offer = getOfferProduct($pro, $stock);
                                                                                @endphp
                                                                                @if ($offer != null)
                                                                                    <del class="enable-del">$.{{ formattedNepaliNumber($stock->price) }}</del><span
                                                                                        class="price_list">$.{{ formattedNepaliNumber($offer) }}</span>
                                                                                @elseif($stock->special_price)
                                                                                    <del class="enable-del">$.{{ formattedNepaliNumber($stock->price) }}</del><span
                                                                                        class="price_list">$.{{ formattedNepaliNumber($stock->special_price) }}</span>
                                                                                @else
                                                                                    <span class="price_list">$.
                                                                                        {{ formattedNepaliNumber($stock->price) }}</span>
                                                                                @endif
                                                                            @endif
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                                <div class="product_rating">
                                                                    @php
                                                                    $rating = intval($pro->rating);
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
                                                <h4 class="text-center !important">Sorry No Item Found!!</h4>
                                            @endif
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="list" role="tabpanel" aria-labelledby="list-tab"
                                tabindex="0">
                                <div class="list-view-category product-view" id="list_view">
                                    <div class="row margin">
                                        
                                            @if (count($products) > 0)
                                                @foreach ($products as $pro)
                                                    <div class="col-lg-6 col-md-6 padding">
                                                        <div class="product-col">
                                                            @if ($pro->on_sale == 1)
                                                                <div class="thumbnail_sellbadge">
                                                                    sale
                                                                </div>
                                                            @endif
                                                            <div class="product-media">
                                                                @foreach ($pro->images as $key => $photos)
                                                                    @if ($key == 0)
                                                                        <a href="{{ route('product.details', $pro->slug) }}"
                                                                            title="{{ $pro->name }}">
                                                                            <img src="{{ $photos->image }}" alt="">
                                                                        </a>
                                                                    @endif
                                                                @endforeach
                                                                <div class="add_cart_btn">
                                                                    <a href="javascript:;" class="ajax-add-to-cart"
                                                                        data-product_id="{{ $pro->id }}">
                                                                        <i class="las la-shopping-cart"></i>
                                                                    </a>
                                                                </div>
                                                                @php
                                                                    echo getDiscountValue($pro);
                                                                @endphp
                                                            </div>
                                                            <div class="product-content">
                                                                <h3>
                                                                    <a href="{{ route('product.details', $pro->slug) }}">
                                                                        {{ Str::limit($pro->name, 35, $end = '...') }}
                                                                    </a>
                                                                </h3>
                                                                <div class="price-group">
                                                                    <div class="old-price-list">
                                                                        @foreach ($pro->stocks as $key => $stock)
                                                                            @if ($key == 0)
                                                                                @php
                                                                                    $offer = getOfferProduct($pro, $stock);
                                                                                @endphp
                                                                                @if ($offer != null)
                                                                                    <del class="enable-del">$.{{ formattedNepaliNumber($stock->price) }}</del><span
                                                                                        class="price_list">$.{{ formattedNepaliNumber($offer) }}</span>
                                                                                @elseif($stock->special_price)
                                                                                    <del class="enable-del">$.{{ formattedNepaliNumber($stock->price) }}</del><span
                                                                                        class="price_list">$.{{ formattedNepaliNumber($stock->special_price) }}</span>
                                                                                @else
                                                                                    <span class="price_list">$.
                                                                                        {{ formattedNepaliNumber($stock->price) }}</span>
                                                                                @endif
                                                                            @endif
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                                <div class="product_rating">
                                                                    @php
                                                                        $rating = intval($pro->rating);
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
                                                <h4 class="text-center !important">Sorry No Item Found!!</h4>
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
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        window.onunload = function() {
            sessionStorage.clear();
        }

        $(document).ready(function() {

            var slug = document.getElementById("slug").value;
        });
    </script>
    <script>
        $('.recentView, .fastView').on('click', function() {
            var id = $(this).data('id');
            var old_pro_id = JSON.parse(localStorage.getItem("pro_id"));
            if (old_pro_id == null) old_pro_id = [];
            old_pro_id.push(id);
            localStorage.setItem("pro_id", JSON.stringify(old_pro_id));
        });
    </script>
    @include('frontend.tag.tag-to-cart')
    <script>
        paginateData();
    </script>
@endpush
