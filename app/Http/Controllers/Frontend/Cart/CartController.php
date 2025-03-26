<?php

namespace App\Http\Controllers\Frontend\Cart;

use App\Models\Area;
use App\Models\Cart;
use App\Models\City;
use App\Models\User;
use App\Models\Local;
use App\Models\State;
use App\Models\Coupon;
use App\Models\Country;
use App\Models\Product;
use App\Events\LogEvent;
use App\Models\District;
use App\Models\Province;
use App\Models\Wishlist as WishList;
use App\Models\CartAssets;
use Illuminate\Support\Str;
use App\Models\Admin\VatTax;
use App\Models\New_Customer;
use App\Models\ProductImage;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use App\Actions\Cart\CartAction;
use App\Models\ProductAttribute;
use App\Models\UserBillingAddress;
use Illuminate\Support\Facades\DB;
use App\Models\UserShippingAddress;
use App\Http\Controllers\Controller;
use App\Models\OrderTimeSetting;
use App\Models\Setting;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\View;

class CartController extends Controller
{

    protected $shipping_address = null;
    protected $billing_address = null;
    protected $cart = null;
    protected $cart_asset = null;

    function __construct(UserShippingAddress $shipping_address, UserBillingAddress $billing_address, Cart $cart, CartAssets $cart_asset)
    {
        $this->shipping_address = $shipping_address;
        $this->billing_address = $billing_address;
        $this->cart = $cart;
        $this->cart_asset = $cart_asset;
    }

    public function getCartCount()
    {
        if (auth()->guard('customer')->user()) {
            $user_id = auth()->guard('customer')->user()->id;
            $total_quantity = Cart::where('user_id', $user_id)->value('total_qty');
            if ($total_quantity == null) {
                $total_quantity = 0;
            }
        } else {
            $total_quantity = 0;
        }

        return response()->json([
            'total' => $total_quantity,
        ]);
    }

    public function getCartData()
    {
        $cart_item = Cart::where('user_id', auth()->guard('customer')->user()->id)->get();
        return view('frontend.cart.cart', compact('cart_item'));
    }

    public function updateCart(Request $request)
    {
        $cart = Cart::where('user_id', auth()->guard('customer')->user()->id)->first();
        $cart_assets = $cart->cartAssets->where('id', $request->item_id)->first();
       
        $product = Product::where('id', $cart_assets->product_id)->first();
        $product_stock = ProductStock::where('product_id', $product->id)->where('id',$cart_assets->varient_id)->first();
    //    dd($product_stock->price);
        if (getOfferProduct($product, $product_stock) != null) {
           
            $actual_price = getOfferProduct($product, $product_stock);
            $discount_per_product = $product_stock->price - getOfferProduct($product, $product_stock);
        } elseif ($product_stock->special_price != null) {
           
            $actual_price = $product_stock->special_price;
            $discount_per_product = $product_stock->price - $product_stock->special_price;
        } else {
            $actual_price = $product_stock->price;
            $discount_per_product = 0;
        }
        
        $total_actual=$actual_price * $request->product_qty;

        $vatAmount=0;
        if($product->vat_percent==0)
        {
            $vatTax=VatTax::first();
            $vatPercent=(int)$vatTax->vat_percent;
            $vatAmount=($total_actual*$vatPercent)/100;
            $total_actual=$total_actual+round($vatAmount);
        }
        else
        {
            $total_actual=$total_actual;
            $vatAmount=0;
        }
        // dd($discount_per_product);
        $cart_assets->qty = $request->product_qty;
        $cart_assets->price=$product_stock->price-$discount_per_product;
        // $cart_assets->sub_total_price = $request->product_qty * $actual_price;
        $cart_assets->sub_total_price = $total_actual;
        $cart_assets->discount = $discount_per_product;
        $cart_assets->vatamountfield=round($vatAmount);

        $show_amount = $total_actual;

        $status = $cart_assets->save();
        if ($status) {
            $cart_total = null;
            $cart_qty = null;
            $cart_discount = 0;

            $totalVatAmount=0;

            foreach ($cart->cartAssets  as $cart_data) {
                // dd($cart->cartAssets);
                $totalVatAmount=$totalVatAmount+$cart_data->vatamountfield;
                $cart_total = $cart_total + $cart_data->sub_total_price;
                $cart_qty = $cart_qty + $cart_data->qty;
                $cart_discount = $cart_discount + ($cart_data->discount*$cart_qty);

            }
            // dd($cart_discount);
            $input['total_price'] = $cart_total;
            $input['total_qty'] = $cart_qty;
            $input['total_discount'] = $cart_discount;
            $cart->fill($input);
            $cart->save();
            $cart_item = Cart::where('user_id', auth()->guard('customer')->user()->id)->get();
            // return view('frontend.cart.side_cart', compact('cart_item'));
            $cart_total_price = (int)$cart_total + $cart_discount;
            $cart_total_qty = (int)$cart_qty;
            $discount_per = round(($cart_discount / ($cart_total + $cart_discount)) * 100, 2);
            $sub_total_price = (int)$cart_total;
            $view = View::make("frontend.cart.side_cart", compact('cart'));
            $html = $view->render();
            return response()->json([
                'error' => false,
                'data' => number_format($show_amount),
                'cart_total_price' => number_format($cart_total_price),
                'cart_total_qty' => $cart_total_qty,
                'discount_per' => $discount_per,
                'sub_total_price' => number_format($sub_total_price),
                'vatAmount'=>number_format($totalVatAmount) ?? 0,
                'msg' => 'Success !',
                'view'=>$html,
            ], 200);
        }
    }
    public function priceCalculation()
    {
        $total_price_after_discount = Cart::where('user_id', auth()->guard('customer')->user()->id)->value('total_price');
        $total_discount = Cart::where('user_id', auth()->guard('customer')->user()->id)->value('total_discount');
        $total_price = $total_price_after_discount + $total_discount;
        $sub_total = $total_price_after_discount;
        $total_qty = Cart::where('user_id', auth()->guard('customer')->user()->id)->value('total_qty');
        return response()->json([
            'total_price' => $total_price,
            'total_discount' => $total_discount,
            'sub_total' => $total_price_after_discount,
            'total_qty' => $total_qty,
        ]);
    }


