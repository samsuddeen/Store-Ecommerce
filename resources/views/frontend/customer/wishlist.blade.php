@extends('frontend.layouts.app')
@section('title','Customer|Wishlist')
@section('content')

    <div class="dashboard-wrapper mt mb">
        <div class="container">
            <div class="customer_dashboard_wrap">
                @include('frontend.customer.sidebar')
                <div class="dashboard-main-wrapper">
                    <div class="dash-toggle">
                        <i class="las la-bars"></i>
                    </div>
                    <div class="dashboard-main-col">
                        <div class="dashboard_contentArea">
                            <div class="dashboard_content">
                                <div class="dashboard-tables-head">
                                    <h3>My wishlist</h3>
                                </div>
                                <div class="wishlist_tab_wrapper">
                                    <div class="tab_inside_content table_wrapper">
                                        <div class="wishlist_table">
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>First Image</th>
                                                            <th>Product Name</th>
                                                            <th>Price/pcs</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($wishlists as $wishlist)
                                                            <tr>
                                                                <?php
                                                                $image = $wishlist->getproduct->images;
                                                                ?>
                                                                <td>
                                                                    <a
                                                                        href="{{ route('product.details', $wishlist->getproduct->slug) }}">
                                                                        <img src="{{ asset(@$image[0]->image) }}"
                                                                            alt="">
                                                                    </a>
                                                                </td>
                                                                <td>
                                                                    <div>
                                                                        <p>{{ $wishlist->getproduct->name }}</p>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    @php
                                                                        $offer = getOfferProduct($wishlist->getproduct, $wishlist->getProduct->stocks[0]);
                                                                    @endphp
                                                                    @if ($offer != null)
                                                                        <span
                                                                            class="price_list">{{ $offer }}</span><br>
                                                                    @elseif($wishlist->getProduct->stocks[0]->special_price)
                                                                        <span
                                                                            class="price_list">{{ $wishlist->getProduct->stocks[0]->special_price }}</span><br>
                                                                    @else
                                                                        <span
                                                                            class="price_list">{{ $wishlist->getProduct->stocks[0]->price }}</span><br>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <div class="table_btn">
                                                                        <a href="{{ route('removeWishlistProduct', $wishlist->product_id) }}"
                                                                            title="Remove" class="btn_remove delete">
                                                                            <i class="las la-trash"></i>
                                                                        </a>
                                                                        <a href="javascript:;" class="view add-to-cart" title="Add to Cart"
                                                                            data-id="{{ $wishlist->product_id }}">
                                                                            <i class="las la-cart-plus"></i>
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('script')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
        $(document).ready(function() {
            $('.add-to-cart').on('click', function() {
                let product_id = $(this).data('id');
                $.ajax({
                    url: "{{ route('addSingleProductToCart') }}",
                    type: 'post',
                    data: {
                        product_id: product_id,
                    },
                    success: function(response) {
                        // alert(response.msg);
                        $('.cart-remove').replaceWith(response);
                        removeProduct();
                        allCartCount();
                        toastr.options =
                        {
                            "closeButton" : true,
                            "progressBar" : true
                        }
                        toastr.success("Added To Cart Successfully !! ");
                    },

                    error: function(response) {

                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.wishlistTocart').on('click', function() {
                $.ajax({
                    url: "{{ route('wishlistToCart') }}",
                    type: 'get',
                    success: function(response) {
                        $('.cart-remove').replaceWith(response);
                        removeProduct();
                        allCartCount();
                    },

                    error: function(response) {

                    }
                });
            });
        });
    </script>
@endpush
