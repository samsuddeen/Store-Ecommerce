@props(['productId'])

{{-- <span>
    <a rel="nofollow" class="ajax-add-to-cart" data-product_id={{ $productId }}>
        <i class="fas fa-cart-plus"></i>
    </a>
</span> --}}

<div class="add_cart_btn">
    <a class="ajax-add-to-cart" data-product_id={{ $productId }}>
        <i class="las la-cart-plus"></i>
    </a>
</div>
@once
    @push('script')
        @include('frontend.layouts.cartScript')
    @endpush
@endonce