    public function getCart()
    {
        $cart = Cart::where('user_id', auth()->guard('customer')->user()->id)->first();
        $vatAmount=0;
        if($cart !=null)
        {
            $cart_asset=$cart->cartAssets;
            if(count($cart_asset) >0)
            {
                foreach($cart_asset as $cartItem)
                {
                    $vatAmount=$vatAmount+$cartItem->vatamountfield;
                }
            }
        }
        
        
        

        if (!empty($cart)) {
            $cart_item = $cart->cartAssets;
           
        } else {
            $cart_item = $cart;
        }
        $loginUser=auth()->guard('customer')->user();
       
        return view('frontend.cart.cart', compact('cart_item','vatAmount','loginUser'));
    }

    public function getCheckout()
    {
       
        $cart = Cart::where('user_id', auth()->guard('customer')->user()->id)->first();

        if ($cart==null) {
            request()->session()->flash('error','Sorry !! No items In The Cart');
            return redirect()->back();
        }
        $total_amount=null;
        foreach ($cart->cartAssets as $items) {
            if ($items == null || empty($items)) {
              return redirect()->back();
            }
            $total_amount = $total_amount + $items->sub_total_price;
          }
      
        $coupon = Coupon::first();
        $consumer = New_Customer::where('id', auth()->guard('customer')->user()->id)->first();
        $cart_item = CartAssets::where('cart_id', $cart->id)->get();
        $countries = Country::where('status', true)->get();
        $provinces = Province::where('status', 'active')->get();
        $shipping_address = UserShippingAddress::where('user_id', auth()->guard('customer')->user()->id)->take(5)->latest()->get();
        $billing_address = UserBillingAddress::where('user_id', auth()->guard('customer')->user()->id)->take(5)->latest()->get();
        $default_shipping_address = UserShippingAddress::where('user_id',auth()->guard('customer')->user()->id)->first();
        if(!$default_shipping_address->getLocation || !$default_shipping_address->getLocation->deliveryRoute)
        {
            request()->session()->flash('error','Something Went Wrong !!');
            return redirect()->route('index');
        }
        if($default_shipping_address->getLocation->deliveryRoute->charge){
            $default_shipping_charge = $default_shipping_address->getLocation->deliveryRoute->charge;
        }else{
            $default_shipping_charge = Setting::where('key','default_shipping_charge')->pluck('value')->first();
        }
        // $billing_address = UserBillingAddress::where('user_id', auth()->guard('customer')->user()->id)->get();
        return view('frontend.cart.checkout', compact('coupon', 'cart_item', 'provinces', 'consumer', 'countries', 'shipping_address', 'billing_address','total_amount','default_shipping_charge'));
    }

    public function directBuy(Request $request, $id)
    {

        if (!auth()->guard('customer')->user()) {
            return redirect()->route('Clogin');
        }


        if ($request->color_id == null) {
            $product = ProductStock::where('product_id', $id)->where('color_id', $request->color)->first();
        } else {
            $product = ProductStock::where('product_id', $id)->where('color_id', $request->color_id)->first();
        }

        if ($product == null) {
            return redirect()->back()->with('error', "Product Stock is not available");
        }

        $consumer = New_Customer::where('id', auth()->guard('customer')->user()->id)->first();
        $countries = Country::where('status', true)->get();
        $provinces = Province::where('status', 'active')->get();
        $shipping_address = $this->shipping_address->where('user_id', auth()->guard('customer')->user()->id)->get();
        $billing_address = $this->billing_address->where('user_id', auth()->guard('customer')->user()->id)->get();
        $district = District::get();
        $area = Local::get();
        return view('frontend.cart.oneProductCheckout', compact('product', 'provinces', 'consumer', 'countries', 'shipping_address', 'billing_address', 'district', 'area'));
    }
    public function addToWishlist(Request $request)
    {
        $product_id = $request->product_id;
        if (!auth()->guard('customer')->user()) {
            return response()->json([
                'login' => 'login first',
            ]);
        }

        $data = WishList::where(['user_id' => auth()->guard('customer')->user()->id, 'product_id' => $product_id])->first();
        if ($data) {
            $data->delete();
            return response()->json([
                'remove' => "product removed",
            ]);
        }

        LogEvent::dispatch('Wishlist Created', 'Wishlist Created', route('addToWishlist'));        
        WishList::create(['user_id' => auth()->guard('customer')->user()->id, 'product_id' => $product_id]);


        return response()->json([
            'success' => "Successfully added to wishlist",
        ]);
    }

    public function addToCart(Request $request)
    {   
        // dd($request->all());
        $today = Carbon::now()->format('l');
        $order_timing_check = OrderTimeSetting::where('day', $today)->exists();

        // if($order_timing_check)
        // {
        //     $order_timing = OrderTimeSetting::where('day', $today)->first();
        //     if($order_timing->day_off == 1)
        //     {   
        //         $request->session()->flash('error', 'Today is day off, so you cannot place your order today');
        //         return back();
        //     }
        //     $currentTime = Carbon::now()->format('H:i');
        //     if($currentTime > $order_timing->end_time || $currentTime < $order_timing->start_time){
        //         $request->session()->flash('error', 'Please place your order between ' . $order_timing->start_time . ' and ' .$order_timing->end_time);
        //         return back();
        //     }

        // }
        if (!auth()->guard('customer')->user()) {
            $url = '/customer/login';
            return redirect($url);
        }

        
        $options  = [];
        if ($request->has('key')) {
            if (count($request->key) > 0) {
                foreach ($request->key as $index => $k) {
                    $options[] = [
                        'id' => $k,
                        'title' => $request->key_title[$index],
                        'value' => $request->value[$index],
                    ];
                }
            }
        }
       
        // $varient_id = ProductStock::where('product_id', $request->product_id)->where('color_id', $request->color)->first();
        $varient_id = $request->varient_id;
        $stock_id = ProductStock::where('id', $varient_id)->where('product_id', $request->product_id)->first();
        if($stock_id)
        {
            if ((int)$stock_id->quantity <= 0 || $request->qty > (int)$stock_id->quantity) {
                $request->session()->flash('error', 'Sorry !! Product Is Outof Stocks');
                return redirect()->back();
            }
    
        }
        DB::beginTransaction();
        try {
                
        (new CartAction($request))->addToCart($options, $varient_id);
        LogEvent::dispatch('Add to Cart', 'Add to Cart', route('cart.add-to-cart'));    
       
        DB::commit();
        
        $request->session()->flash('success', 'Added To The Cart Successfully !!');
        
        return redirect()->route('cart.index');
        return view('frontend.cart.side_cart', compact('cart'));
        } catch (\Throwable $th) {
            DB::rollBack();
            $request->session()->flash('error',$th->getMessage());
            return back();
        }

    }



