{{-- @dd(request()->session()->get('guest_cart')['cart_item']) --}}
<div class="cart-remove">
    @if (request()->session()->get('guest_cart') != null && request()->session()->get('guest_cart')['cart_item'] !=null)
        <div class="cart-table">
            <div class="cart-table-item">
                <ul>
                    @foreach (request()->session()->get('guest_cart')['cart_item'] as $key => $item)
                        <li>
                            <div class="p-img">
                                <a href="{{ route('product.details', $item['product_name']) }}"
                                    title="{{ $item['product_name'] }}">
                                    <img src="{{ $item['image'] }}" alt="images">
                                </a>
                            </div>
                            <div class="p-name">
                                <h3>{{ $item['product_name'] }}</h3>
                                <div class="cart-del">
                                    <span>{{ $item['qty'] }} * Rs. {{ $item['price'] }}</span>

                                    <a href="javascript:;" class="deleteGuestItemData" data-itemId="{{$item['product_id']}}"> <i
                                            class="las la-trash"></i> </a>
                                    {{-- <a href="javascript:(0);" class="delete-a-guestcart-singleproduct"
                                    data-id="{{ $key }}"><i class="las la-trash"></i></a> --}}

                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="sub-total">
                <ul>
                    <li>Sub Total:</li>
                    <li><b>Rs {{ request()->session()->get('guest_cart')['total_price'] ?? 0 }}</b></li>
                </ul>
            </div>
            <div class="group-btns">
                <a href="{{ route('directguestallcheckout-to-cart') }}" class="btns">Checkout </a>
                <a href="{{ route('guest.cartDelete') }}" class="btns btns-second">Delete All </a>
            </div>
        </div>
    @else
        <div class="empty-cart">
            <img src="{{ asset('frontend/images/empty.png') }}" alt="images">
            <h4>Your Cart is Empty</h4>
            <p>Looks like you haven't added anything to your cart yet.</p>
        </div>
    @endif
</div>