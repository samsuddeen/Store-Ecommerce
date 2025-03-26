<?php

namespace App\Http\Controllers\Datatables;


use App\Models\Cart;
use App\Models\Coupon;
use Illuminate\Http\Request; 
use Illuminate\Support\Carbon;
use App\Models\UserShippingAddress;
use App\Datatables\CouponDatatables;
use App\Http\Controllers\Controller;
use App\Models\Admin\Coupon\Customer\CustomerCoupon;
use App\Models\CustomerAllUsedCoupon;
use App\Models\Setting;

class CouponController extends Controller
{
    private $datatable;
    public function __construct(CouponDatatables $datatables)
    {
        $this->datatable = $datatables;
    }
    /**
     * Display a listing of the resource.
     *P
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return   $this->datatable->getData();
    }

    public function verifyCoupon(Request $request)
    {
        

        if($request->coupon_code===null)
        {
            $request->session()->put('coupon_charge',null);
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Plz Enter Coupon Code Here !! '
            ];
            return response()->json($response, 200);
        }

        if($request->shipping_id===null)
        {
            $request->session()->put('coupon_charge',null);
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Plz Select Shipping Address First !! '
            ];
            return response()->json($response, 200);
        }
        
        $user = auth()->guard('customer')->user();
        if (!$user) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Invalid User !!'
            ];

            return response()->json($response, 200);
        }

        $shipping_address=UserShippingAddress::where('id',$request->shipping_id)->where('user_id',auth()->guard('customer')->user()->id)->first();
        if($shipping_address->getLocation->deliveryRoute->charge){
            $shipping_charge=$shipping_address->getLocation->deliveryRoute->charge;
        }else{
            $shipping_charge = Setting::where('key','default_shipping_charge')->pluck('value')->first();
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
        
        $coupon_data = CustomerCoupon::where('is_expired', '0')->where('code', $request->coupon_code)->first();
        if(!$coupon_data)
        {
            $request->session()->put('coupon_charge',null);
            $response = [
                'error' => true,
                'data' => [
                    'shipping_charge'=>$shipping_address->getLocation->deliveryRoute->charge
                ],
                'msg' => 'Sorry !! Coupon Expires '
            ];
            return response()->json($response, 200);
        }
        $main_coupon_data=Coupon::find($coupon_data->coupon_id);
        if(!$main_coupon_data)
        {
            $request->session()->put('coupon_charge',null);
            $response = [
                'error' => true,
                'data' => [
                    'shipping_charge'=>$shipping_address->getLocation->deliveryRoute->charge
                ],
                'msg' => 'Sorry !! Coupon Expires '
            ];
            return response()->json($response, 200);
        }
        
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
                            $discount_price = (($request->total_amount+$shipping_charge) * $discount / 100);
                            
                        } else {
                            $coupon_name=$coupon_value->title;
                            $coupon_code=$coupon_data->code;
                            $discount = $coupon_value->discount;
                            $discount_price = $discount;
                            
                        }
                    } else {
                        $request->session()->put('coupon_charge',null);
                        $response = [
                            'error' => true,
                            'data' => [
                                'shipping_charge'=>$shipping_address->getLocation->deliveryRoute->charge
                            ],
                            'msg' => 'Sorry !! You Have No Access To This Coupon !!'
                        ];
                        return response()->json($response, 200);
                    }
                } else {
                    $alreadyCouponExists=CustomerAllUsedCoupon::where('coupon_id',$coupon_data->coupon_id)->where('customer_coupon_id',$coupon_data->id)->where('customer_id',$user->id)->first();
                    
                    if($alreadyCouponExists)
                    {
                        $request->session()->put('coupon_charge',null);
                        $response = [
                            'error' => true,
                            'data' => [
                                'shipping_charge'=>$shipping_address->getLocation->deliveryRoute->charge
                            ],
                            'msg' => 'Sorry !! Coupon Expires '
                        ];
                        return response()->json($response, 200);
                    }
                    $coupon_value = $coupon_data->coupon;
                    if ($coupon_value->is_percentage === 'yes') {
                        $coupon_name=$coupon_value->title;
                            $coupon_code=$coupon_data->code;
                        $discount = $coupon_value->discount;
                        $discount_price = (($request->total_amount+$shipping_charge) * $discount / 100);
                    } else {
                        $coupon_name=$coupon_value->title;
                        $coupon_code=$coupon_data->code;
                        $discount = $coupon_value->discount;
                        $discount_price = $discount;
                    }
                }
            } else { 
                $request->session()->put('coupon_charge',null);
                $response = [
                    'error' => true,
                    'data' => [
                        'shipping_charge'=>$shipping_charge
                    ],
                    'msg' => 'Sorry !! Coupon Expires '
                ];
                return response()->json($response, 200);
            }
            $discount_price=round($discount_price);
            $request->session()->put('coupon_charge',$discount_price);
            
            
            $response = [
                'error' => false,
                'data' => [
                    'coupon_name'=>$coupon_name,
                    'coupon_code_name'=>$coupon_code,
                    'discount_amount' => $discount_price,
                    'total_cart_amount' => $request->total_amount,
                    'total_cart_amount_after_discount' => $request->total_amount - $discount_price,
                    'shipping_charge'=>$shipping_charge
                ]
            ];
            return response()->json($response, 200);
        }
        else
        {
            $request->session()->put('coupon_charge',null);
            $response = [
                'error' => true,
                'data' => [
                    'shipping_charge'=>$shipping_charge
                ],
                'msg' => 'Sorry !! Coupon Expires '
            ];
            return response()->json($response, 200);
        }
       
        
    }

    public function verifyDirectCoupon(Request $request)
    {
        
        if($request->coupon_code===null)
        {
            $request->session()->put('coupon_charge',null);
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Plz Enter Coupon Code Here !! '
            ];
            return response()->json($response, 200);
        }

        if($request->shipping_id===null)
        {
            $request->session()->put('coupon_charge',null);
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Plz Select Shipping Address First !! '
            ];
            return response()->json($response, 200);
        }
        
        $user = auth()->guard('customer')->user();
        if (!$user) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Invalid User !!'
            ];

            return response()->json($response, 200);
        }

        $shipping_address=UserShippingAddress::where('id',$request->shipping_id)->where('user_id',auth()->guard('customer')->user()->id)->first();
        if($shipping_address && $shipping_address->getLocation)
        {
            $shipping_charge=$shipping_address->getLocation->deliveryRoute->charge;
        }
        else
        {
            $shipping_charge=100;
        }
        // dd($shipping_charge);
        $coupon_data = CustomerCoupon::where('is_expired', '0')->where('code', $request->coupon_code)->first();
        if(!$coupon_data)
        {
            $request->session()->put('coupon_charge',null);
            $response = [
                'error' => true,
                'data' => [
                    'shipping_charge'=>$shipping_charge
                ],
                'msg' => 'Sorry !! Coupon Expires '
            ];
            return response()->json($response, 200);
        }
        $main_coupon_data=Coupon::find($coupon_data->coupon_id);
        if(!$main_coupon_data)
        {
            $request->session()->put('coupon_charge',null);
            $response = [
                'error' => true,
                'data' => [
                    'shipping_charge'=>$shipping_charge
                ],
                'msg' => 'Sorry !! Coupon Expires '
            ];
            return response()->json($response, 200);
        }
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
                            $discount_price = (($request->total_amount+$shipping_charge) * $discount / 100);
                        } else {
                            $coupon_name=$coupon_value->title;
                            $coupon_code=$coupon_data->code;
                            $discount = $coupon_value->discount;
                            $discount_price = $discount;
                        }
                    } else {
                        $request->session()->put('coupon_charge',null);
                        $response = [
                            'error' => true,
                            'data' => [
                                'shipping_charge'=>$shipping_charge
                            ],
                            'msg' => 'Sorry !! You Have No Access To This Coupon !!'
                        ];
                        return response()->json($response, 200);
                    }
                } else {
                    $alreadyCouponExists=CustomerAllUsedCoupon::where('coupon_id',$coupon_data->coupon_id)->where('customer_coupon_id',$coupon_data->id)->where('customer_id',$user->id)->first();
                    
                    if($alreadyCouponExists)
                    {
                        $request->session()->put('coupon_charge',null);
                        $response = [
                            'error' => true,
                            'data' => [
                                'shipping_charge'=>$shipping_charge
                            ],
                            'msg' => 'Sorry !! Coupon Expires '
                        ];
                        return response()->json($response, 200);
                    }
                    $coupon_value = $coupon_data->coupon;
                    if ($coupon_value->is_percentage === 'yes') {
                        $coupon_name=$coupon_value->title;
                            $coupon_code=$coupon_data->code;
                        $discount = $coupon_value->discount;
                        $discount_price = (($request->total_amount+$shipping_charge) * $discount / 100);
                    } else {
                        $coupon_name=$coupon_value->title;
                        $coupon_code=$coupon_data->code;
                        $discount = $coupon_value->discount;
                        $discount_price = $discount;
                    }
                }
            } else { 
                $request->session()->put('coupon_charge',null);
                $response = [
                    'error' => true,
                    'data' => [
                        'shipping_charge'=>$shipping_charge
                    ],
                    'msg' => 'Sorry !! Coupon Expires '
                ];
                return response()->json($response, 200);
            }
            $discount_price=round($discount_price);
            $request->session()->put('coupon_charge',$discount_price);
            $response = [
                'error' => false,
                'data' => [
                    'coupon_name'=>$coupon_name,
                    'coupon_code_name'=>$coupon_code,
                    'discount_amount' => $discount_price,
                    'total_cart_amount' => $request->total_amount,
                    'total_cart_amount_after_discount' => $request->total_amount - $discount_price,
                    'shipping_charge'=>$shipping_charge
                ]
            ];
            return response()->json($response, 200);
        }
        else
        {
            $request->session()->put('coupon_charge',null);
            $response = [
                'error' => true,
                'data' => [
                    'shipping_charge'=>$shipping_charge
                ],
                'msg' => 'Sorry !! Coupon Expires '
            ];
            return response()->json($response, 200);
        }
       
    }
}