    public function addSingleProductToCart(Request $request)
    {
        $today = Carbon::now()->format('l');
        $order_timing_check = OrderTimeSetting::where('day', $today)->exists();

        if($order_timing_check)
        {
            $order_timing = OrderTimeSetting::where('day', $today)->first();
            if($order_timing->day_off == 1)
            {   
                $request->session()->flash('error', 'Today is day off, so you cannot place your order today');
                return back();
            }
            $currentTime = Carbon::now()->format('H:i');
            if($currentTime > $order_timing->end_time || $currentTime < $order_timing->start_time){
                $request->session()->flash('error', 'Please place your order between ' . $order_timing->start_time . ' and ' .$order_timing->end_time);
                return back();
            }

        }
        
        $user = auth()->guard('customer')->user();

        $product = Product::findOrFail($request->product_id);
        $varient_id = $product->stocks[0]->id;
        DB::beginTransaction();
        try {
            $product_stock = ProductStock::where('id', $varient_id)->where('product_id', $product->id)->first();
            $price = $product_stock->price;
            $color = $product_stock->color_id;
            $special_price = $product_stock->special_price;
            $offer_price = getOfferProduct($product, $product_stock);
            $qty = 1;
            if ($price <= 0 || $price == null) {
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => 'Sorry !! Price Must Me Equal Or Greater Than 1 !'
                ];

                return response()->json($response, 500);
            }
            if ($qty <= 0) {
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => 'Sorry !! Qty Must Me Equal Or Greater Than 1 !e'
                ];

                return response()->json($response, 500);
            }

            if ($offer_price != null) {
                if ($offer_price > $price) {
                    $response = [
                        'error' => true,
                        'data' => null,
                        'msg' => 'Sorry ! Something Went Wrong Offer Price Is Grater Than Product Original Price!!'
                    ];
                    return response()->json($response, 500);
                }
                $original_price = $offer_price;
                $discount = $price - $offer_price;
            } elseif ($special_price != null) {
                if ($special_price > $price) {
                    $response = [
                        'error' => true,
                        'data' => null,
                        'msg' => 'Sorry ! Something Went Wrong Special Price Is Grater Than Product Original Price!!'
                    ];
                    return response()->json($response, 500);
                }
                $original_price = $special_price;
                $discount = $price - $special_price;
            } else {
                $original_price = $price;
                $discount = 0;
            }

            $product_varient = [];
            foreach ($product_stock->getStock as $key => $stock) {
                $product_varient[$key]['id'] = $stock->getOption->id;
                $product_varient[$key]['title'] = $stock->getOption->title;
                $product_varient[$key]['value'] = $stock->value;
            }

            $cart = $this->cart->where('user_id', $user->id)->first();
            // -------------------------Cart Table--------------------------
            if ($cart) {
                $old_cart_asset_data = $this->cart_asset->where('cart_id', $cart->id)->get();
                $total_cart_price = null;
                $total_cart_qty = null;
                $total_cart_discount = null;
                foreach ($old_cart_asset_data as $data) {
                    $total_cart_price = $total_cart_price + $data->sub_total_price;
                    $total_cart_qty = $total_cart_qty + $data->qty;
                    $total_cart_discount = $total_cart_discount + $data->discount;
                }
                $total_cart_price = $total_cart_price + ($original_price * $qty);
                $total_cart_qty = $total_cart_qty + $qty;
                $total_cart_discount = $total_cart_discount + ($discount * $qty);
                $cart_data['user_id'] = $user->id;
                $cart_data['total_price'] = $total_cart_price;
                $cart_data['total_qty'] = $total_cart_qty;
                $cart_data['total_discount'] = $total_cart_discount;
                $cart->fill($cart_data);
                $cart->save();
            } else {
                $cart_data['user_id'] = $user->id;
                $cart_data['total_price'] = $original_price * $qty;
                $cart_data['total_qty'] = $qty;
                $cart_data['total_discount'] = $discount * $qty;
                $this->cart->fill($cart_data);
                $this->cart->save();
            }
            // -------------------------/Cart Table--------------------------


            // ----------------------------Cart Asset---------------------

            if ($cart) {
                $cart_asset = $this->cart_asset->where('cart_id', $cart->id)->where('product_id', $product->id)->where('color', $color)->where('varient_id', $request->varient_id)->first();
                $id = $cart->id;
            } else {
                $cart_asset = $this->cart_asset->where('cart_id', $this->cart->id)->where('product_id', $product->id)->where('color', $color)->where('varient_id', $request->varient_id)->first();
                $id = $this->cart->id;
            }
            if ($cart_asset) {
                $old_qty = $cart_asset->qty;
                $cart_asset_data['cart_id'] = $id;
                $cart_asset_data['product_id'] = $product->id;
                $cart_asset_data['product_name'] = $product->name;
                $cart_asset_data['price'] = $original_price;
                $cart_asset_data['qty'] = $qty + $old_qty;
                $cart_asset_data['sub_total_price'] = $cart_asset_data['price'] * $cart_asset_data['qty'];
                $cart_asset_data['color'] = $color;
                $cart_asset_data['discount'] = $discount * $cart_asset_data['qty'];
                $cart_asset_data['varient_id'] = $request->varient_id;
                $cart_asset_data['options'] = json_encode($product_varient);
                $cart_asset->fill($cart_asset_data);
                $cart_asset->save();
            } else {
                $cart_asset_data['cart_id'] = $id;
                $cart_asset_data['product_id'] = $product->id;
                $cart_asset_data['product_name'] = $product->name;
                $cart_asset_data['price'] = $original_price;
                $cart_asset_data['qty'] = $qty;
                $cart_asset_data['sub_total_price'] = $original_price * $qty;
                $cart_asset_data['color'] = $color;
                $cart_asset_data['discount'] = $discount * $qty;
                $cart_asset_data['varient_id'] = $request->varient_id;
                $cart_asset_data['options'] = json_encode($product_varient);
                $this->cart_asset->fill($cart_asset_data);
                $this->cart_asset->save();
            }

