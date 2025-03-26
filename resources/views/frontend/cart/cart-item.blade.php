<div class="row cart-item">
    <div class="col-lg-8">
        <div class="cart_table">
            <h3>Your Cart Details</h3>
            <div class="table-responsive">
                <table>
                    @foreach($cart_item as $item)
                    <tr>

                        @foreach($item->getProduct->images as $key=>$image)
                            @if($key==0)
                                <td><a href="#"><img src="{{ $image->image }}" alt=""></a></td>
                            @endif
                        @endforeach
                        <td>
                            <p>{{$item->title}}</p>
                            {{-- <span>1M No Remote, USB</span> --}}
                            <a href="javascript:;" class="btn-closes"  data-id="{{$item->id}}"><i class="las la-trash"></i> Remove</a>
                        </td>
                        <td>
                            <div class="qty_size">
                                <span>QTY </span>
                                <select class="form-select" aria-label="Default select example">
                                    <option value="{{$item->qty }}" selected>{{$item->qty}}</option>
                                    <option value="1">2</option>
                                    <option value="2">3</option>
                                    <option value="3">4</option>
                                </select>
                            </div>
                            <span>Modify your quantity</span>
                        </td>
                        <td>
                            <p>Rs {{$item->total_price}}</p>
                            {{-- <p>+us $1.99</p> --}}
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
            <div class="cart_offer">
                <div class="icon_wraping">
                    <i class="lar la-check-circle"></i>
                </div>
                <div class="text_wraping_te">
                    <p>offer applied</p>
                    <p>save up to 50%</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="cart_total_detail">
            <a href="#" class="btn btn-danger">Go to checkout</a>
            <div class="row">
                <div class="col-lg-6 col-6">
                    <span>Item ({{$cart_item->sum('qty')}})</span>
                </div>
                <div class="col-lg-6 col-6 text-end">
                    <span>$ {{$cart_item->sum('total_price')}}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-6">
                    <span>Discount</span>
                </div>
                <div class="col-lg-6 col-6 text-end">
                    <span>13%</span>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-6">
                    <span>Shipping</span>
                </div>
                <div class="col-lg-6 col-6 text-end">
                    <span>US $1.99</span>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-6">
                    <span>Sub Total</span>
                </div>
                <div class="col-lg-6 col-6 text-end">
                    <span>US $1.99</span>
                </div>
            </div>
            <div class="cart_bottom">
                <div class="row">
                    <div class="col-lg-6 col-6">
                        <p>Total</p>
                    </div>
                    <div class="col-lg-6 col-6 text-end">
                        <p>
                            {{$cart_item->sum('total_price')}}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
