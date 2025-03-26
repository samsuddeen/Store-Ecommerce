@extends('frontend.layouts.app')
@section('title',env('DEFAULT_TITLE').'|'.'search')
@section('content')
<section id="category_product">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-lg-6 col-md-12">
				<nav aria-label="breadcrumb" class="nav_breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="{{route('index')}}">Home</a></li>
						<li class="breadcrumb-item active" aria-current="page">All category</li>
					</ol>
				</nav>
                <input type="hidden" name="search" class="search" value="{{$search_text}}">
				<div class="category_product_number">
					<p>All Product of this Category
						- <span>{{count($products) ??0}}</span> items</p>
				</div>
			</div>
			<div class="col-lg-6 col-md-12">
					<div class="in_flex_box">

						<ul class="switch_grid_style">
							<li class="switch_list"><span>List View</span><i class="las la-list"></i></li>
							<li class="switch_grid active"><span>Grid view</span><i class="las la-th-large"></i></li>
						</ul>
                            <div class="wrap_select">
                                <p>Sort by :</p>
                                <select class="form-select for_space" id="data_sort" aria-label="Default select example">
                                    <option selected>Select option</option>
                                    <option value="ASC" >A to Z</option>
                                    <option value="DESC">Z to A</option>
                                    <option value="increasing">Low to high</option>
                                    <option value="decreasing">high to low</option>
                                    <option value="recent">Recent</option>
                                    <option value="old">Old</option>
                                </select>
                            </div>
                            <div class="wrap_select">
                                <p>Show :</p>
                                <select class="form-select" id='paginate' aria-label="Default select example">
                                    <option value="all" selected>Select option</option>
                                    <option value="10">show 10</option>
                                    <option value="20">show 20</option>
                                    <option value="30">show 30</option>
                                    <option value="all">show All</option>
                                </select>
                            </div>
					</div>
				</div>
			</div>

            {{-- @dd(request()->session()->get('searched_items')) --}}

			<div class="row">
				<div class="col-lg-3 col-md-3 animate-true transition reach_section delay3" data-animatetype="reach_section" data-delay="delay3">
					<div class="cat_sideBar_wrap">
						<p>Filter By Colors</p>
                        <div id="section">
                            <div class="article">

                                <div>
                                    <div class="color_filtergroup">
                                        @foreach($colors as $key=>$color)
                                            @if($key<10)
                                                <div class="form-check">
                                                    <input class="color_id" name="color" type="checkbox" value="{{$color->id}}" id="color">
                                                    <div class="color_indicator" style="background-color: {{$color->colorCode}}"></div>
                                                    <label class="form-check-label" for="flexCheckDefault">
                                                        {{$color->title}}
                                                    </label>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                <div class="moretext">
                                    @foreach($colors as $key=>$color)
                                            @if($key>9)
                                                <div class="form-check">
                                                    <input class="color_id" name="color" type="checkbox" value="{{$color->id}}" id="color">
                                                    <div class="color_indicator" style="background-color: {{$color->colorCode}}">

                                                    </div>
                                                    <label class="form-check-label" for="flexCheckDefault">
                                                        {{$color->title}}
                                                    </label>
                                                </div>
                                            @endif
                                        @endforeach
                                </div>

                            </div>
                            <a class="moreless-button" href="#">More Colors</a>
                        </div>
                        <br>
						<p>Filter By Price</p>
						<form class="price_filterGroup" >
							 <input type="number" name="min" id="min_price" min="0" class="form-control min" placeholder="Min">-
							 <input type="number" name="max" id="max_price" min="0" class="form-control max" placeholder="Max">
                             <button type='button' class="submit" id="submit">Search</button>
						</form>

						<p>Filter By Brands</p>
                        <div id="brandsection">
                            <div class="brandarticle">
                                <div>
                                    @foreach($brands as $key=>$brand)
                                        @if($key<10)
                                        <div class="form-check">
                                            <input class="form-check-input brand_id" name="brand" type="checkbox" value="{{$brand->id}}" id="brand">  {{-- flexCheckDefault  --}}
                                            <label class="form-check-label" for="flexCheckDefault">
                                                {{$brand->name}}
                                            </label>
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                                <div class="morebrand">
                                    @foreach($brands as $key=>$brand)
                                            @if($key>9)
                                                <div class="form-check">
                                                    <input class="form-check-input brand_id" name="brand" type="checkbox" value="{{$brand->id}}" id="brand">  {{-- flexCheckDefault  --}}
                                                    <label class="form-check-label" for="flexCheckDefault">
                                                        {{$brand->name}}
                                                    </label>
                                                </div>
                                            @endif
                                        @endforeach
                                </div>

                            </div>
                            <a class="brandmoreless-button" href="#">More Brands</a>
                        </div>

					</div>
				</div>
				<div class="col-lg-9 col-md-9 animate-true transition reach_section delay4 replace" data-animatetype="reach_section" data-delay="delay4">
					<div class="row" id="product">                        
                        @foreach($products as $product)
						<div class="col-lg-3 col-md-6">
							<!-- start  -->
							<div class="util_card">

                                @foreach($product->featuredSections as $feature)
                                    @if($feature->title == 'on_sale')
                                    <div class="thumbnail_sellbadge">
                                        sale
                                    </div>
                                    @endif
                                @endforeach

								<x-_cart :productId="$product->id"  > </x-_cart>
								<div class="util_images">
                                    @foreach($product->images as $key=>$image)
                                        @if($key==0)
                                            <a href="{{route('product.details',$product->slug)}}" data-id={{$product->id}}><img src="{{$image->image}}" alt=""></a>
                                        @endif
                                    @endforeach
								</div>
								<div class="thumbnail_description">
									<a href="{{route('product.details',$product->slug)}}">{{$product->name}}</a>
                                        @if(isset($product->stocks->first()->special_price))
                                            <p>{{$product->stocks->first()->special_price}}</p>
                                            <del class="text-danger">{{$product->stocks->first()->price}}</del>
                                        @else
                                            <p>{{$product->stocks->first()->price}}</p>
                                        @endif
                                    @if(isset($product->min_order))
									    <span>{{$product->min_order}} Piece (Min. Oder)</span>
                                    @else
									    <span>1 Piece (Min. Oder)</span>
                                    @endif
									<p>{{$product->short_description}}</p>
								</div>
							</div>
							<!-- end -->
						</div>
                        @endforeach
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
        window.onunload = function () {
                sessionStorage.clear();
        }

        $(document).ready(function () {
            var color_ids = [];
            var brand_ids = [];
        $('.color_id,.brand_id,.submit').on('click',function(event){
            var slug=$('#slug').val();
            var sort_by=$('#data_sort').val();
            var paginate=$('#paginate').val();
            var min_price=$('#min_price').val();
            var max_price=$('#max_price').val();
            @php
            request()->session()->put('search_item',$products);
            @endphp
            
            sessionStorage.setItem('search_product',"{{$product ?? null}}");
            if(parseInt(min_price) > parseInt(max_price))
            {
                alert('Min Price Must Not Be Greater Than Max Price');
                return false;
            }
            let{
                value,
                id,
                checked
            }=event.target;
            if (id == "color")
            {
                if (checked)
                {
                    color_ids.push(value);
                } else
                {
                    color_ids = color_ids.filter(function(data) {
                        return data != value;
                    });
                }
            }
            if (id == "brand")
            {
                if (checked)
                {
                    brand_ids.push(value);
                } else
                {
                    brand_ids = brand_ids.filter(function(data) {
                        return data != value;
                    });
                }
            }

            $.ajax({
                url:"{{ route('search-multiple-filter') }}",
                type:"get",
                data:{
                    slug:slug,
                    sort_by:sort_by,
                    paginate:paginate,
                    min_price:min_price,
                    max_price:max_price,
                    color_id:color_ids,
                    brand_id:brand_ids
                },
                success:function(response)
                {
                    $("#product").replaceWith(response);
                }
            });
        });

        $('#data_sort,#paginate').change(function(event){
            var slug=$('#slug').val();
            var sort_by=$('#data_sort').val();
            var paginate=$('#paginate').val();
            var min_price=$('#min_price').val();
            var max_price=$('#max_price').val();
            @php
            request()->session()->put('search_item',$products);
            @endphp
            if(parseInt(min_price) > parseInt(max_price))
            {
                alert('Min Price Must Not Be Greater Than Max Price');
                return false;
            }
            let{
                value,
                id,
                checked
            }=event.target;
            if (id == "color")
            {
                if (checked)
                {
                    color_ids.push(value);
                } else
                {
                    color_ids = color_ids.filter(function(data) {
                        return data != value;
                    });
                }
            }
            if (id == "brand")
            {
                if (checked)
                {
                    brand_ids.push(value);
                } else
                {
                    brand_ids = brand_ids.filter(function(data) {
                        return data != value;
                    });
                }
            }

            $.ajax({
                url:"{{ route('search-multiple-filter') }}",
                type:"get",
                data:{
                    slug:slug,
                    sort_by:sort_by,
                    paginate:paginate,
                    min_price:min_price,
                    max_price:max_price,
                    color_id:color_ids,
                    brand_id:brand_ids
                },
                success:function(response)
                {
                    $("#product").replaceWith(response);
                }
            });
        });

            // $('#data_sort, #paginate').change(function(){
            //     alert('show');
            //     return false;
            //     var sort = $('#data_sort').val();
            //     var paginate = $('#paginate').val();
            //     var search_text = $('.search').val();
            //     sessionStorage.setItem('sort', sort);
            //     sessionStorage.setItem('paginate', paginate);
            //     min_price = sessionStorage.getItem('min_price');
            //     max_price = sessionStorage.getItem('max_price');

            //     $.ajax({
            //         url: "{{route('searchFilterData')}}",
            //         type: 'post',
            //         data: {
            //             paginate: paginate,
            //             data_sort: sort,
            //             color_id : color_ids,
            //             brand_id : brand_ids,
            //             min_price: min_price,
            //             max_price: max_price,
            //             search_text: search_text,
            //         },
            //         // dataType: 'JSON',
            //         success:function(response)
            //         {
            //             console.log(response);
            //             // $( "#product" ).empty();
            //             $("#product").replaceWith(response);

            //             singleProduct();
            //             // getData();
            //         },
            //         error: function(response) {
            //         }
            //     });

            // });

            // $('.color_id, .brand_id, .submit').on('click',function (event) {
            //     alert('color');
            //     return false;
            //     var min_price = $('.min').val();
            //     var search_text = $('.search').val();
            //     var max_price = $('.max').val();
            //     if( parseInt(min_price)> parseInt(max_price)){
            //         alert('invalid price value');
            //         return false;
            //     }
            //     sessionStorage.setItem('min_price', min_price);
            //     sessionStorage.setItem('max_price', max_price);

            //     sort = sessionStorage.getItem('sort');
            //     paginate = sessionStorage.getItem('paginate');

            //     let{
            //         value,
            //         id,
            //         checked
            //     } = event.target;
            //     if(id=="color"){
            //         if(checked){
            //         color_ids.push(value);
            //         }
            //         else{
            //            color_ids = color_ids.filter(function(data){
            //                 return data != value;
            //             })
            //         }
            //     }
            //     if(id=="brand"){
            //         if(checked){
            //         brand_ids.push(value);
            //         }
            //         else{
            //            brand_ids = brand_ids.filter(function(data){
            //                 return data != value;
            //             })
            //         }
            //     }
            //     // console.log(brand_ids);
            //     $.ajax({
            //         url: "{{route('searchFilterData')}}",
            //         type: 'post',
            //         data: {
            //             search_text: search_text,
            //             paginate: paginate,
            //             data_sort: sort,
            //             color_id : color_ids,
            //             brand_id : brand_ids,
            //             min_price: min_price,
            //             max_price: max_price,
            //         },
            //         // dataType: 'JSON',
            //         success:function(response)
            //         {
            //             console.log(response);
            //             // $( "#product" ).empty();
            //             $("#product").replaceWith(response);
            //             singleProduct();
            //             // getData();
            //         },
            //         error: function(response) {
            //         }
            //     });
            // });

        });
    </script>
@endpush
