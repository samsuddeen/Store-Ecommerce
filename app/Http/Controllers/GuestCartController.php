<?php


namespace App\Http\Controllers;

use Error;
use App\Models\Local;
use App\Models\Product;
use Illuminate\Support\Str;
use App\Models\ProductImage;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use App\Models\DeliveryRoute;
use App\Models\Location;

use function GuzzleHttp\Promise\all;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class GuestCartController extends Controller
{
    public function Cart(Request $request)
    {

        $product = Product::whereId($request->product_id)->first();
        $product_stock = ProductStock::where('id', $request->varient_id)->where('product_id', $product->id)->where('color_id', $request->color)->where('id', $request->varient_id)->first();
        $offer_price = getOfferProduct($product, $product_stock);
        $product_image = ProductImage::where('product_id', $request->product_id)->where('color_id', $request->color)->first()->image ?? null;
        
        $varient_id = $request->varient_id;
        $stock_id = ProductStock::where('id', $varient_id)->where('product_id', $request->product_id)->where('color_id', $request->color)->first();
        if ((int)$stock_id->quantity <= 0 || $request->qty > (int)$stock_id->quantity) {
            $request->session()->flash('error', 'Sorry !! Product Is Outof Stocks');
            return redirect()->back();
        }
        $stock_ways = $product_stock->stockWays;

        $option = [];
        foreach ($stock_ways as $key => $data) {
            $option[$key]['id'] = $data->category->id;
            $option[$key]['title'] = $data->category->title;
            $option[$key]['value'] = $data->value;
        }
        $options = json_encode($option);

        if ($offer_price != null) {
            $product_amount = $offer_price;
            $product_discount = $product_stock->price - $offer_price;
        } elseif ($product_stock->special_price != null) {
            $product_amount = $product_stock->special_price;
            $product_discount = $product_stock->price - $product_stock->special_price;
        } else {
            $product_amount = $product_stock->price;
            $product_discount = 0;
        }

        $current_cart = [
            'cart_id' => Str::random(100) . rand(11111, 99999),
            'product_id' => $request->product_id,
            'product_name' => $product->name,
            'product_slug' => $product->slug,
            'color_id' => $request->color,
            'varient_id' => $product_stock->id,
            'image' => $product_image,
            'options' => $options,
            'qty' => $request->qty,
            'price' => $product_amount,
            'discount' => $product_discount,
            'pdiscount' => $product_discount,
            'pprice' => $product_amount
        ];


        $total_amount = null;
        $total_qty = null;

        $cart = session('guest_cart') ?? [];

        if ($cart) {

            foreach ($cart as $key => $item) {
                $cart_id_array[] = $item['product_id'];
            }

            foreach ($cart as $key => $item) {
                $cart_varient_array[] = $item['varient_id'];
            }

            if (in_array($current_cart['product_id'], $cart_id_array) && in_array($current_cart['varient_id'], $cart_varient_array)) {
                foreach ($cart as $key => $data) {
                    if ($data['product_id'] == $current_cart['product_id'] && $data['varient_id'] == $current_cart['varient_id']) {
                        $cart[$key]['product_id'] = $current_cart['product_id'];
                        $cart[$key]['product_name'] = $current_cart['product_name'];
                        $cart[$key]['product_slug'] = $current_cart['product_slug'];
                        $cart[$key]['color_id'] = $current_cart['color_id'];
                        $cart[$key]['image'] = $current_cart['image'];
                        $cart[$key]['qty'] = $cart[$key]['qty'] + $current_cart['qty'];
                        $cart[$key]['price'] = $current_cart['price'];
                        $cart[$key]['pdiscount'] = $current_cart['discount'] * $cart[$key]['qty'];
                        $cart[$key]['pprice'] = $current_cart['price'] * $cart[$key]['qty'];
                    }
                }
            } else {
                $cart[] = $current_cart;
            }
        } else {
            $cart[] = $current_cart;
        }

        foreach ($cart as $item) {
            $total_amount = $total_amount + $item['pprice'];
            $total_qty = $total_qty + $item['qty'];
        }

        session()->put('guest_cart', $cart);
        session()->put('total_cart_amount', $total_amount);
        session()->put('total_cart_qty', $total_qty);
        $request->session()->flash('success', 'Successfully added to Cart.');
        return back();
    }




    public function CartFromCategory(Request $request)
    {

        $product = Product::whereId($request->product_id)->first();
        $product_stock = ProductStock::where('product_id', $product->id)->first();
        $offer_price = getOfferProduct($product, $product_stock);
        $product_image = ProductImage::where('product_id', $product->id)->first()->image;

        $stock_ways = $product_stock->stockWays;

        $option = [];
        foreach ($stock_ways as $key => $data) {
            $option[$key]['id'] = $data->category->id;
            $option[$key]['title'] = $data->category->title;
            $option[$key]['value'] = $data->value;
        }
        $options = json_encode($option);

        if ($offer_price != null) {
            $product_amount = $offer_price;
            $product_discount = $product_stock->price - $offer_price;
        } elseif ($product_stock->special_price != null) {
            $product_amount = $product_stock->special_price;
            $product_discount = $product_stock->price - $product_stock->special_price;
        } else {
            $product_amount = $product_stock->price;
            $product_discount = 0;
        }

        $current_cart = [
            'cart_id' => Str::random(100) . rand(11111, 99999),
            'product_id' => $request->product_id,
            'product_name' => $product->name,
            'product_slug' => $product->slug,
            'color_id' => $request->color,
            'varient_id' => $product_stock->id,
            'image' => $product_image,
            'options' => $options,
            'qty' => 1,
            'price' => $product_amount,
            'discount' => $product_discount,
            'pdiscount' => $product_discount,
            'pprice' => $product_amount
        ];


        $total_amount = null;
        $total_qty = null;

        $cart = session('guest_cart') ?? [];

        if ($cart) {

            foreach ($cart as $key => $item) {
                $cart_id_array[] = $item['product_id'];
            }

            foreach ($cart as $key => $item) {
                $cart_varient_array[] = $item['varient_id'];
            }

            if (in_array($current_cart['product_id'], $cart_id_array) && in_array($current_cart['varient_id'], $cart_varient_array)) {
                foreach ($cart as $key => $data) {
                    if ($data['product_id'] == $current_cart['product_id'] && $data['varient_id'] == $current_cart['varient_id']) {
                        $cart[$key]['product_id'] = $current_cart['product_id'];
                        $cart[$key]['product_name'] = $current_cart['product_name'];
                        $cart[$key]['product_slug'] = $current_cart['product_slug'];
                        $cart[$key]['color_id'] = $current_cart['color_id'];
                        $cart[$key]['image'] = $current_cart['image'];
                        $cart[$key]['qty'] = $cart[$key]['qty'] + $current_cart['qty'];
                        $cart[$key]['price'] = $current_cart['price'];
                        $cart[$key]['pdiscount'] = $current_cart['discount'] * $cart[$key]['qty'];
                        $cart[$key]['pprice'] = $current_cart['price'] * $cart[$key]['qty'];
                    }
                }
            } else {
                $cart[] = $current_cart;
            }
        } else {
            $cart[] = $current_cart;
        }

        foreach ($cart as $item) {
            $total_amount = $total_amount + $item['pprice'];
            $total_qty = $total_qty + $item['qty'];
        }

        session()->put('guest_cart', $cart);
        session()->put('total_cart_amount', $total_amount);
        session()->put('total_cart_qty', $total_qty);
        return back();
    }

    // public function deleteGuestCartSingleProduct(Request $request)
    // {                
    //     $index = (int)$request->cart_id;
    //     $cart = $request->session()->get('guest_cart');
    //     unset($cart[$index]);
    //     $total_amount=null;
    //     $total_qty=null;
    //     foreach ($cart as $item) {
    //         $total_amount = $total_amount + $item['pprice'];
    //         $total_qty = $total_qty + $item['qty'];
    //     }        
    //     session()->put('guest_cart', $cart);
    //     session()->put('total_cart_amount', $total_amount);
    //     session()->put('total_cart_qty', $total_qty);

    //     $response = [
    //         'error'=> false,
    //         'msg'=> 'succefully deleted',            
    //     ];
    //     // return response()->json($response, 200);
    //     $guest_cart = $request->session()->get('guest_cart');        
    //     $total_cart_amount = $request->session()->get('total_cart_amount');     
    //     return view('frontend.cart.guest_side_cart', compact('guest_cart', 'total_cart_amount'))->with('success', 'Items Added Successfully !!');
    // }


    public function deleteGuestCartSilngle(Request $request, $key)
    {

        $index = (int)$key;
        $cart = $request->session()->get('guest_cart');
        unset($cart[$index]);
        $total_amount = null;
        $total_qty = null;
        foreach ($cart as $item) {
            $total_amount = $total_amount + $item['pprice'];
            $total_qty = $total_qty + $item['qty'];
        }
        try {
            session()->put('guest_cart', $cart);
            session()->put('total_cart_amount', $total_amount);
            session()->put('total_cart_qty', $total_qty);
            $request->session()->flash('success', 'Successfully deleted from Cart.');
            return redirect('/');
        } catch (\Throwable $th) {
            $request->session()->flash('error', 'OOPs, Something is wrong.');
            return redirect('/');
        }
    }



    public function CartDelete(Request $request)
    {
        try {
            $request->session()->forget('guest_cart');
            $request->session()->forget('total_cart_amount');
            $request->session()->forget('total_cart_qty');
            $request->session()->flash('success', 'Successfully deleted from Cart.');
            return back();
        } catch (\Throwable $th) {
            $request->session()->flash('error', 'OOPs, Sometnig is wrong.');
            return back();
        }
    }

    // get Shipping Charge
    public function guestShippingCharge(Request $request)
    {
        $additional_address = DeliveryRoute::where('id', $request->area_id)->first();
        $address = Location::where('id', $request->area_id)->first();
        $shipping_charge  = $additional_address->charge ?? null;
        $zip_code = $address->zip_code;
        $code = '<select name="zip" id="zip" class="form-control" disabled> <option value="'. $zip_code.'">--- ' . $zip_code . ' ---</option> </select>';

        $response = [
            'error' => false,
            'msg' => 'Successfully get Shipping Charge',
            'charge' => $shipping_charge,
            'zip_code_is' => $code,
        ];
        return response()->json($response, 200);
    }

    public function guestShippingChargeForSingleProduct(Request $request)
    {
        $additional_address = DeliveryRoute::where('id', $request->area_id)->first();
        $address = Location::where('id', $request->area_id)->first();
        $charge = $additional_address->charge;
        $zip_code = $address->zip_code;
        $code = '<select name="zip" id="zip" class="form-control" disabled> <option value="'. $zip_code .'">--- ' . $zip_code . ' ---</option> </select>';
        $response = [
            'error' => false,
            'msg' => 'Successfully get Shipping Charge',
            'charge' => $charge,
            'zip_code_is' => $code,
        ];

        return response()->json($response, 200);
    }

    public function storeGuestInfoForSingleCheckout(Request $request)
    {

        $guestInfo = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'email' => ' required',
            'province' => 'required',
            'district' => 'required',
            'area' => 'required',
            'additional_address' => 'required',
            'zip' => 'required',
        ]);

        $input = $request->all();
        if ($guestInfo->fails()) {
            $response = [
                'error' => true,
                'msg' => 'All Field Are Required For Best Order !!'
            ];
            return response()->json($response, 200);
        }

        session()->put('guest_info_forSingleProduct', $input);

        $response = [
            'error' => false,
            'msg' => 'Success !!'
        ];

        return response()->json($response, 200);
    }

    public function storeGuestInfoForAllProduct(Request $request)
    {
        $guestInfo = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'email' => ' required',
            'province' => 'required',
            'district' => 'required',
            'area' => 'required',
            'additional_address' => 'required',
            'zip' => 'required',
        ]);

        $input = $request->all();
        if ($guestInfo->fails()) {
            $response = [
                'error' => true,
                'msg' => 'All Field Are Required For Best Order !!'
            ];
            return response()->json($response, 200);
        }

        session()->put('guest_info_forAllProduct', $input);

        $response = [
            'error' => false,
            'msg' => 'Success !!'
        ];

        return response()->json($response, 200);
    }

    public function getGuestCartItem()
    {
        if (!auth()->guard('customer')->user()) {
            return view('frontend.guest.cartitem');
        }
    }

    public function getGuestCartCount(Request $request)
    {
        $guest_cart_count = $request->session()->get('total_cart_qty');
        $response = [
            'error' => false,
            'guest_cart_count' => $guest_cart_count,
            'msg' => 'success',
        ];

        return response()->json($response, 200);
    }
}
