<div class="guest-cart-item">
    <div class="cart-remove">
        @if (request()->session()->get('guest_cart') != null)
            <div class="cart-table">
                <div class="cart-table-item">
                    <ul>
                        @foreach (request()->session()->get('guest_cart') as $key => $item)
                            <li>
                                <div class="p-img">
                                    <a href="{{ route('product.details', $item['product_slug']) }}"
                                        title="{{ $item['product_name'] }}">
                                        <img src="{{ $item['image'] }}" alt="images">
                                    </a>
                                </div>
                                <div class="p-name">
                                    <h3>{{ $item['product_name'] }}</h3>
                                    <div class="cart-del">
                                        <span>{{ $item['qty'] }} * $. {{ $item['price'] }}</span>
                                        <a href="javascript:;" class="delete-guestcart-singleproduct"
                                            data-id="{{ $key }}"><i class="las la-trash"></i></a>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="sub-total">
                    <ul>
                        <li>Sub Total:</li>
                        <li><b>Rs {{ request()->session()->get('total_cart_amount') }}</b></li>
                    </ul>
                </div>
                <div class="group-btns">
                    <a href="{{ route('guest-checkout-all') }}" class="btns">Checkout </a>
                    <a href="{{ route('guest.cartDelete') }}" class="btns btns-second">Delete All </a>
                </div>
            </div>
        @else
            <h4>Sorry No Items In The Cart..</h4>
        @endif
    </div>
</div>