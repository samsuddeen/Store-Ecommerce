<div class="store-page-menu">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bgColor_with_shadow" id="navbar_navigation">
            <div class="nav-wrap">
                <div class="toggle-btn">
                    <i class="las la-bars"></i>
                </div>
                <div class="nav-menu">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link cat-menu" href="#"><i class="las la-bars"></i>category
                                    <i class="las la-angle-down"></i></a>
                                <ul class="sub_nav_menu">
                                    @if(!empty(session('seller_category')))
                                        @foreach ($categories as $category)

                                            <li>
                                                @if(in_array($category->id,session('seller_category')))
                                                <a
                                                    href="{{ route('seller.products',[@$seller->slug,@$category->slug])}}">{{ $category->title }}
                                                </a>
                                                @endif
                                                @if (count($category->children) > 0)
                                                    <div class="nav_mega_menu">
                                                        <div class="top_menu_flex">
                                                            @foreach ($category->children as $secondChild)
                                                                <div class="inner_block">
                                                                    <ul>
                                                                        @if (count($secondChild->children) > 0)
                                                                            <li class="sub-child-parent">
                                                                                @if(in_array($secondChild->id,session('seller_category')))
                                                                                <a
                                                                                    href="{{ route('seller.products',[@$seller->slug,@$secondChild->slug])}}">{{ $secondChild->title }}
                                                                                </a>
                                                                                @endif
                                                                                <div class="sub-child-menu">
                                                                                    @foreach ($secondChild->children as $items)
                                                                                        <ul>
                                                                                            @if(in_array($items->id,session('seller_category')))
                                                                                            <li>
                                                                                                <a
                                                                                                    href="{{ route('seller.products',[@$seller->slug,@$items->slug])}}">{{ $items->title }}</a>
                                                                                            </li>
                                                                                            @endif
                                                                                        </ul>
                                                                                    @endforeach
                                                                                </div>
                                                                            </li>
                                                                        @else
                                                                        @if(in_array($secondChild->id,session('seller_category')))
                                                                            <li class="">
                                                                                <a
                                                                                    href="{{ route('seller.products',[@$seller->slug,@$secondChild->slug])}}">{{ $secondChild->title }}</a>
                                                                            </li>
                                                                        @endif
                                                                        @endif

                                                                    </ul>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="store-additional-menu">
                    <ul>
                        <li class="{{ (request()->route()->getName() == 'seller') ?'active':''}}">
                            <a href="{{ route('seller', @$seller->slug) }}">Home Page</a>
                        </li>
                        <li class="{{(request()->route()->getName()=='seller.products') ? 'active' : ''}}">
                            <a href="{{ route('seller.products', @$seller->slug) }}">All Products</a>
                        </li>
                        <li class="{{(request()->route()->getName()=='seller.profile') ? 'active' : ''}}">
                            <a href="{{ route('seller.profile', @$seller->slug) }}">Profile</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="store-search">
                <form action="{{route('seller-item.search')}}" method="get">
                    <div class="form-group">
                        <input type="text" class="form-control" name="search_field" placeholder="Search in this store" required>
                        <input type="hidden" class="form-control" name="seller_id" value="{{@$seller->id}}" required>
                        <button type="submit"><i class="las la-search"></i></button>
                    </div>
                </form>
            </div>
        </nav>
    </div>
</div>