<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use App\Models\Setting;

use Illuminate\Http\Request;
use App\Models\{
    Product,
    User,
    Cart,
    CartAssets,
    Coupon,
    ProductStock,
    UserPaymentId,
    Color,
    OrderTimeSetting,
    ProductImage,
    
};
use App\Models\Admin\VatTax;
use App\Models\Admin\Coupon\Customer\CustomerCoupon;
use Str;
use Illuminate\Support\Facades\DB;
use App\Models\CustomerAllUsedCoupon;

class CartController extends Controller
{
    protected $product = null;
    protected $user = null;
    protected $cart = null;
    protected $cart_asset = null;
    protected $user_payment_id = null;

    public function __construct(Product $product, User $user, Cart $cart, CartAssets $cart_asset, UserPaymentId $user_payment_id)
    {
        $this->product = $product;
        $this->user = $user;
        $this->cart = $cart;
        $this->cart_asset = $cart_asset;
        $this->user_payment_id = $user_payment_id;
    }

    public function addToCart(Request $request)
    {     
        $today = Carbon::now()->format('l');
        $order_timing_check = OrderTimeSetting::where('day', $today)->exists();

        if($order_timing_check)
        {
            $order_timing = OrderTimeSetting::where('day', $today)->first();
            if($order_timing->day_off == 1)
            {
                return response()->json(['error'=>true,'status'=>500, 'msg'=>'You cannot place order today because it is our off day']);
            }
            $currentTime = Carbon::now()->format('H:i');
            if($currentTime > $order_timing->end_time || $currentTime < $order_timing->start_time){
                return response()->json(['error'=>true,'status'=>500, 'msg'=>'Please place your order between ' . Carbon::parse($order_timing->start_time)->format('h:i A') . ' and ' . Carbon::parse($order_timing->end_time)->format('h:i A')]);
            }
        }         
            
        $user = \Auth::user();
        $wholeSellerStatus=false;
        if($user->wholeseller=='1'){
            $wholeSellerStatus=true;
        }
        if (!$user) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Unauthorized User !!'
            ];
            return response()->json($response, 500);
        }
        $validate = Validator::make($request->all(), [
            'product_id' => 'required|int|exists:products,id',
            'qty' => 'required|int',
            'varient_id' => 'required|int|exists:product_stocks,id'
        ]);
        if ($validate->fails()) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => $validate->errors()
            ];
            return response()->json($response, 500);
        }
        DB::beginTransaction();
        try {
           
            $product = Product::where('id', $request->product_id)->first();
            $product_stock = ProductStock::where('product_id', $product->id)->where('id',$request->varient_id)->first();
            if($wholeSellerStatus){
                $product_stock->price=$product_stock->wholesaleprice;
            }
            $cart=Cart::where('user_id',$user->id)->first();
          
            if($cart)
            {
                $already_product=CartAssets::where('varient_id',$request->varient_id)->where('product_id',$request->product_id)->where('cart_id',$cart->id)->first();
            }
            else
            {
                $already_product=null;
            }
           
            if($already_product)
            {
                if( ((int)$already_product->qty+(int)$request->qty) > (int)$product_stock->quantity)
                {
                    $response=[
                        'error'=>true,
                        'data'=>null,
                        'msg'=>'Sorry !! Product Is Outof Stock'
                    ];
                    return response()->json($response,200);
                }
            }
            if((int)$product_stock->quantity <=0)
            {
                $response=[
                    'error'=>true,
                    'data'=>null,
                    'msg'=>'Sorry !! Product Is Outof Stock'
                ];
                return response()->json($response,200);
            }
            
            
            $color_image=ProductImage::where('product_id',$product_stock->product_id)->first();
            if($color_image->image !=null)
            {
                $product_image=$color_image->image;
            }
            else
            {
                $product_image=null;
            }
            
            $price = $product_stock->price;
            $color = $product_stock->color_id;
            $special_price = $product_stock->special_price;
            $offer_price = getOfferProduct($product, $product_stock);
            
            $additional_charge = $product_stock->additional_charge;
            $qty = (int)$request->qty;
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
            
            if($product->getStock)
            {
                foreach ($product_stock->getStock as $key => $stock) {
                    $product_varient[$key]['id'] = $stock->getOption->id;
                    $product_varient[$key]['title'] = $stock->getOption->title;
                    $product_varient[$key]['value'] = $stock->value;
    
                }
            }

            $vatAmount=0;
            $fixed_price=0;
            if($product->vat_percent==0)
            {   
                $vatTax=VatTax::first();
                if($vatTax){
                    $vatPercent=(int)$vatTax->vat_percent;
                    $vatAmount=(($original_price*$qty)*$vatPercent)/100;
                    $fixed_price=round(($original_price*$qty)+round($vatAmount));
                }
            }
            else
            {
                $fixed_price=round($original_price *$qty);
                $vatAmount=0;
            }
           
            $cart = $this->cart->where('user_id', $user->id)->first();
            // -------------------------Cart Table--------------------------
            if ($cart) {
                
                $old_cart_asset_data = $this->cart_asset->where('cart_id', $cart->id)->get();
                $total_cart_price = null;
                $total_cart_qty = null;
                $total_cart_discount = null;
                $total_additional_charge = null;
                foreach ($old_cart_asset_data as $data) {
                    
                    $total_cart_price = $total_cart_price + $data->sub_total_price;
                    $total_cart_qty = $total_cart_qty + $data->qty;
                    $total_cart_discount = $total_cart_discount + $data->discount;
                    $total_additional_charge = $total_additional_charge + $data->additional_charge;
                }
                $total_cart_price = $total_cart_price + $fixed_price + $additional_charge;
                $total_cart_qty = $total_cart_qty + $qty;
                $total_cart_discount = $total_cart_discount + ($discount * $qty);
                $cart_data['user_id'] = $user->id;
                $cart_data['total_price'] = $total_cart_price;
                $cart_data['total_qty'] = $total_cart_qty;
                $cart_data['total_discount'] = $total_cart_discount;
                //  dd($cart_data);   
                $cart_data['additional_charge'] = $total_additional_charge;
                $cart->fill($cart_data);
                $cart->save();
            } else {
                   
                $cart_data['user_id'] = $user->id;
                // $cart_data['total_price'] = $original_price * $qty;
                $cart_data['total_price'] = $fixed_price;
                $cart_data['total_qty'] = $qty;
                $cart_data['total_discount'] = $discount * $qty;
                $cart_data['additional_charge'] = $additional_charge;

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
            $mainPrice=$fixed_price/$qty;
            $request['key']=$product_stock->stockWays;
            $options  = [];
            if ($request->has('key')) {
                if (count($request->key) > 0) {
                    foreach ($request->key as $index => $k) {
                        $options[] = [
                            'id' => $k->key,
                            'title' => $k->categoryAttribute->title,
                            'value' => $k->value ?? null,
                        ];
                    }
                }
                $options=json_encode($options);
            }
            
            if ($cart_asset) {
                
                $old_qty = $cart_asset->qty;
                $cart_asset_data['cart_id'] = $id;
                $cart_asset_data['product_id'] = $product->id;
                $cart_asset_data['product_name'] = $product->name;
                $cart_asset_data['price'] = $original_price;
                $cart_asset_data['qty'] = $qty + $old_qty;
                $cart_asset_data['sub_total_price'] = $mainPrice* $cart_asset_data['qty'] ;
                $cart_asset_data['color'] = $color;
                $cart_asset_data['image'] = $product_image;
                $cart_asset_data['discount'] = $discount;
                $cart_asset_data['additional_charge'] = $additional_charge;
                $cart_asset_data['varient_id'] = $request->varient_id;
                $cart_asset_data['options'] = $options;
                $cart_asset_data['vatamountfield'] = round($vatAmount)*$cart_asset_data['qty'];
                $cart_asset->fill($cart_asset_data);
                $cart_asset->save();
            } else {
                   
                $cart_asset_data['cart_id'] = $id;
                $cart_asset_data['product_id'] = $product->id;
                $cart_asset_data['product_name'] = $product->name;
                $cart_asset_data['price'] = $original_price;
                $cart_asset_data['qty'] = $qty;

                $cart_asset_data['sub_total_price'] = $fixed_price;
                $cart_asset_data['color'] = $color;
                $cart_asset_data['image'] = $product_image;
                $cart_asset_data['discount'] = $discount;

                $cart_asset_data['additional_charge'] = $additional_charge;
                $cart_asset_data['varient_id'] = $request->varient_id;

                $cart_asset_data['options'] = $options;
                

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
                $total_additional_charge = null;
                foreach ($old_cart_asset_data as $data) {
                    $total_cart_price = $total_cart_price + $data->sub_total_price;
                    $total_cart_qty = $total_cart_qty + $data->qty;
                    $total_cart_discount = $total_cart_discount + ($data->discount*$data->qty);
                    $total_additional_charge = $additional_charge;
                }
        $default_material_charge = Setting::where('key','materials_price')->pluck('value')->first();

                $this->cart->where('id', $cart->id)->update([
                    'user_id' => $user->id,
                    'total_price' => $total_cart_price,
                    'total_qty' => $total_cart_qty,
                    'total_discount' => $total_cart_discount,
                    'additional_charge' => $total_additional_charge,
                ]);
            }

            $response = [
                'error' => false,
                'data' => $this->cart->where('user_id', $user->id)->first(),
                'msg' => 'Items Added Successfully !',
            ];
            DB::commit();
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
    
    public function cartItem(Request $request)
    {
        $this->user = \Auth::user();
        if (!$this->user) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Sorry !! Unauthorized User !'
            ];
            return response()->json($response, 500);
        }
        $wholeSellerStatus=false;
        if($this->user->wholeseller =='1'){
            $wholeSellerStatus=true;
        }
        $total_qty = null;
        $total_amount = null;
        $vatAmount=0;
        $user_cart = $this->cart->where('user_id', $this->user->id)->get();
        $getCart=$this->cart->where('user_id', $this->user->id)->first();
        if($getCart !=null)
        {
            $cartAssetItem=$getCart->cartAssets;
            $vatAmount=collect($cartAssetItem)->sum('vatamountfield');
        }
       
        if ($user_cart->count() <= 0) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'No Item In The Cart !'
            ];
            return response()->json($response, 500);
        }


        foreach ($user_cart as $data) {
            $total_qty = $total_qty + $data->total_qty;
            $total_amount = $total_amount + $data->total_price;
        }
        $new = $this->cart->where('user_id', $this->user->id)->with('cartAssets.productImage')->get();
        

        foreach ($new as $key => $n) {
            $attribute = json_decode($n->cartAssets[$key]->options);
            $n->cartAssets[$key]->makeHidden([
                'created_at',
                'updated_at'
            ]);
            $n->cartAssets[$key]->setAttribute('varient', $attribute);
          
        }

        $new=collect($new)->each(function($item)
        {
           
            return collect($item->cartAssets)->map(function($item2)
            {
                $item2->slug=$item2->product->slug;
                return $item2;
            });
        });
        $default_material_charge = Setting::where('key','materials_price')->pluck('value')->first();
        $proceed_status=true;
        $wholeSellerMinimumAmount=0;
        if($wholeSellerStatus){
            $setting=Setting::where('key','minimum_order_price')->first();
            $wholeSellerMinimumAmount=$setting->value ?? 0;
            if($total_amount < $wholeSellerMinimumAmount){
                $proceed_status=false;
            }
        }

        $response = [
            'error' => false,
            'data' => $new,
            'total_amount' => $total_amount-$vatAmount,
            'material_price' => $default_material_charge,
            'total_qty' => $total_qty,
            'vat'=>$vatAmount,
            'grand_total' => $total_amount,
            'proceed_status' => $proceed_status,
            'whole_seller_min_price'=>$wholeSellerMinimumAmount ?? 0,
            'msg' => 'Cart Data Details'
        ];
        return response()->json($response, 200);
    }

    public function removeFromCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'error' => true,
                'data' => null,
                'message' => $validator->errors()
            ];
            return response()->json($response, 500);
        }
        $user = \Auth::user();
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
            $product = $this->cart_asset->where('cart_id', $this->cart->id)->where('id', $request->id)->first();
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
            return response()->json($response, 200);
        } catch (\Exception $ex) {
            DB::rollBack();
            $response = [
                'error' => true,
                'msg' => $ex->getMessage()
            ];
            return response()->json($response, 200);
        }
    }
    
    public function updateFromCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'qty' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'error' => true,
                'data' => null,
                'message' => $validator->errors()
            ];
            return response()->json($response, 500);
        }


        $user = \Auth::user();
        $wholeSellerStatus=false;
        if($user->wholeseller =='1'){
            $wholeSellerStatus=true;
        }
        if (!$user) {
            $response = [
                'error' => true,
                'data' => null,
                'message' => 'Sorry ! User Not Found !!'
            ];
            return response()->json($response, 500);
        }


        $qty = (int)($request->qty);
        if ($qty <= 0) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Sorry !! Qty Must Me Equal Or Greater Than 1 !'
            ];
            return response()->json($response, 500);
        }
        DB::beginTransaction();
        try {
            $this->cart = $this->cart->where('user_id', $user->id)->first();
            if (!$this->cart) {
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => 'Sorry !! No Cart Found'
                ];
                return response()->json($response, 500);
            }
            $this->cart_asset = $this->cart_asset->where('cart_id', $this->cart->id)->where('id', $request->id)->first();
            if(!$this->cart_asset)
            {
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => 'Sorry !! Something Went Wrong'
                ];
                return response()->json($response, 200);
            }
            $product_stock=ProductStock::where('id',$this->cart_asset->varient_id)->first();
            // return $product_stock;
           
            // if(((int)$this->cart_asset->qty+(int)$request->qty) > $product_stock->quantity)
            // {
            //     $response = [
            //         'error' => true,
            //         'data' => null,
            //         'msg' => 'Sorry !! Product Is Out Of Stocks'
            //     ];
            //     return response()->json($response, 200);
            // }
             if(((int)$request->qty) > $product_stock->quantity)
            {
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => 'Sorry !! Product Is Out Of Stocks'
                ];
                return response()->json($response, 200);
            }
            if (!$this->cart_asset) {
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => 'Sorry !! No Item Found For This Request'
                ];
                return response()->json($response, 500);
            }

            $fixed_price=($this->cart_asset->price+$this->cart_asset->vatamountfield) * $qty;
            
            $discount_per_product = ($this->cart_asset->discount);
            // $data['sub_total_price'] = $this->cart_asset->price * $qty;
            $data['sub_total_price'] = $fixed_price;
            $data['qty'] = $qty;
            $data['discount'] = $discount_per_product;

            // dd($data);
            $this->cart_asset->fill($data);
            $status = $this->cart_asset->save();
            if ($status) {
                $response = [
                    'error' => false,
                    'msg' => 'Cart Updated Successfully !'
                ];
                $cart_total = null;
                $cart_qty = null;
                $cart_discount = null;
                foreach ($this->cart->cartAssets  as $cart_data) {
                    $cart_total = $cart_total + $cart_data->sub_total_price;
                    $cart_qty = $cart_qty + $cart_data->qty;
                    $cart_discount = $cart_discount + $cart_data->discount;
                }
                $input['total_price'] = $cart_total;
                $input['total_qty'] = $cart_qty;
                $input['total_discount'] = $cart_discount;
                $this->cart->fill($input);
                $this->cart->save();
                DB::commit();
                $response = [
                    'error' => false,
                    'msg' => 'Cart Updated Successfully !'
                ];
                return response()->json($response, 200);
            }
        } catch (\Exception $ex) {
            DB::rollBack();
            $response = [
                'error' => true,
                'msg' => $ex->getMessage()
            ];
            return response()->json($response, 200);
        }
    }

    public function generaterefId()
    {
        $user = \Auth::user();

        if (!$user) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Unauthorized User !!'
            ];

            return response()->json($response, 500);
        }



        $cart_detail = $this->cart->where('user_id', $user->id)->get();


        if (count($cart_detail) <= 0) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Sorry !! There Is No Items In The Cart Or Cart Is Empty !!'
            ];

            return response()->json($response, 500);
        }



        $bill_id = Str::random(6) . rand(1000, 2000);


        $this->user_payment_id->updateOrCreate(
            [
                'user_id' => $user->id
            ],
            [
                'user_id' => $user->id,
                'payment_bill_id' => $bill_id
            ]
        );


        $response = [
            'error' => false,
            'data' => [
                'bill_id' => $bill_id,
                'cart_detail' => $cart_detail,
            ],
            'msg' => 'Order Generated'
        ];

        return response()->json($response, 200);
    }


    public function verifyCoupon(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'coupon' => 'required',
            'shipping_charge' => 'required|int',
            
        ]);
        if ($validator->fails()) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Please enter your coupon code'
            ];
            return response()->json($response, 500);
        }
        $user = \Auth::user();
        if (!$user) {
            $response = [
                
                'error' => true,
                'data' => null,
                'msg' => 'Invalid User !!'
            ];

            return response()->json($response, 500);
        }
        $cart = Cart::where('user_id', $user->id)->first();
        if (!$cart || $cart == null) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Sorry !! No Item In The Cart !!'
            ];
            return response()->json($response, 200);
        }
    $coupon_data = CustomerCoupon::where('is_expired', '0')->where('code', $request->coupon)->first();
    if(!$coupon_data)
    {
        $response = [
            'error' => true,
            'data' =>null,
            'msg' => 'Sorry !! Coupon Expires '
        ];
        return response()->json($response, 200);
    }
    $main_coupon_data=Coupon::find($coupon_data->coupon_id);
    $current_date=Carbon::now()->format('Y-m-d');
        if($main_coupon_data->from <= $current_date && $main_coupon_data->to >= $current_date)
        {
            if ($coupon_data) {
                if ($coupon_data->is_for_same === 1) {
                    if ($coupon_data->customer_id === $user->id) {
                        $coupon_value = $coupon_data->coupon;
                        if ($coupon_value->is_percentage === 'yes') {
                            $coupon_name=$coupon_value->title;
                            $coupon_code=$coupon_data->code;
                            $discount = $coupon_value->discount;
                            $discount_price = (($cart->total_price+$request->shipping_charge) * $discount / 100);
                        } else {
                            $coupon_name=$coupon_value->title;
                            $coupon_code=$coupon_data->code;
                            $discount = $coupon_value->discount;
                            $discount_price = $discount;
                        }
                    } else {
                        
                        $response = [
                            'error' => true,
                            'data' => null,
                            'msg' => 'Sorry !! You Have No Access To This Coupon !!'
                        ];
                        return response()->json($response, 200);
                    }
                } else {
                    $alreadyCouponExists=CustomerAllUsedCoupon::where('coupon_id',$coupon_data->coupon_id)->where('customer_coupon_id',$coupon_data->id)->where('customer_id',$user->id)->first();
                    
                    if($alreadyCouponExists)
                    {
                       
                        $response = [
                            'error' => true,
                            'data' =>null,
                            'msg' => 'Sorry !! Coupon Expires '
                        ];
                        return response()->json($response, 200);
                    }
                    $coupon_value = $coupon_data->coupon;
        
                    if ($coupon_value->is_percentage === 'yes') {
                        $coupon_name=$coupon_value->title;
                            $coupon_code=$coupon_data->code;
                        $discount = $coupon_value->discount;
                        $discount_price = (($cart->total_price+$request->shipping_charge) * $discount / 100);
                    } else {
                        $coupon_name=$coupon_value->title;
                        $coupon_code=$coupon_data->code;
                        $discount = $coupon_value->discount;
                        $discount_price = $discount;
                    }
                }
            } else { 
               
                $response = [
                    'error' => true,
                    'data' =>null,
                    'msg' => 'Sorry !! Coupon Expires '
                ];
                return response()->json($response, 200);
            }
                $response = [
                    'error' => false,
                    'data' => [
                        'discount_amount' => round($discount_price),
                        'total_cart_amount' => round($cart->total_price),
                        'total_cart_amount_after_discount' => round($cart->total_price - round($discount_price)+round($request->shipping_charge))
                    ],
                    'msg'=>'Send For Verification'
                ];
                return response()->json($response, 200);
        }
        else
        {
            
            $response = [
                'error' => true,
                'data' =>null,
                'msg' => 'Sorry !! Coupon Expires '
            ];
            return response()->json($response, 200);
        }
    
    }

    public function clearCart(Request $request)
    {
        
        DB::beginTransaction();
        try {
            $user = \Auth::user();

            if (!$user) {
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => 'Unauthorize User !!'
                ];

                return response()->json($response, 500);
            }

            $cart = Cart::where('user_id', $user->id)->first();

            if (!$cart || $cart == null) {

                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => 'Sorry No Items In The Cart !!'
                ];

                return response()->json($response, 200);
            }
            $cart->delete();
            DB::commit();
            $response = [
                'error' => false,
                'data' => null,
                'msg' => 'Cart Deleted Successfully !!'
            ];
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
}