            // ----------------------------/Cart Asset---------------------

            if ($cart) {
                $old_cart_asset_data = $this->cart_asset->where('cart_id', $cart->id)->get();
                $total_cart_price = null;
                $total_cart_qty = null;
                $total_cart_discount = null;
                foreach ($old_cart_asset_data as $data) {
                    $total_cart_price = $total_cart_price + $data->sub_total_price;
                    $total_cart_qty = $total_cart_qty + $data->qty;
                    $total_cart_discount = $total_cart_discount + $data->discount;
                }
                $this->cart->where('id', $cart->id)->update([
                    'user_id' => $user->id,
                    'total_price' => $total_cart_price,
                    'total_qty' => $total_cart_qty,
                    'total_discount' => $total_cart_discount
                ]);
            }
            $total_qty = $this->cart->where('user_id', $user->id)->first();
            $response = [
                'error' => false,
                'data' => $total_qty->total_qty,
                'msg' => 'Items Added Successfully !'
            ];
            DB::commit();
            $cart = $this->cart->where('user_id', $user->id)->first();
        return view('frontend.cart.side_cart', compact('cart'));
        }catch(\Throwable $th){
            $response=[
                'error'=>true,
                'msg'=>$th->getMessage()
            ];
            return response()->json($response,200);
        }
       
    }

    public function wishlistToCart(Request $request)
    {
        $wishlist_products = Wishlist::get();
        $user_id = auth()->guard('customer')->user()->id;
        foreach ($wishlist_products as $wishlist_product) {
            $product_id = $wishlist_product->product_id;
            $title = Product::where('id', $product_id)->value('name');
            $price = Product::where('id', $product_id)->first()->stocks->first()->price;
            $special_price = Product::where('id', $product_id)->first()->stocks->first()->special_price;
            $qty = 1;
            if (isset($special_price)) {
                $discount = $price - $special_price;
                $total_price = $special_price;
            } else {
                $discount = 0;
                $total_price = $price;
            }
            $color_id = Product::where('id', $product_id)->first()->stocks->first()->color_id;

            $product = Product::findOrFail($product_id);
            $availableStock = $product->stocks->first()->quantity;

            $data = ['user_id' => $user_id, 'product_id' => $product_id, 'title' => $title, 'price' => $price, 'qty' => $qty, 'color_id' => $color_id, 'total_price' => $total_price];
            $data_exist = Cart::with('cartAssets')->where('user_id', $user_id)->first();

            // Check user have cart or not
            if (!empty($data_exist)) {
                $previous_total_qty = Cart::where('user_id', $user_id)->value('total_qty');
                $previous_total_price = Cart::where('user_id', $user_id)->value('total_price');
                $previous_total_discount = Cart::where('user_id', $user_id)->value('total_discount');
                $new_total_price = $previous_total_price + $total_price;
                $new_total_qty = $previous_total_qty + $qty;
                $new_total_discount = $previous_total_discount + $discount;

                // check stock quantity
                $product_in_assets = $data_exist->cartAssets->where('product_id', $product_id)->where('color', $color_id)->where('cart_id', $data_exist->id)->first();
                if (!empty($product_in_assets)) {
                    $total_qty_on_cart = $product_in_assets->qty + $qty;
                    if ($availableStock < $total_qty_on_cart) {
                        return response()->json([
                            'error' => 'Error',
                        ]);
                    }
                }

                // Update cart data
                Cart::where('user_id', $user_id)->update(['total_qty' => $new_total_qty, 'total_price' => $new_total_price, 'total_discount' => $new_total_discount]);
                // $total_quantity = Cart::where('user_id', $user_id)->sum('qty');


                // update or create cart Assets
                $cart = Cart::where('user_id', $user_id)->first();
                $cart_assets = $cart->cartAssets->where('product_id', $product_id)->where('color', $color_id)->where('cart_id', $cart->id)->first();
                if (!empty($cart_assets)) {
                    $new_sub_total_price = $cart_assets->sub_total_price + $total_price;
                    $new_qty = $cart_assets->qty + $qty;
                    $new_discount = $cart_assets->discount + $discount;
                    // $cart_assets = CartAssets::where(['product_id'=>$product_id,'color'=>$color_id,'cart_id'=>$cart_id])->first();
                    $cart_assets->update(['qty' => $new_qty, 'sub_total_price' => $new_sub_total_price, 'discount' => $new_discount]);
                } else {
                    CartAssets::create(['cart_id' => $cart->id, 'product_id' => $product_id, 'product_name' => $title, 'price' => $price, 'qty' => $qty, 'sub_total_price' => $total_price, 'color' => $color_id, 'discount' => $discount]);
                }
            } else {
                Cart::create(['user_id' => $user_id, 'total_price' => $total_price, 'total_qty' => $qty, 'total_discount' => $discount]);
                $cart_id = Cart::where('user_id', $user_id)->value('id');
                CartAssets::create(['cart_id' => $cart_id, 'product_id' => $product_id, 'product_name' => $title, 'price' => $price, 'qty' => $qty, 'sub_total_price' => $total_price, 'color' => $color_id, 'discount' => $discount]);
                // $total_quantity = Cart::where('user_id', $user_id)->sum('qty');


            }
        }
        $cart = Cart::with('cartAssets')->where('user_id', $user_id)->first();
        return view('frontend.cart.side_cart', compact('cart'));
    }

    // remove product from cart
    public function ajaxRemoveCart(Request $request)
    {

        if ($request->cart_assets_id == null || empty($request->cart_assets_id) || $request->cart_assets_id == 0) {
            $response = [
                'error' => true,
                'msg' => 'Something Went Wrong !!'
            ];
            return response()->json($response, 200);
        }
        $user = auth()->guard('customer')->user();
        if (!$user) {
            $response = [
                'error' => true,
                'msg' => 'Plz Login To Delete From Cart !!'
            ];
            return response()->json($response, 200);
        }

        DB::beginTransaction();
        try {
            $this->cart = $this->cart->where('user_id', $user->id)->first();
            if (!$this->cart) {
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => 'Sorry !! Cart Not Found'
                ];
                return response()->json($response, 500);
            }
            $product = $this->cart_asset->where('cart_id', $this->cart->id)->where('id', $request->cart_assets_id)->first();
            if (!$product) {
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => 'Sorry !! Something Went Wrong'
                ];
                return response()->json($response, 500);
            }
            $product_sub_total = $product->sub_total_price;
            $product_qty = $product->qty;
            $product_discount = $product->discount;
            $del = $this->cart_asset->where('id', $product->id)->delete();
            if ($del) {
                $this->cart->update([
                    'total_price' => $this->cart->total_price - $product_sub_total,
                    'total_qty' => $this->cart->total_qty - $product_qty,
                    'total_discount' => $this->cart->total_discount - $product_discount
                ]);
                $cart = $this->cart->where('user_id', $user->id)->first();
                $cart_item = $this->cart_asset->where('cart_id', $cart->id)->get();
                if ($cart_item->count() <= 0) {
                    $this->cart->where('user_id', $user->id)->delete();
                }
                $response = [
                    'error' => false,
                    'msg' => 'Item Deleted Successfully !!'
                ];
            }
            DB::commit();
            $cart = $this->cart->where('user_id', $user->id)->first();
            return view('frontend.cart.side_cart', compact('cart'));
        } catch (\Throwable $th) {
            DB::rollBack();
            $response = [
                'error' => true,
                'msg' => $th->getMessage()
            ];
            return response()->json($response, 200);
        }
    }

    public function removeProduct(Request $request, $id)
    {

        $user = auth()->guard('customer')->user();
        $this->cart = $this->cart->where('user_id', $user->id)->first();
        if (!$this->cart) {

            $request->session()->flash('error', "No Items In The Cart");
            return redirect()->back();
        }
        $product = $this->cart_asset->where('cart_id', $this->cart->id)->where('id', $id)->first();
        if (!$product) {
            $request->session()->flash('error', "Invalid Product Id");
            return redirect()->back();
        }
        $product_sub_total = $product->sub_total_price;
        $product_qty = $product->qty;
        $product_discount = $product->discount;
        $del = $this->cart_asset->where('id', $product->id)->delete();

        if ($del) {

            $this->cart->update([
                'total_price' => $this->cart->total_price - $product_sub_total,
                'total_qty' => $this->cart->total_qty - $product_qty,
                'total_discount' => $this->cart->total_discount - $product_discount
            ]);
            $cart = $this->cart->where('user_id', $user->id)->first();
            $cart_item = $this->cart_asset->where('cart_id', $cart->id)->get();
            if ($cart_item->count() <= 0) {
                $this->cart->where('user_id', $user->id)->delete();
            }
            $request->session()->flash('success', 'Product Removed Successfully !!');
            return redirect()->back();
        } else {
            $request->session()->flash('error', "There Was A Problem While Deleting Product !!");
            return redirect()->back();
        }
    }


    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $request->all();

        try {
        } catch (\Throwable $th) {
        }
    }

    public function tagToCart(Request $request)
    {
        
        $user = auth()->guard('customer')->user();
        if(!$user)
        {
            $response=[
                'login'=>true,
                'msg'=>'Plz Login !!'
            ];
            return response()->json($response,200);
        }
        $product = Product::findOrFail($request->product_id);
        $varient_id = $product->stocks[0]->id;
        DB::beginTransaction();
        try {
            $product_stock = ProductStock::where('id', $varient_id)->where('product_id', $product->id)->first();
            $stock_id = ProductStock::where('id', $varient_id)->where('product_id', $request->product_id)->where('color_id', $product_stock->color_id)->first();
            if ((int)$stock_id->quantity <= 0 || $request->qty > (int)$stock_id->quantity) {
               $response=[
                'error'=>true,
                'data'=>null,
                'msg'=>'Sorry !! Product Is Outof Stocks !!'
               ];
                return response()->json($response,200);
            }
            
            $price = $product_stock->price;
            $color = $product_stock->color_id;
            $special_price = $product_stock->special_price;
            $offer_price = getOfferProduct($product, $product_stock);
            $qty = 1;
            if($user->wholeseller=='1')
            {
                $qty=$product_stock->mimquantity ?? 1;
            }
            if ($price <= 0 || $price == null) {
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => 'Sorry !! Price Must Me Equal Or Greater Than 1 !'
                ];

                return response()->json($response, 500);
            }
            if ($qty <= 0) {
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => 'Sorry !! Qty Must Me Equal Or Greater Than 1 !e'
                ];

                return response()->json($response, 500);
            }

            if ($offer_price != null) {
                if ($offer_price > $price) {
                    $response = [
                        'error' => true,
                        'data' => null,
                        'msg' => 'Sorry ! Something Went Wrong Offer Price Is Grater Than Product Original Price!!'
                    ];
                    return response()->json($response, 500);
                }
                $original_price = $offer_price;
                $discount = $price - $offer_price;
            } elseif ($special_price != null) {
                if ($special_price > $price) {
                    $response = [
                        'error' => true,
                        'data' => null,
                        'msg' => 'Sorry ! Something Went Wrong Special Price Is Grater Than Product Original Price!!'
                    ];
                    return response()->json($response, 500);
                }
                $original_price = $special_price;
                $discount = $price - $special_price;
            } else {
                $original_price = $price;
                $discount = 0;
            }

            $product_varient = [];
            foreach ($product_stock->getStock as $key => $stock) {
                $product_varient[$key]['id'] = $stock->getOption->id;
                $product_varient[$key]['title'] = $stock->getOption->title;
                $product_varient[$key]['value'] = $stock->value;
            }

            if($product->vat_percent==0)
            {
                $vatTax=VatTax::first();
                $vatPercent=(int)$vatTax->vat_percent;
                $vatAmount=($original_price*$vatPercent)/100;
                $fixed_price=$original_price+round($vatAmount);
            }
            else
            {
                $fixed_price=$original_price;
                $vatAmount=0;
            }

            $cart = $this->cart->where('user_id', $user->id)->first();
            // -------------------------Cart Table--------------------------
            if ($cart) {
                $old_cart_asset_data = $this->cart_asset->where('cart_id', $cart->id)->get();
                $total_cart_price = null;
                $total_cart_qty = null;
                $total_cart_discount = null;
                foreach ($old_cart_asset_data as $data) {
                    $total_cart_price = $total_cart_price + $data->sub_total_price;
                    $total_cart_qty = $total_cart_qty + $data->qty;
                    $total_cart_discount = $total_cart_discount + $data->discount;
                }
                $total_cart_price = $total_cart_price + ($fixed_price * $qty);
                $total_cart_qty = $total_cart_qty + $qty;
                $total_cart_discount = $total_cart_discount + ($discount * $qty);
                $cart_data['user_id'] = $user->id;
                $cart_data['total_price'] = $total_cart_price;
                $cart_data['total_qty'] = $total_cart_qty;
                $cart_data['total_discount'] = $total_cart_discount;
                $cart->fill($cart_data);
                $cart->save();
            } else {
                $cart_data['user_id'] = $user->id;
                $cart_data['total_price'] = $fixed_price * $qty;
                $cart_data['total_qty'] = $qty;
                $cart_data['total_discount'] = $discount * $qty;
                $this->cart->fill($cart_data);
                $this->cart->save();
            }
            // -------------------------/Cart Table--------------------------


            // ----------------------------Cart Asset---------------------
            // dd('sumit',$varient_id);
            if ($cart) {
                // $cart_asset = $this->cart_asset->where('cart_id', $cart->id)->where('product_id', $product->id)->where('color', $color)->where('varient_id', $varient_id)->first();
                $cart_asset = $this->cart_asset->where('cart_id', $cart->id)->where('product_id', $product->id)->where('varient_id', $varient_id)->first();
                $id = $cart->id;
            } else {
                // $cart_asset = $this->cart_asset->where('cart_id', $this->cart->id)->where('product_id', $product->id)->where('color', $color)->where('varient_id', $varient_id)->first();
                $cart_asset = $this->cart_asset->where('cart_id', $this->cart->id)->where('product_id', $product->id)->where('varient_id', $varient_id)->first();
                $id = $this->cart->id;
            }
            if ($cart_asset) {
                $old_qty = $cart_asset->qty;
                $cart_asset_data['cart_id'] = $id;
                $cart_asset_data['product_id'] = $product->id;
                $cart_asset_data['product_name'] = $product->name;
                $cart_asset_data['price'] = $original_price;
                $cart_asset_data['qty'] = $qty + $old_qty;
                $cart_asset_data['sub_total_price'] = $fixed_price * $cart_asset_data['qty'];
                $cart_asset_data['color'] = $color;
                $cart_asset_data['discount'] = $discount;
                $cart_asset_data['varient_id'] = $varient_id;
                $cart_asset_data['options'] = json_encode($product_varient);
                $cart_asset->fill($cart_asset_data);
                $cart_asset->save();
            } else {
                $cart_asset_data['cart_id'] = $id;
                $cart_asset_data['product_id'] = $product->id;
                $cart_asset_data['product_name'] = $product->name;
                $cart_asset_data['price'] = $original_price;
                $cart_asset_data['qty'] = $qty;
                $cart_asset_data['sub_total_price'] = $fixed_price * $qty;
                $cart_asset_data['color'] = $color;
                $cart_asset_data['discount'] = $discount;
                $cart_asset_data['varient_id'] = $varient_id;
                $cart_asset_data['options'] = json_encode($product_varient);
                $cart_asset_data['vatamountfield'] = round($vatAmount);
                $this->cart_asset->fill($cart_asset_data);
                $this->cart_asset->save();
            }

            // ----------------------------/Cart Asset---------------------

            if ($cart) {
                $old_cart_asset_data = $this->cart_asset->where('cart_id', $cart->id)->get();
                $total_cart_price = null;
                $total_cart_qty = null;
                $total_cart_discount = null;
                foreach ($old_cart_asset_data as $data) {
                    $total_cart_price = $total_cart_price + $data->sub_total_price;
                    $total_cart_qty = $total_cart_qty + $data->qty;
                    $total_cart_discount = $total_cart_discount + $data->discount;
                }
                $this->cart->where('id', $cart->id)->update([
                    'user_id' => $user->id,
                    'total_price' => $total_cart_price,
                    'total_qty' => $total_cart_qty,
                    'total_discount' => $total_cart_discount
                ]);
            }
            $total_qty = $this->cart->where('user_id', $user->id)->first();
            $response = [
                'error' => false,
                'data' => $total_qty->total_qty,
                'msg' => 'Items Added Successfully !'
            ];
            DB::commit();
            $cart = $this->cart->where('user_id', $user->id)->first();
            return view('frontend.cart.side_cart', compact('cart'))->with('success', 'Items Added Successfully !!');
            return response()->json($response, 200);
        } catch (\Exception $ex) {
            DB::rollBack();
            $response = [
                'error' => true,
                'data' => null,
                'msg' => $ex->getMessage()
            ];
            return response()->json($response, 200);
        }
    }

    public function directAddToCart(Request $request)
    {
        // dd($request->all());
        $user = New_Customer::where('id',63)->first();
        if(!$user)
        {
            $response=[
                'login'=>true,
                'msg'=>'Plz Login !!'
            ];
            return response()->json($response,200);
        }
        $product = Product::findOrFail($request->product_id);
        if($request->varientId && $request->varientId !=null){
            $varient_id = $request->varientId;
        }else{
            $varient_id = $product->stocks[0]->id;
        }
        DB::beginTransaction();
        try {
            $product_stock = ProductStock::where('id', $varient_id)->where('product_id', $product->id)->first();
            $stock_id = ProductStock::where('id', $varient_id)->where('product_id', $request->product_id)->where('color_id', $product_stock->color_id)->first();
            if ((int)$stock_id->quantity <= 0 || $request->qty > (int)$stock_id->quantity) {
               $response=[
                'error'=>true,
                'data'=>null,
                'msg'=>'Sorry !! Product Is Outof Stocks !!'
               ];
                return response()->json($response,200);
            }
            
            $price = $product_stock->price;
            $color = $product_stock->color_id;
            $special_price = $product_stock->special_price;
            $offer_price = getOfferProduct($product, $product_stock);
            $qty = $request->qty;
            if($user->wholeseller=='1')
            {
                $qty=$product_stock->mimquantity ?? 1;
            }
            if ($price <= 0 || $price == null) {
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => 'Sorry !! Price Must Me Equal Or Greater Than 1 !'
                ];

                return response()->json($response, 500);
            }
            if ($qty <= 0) {
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => 'Sorry !! Qty Must Me Equal Or Greater Than 1 !e'
                ];

                return response()->json($response, 500);
            }

            if ($offer_price != null) {
                if ($offer_price > $price) {
                    $response = [
                        'error' => true,
                        'data' => null,
                        'msg' => 'Sorry ! Something Went Wrong Offer Price Is Grater Than Product Original Price!!'
                    ];
                    return response()->json($response, 500);
                }
                $original_price = $offer_price;
                $discount = $price - $offer_price;
            } elseif ($special_price != null) {
                if ($special_price > $price) {
                    $response = [
                        'error' => true,
                        'data' => null,
                        'msg' => 'Sorry ! Something Went Wrong Special Price Is Grater Than Product Original Price!!'
                    ];
                    return response()->json($response, 500);
                }
                $original_price = $special_price;
                $discount = $price - $special_price;
            } else {
                $original_price = $price;
                $discount = 0;
            }

            $product_varient = [];
            foreach ($product_stock->getStock as $key => $stock) {
                $product_varient[$key]['id'] = $stock->getOption->id;
                $product_varient[$key]['title'] = $stock->getOption->title;
                $product_varient[$key]['value'] = $stock->value;
            }

            if($product->vat_percent==0)
            {
                $vatTax=VatTax::first();
                $vatPercent=(int)$vatTax->vat_percent;
                $vatAmount=($original_price*$vatPercent)/100;
                $fixed_price=$original_price+round($vatAmount);
            }
            else
            {
                $fixed_price=$original_price;
                $vatAmount=0;
            }
            $arrayData=[];
            $guestSessionCartData=null;
            $guestSessionCartData=session()->get('guest_cart');
            // dd($guestSessionCartData);
            // session()->forget('guest_cart');
            $currentCartData[]=[
                    'cart_id' => 1,
                    'slug'=>$product->slug,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $original_price, 
                    'qty' => $qty,
                    'sub_total_price' => $fixed_price * $qty,
                    'color' => $color,
                    'discount' => $discount,
                    'varient_id' => $varient_id,
                    'options' => json_encode($product_varient),
                    'vatamountfield' => round($vatAmount),
                    'image'=>$product->images[0]->image ?? null
            ];
            // dd($currentCartData);
            if($guestSessionCartData){
                if($guestSessionCartData['cart_item'] && $guestSessionCartData['cart_item'] !=null){
                    foreach($guestSessionCartData['cart_item'] as $key=>$data){
                        if($data['product_id']==$product->id)
                        {
                            $guestSessionCartData['cart_item'][$key]['qty']=$guestSessionCartData['cart_item'][$key]['qty']+$request->qty;
                            $guestSessionCartData['cart_item'][$key]['sub_total_price']=$guestSessionCartData['cart_item'][$key]['qty']*$original_price;
                        }
                        else
                        {
                            $guestSessionCartData['cart_item']=array_merge($currentCartData,$guestSessionCartData['cart_item']);
                        }
                        break;
                    }
                }else{
                    $guestSessionCartData['cart_item']=array_merge($currentCartData,$guestSessionCartData['cart_item']);
                }
                $guestSessionCartData['total_price']=collect($guestSessionCartData['cart_item'])->sum('sub_total_price');
                $guestSessionCartData['total_qty']=collect($guestSessionCartData['cart_item'])->sum('qty');
                session()->put('guest_cart',$guestSessionCartData);
            }else{
                
                $arrayData=[
                    'id'=>1,
                    'user_id'=> $user->id,
                    'total_price'=> $fixed_price * $qty,
                    'total_qty'=> $qty,
                    'total_discount'=> $discount * $qty,
                    'cart_item'=>$currentCartData
                ];
                session()->put('guest_cart',$arrayData);
            }

            $cart = $this->cart->where('user_id', $user->id)->first();
            // dd($cart);
            // -------------------------Cart Table--------------------------
            if ($cart) {
                $old_cart_asset_data = $this->cart_asset->where('cart_id', $cart->id)->get();
                $total_cart_price = null;
                $total_cart_qty = null;
                $total_cart_discount = null;
                foreach ($old_cart_asset_data as $data) {
                    $total_cart_price = $total_cart_price + $data->sub_total_price;
                    $total_cart_qty = $total_cart_qty + $data->qty;
                    $total_cart_discount = $total_cart_discount + $data->discount;
                }
                $total_cart_price = $total_cart_price + ($fixed_price * $qty);
                $total_cart_qty = $total_cart_qty + $qty;
                $total_cart_discount = $total_cart_discount + ($discount * $qty);
                $cart_data['user_id'] = $user->id;
                $cart_data['total_price'] = $total_cart_price;
                $cart_data['total_qty'] = $total_cart_qty;
                $cart_data['total_discount'] = $total_cart_discount;
                $cart->fill($cart_data);
                $cart->save();
            } else {
                $cart_data['user_id'] = $user->id;
                $cart_data['total_price'] = $fixed_price * $qty;
                $cart_data['total_qty'] = $qty;
                $cart_data['total_discount'] = $discount * $qty;
                $this->cart->fill($cart_data);
                $this->cart->save();
            }
            // -------------------------/Cart Table--------------------------


            // ----------------------------Cart Asset---------------------
            // dd('sumit',$varient_id);
            if ($cart) {
                // $cart_asset = $this->cart_asset->where('cart_id', $cart->id)->where('product_id', $product->id)->where('color', $color)->where('varient_id', $varient_id)->first();
                $cart_asset = $this->cart_asset->where('cart_id', $cart->id)->where('product_id', $product->id)->where('varient_id', $varient_id)->first();
                $id = $cart->id;
            } else {
                // $cart_asset = $this->cart_asset->where('cart_id', $this->cart->id)->where('product_id', $product->id)->where('color', $color)->where('varient_id', $varient_id)->first();
                $cart_asset = $this->cart_asset->where('cart_id', $this->cart->id)->where('product_id', $product->id)->where('varient_id', $varient_id)->first();
                $id = $this->cart->id;
            }
            if ($cart_asset) {
                $old_qty = $cart_asset->qty;
                $cart_asset_data['cart_id'] = $id;
                $cart_asset_data['product_id'] = $product->id;
                $cart_asset_data['product_name'] = $product->name;
                $cart_asset_data['price'] = $original_price;
                $cart_asset_data['qty'] = $qty + $old_qty;
                $cart_asset_data['sub_total_price'] = $fixed_price * $cart_asset_data['qty'];
                $cart_asset_data['color'] = $color;
                $cart_asset_data['discount'] = $discount;
                $cart_asset_data['varient_id'] = $varient_id;
                $cart_asset_data['options'] = json_encode($product_varient);
                $cart_asset->fill($cart_asset_data);
                $cart_asset->save();
            } else {
                $cart_asset_data['cart_id'] = $id;
                $cart_asset_data['product_id'] = $product->id;
                $cart_asset_data['product_name'] = $product->name;
                $cart_asset_data['price'] = $original_price;
                $cart_asset_data['qty'] = $qty;
                $cart_asset_data['sub_total_price'] = $fixed_price * $qty;
                $cart_asset_data['color'] = $color;
                $cart_asset_data['discount'] = $discount;
                $cart_asset_data['varient_id'] = $varient_id;
                $cart_asset_data['options'] = json_encode($product_varient);
                $cart_asset_data['vatamountfield'] = round($vatAmount);
                $this->cart_asset->fill($cart_asset_data);
                $this->cart_asset->save();
            }

            // ----------------------------/Cart Asset---------------------

            if ($cart) {
                $old_cart_asset_data = $this->cart_asset->where('cart_id', $cart->id)->get();
                $total_cart_price = null;
                $total_cart_qty = null;
                $total_cart_discount = null;
                foreach ($old_cart_asset_data as $data) {
                    $total_cart_price = $total_cart_price + $data->sub_total_price;
                    $total_cart_qty = $total_cart_qty + $data->qty;
                    $total_cart_discount = $total_cart_discount + $data->discount;
                }
                $this->cart->where('id', $cart->id)->update([
                    'user_id' => $user->id,
                    'total_price' => $total_cart_price,
                    'total_qty' => $total_cart_qty,
                    'total_discount' => $total_cart_discount
                ]);
            }
            $total_qty = $this->cart->where('user_id', $user->id)->first();
            $response = [
                'error' => false,
                'data' => $total_qty->total_qty,
                'msg' => 'Items Added Successfully !'
            ];
            DB::commit();
            $cart = $this->cart->where('user_id', $user->id)->first();
            return $response=[
                'error'=>false,
                'cartHtml'=>view('frontend.guestcartupdate')->with('success', 'Items Added Successfully !!')->render(),
                'total_qty'=>request()->session()->get('guest_cart')['total_qty'] ?? 0
            ];
        } catch (\Exception $ex) {
            DB::rollBack();
            $response = [
                'error' => true,
                'data' => null,
                'msg' => $ex->getMessage()
            ];
            return response()->json($response, 200);
        }
    }

    public function directGuestDeleteToCart(Request $request){
        try{
            $guestSessionCartData=session()->get('guest_cart');
            foreach($guestSessionCartData['cart_item'] as $key=>$data){
                
                if($data['product_id']==$request->itemId)
                {
                    unset($guestSessionCartData['cart_item'][$key]);
                }
                
            }
            $guestSessionCartData['total_price']=collect($guestSessionCartData['cart_item'])->sum('sub_total_price');
            $guestSessionCartData['total_qty']=collect($guestSessionCartData['cart_item'])->sum('qty');
            session()->put('guest_cart',$guestSessionCartData);
            return $response=[
                'error'=>false,
                'cartHtml'=>view('frontend.guestcartupdate')->with('success', 'Items Added Successfully !!')->render(),
                'total_qty'=>request()->session()->get('guest_cart')['total_qty'] ?? 0,
                'msg'=>'Item Deleted Successfully !!'
            ];
        }catch(\Throwable $ex){
            $response = [
                'error' => true,
                'data' => null,
                'msg' => $ex->getMessage()
            ];
            return response()->json($response, 200);
        }
    }

    public function guestAllCheckout(Request $request){
        $guestCartItem=session()->get('guest_cart');
        if(!$guestCartItem || $guestCartItem['cart_item']==null){
            $request->session()->flash('error','Something Went Wrong !!');
            return redirect()->back();
        }
        // $wholeSellecustomerShippingChargePerKg = Setting::where('key', 'shippping_charge_per_kg')->first()->value;
        $shipping_chargeNew=0;
        $packetWeight=0;
        foreach($guestCartItem['cart_item'] as $item) {
            $product=Product::where('id',$item['product_id'])->first();
            $shipping_chargeNew=$shipping_chargeNew+($product->shipping_charge * $item['qty']);
            $packetWeight = $packetWeight+$product->package_weight;
        }
        // $default_shipping_charge = ((int)$wholeSellecustomerShippingChargePerKg * (int)$packetWeight) ?? 0;
        $default_shipping_charge = $shipping_chargeNew ?? 0;

        $guestCartItem['shipping_charge']=$default_shipping_charge ?? 0;
        session()->put('guest_cart',$guestCartItem);
        return view('frontend.cart.bulckProductCheckout');
    }
}
