@extends('frontend.layouts.app')
@section('title', env('DEFAULT_TITLE') . '|' . 'Cart')
@section('content')
    <section id="cart_page_wrapper" class="mt mb">
        <div class="container">
            <div class="row cart-item">
                <div class="col-lg-8">
                    <div class="cart_table">
                        <div class="cart_table_head">
                            <h3>Your Checkout Details</h3>
                            <div class="form-check">
                                @if ($cart_item != null && !empty($cart_item))
                                <input type="checkbox" name="selectall" value="" class="select-all" id="select-all">
                                    <la.on('click',function() { })be;l for.on('click',function() { })=";" class="ms-2">Select
                                        All</label>
                                @endif
                            </div>
                        </div>
                        @if(session()->has('error'))
                        <span class="text text-danger">{{ session('error') }}</span>
                        @endif
                        <form action="{{ route('pre-checkout.post') }}" method="post">
                            @csrf
                            @if ($cart_item != null && !empty($cart_item))
                                <div class="table-responsive">
                                    <table width="100%">
                                        @isset($cart_item)
                                            @foreach ($cart_item as $item)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" name="items[{{ $item->id }}]" value=""
                                                            class="select-single" id="product{{ $item->id }}">
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('product.details', $item->product->slug) }}">
                                                            <img src="{{ $item->product->images[0]->image ?? null }}"
                                                                alt="">
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('product.details', $item->product->slug) }}">
                                                            {{ $item->product->name }}{!!getTaxStatus($item) ? '<span class="text-danger">(vat 13 %)</span>' : ''!!}
                                                        </a>
                                                        @if($item->options)
                                                        @php
                                                            $optionsArray = json_decode($item->options, true);
                                                        @endphp
                                                        @foreach ($optionsArray as $option)
                                                        <span>{{ $option['title'] }}:{{ $option['value'] }}</span>
                                                        @endforeach
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="qty_size">
                                                            <span> QTY </span>
                                                            <div class="product_item_count">
                                                                <div class="container_box">
                                                                    @php
                                                                        $product = \App\Models\Product::where('id', $item->product_id)->first();
                                                                        $initialQty = 1;
                                                                    @endphp
                                                                    @foreach ($product->stocks as $key => $stock)
                                                                        @if ($key == 0)
                                                                            @php
                                                                                $quantity = $stock->quantity;
                                                                                if (@$loginUser->wholeseller == '1') {
                                                                                    $initialQty = (int) $stock->mimquantity;
                                                                                }
                                                                            @endphp
                                                                        @endif
                                                                    @endforeach
                                                                    @php
                                                                        if ($quantity == null) {
                                                                            $quantity = 0;
                                                                        }
                                                                    @endphp
                                                                    <input type="hidden" id="{{ $item->id }}"
                                                                        name="price" value="{{ $item->sub_total_price }}">
                                                                    <div class="qtySelector">
                                                                        <span
                                                                            class="decreaseQty btns-qty Product-qty-change{{ $item->id }}">
                                                                            <i class="las la-minus"></i>
                                                                        </span>
                                                                        <input type="text" hidden class="stock_qty"
                                                                            value="{{ $quantity }}" data-minqty={{$initialQty}}>
                                                                        <input type="text" class="product_qty qtyValue"
                                                                            min="{{$initialQty}}" max="{{ $quantity }}"
                                                                            data-item_id="{{ $item->id }}" name="qty"
                                                                            value="{{ $item->qty }}"
                                                                            id="product_qty{{ $item->id }}">
                                                                        <span
                                                                            class="increaseQty btns-qty Product-qty-change{{ $item->id }}"
                                                                            data-id="{{ $quantity }}">
                                                                            <i class="las la-plus"></i>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="{{ $item->id }}" style="white-space: nowrap;">
                                                        <b>Rs. {{ formattedNepaliNumber($item->sub_total_price) }}</b>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('removeProduct', $item->id) }}" class="tbl-close"
                                                            data-id="{{ $item->id }}">
                                                            <i class="las la-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endisset
                                    </table>
                                </div>
                            @else
                                <p> Your cart is empty </p>
                            @endif
                            <div class="cart_offer">
                                <div class="saving-offer">
                                    <div class="icon_wraping">
                                        <i class="lar la-check-circle"></i>
                                    </div>
                                    <div class="text_wraping_te">
                                        @if (isset($coupon))
                                            <p>offer applied</p>
                                            <p>save up to @if ($coupon->is_percentage == 'yes')
                                                    {{ $coupon->discount }}%
                                                @else
                                                    Rs {{ $coupon->discount }}
                                                @endif
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                @if ($cart_item != null && !empty($cart_item))
                                <button type="submit" class="btn btn-danger ms-2 btn-checkout" disabled>
                                    Checkout
                                </button>
                                @endif
                            </div>

                        </form>
                    </div>
                </div>
                <div class="col-lg-4">
                    @if ($cart_item == null)
                        <div class="cart_total_detail">
                            <div class="cart_table_head">
                                <h3>Your Order Details</h3>
                            </div>
                            <ul>
                                <li>
                                    <span>Item 0</span>
                                    <b>Rs. 0</b>
                                </li>
                                <li>
                                    <span>Discount</span>
                                    <b>0 %</b>
                                </li>
                                <li>
                                    <span>Shipping</span>
                                    <b>Rs. 0</b>
                                </li>
                                <li>
                                    <span>Sub Total</span>
                                    <b>0</b>
                                </li>
                                <li>
                                    <span>Additional Charges</span>
                                    <b>Rs. 0</b>
                                </li>
                                <li>
                                    <span>Total</span>
                                    <b>0</b>
                                </li>
                            </ul>
                        </div>
                    @else
                    
                        <div class="cart_total_detail">
                            <div class="cart_table_head">
                                <h3>Your Order Details</h3>
                            </div>
                            <ul>
                                <li>
                                    <span class="total_cart_item">Item ({{ $cart->total_qty }})</span>
                                    <b class="cart_total_price">Rs.
                                        {{ formattedNepaliNumber($cart->total_price + $cart->total_discount) }}</b>
                                </li>
                                <li>
                                    @php
                                        if ($cart->total_price == 0) {
                                            $total = 1;
                                        } else {
                                            $total = $cart->total_price + $cart->total_discount;
                                        }
                                        $discount = round(($cart->total_discount / $total) * 100, 2);
                                    @endphp
                                    <span>Discount</span>
                                    <b class="total_discount_per">Rs.{{ $cart->total_discount }}</b>
                                    {{-- <b class="total_discount_per">{{ $discount }} %</b> --}}
                                </li>
                                @if($vatAmount >0 && $vatAmount !=0)
                                <li>
                                    <span>Vat</span>
                                    <b class="sub_vat">Rs. {{ formattedNepaliNumber($vatAmount) }}</b>
                                </li>
                                @endif
                                <li>
                                    <span>Sub Total</span>
                                    <b class="sub_total_price">Rs. {{ formattedNepaliNumber($cart->total_price) }}</b>
                                </li>
                                @php
                                $totalAdditionalCharge = $cart->cartAssets->sum(function ($cartItem) {
                                    return $cartItem->additional_charge;
                                });
                               
                                @endphp
                                <li>
                                    <span>Additional Charges</span>
                                    <b class="additional_charge">Rs. {{ formattedNepaliNumber($totalAdditionalCharge) }}</b>
                                </li>
                                <li>
                                    <span>Total</span>
                                    <b class="sub_total_price">Rs. {{ formattedNepaliNumber($cart->total_price + $totalAdditionalCharge) }}</b>
                                </li>
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
@push('script')
    <script>
         var userDataValue="{{auth()->guard('customer')->user()->wholeseller}}" ?? 0;
        var minVal = 1,
            maxVal = 1;

        $(".increaseQty").on('click', function() {
            var $parentElm = $(this).parents(".qtySelector");
            var stock_qty = $parentElm.find('.stock_qty').val()
            maxVal = parseInt(stock_qty);
            $(this).addClass("clicked");
            setTimeout(function() {
                $(".clicked").removeClass("clicked");
            }, 100);
            var value = $parentElm.find(".qtyValue").val();
            if (value < maxVal) {
                value++;
            }
            $parentElm.find(".qtyValue").val(value);
        });

        $(".decreaseQty").on('click', function() {
            var $parentElm = $(this).parents(".qtySelector");
            var mimquantityDec=$parentElm.find('.stock_qty').data('minqty');
            $(this).addClass("clicked");
            setTimeout(function() {
                $(".clicked").removeClass("clicked");
            }, 100);
            var value = $parentElm.find(".qtyValue").val();
            if(userDataValue=='1')
            {
                if (value > mimquantityDec) {
                    value--;
                }
            }
            else
            {
                if (value > 1) {
                    value--;
                }
            }
            $parentElm.find(".qtyValue").val(value);
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.btn-closes').on('click', function() {
                var cart_id = $(this).data('id');
                var status = confirm("Do you want to remove product from Cart?");
                if (status) {
                    return true;
                } else {
                    return false;
                }
            })

            @if(!empty($cart_item) && $cart_item!=null)
                @foreach ($cart_item as $item)
                    $('.Product-qty-change{{ $item->id }}').on('click', function(e) {
                        e.preventDefault();
                        let product_qty = $("#product_qty{{ $item->id }}").val();
                        let item_id = $("#product_qty{{ $item->id }}").data(
                            'item_id'); //cart_asset_id                
                        let price = $('#' + item_id).val();
                        let total = parseInt(product_qty) * parseInt(price);
                        $.ajax({
                            url: "{{ route('updateCart') }}",
                            type: "post",
                            data: {
                                product_qty: product_qty,
                                item_id: item_id,
                            },
                            success: function(response) {
                                if (typeof(response) != 'object') {
                                    alert(typeof(response));
                                    response = JSON.parse(response);
                                }
                                console.log(response);
                                var para = '';
                                para += '<p class="' + item_id + '"><b>Rs. ' + response.data +
                                    '</b></p>';
                                // $('.cart-remove').replaceWith(response);
                                $('.total_cart_item').text('Item(' + response.cart_total_qty + ')');
                                $('.cart_total_price').text('Rs. ' + response.cart_total_price);
                                $('.total_discount_per').text(response.discount_per+' %');
                                $('.sub_vat').text('Rs. '+response.vatAmount);
                                $('.sub_total_price').text('Rs. '+response.sub_total_price);
                                $('#side-cart-update').html(response.view);
                                removeProduct();
                                allCartCount();
                                priceCalculation();
                                $('.' + item_id).html(para);
                            },
                            error: function(response) {

                            }
                        })
                    });
                @endforeach
            @endif
        })
    </script>
    @isset($cart_item)
        @foreach ($cart_item as $item)
            <script>
                $(document).ready(function() {
                    $('#product{{ $item->id }}').on('change', function() {
                        var active = $(this).val();
                        if (active == '') {
                            $(this).attr('value', 1);
                        } else {
                            $(this).attr('value', '');
                        }
                        var active = $('#product{{ $item->id }}').val();
                        if (active == '') {
                            OnTest(active);
                        } else {
                            $('.btn-checkout').removeAttr('disabled');
                        }
                    });
                });

                function OnTest(active) {
                    var data = [];
                    @foreach ($cart_item as $item)
                        var new_active = $('#product{{ $item->id }}').val();
                        data.push(new_active);
                    @endforeach
                    var disable_checkout = $.inArray('1', data);
                    if (disable_checkout == -1) {
                        $('.btn-checkout').attr('disabled', true);
                    }
                }
            </script>
        @endforeach
    @endisset

    <script type="text/javascript">
        $('#select-all').click(function() {
            var test = $('#select-all').val();
            if (test == '') {
                $('.select-single').prop('checked', true)
                $(".select-single").val('1');
                $('.btn-checkout').removeAttr('disabled');
                $(this).val('1');
            } else {
                $('.select-single').prop('checked', false)
                $(".select-all").val('');
                $(".select-single").val('');
                $('.btn-checkout').attr('disabled', true);
            }
        });
    </script>
    <script>
        window.addEventListener("pageshow", function(event) {
            var historyTraversal = event.persisted ||
                (typeof window.performance != "undefined" &&
                    window.performance.navigation.type === 2);
            if (historyTraversal) {
                window.location.reload();
            }
        });
    </script>
     @if (session('error') && !empty(session('error')))
        <script>
            $(document).ready() {
                swal({
                    title: "Sorry!",
                    text: "{{ request()->session()->get('error') }}",
                    icon: "error",
                });
            }
        </script>
        @php
            session()->forget('error');
        @endphp
    @endif
@endpush
