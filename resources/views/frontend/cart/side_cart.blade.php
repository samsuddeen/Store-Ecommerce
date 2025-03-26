<div class="cart-remove">
    @if (!empty($cart))
        <div class="cart-table">
            <div class="cart-table-item">
                <ul>
                    @foreach ($cart->cartAssets as $item)
                        <li>
                            <div class="p-img">
                                @if ($item->product != null)
                                    @foreach ($item->product->images as $key => $image)
                                        @if ($key == 0)
                                            <a href="{{ route('product.details', $item->product->slug) }}"
                                                title="{{ $item->product_name }}">
                                                <img src="{{ $image->image }}" alt="images">
                                            </a>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="p-name">
                                <h3>{{ $item->product_name }}</h3>
                                <div class="cart-del">
                                    {{-- <span>{{ $item->qty }} * $. {{ $item->price }}=$. {{$item->sub_total_price}}</span> --}}
                                    <span>{{ $item->qty }} * Rs. {{ $item->price }}</span>
                                    <a href="javascript:void(0)" class="tbl-close" data-id="{{ $item->id }}"><i
                                            class="las la-trash"></i></a>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="sub-total">
                <ul>
                    <li>Sub Total:</li>
                    <li><b>Rs. {{ $cart->total_price }}</b></li>
                </ul>
            </div>
            <div class="group-btns">
                <a href="{{ route('cart.index') }}" class="btns">View Cart</a>
                <form action="{{ route('pre-checkout.post') }}" method="post">
                    @csrf
                    <input type="price" name="price" id="price" value="{{ $item->qty * $item->price }}" hidden>
                    <input type="qty" name="qty" id="qty" value="{{ $item->qty }}" hidden>
                    @foreach ($cart->cartAssets as $item)
                        <input type="checkbox" name="items[{{ $item->id }}]" value="1" checked hidden>
                    @endforeach
                    <button type="submit" class="btns btns btns-second"> Checkout</button>
                </form>
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

<script>
    function onload() {
        $('#total_quantity').text("{{ $cart != null ? count($cart->cartAssets) : '0' }}");
    }
</script>
