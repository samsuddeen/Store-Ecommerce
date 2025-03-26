{{-- <style>
.loader-container {
    flex-direction: column;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: #ffffff;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.loader {
  border: 8px solid #f3f3f3;
  border-radius: 50%;
  border-top: 8px solid #3498db;
  width: 60px;
  height: 60px;
  animation: spin 1.5s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.loaderContent {
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
}

</style> --}}

@extends('frontend.layouts.app')
@section('title', @$meta_setting['name'] ? $meta_setting['name'] : 'Home Page')
@section('content')

    @if ($skip_ads)
        <!-- Modal -->
        @foreach ($skip_ads as $ad)
            <div class="modal fade skipAdModal" id="skipAdModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content" style="background: none; border:none;">
                        <div class="modal-body">
                            <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal"
                                aria-label="Close"
                                style="position: absolute;
                        right: 0;
                        border-radius: 30%;
                        top: 0;">Skip</button>
                            <a href="{{ $ad->url }}" target="_blank">
                                <img src="{{ @$ad->image }}" alt="{{ @$ad->title }}" width="100%" loading="lazy">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

    <!-- LOADER -->

    {{-- <div class="loader-container">
        <div class="loader"></div>
        <div class="loading">
            <p>Loading...</p>
        </div>
    </div> --}}

    <!-- LOADER -->

    <!-- Slider  -->
    <section class="loaderContent site_wrap_banner">
        <div class="banner-wrap">
            <div id="strap_banner" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">

                    @foreach ($sliders as $key => $slider)
                        @if ($key == 0)
                            <button type="button" data-bs-target="#strap_banner" data-bs-slide-to="{{ $key }}"
                                class="active" aria-current="true" aria-label="Slide {{ $key + 1 }}"></button>
                        @else
                            <button type="button" data-bs-target="#strap_banner" data-bs-slide-to="{{ $key }}"
                                aria-label="Slide {{ $key + 1 }}"></button>
                        @endif
                    @endforeach
                </div>
                <div class="carousel-inner">
                    @foreach ($sliders as $key => $slider)
                        @if ($key == 0)
                            <div class="carousel-item active">
                                <a href="{{ $slider->link }}">
                                    <img src="{{ asset($slider->image) ?? asset('dummyimage.png') }}" alt="Slider" />
                                </a>
                            </div>
                        @else
                            <div class="carousel-item">
                                <a href="{{ $slider->link }}">
                                    <img src="{{ asset($slider->image) ?? asset('dummyimage.png') }}" alt="Slider" />
                                </a>
                            </div>
                        @endif
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#strap_banner" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#strap_banner" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </section>

    <!-- Slider  End-->

    {{-- @dd(auth()->guard('seller')->user()); --}}
    {{-- @if ($position->isNotEmpty())
    <div class="site-adds">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div href="" class="adds owl-carousel owl-theme">
                        @foreach ($position as $positionData)
                        <div class="item">  
                            <a href="{{$positionData->url}}" target="_blank">
                            <img src="{{$positionData->image  ?? asset('dummyimage.png')}}" alt="adds">
                        </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                
            </div>       
        </div>    
    </div>
@endif --}}
    <!-- Tags  -->
    {{-- <div class="top-categories">
        <div class="container">
            <ul>

                @foreach ($tags as $key => $tag)
                    <li>
                        <a href="{{ route('tags.show', $tag->slug) }}">
                            <img src="{{ $tag->image  ?? asset('dummyimage.png')}}" alt="images" />
                            <span>{{ $tag->title }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div> --}}
    <!-- Tags End  -->
    <!-- Top Sale  -->
    {{-- <section class="product-listing-section mt">
        <div class="container">
           <div class="row">
            @foreach ($product_features as $key => $product_feature)
            <div class="product-listing-wrap">
                <div class="main-title">
                    <h3>{{ $product_feature->title }}</h3>
                </div>
                <div class="product-listing">
                    <div class="owl-carousel owl-theme product-carousel">
                        @if ($product_feature->type == '1')
                        @include('frontend.featuretype1',['data'=>getItem($product_feature->featured,$product_feature->type),'status'=>'cat'])
                        @elseif($product_feature->type=='2')
                        @include('frontend.featuretype1',['data'=>getItem($product_feature->featured,$product_feature->type),'status'=>'brand'])
                        @else
                        @include('frontend.featuretype3',['data'=>getItem($product_feature->featured,$product_feature->type)])
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
           </div>
        </div>
    </section> --}}
    <!-- Top Sale End  -->

    {{-- special offer --}}
    {{-- <section class="bulk-section mt">
        <div class="container">
            <div class="row">

                <div class="col-lg-12 col-md-12">
                    <div class="main-title">
                        <h3>Special Offer</h3>
                        <div class="main-btns">
                            <a href="{{ route('special_offer.list') }}" class="btns">View More</a>
                        </div>
                    </div>
                    <div id="latestProducts-placeholder">
                        <div class="bulk-section-wrap" >
                            <div class="owl-carousel owl-loaded owl-drag" id="SpecialOfferCarousel"> 
                                @include('partials.special_offer')
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </section> --}}

    {{-- end special offer  --}}
    @if ($selectedProductItem && count($selectedProductItem) > 0)
        <section class="loaderContent bulk-section mt">
            <div class="container">
                <div class="row">

                    <div class="col-lg-12 col-md-12">
                        <div class="main-title">
                            <h3>Our Products</h3>
                            <div class="main-btns">
                                <a href="{{ route('selectedproduct.list') }}" class="btns">View More</a>
                            </div>
                        </div>
                        <div id="latestProducts-placeholder">
                            <div class="bulk-section-wrap">
                                <div class="owl-carousel owl-loaded owl-drag selectedProductsCarousel">
                                    @include('partials.selectedProducts')
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </section>
    @endif

    <section class="loaderContent featured-categories bulk-section mt">
        <div class="container">
            <div class="row">
                @foreach ($product_features as $key => $product_feature)
                    <div class="col-lg-12 col-md-12">
                        <div class="main-title">
                            <h3>{{ $product_feature->title }}</h3>
                            {{-- <div class="main-btns">
                            <a href="#" class="btns">View More</a>
                        </div> --}}
                        </div>
                        <div id="categorySlider-placeholder">
                            <div class="bulk-section-wrap">
                                <div class="owl-carousel owl-loaded owl-drag categoryCarousel">
                                    @if ($product_feature->type == '1')
                                        @include('frontend.featuretype1', [
                                            'data' => getItem($product_feature->featured, $product_feature->type),
                                            'status' => 'cat',
                                        ])
                                    @elseif($product_feature->type == '2')
                                        @include('frontend.featuretype1', [
                                            'data' => getItem($product_feature->featured, $product_feature->type),
                                            'status' => 'brand',
                                        ])
                                    @else
                                        @include('frontend.featuretype3', [
                                            'data' => getItem($product_feature->featured, $product_feature->type),
                                        ])
                                    @endif

                                </div>
                            </div>
                        </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Bulk Section  -->
    <section class="loaderContent bulk-section mt">
        <div class="container">
            <div class="row">

                <div class="col-lg-12 col-md-12">
                    <div class="main-title">
                        <h3>Our Latest Products</h3>
                        <div class="main-btns">
                            <a href="{{ route('newproduct.list') }}" class="btns">View More</a>
                        </div>
                    </div>
                    <div id="latestProducts-placeholder">
                        <div class="bulk-section-wrap">
                            <div class="owl-carousel owl-loaded owl-drag" id="latestProductsCarousel">
                                @include('partials.latestProducts')
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </section>

    <!-- Bulk Section End -->
    @if ($advertisementsData && count($advertisementsData) > 0)
        <section class="loaderContent bulk-section mt deals">
            <div class="container">
                <div class="row">
                    @foreach ($advertisementsData as $advertisement)
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <a href="{{ $advertisement->url }}" target="_blank"><img src="{{ $advertisement->image }}"
                                    alt=""></a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Overal Product  -->
    <section class="loaderContent overall-product mt">
        <div class="container">
            <div class="main-title">
                <h3>Recommended Products</h3>
            </div>
            <ul>
                @foreach ($recommended_products as $recommended_product)
                    <li>
                        <div class="product-col">
                            <div class="product-media">
                                @foreach ($recommended_product->images as $key => $image)
                                    @if ($key == 0)
                                        <a href="{{ route('product.details', $recommended_product->slug) }}"
                                            title="{{ $recommended_product->name }}">
                                            @if ($recommended_product->images->first()->image)
                                                <img src="{{ $recommended_product->images->first()->image }}"
                                                    alt="{{ $recommended_product->slug }}" loading="lazy" />
                                            @else
                                                <img src="{{ asset('frontend/img/folder.png') }}"
                                                    alt="{{ $recommended_product->slug }}" loading="lazy" />
                                            @endif
                                        </a>
                                    @endif
                                @endforeach
                                <div class="add_cart_btn">
                                    @if (auth()->guard('customer')->user() != null)
                                        <a href="javascript:;" class="ajax-add-to-cart-front"
                                            data-product_id="{{ $recommended_product->id }}" title="Add To Cart">
                                            <i class="las la-shopping-cart"></i>
                                        </a>
                                    @else
                                        <a href="javascript:;" class="guestdirectajax-add-to-cart"
                                            data-productId="{{ $recommended_product->id }}"
                                            data-product_id="{{ $recommended_product->id }}" title="Add To Cart">
                                            <i class="las la-shopping-cart"></i>
                                        </a>
                                    @endif
                                </div>
                                @php
                                    echo getDiscountValue($recommended_product);
                                @endphp
                            </div>
                            <div class="product-content">
                                <h3>
                                    <a href="{{ route('product.details', $recommended_product->slug) }}">
                                        {{ Str::limit($recommended_product->name, 40, $end = '...') }}
                                    </a>
                                </h3>
                                <div class="price-group">
                                    <div class="old-price-list">
                                        @foreach ($recommended_product->stocks as $key => $stock)
                                            @php
                                                $offer = getOfferProduct($recommended_product, $stock);
                                            @endphp

                                            @if ($key == 0)
                                                @if ($offer != null)
                                                    <del
                                                        class="enable-del">Rs.{{ formattedNepaliNumber($stock->price) }}</del>
                                                    {{-- @if ($stock->special_price != null)
                                                        <del>${{ $stock->special_price }}</del>
                                                    @endif --}}
                                                    <span class="price_list">Rs.{{ formattedNepaliNumber($offer) }}</span>
                                                @elseif($stock->special_price)
                                                    <del
                                                        class="enable-del">Rs.{{ formattedNepaliNumber($stock->price) }}</del>
                                                    <span
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
                                        $rating = intval($recommended_product->rating);
                                    @endphp
                                    @for ($i = 0; $i < $rating; $i++)
                                        <i class="las la-star"></i>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>
    <!-- Overal Product End  -->

    <!-- Recently Viewed Product  -->
    <section class="loaderContent recent-viewed-product mt mb">
        <div class="container">
            <div class="main-title">
                <h3>Recently viewed products</h3>
            </div>
            <div class="owl-carousel" id="recent_product">
                {{-- @dd($recent_view_products) --}}
                @foreach ($recent_view_products as $product)
                    <div class="item">
                        <div class="top-viewed-product-wrap">
                            <div class="top-viewed-product-media">
                                @foreach ($product->images as $key => $image)
                                    @if ($key == 0)
                                        <a href="{{ route('product.details', $product->slug) }}"
                                            title="{{ $product->name }}" class="recentView" data-id={{ $product->id }}>
                                            <img src="{{ $image->image ?? asset('dummyimage.png') }}" alt="">
                                        </a>
                                    @endif
                                @endforeach
                                @php
                                    echo getDiscountValue($product);
                                @endphp
                            </div>
                            <div class="top-viewed-product-content">
                                <h3>
                                    <a href="{{ route('product.details', $product->slug) }}">
                                        {{ Str::limit($product->name, 38, $end = '...') }}
                                    </a>
                                </h3>
                                <div class="product_rating">
                                    @php
                                        $rating = intval($product->rating);
                                    @endphp
                                    @for ($i = 0; $i < $rating; $i++)
                                        <i class="las la-star"></i>
                                    @endfor
                                </div>
                                <div class="price-group">
                                    <div class="old-price-list">
                                        @foreach ($product->stocks as $key => $stock)
                                            @php
                                                $offer = getOfferProduct($product, $stock);
                                            @endphp

                                            @if ($key == 0)
                                                @if ($offer != null)
                                                    <del
                                                        class="enable-del">Rs.{{ formattedNepaliNumber($stock->price) }}</del>
                                                    {{-- <del class="enable-del">${{ $stock->special_price }}</del> --}}
                                                    <span class="price_list">Rs.{{ formattedNepaliNumber($offer) }}</span>
                                                @elseif($stock->special_price)
                                                    <del
                                                        class="enable-del">Rs.{{ formattedNepaliNumber($stock->price) }}</del>
                                                    <span
                                                        class="price_list">Rs.{{ formattedNepaliNumber($stock->special_price) }}</span>
                                                @else
                                                    <span
                                                        class="price_list">Rs.{{ formattedNepaliNumber($stock->price) }}</span>
                                                @endif
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="content cta-section">
        <div class="container">
            <div class="row align-items-center mb-5">
                <div class="left col-lg-8 col-md-6 col-sm-12">
                    <h1>Subscribe to Our Newsletter!!</h1>
                    <form action="">
                        <input type="email" class="form-control" name="subscribe" id="subscribe"
                            placeholder="Your email" />
                        <div name="captcha" class="g-recaptcha footerCaptcha"
                            data-sitekey="6Lc3ZuIpAAAAAK7jtHdENSR7UIMh3jKYNebOHsLv" required></div>

                        <span class="text-danger captchaerror" hidden> The reCAPTCHA was invalid. Go back and try
                            it again.
                        </span>

                </div>
                <div class="right col-lg-4 col-md-6 col-sm-12">
                    <a href="javascript:void(0);" class="subscribe subscribe-btn">Subscribe</a>
                </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Recently Viewed Product End -->

    <!-- Supplier Section  -->
    {{-- <section class="brand-section mt">
        <div class="container">
            <div class="main-title">
                <h3>Suppliers Lists</h3>
            </div>
            <div class="owl-carousel" id="suppliers_sliders">
                @foreach ($countries as $country)
                    <div class="item">
                        <div class="brand-wrapper">
                            <div class="brand-img">
                                <a href="" title="{{ $country->name }}">
                                    @php
                                        $country_code = strtolower($country->iso_2);
                                    @endphp
                                    <img src="https://flagcdn.com/48x36/{{ $country_code }}.png" alt="flag" />
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section> --}}
    <!-- Supplier Section End -->



@endsection


@push('script')
    {{-- <script>
        // Simulating website loading time
        setTimeout(function() {
        document.querySelector('.loaderContent').classList.remove('visually-hidden');
        document.querySelector('.loader-container').style.display = 'none';
        }, 3000); // Adjust the delay time as needed
    </script>
    <script>
        $(document).ready(function() {
            console.log('ready');
            $('.skipAdModal').modal('show');
        });
    </script> --}}
@endpush
