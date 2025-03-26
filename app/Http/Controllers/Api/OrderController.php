<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\CustomerOrderMail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\{
    Cart,
    Order,
    OrderAsset,
    UserShippingAddress,
    UserBillingAddress,
    Coupon,
    UserPaymentId,
    Local,
    Location,
    OrderTimeSetting,
    Product,
    Setting
};
use App\Models\Admin\Coupon\Customer\CustomerCoupon;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Mail;
use Illuminate\Support\Facades\DB;
use App\Api\Seller\SellerOrderAction;
use App\Helpers\EmailSetUp;
use App\Enum\MessageSetup\MessageSetupEnum;
use App\Models\Admin\Returned\ReturnedStatus;
use App\Http\Requests\CancelReasonRequest;
use App\Models\Order\OrderStatus;
use App\Models\ProductCancelReason;
use App\Models\Customer\ReturnOrder;
use App\Data\Api\ReturnStatusData;
use App\Models\Customer\RefundOrder;
use App\Models\Refund\Refund;
use App\Models\CustomerAllUsedCoupon;
use App\Traits\ApiNotification;
use App\Actions\MobilePdf\MobilePdfGenerate;
class OrderController extends Controller
{
    use ApiNotification;
    protected $cart = null;
    protected $order = null;
    protected $order_asset = null;
    protected $shipping_address = null;
    protected $billing_address = null;
    protected $seller_order_action;

    protected $return_order=null;
    protected $refund_order=null;
    protected $refund=null;

    public function __construct(ReturnOrder $return_order,RefundOrder $refund_order,Refund $refund,Cart $cart, Order $order, OrderAsset $order_asset, UserShippingAddress $shipping_address, UserBillingAddress $billing_address, SellerOrderAction $seller_order_action)
    {
        $this->cart = $cart;
        $this->order = $order;
        $this->order_asset = $order_asset;
        $this->shipping_address = $shipping_address;
        $this->billing_address = $billing_address;
        $this->seller_order_action = $seller_order_action;
        $this->return_order=$return_order;
        $this->refund_order=$refund_order;
        $this->refund=$refund;
    }


    public function orderRejectedreason(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'orderId'=>'required'
        ]);
        if ($validator->fails()) {
            $response = [
                'error' => true,
                'data' => null,
                'message' => $validator->errors()
            ];
            return response()->json($response, 200);
        }
        $reason=ReturnedStatus::where('return_id',$request->orderId)->first();
        $response=[
            'error'=>false,
            'data'=>$reason->remarks ?? null,
        ];
        return response()->json($response,200);
    }
    public function returnOrder(Request $request)
    {
        $user=\Auth::user();
        $returningProducts = ReturnOrder::with('getproduct','getproduct.images')->orderByDesc('created_at')->where('user_id',$user->id)->get();
        $returningProducts=collect($returningProducts)->each(function($item)
        {
            $item->setAttribute('applyRefund',false);
            $status=(new ReturnStatusData($item))->getStatus();
            $item->setAttribute('returnStatus',$status);
            $refundStatus=(new ReturnStatusData($item))->getRefundStatus();
            $item->setAttribute('refundShowStatus',$refundStatus);
            $item->makeHidden('refund_data');
        });
        $response=[
            'error'=>false,
            'data'=>$returningProducts,
            'msg'=>'Return Order Details'
        ];
        return response()->json($response,200);
    }

    public function applyRefund(Request $request,$id)
    {
        
        if($request->return_type=='bank')
        {
            $validator = Validator::make($request->all(), [
                'return_type'=>'required|in:bank',
                'name'=>'required|string',
                'payment_method'=>'required|string',
                'branch'=>'required|string',
                'acc_no'=>'required|string',
                'contact_no'=>'required|string',
                'account_type'=>'required|in:current,saving'
            ]);
    
            if ($validator->fails()) {
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => $validator->errors()
                ];
                return response()->json($response, 200);
            }

        }
        elseif($request->return_type=='esewa' || $request->return_type=='khalti')
        {
            $validator = Validator::make($request->all(), [
                'name'=>'required|in:esewa,khalti',
                'contact_no'=>'required|string',
                'wallet_id'=>'required|string'
            ]);
            if ($validator->fails()) {
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => $validator->errors()
                ];
                return response()->json($response, 200);
            }

        }

        
        $user=\Auth::user();
        if(!$user)
        {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Somethind Went Wrong !!'
            ];
            return response()->json($response, 200);
        }
        $this->return_order=ReturnOrder::where('id',$id)->where('user_id',$user->id)->first();
        
        $data['return_id']=$this->return_order->id;
        $data['user_id']=$user->id;
        $data['is_new']=1;
        $data['status']=1;
        DB::beginTransaction();
        try{
            $this->refund->fill($data);
            $status=$this->refund->save();
            if($status)
            {
                $value=[];
               foreach($request->except(['_token','_method']) as $key=>$data)
               {
                   $value[][$key]=$data;
               }

                $refund_data['refund_id']=$this->refund->id;
                $refund_data['refund_detail']=json_encode($value);
                $this->refund_order->fill($refund_data);
                $this->refund_order->save();
            }                                                                   
            DB::commit();
            $response = [
                'error' => false,
                'msg' => 'Refund Apply Success !!'
            ];
            return response()->json($response, 200);
        }catch(\Throwable $th){
            DB::rollBack();
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Somethind Went Wrong !!'
            ];
            return response()->json($response, 200);
        }
    }

    public function cancelReason(CancelReasonRequest $request)
    {
        // dd($request->all());
        $order=Order::where('id',$request->orderid)->first();
        if(!$order)
        {
            $response=[
                'error'=>true,
                'data'=>null,
                'msg'=>'Something Went Wrong !!'
            ];
            return response()->json($response,200);
        }
        if($order->status==6)
        {

            $orderStatus=OrderStatus::where('order_id',$request->orderid)->where('status',$order->status)->first();
            $data=$orderStatus->remarks ?? null;
            if(!$orderStatus)
            {
                $orderStatus=ProductCancelReason::where('order_id',$request->reasonId)->first();
                $data=($orderStatus->reason ?? null).' <br> '.($orderStatus->additional_reason ?? null);
    
            }
            if($orderStatus)
            {
                $response=[
                    'error'=>false,
                    'data'=>$data,
                ];
                return response()->json($response,200);
            }
            else
            {
                $response=[
                    'error'=>true,
                    'data'=>null
                ];
                return response()->json($response,200);
            }
        }elseif($order->status==7)
        {
            
            $orderStatus=OrderStatus::where('order_id',$request->orderid)->where('status',7)->first();
            
            $data=$orderStatus->remarks ?? null;
            if(!$orderStatus)
            {
                $orderStatus=ProductCancelReason::where('order_id',$request->reasonId)->first();
                $data=($orderStatus->reason ?? null).' <br> '.($orderStatus->additional_reason ?? null);
    
            }
            if($orderStatus)
            {
                $response=[
                    'error'=>false,
                    'data'=>$data,
                ];
                return response()->json($response,200);
            }
            else
            {
                $response=[
                    'error'=>true,
                    'data'=>null
                ];
                return response()->json($response,200);
            }
        }else

        {
            $response=[
                'error'=>true,
                'data'=>null,
                'msg'=>'Something Went Wrong !!'
            ];
            return response()->json($response,200);
        }
        
    }
    public function userOrder()
    {
        $user = \Auth::user();
        $this->order = $this->order->where('user_id', $user->id)->with('orderAssets.product.images')->orderBy('created_at','DESC')->get();
        $status=collect($this->order)->each(function($item)
        {
           
            $item->setAttribute('pdf_url',asset('mobilepdf/'.$item->id.'.pdf') ?? '');
            $item->setAttribute('cancelReasonStatus',false);
            $item->setAttribute('refundStatus',false);
            $item->setAttribute('refundStatusValue',null);
            $item->setAttribute('refundRejectReason',null);
            if($item->is_new==0 && $item->status==1)
            {
                $item->setAttribute('status_value','PENDING');
                $item->setAttribute('cancelStatus',true);
                switch($item->status)
                {
                    case 1:
                       
                        $item->setAttribute('cancelStatus',true);
                        break;
                    case 2:
                        
                        $item->setAttribute('cancelStatus',true);
                        break;
                    case 3:
                       
                        $item->setAttribute('cancelStatus',true);
                        break;
                    case 4:
                       
                        $item->setAttribute('cancelStatus',true);
                        break;
                    case 5:
                        
                        $item->setAttribute('cancelStatus',false);
                        break;
                    case 6:
                        
                        $item->setAttribute('cancelStatus',false);
                        $item->setAttribute('cancelReasonStatus',true);
                        break;
                    case 7:
                      
                        $item->setAttribute('cancelStatus',false);
                        $item->setAttribute('cancelReasonStatus',true);
                        break;
                    default:
                    
                    $item->setAttribute('cancelStatus',false);
                    break;

                }
            }
            elseif($item->status==6 || $item->status==7)
            {
                switch($item->status)
                {
                    case 1:
                        $item->setAttribute('status_value','SEEN');
                        $item->setAttribute('cancelStatus',true);
                        break;
                    case 2:
                        $item->setAttribute('status_value','READY TO SHIP');
                        $item->setAttribute('cancelStatus',true);
                        break;
                    case 3:
                        $item->setAttribute('status_value','DISPATCHED');
                        $item->setAttribute('cancelStatus',true);
                        break;
                    case 4:
                        $item->setAttribute('status_value','SHPIED');
                        $item->setAttribute('cancelStatus',true);
                        break;
                    case 5:
                        $item->setAttribute('status_value','DELIVERED');
                        $item->setAttribute('cancelStatus',false);
                        break;
                    case 6:
                        $item->setAttribute('status_value','CANCELED');
                        $item->setAttribute('cancelStatus',false);
                        $item->setAttribute('cancelReasonStatus',true);
                        
                        break;
                    case 7:
                        $item->setAttribute('status_value','REJECTED');
                        $item->setAttribute('cancelStatus',false);
                        $item->setAttribute('cancelReasonStatus',true);
                        break;
                    default:
                    $item->setAttribute('status_value','NOT DEFINE');
                    $item->setAttribute('cancelStatus',false);
                    break;

                }
            }
            else
            {
                switch($item->status)
                {
                    case 1:
                        $item->setAttribute('status_value','SEEN');
                        $item->setAttribute('cancelStatus',true);
                        break;
                    case 2:
                        $item->setAttribute('status_value','READY TO SHIP');
                        $item->setAttribute('cancelStatus',true);
                        break;
                    case 3:
                        $item->setAttribute('status_value','DISPATCHED');
                        $item->setAttribute('cancelStatus',true);
                        break;
                    case 4:
                        $item->setAttribute('status_value','SHPIED');
                        $item->setAttribute('cancelStatus',true);
                        break;
                    case 5:
                        $item->setAttribute('status_value','DELIVERED');
                        $item->setAttribute('cancelStatus',false);
                        break;
                    case 6:
                        $item->setAttribute('status_value','CANCELED');
                        $item->setAttribute('cancelStatus',false);
                        $item->setAttribute('cancelReasonStatus',true);
                        
                        break;
                    case 7:
                        $item->setAttribute('status_value','REJECTED');
                        $item->setAttribute('cancelStatus',false);
                        $item->setAttribute('cancelReasonStatus',true);
                        break;
                    default:
                    $item->setAttribute('status_value','NOT DEFINE');
                    $item->setAttribute('cancelStatus',false);
                    break;

                }
            }
            
            if($item->payment_status==1 && $item->directrefund ==null && ($item->status==6 || $item->status==7))
            {
                $item->setAttribute('refundStatus',true);
                
            }

            if($item->directrefund !=null)
            {
                $item->setAttribute('refundStatusValue',($item->directrefund->status==1 ? 'PENDING':($item->directrefund->status==2 ? 'PAID':'REJECTED')));
                if($item->directrefund->status==3)
                {
                    $item->setAttribute('refundRejectReason',$item->directrefund->remarks);
                }

            }


        });

        if (!$this->order || $this->order->count() <= 0) {
            $response = [
                'error' => false,
                'data' => null,
                'msg' => 'Sorry !! No Items In The Order List'
            ];
            return response()->json($response, 200);
        }
       

        $response = [
            'error' => false,
            'data' => $this->order,
            'msg' => 'User Order Details'
        ];

        return response()->json($response, 200);
    }


    public function  esewaPayment(Request $request)
    {   
        $today = Carbon::now()->format('l');
        $order_timing_check = OrderTimeSetting::where('day', $today)->exists();
        if($order_timing_check)
        {   
            $validator = Validator::make($request->all(), [
                'productId' => 'required',
                'totalAmount' => 'required',
                'status' => 'required',
                'shipping_address' => 'required|exists:user_shipping_addresses,id',
                'billing_address' => 'nullable|exists:user_billing_addresses,id',
                'same' => 'nullable|in:0,1',
                'coupon_code' => 'nullable|string',
                'coupon_discount_amount' => 'nullable'
            ]);
            if ($validator->fails()) {
                $response = [
                    'error' => true,
                    'data' => null,
                    'message' => $validator->errors()
                ];
                return response()->json($response, 200);
            }
            $user = \Auth::user();
            if (!$user) {
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => 'UnAuthorized User !!'
                ];
    
                return response()->json($response, 200);
            }
    
    
            if (!$request->productId) {
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => 'Bill Id Required'
                ];
    
                return response()->json($response, 200);
            }
            $user_payment_id = UserPaymentId::where('user_id', $user->id)->where('payment_bill_id', $request->productId)->first();
            if (!$user_payment_id || $user_payment_id == null) {
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => 'Bill Id Doesnot Match'
                ];
    
                return response()->json($response, 200);
            }
    
    
    
            $this->cart = $this->cart->where('user_id', $user->id)->with('cartAssets')->first();
            if (!$this->cart) {
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => 'Sorry !! No Item In The Cart !!'
                ];
                return response()->json($response, 200);
            }

            // May need to shift the code down after selecting the shipping and billing address

            $order_timing = OrderTimeSetting::where('day',$today)->first();
            if($order_timing->day_off == true)
            {   
                return response()->json(['status'=>200, 'message'=>'You cannot place order today beacause it is our off day.']);
            }
            $currentTime = Carbon::now()->format('H:i');
            if($currentTime > $order_timing->end_time || $currentTime < $order_timing->start_time){
                return response()->json(['status'=>200, 'message'=>'Please place your order between ' . $order_timing->start_time . ' and ' .$order_timing->end_time]);
            }
    
            if ($request->coupon_code != null || $request->coupon_code != '') {
                $coupon_data = CustomerCoupon::where('code', $request->coupon_code)->where('is_expired', '0')->first();
    
    
                if ($coupon_data) {
                    $coupon_value = $coupon_data->coupon;
                    if ($coupon_value->is_percentage === 'yes') {
                        $discount = $coupon_value->discount;
                        $discount_price = ($this->cart->total_price * $discount / 100);
                    } else {
                        $discount = $coupon_value->discount;
                        $discount_price = $discount;
                    }
    
                    $cart_price = $this->cart->total_price - $discount_price;
                    $coupon_name = $coupon_value->title;
                    $coupon_code = $coupon_data->code;
                    $coupon_discount_price = $discount_price;
                } else {
                    $cart_price = $this->cart->total_price;
                    $coupon_name = null;
                    $coupon_code = null;
                    $coupon_discount_price = 0;
                }
            } else {
                $cart_price = $this->cart->total_price;
                $coupon_name = null;
                $coupon_code = null;
                $coupon_discount_price = 0;
            }
    
    
    
    
            if ($request->same === null || $request->same <= 0 || $request->same === 0) {
                if ($request->shipping_address == null) {
                    return response()->json($this->response(null, true, 'Select Shipping Address'), 200);
                }
                if ($request->billing_address == null) {
                    return response()->json($this->response(null, true, 'Select Billing Address'), 200);
                }
                $shipping_address = $this->getShippingAddress($user->id, $request->shipping_address);
                $billing_address = $this->getBillingAddress($user->id, $request->billing_address);
            } else {
                if ($request->shipping_address != null) {
                    $shipping_address = $this->getShippingAddress($user->id, $request->shipping_address);
                    $billing_address = $shipping_address;
                } else {
                    $billing_address = $this->getBillingAddress($user->id, $request->billing_address);
                    $shipping_address = $billing_address;
                }
            }
    
    
    
            $fixed_price =  $cart_price + $shipping_address->getLocation->deliveryRoute->charge - (int)round($coupon_discount_price);
            if (!$shipping_address->getLocation->deliveryRoute->charge) {
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => 'Sorry !! Something Went Wrong'
                ];
            }
    
    
            $cart_price = $cart_price + $shipping_address->getLocation->deliveryRoute->charge;
    
            if ($cart_price != $request->totalAmount) {
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => 'The Cart Total Amount Doesnot Match With Payment Amount'
                ];
                return response()->json($response, 200);
            }
    
            if ($request->status != 'COMPLETE') {
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => 'Sorry !! Your transaction has been In-completed.'
                ];
                return response()->json($response, 200);
            }
            $vatamountTotal=0;
            foreach ($this->cart->cartAssets as $cItem) {
                $vatamountTotal=$vatamountTotal+$cItem->vatamountfield;
                
            }
            $ref_id='OD'.rand(100000,999999);
            DB::beginTransaction();
            try {
                $order = [
                    'user_id' => $user->id,
                    'aff_id' => Str::random(10) . rand(100, 1000),
                    'total_quantity' => $this->cart->total_qty,
                    'total_price' => (int)round($fixed_price),
                    'coupon_name' => $coupon_name,
                    'coupon_code' => $coupon_code,
                    'coupon_discount_price' => $coupon_discount_price,
                    'ref_id' => $ref_id,
                    'shipping_charge' => $shipping_address->getLocation->deliveryRoute->charge,
                    'total_discount' => $this->cart->total_discount,
                    'payment_status' => '1',
                    'merchant_name' => $request->merchantName,
                    'payment_with' => 'ESEWA',
                    'payment_date' => date('Y-m-d'),
                    'transaction_ref_id' => $request->refId,
                    'name' => $shipping_address->name,
                    'email' => $shipping_address->email,
                    'phone' => $shipping_address->phone,
                    'province' => $shipping_address->province,
                    'district' => $shipping_address->district,
                    'area' => $shipping_address->area,
                    'additional_address' => $shipping_address->additional_address,
                    'zip' => $shipping_address->zip,
                    'b_name' => $billing_address->name,
                    'b_email' => $billing_address->email,
                    'b_phone' => $billing_address->phone,
                    'b_province' => $billing_address->province,
                    'b_district' => $billing_address->district,
                    'b_area' => $billing_address->area,
                    'b_additional_address' => $billing_address->additional_address,
                    'b_zip' => $billing_address->zip,
                    'vat_amount'=>$vatamountTotal ?? 0
                ];
                $this->order->fill($order);
                $status = $this->order->save();
                if ($status) {
                    $temp = [];
                    foreach ($this->cart->cartAssets as $product) {
                        $seller_product_id[] = $product->product_id;
                        $seller_product[] = [
                            'product' => [
                                'product_id' => $product->product_id,
                                'qty' => $product->qty,
                                'price' => $product->price,
                                'total' => $product->price,
                                'discount' => $product->discount,
                                'sub_total_price' => $product->sub_total_price,
                                'vatAmount'=>$product->vatamountfield
                            ],
                            'seller' => Product::where('id', $product->product_id)->first()->seller->id ?? null,
                        ];
                        $temp[] = [
                            'order_id' => $this->order->id,
                            'product_id' => $product->product_id,
                            'product_name' => $product->product_name,
                            'price' => $product->price + $product->discount,
                            'qty' => $product->qty,
                            'sub_total_price' => $product->sub_total_price,
                            'color' => $product->color,
                            'image'=>$product->image,
                            'discount' => $product->discount,
                            'options' => $product->options
                        ];
                    }
                    $this->order_asset->insert($temp);
                    $this->seller_order_action->sellerOrder($seller_product_id, $this->order, $temp, $seller_product);
                    $this->cart->delete();
                    UserPaymentId::where('user_id', $user->id)->where('id', $user_payment_id->id)->delete();
                    EmailSetUp::sendMail(MessageSetupEnum::ORDER_PLACE, $data = $this->order, $user);
                    if($request->coupon_code)
                    {
                        $coupon_data=CustomerCoupon::where('code',$request->coupon_code)->first();
                        if(!$coupon_data)
                        {
                            $response = [
                                'error' => true,
                                'data' => null,
                                'msg' => 'Sorry !! Invalid Coupon Code !!'
                            ];
                            return response()->json($response, 200);
                        }
    
                        if($coupon_data->is_for_same==1)
                        {
                            $coupon_data->update([
                                'is_expired'=>1
                            ]);
                        }
                        else
                        {
                            CustomerAllUsedCoupon::create([
                                    'coupon_id'=>$coupon_data->coupon_id,
                                    'customer_coupon_id'=>$coupon_data->id,
                                    'customer_id'=>$user->id,
                                    'coupon_code'=>$coupon_data->code
                                ]);
                        }
                       
    
                    }
                    $this->walletSendNotification('Esewa','Notification',$this->order);
                    // Mail::to($user->email)->send(new CustomerOrderMail($this->order));
                    $pdf=(new MobilePdfGenerate($this->order,$this->order->ref_id))->createPdf();
                    DB::commit();
                    $response = [
                        'error' => false,
                        'data' => [
                            'order_details' => $this->order,
                            'pdf_url'=> asset('mobilepdf/'.$this->order->id.'.pdf') ?? ''
                        ],
                        'msg' => $request->message
                    ];
                    return response()->json($response, 200);
                } else {
                    $response = [
                        'error' => true,
                        'data' => null,
                        'msg' => 'Sorry !! There Was A Problem While Creating Your Order !!'
                    ];
                    return response()->json($response, 200);
                }
            } catch (\Throwable $th) {
                DB::rollBack();
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => $th->getMessage()
                ];
                return response()->json($response, 200);
            }
        }

    }

    public function khaltiPayment(Request $request)
    {   
        $today = Carbon::now()->format('l');
        $order_timing_check = OrderTimeSetting::where('day', $today)->exists();
        if($order_timing_check)
        {   


            $validator = Validator::make($request->all(), [
                'productIdentity' => 'required',
                'amount' => 'required',
                'status' => 'required',
                'shipping_address' => 'nullable|exists:user_shipping_addresses,id',
                'billing_address' => 'nullable|exists:user_billing_addresses,id',
                'same' => 'nullable|in:0,1',
                'coupon_code' => 'nullable|string',
                'coupon_discount_amount' => 'nullable'
            ]);

            if ($validator->fails()) {
                $response = [
                    'error' => true,
                    'data' => null,
                    'message' => $validator->errors()
                ];
                return response()->json($response, 200);
            }

            $user = \Auth::user();
            if (!$user) {
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => 'UnAuthorized User !!'
                ];

                return response()->json($response, 200);
            }

            if (!$request->productIdentity) {
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => 'Bill Id Required'
                ];

                return response()->json($response, 200);
            }

            $user_payment_id = UserPaymentId::where('user_id', $user->id)->where('payment_bill_id', $request->productIdentity)->first();

            if (!$user_payment_id || $user_payment_id == null) {
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => 'Bill Id Doesnot Match'
                ];

                return response()->json($response, 200);
            }



            $this->cart = $this->cart->where('user_id', $user->id)->with('cartAssets')->first();
            if (!$this->cart) {
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => 'Sorry !! No Item In The Cart !!'
                ];
                return response()->json($response, 200);
            }

            // May need to shift the code down after selecting the shipping and billing address

            $order_timing = OrderTimeSetting::where('day',$today)->first();
            if($order_timing->day_off == true)
            {   
                return response()->json(['status'=>200, 'message'=>'You cannot place order today beacause it is our off day.']);
            }

            $currentTime = Carbon::now()->format('H:i');
            if($currentTime > $order_timing->end_time || $currentTime < $order_timing->start_time){
                return response()->json(['status'=>200, 'message'=>'Please place your order between ' . $order_timing->start_time . ' and ' .$order_timing->end_time]);
            }
            
            if ($request->coupon_code != null || $request->coupon_code != '') {
                $coupon_data = CustomerCoupon::where('code', $request->coupon_code)->where('is_expired', '0')->first();


                if ($coupon_data) {
                    $coupon_value = $coupon_data->coupon;
                    if ($coupon_value->is_percentage === 'yes') {
                        $discount = $coupon_value->discount;
                        $discount_price = ($this->cart->total_price * $discount / 100);
                    } else {
                        $discount = $coupon_value->discount;
                        $discount_price = $discount;
                    }

                    $cart_price = $this->cart->total_price - $discount_price;
                    $coupon_name = $coupon_value->title;
                    $coupon_code = $coupon_data->code;
                    $coupon_discount_price = $discount_price;
                } else {
                    $cart_price = $this->cart->total_price;
                    $coupon_name = null;
                    $coupon_code = null;
                    $coupon_discount_price = 0;
                }
            } else {
                $cart_price = $this->cart->total_price;
                $coupon_name = null;
                $coupon_code = null;
                $coupon_discount_price = 0;
            }


            if ($request->same === null || $request->same <= 0 || $request->same === 0) {
                if ($request->shipping_address == null) {
                    return response()->json($this->response(null, true, 'Select Shipping Address'), 200);
                }

                if ($request->billing_address == null) {
                    return response()->json($this->response(null, true, 'Select Billing Address'), 200);
                }

                $shipping_address = $this->getShippingAddress($user->id, $request->shipping_address);
                $billing_address = $this->getBillingAddress($user->id, $request->billing_address);
            } else {
                if ($request->shipping_address != null) {
                    $shipping_address = $this->getShippingAddress($user->id, $request->shipping_address);
                    $billing_address = $shipping_address;
                } else {
                    $billing_address = $this->getBillingAddress($user->id, $request->billing_address);
                    $shipping_address = $billing_address;
                }
            }
            $fixed_price =  $cart_price + $shipping_address->getLocation->deliveryRoute->charge - (int)round($coupon_discount_price);

            if (!$shipping_address->getLocation->deliveryRoute->charge) {
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => 'Sorry !! Something Went Wrong'
                ];
            }

            $cart_price = $cart_price + $shipping_address->getLocation->deliveryRoute->charge;

            if ($cart_price != $request->amount) {
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => 'The Cart Total Amount Doesnot Match With Payment Amount'
                ];

                return response()->json($response, 200);
            }

            if ($request->status != 'COMPLETE') {
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => 'Sorry !! Your transaction has been In-completed.'
                ];

                return response()->json($response, 200);
            }
            $vatamountTotal=0;
            foreach ($this->cart->cartAssets as $cItem) {
                $vatamountTotal=$vatamountTotal+$cItem->vatamountfield;
                
            }
            $ref_id='OD'.rand(100000,999999);
            DB::beginTransaction();
            try {
                $order = [
                    'user_id' => $user->id,
                    'aff_id' => Str::random(10) . rand(100, 1000),
                    'total_quantity' => $this->cart->total_qty,
                    'total_price' => (int)round($fixed_price),
                    'coupon_name' => $coupon_name,
                    'coupon_code' => $coupon_code,
                    'coupon_discount_price' => $coupon_discount_price,
                    'ref_id' => $ref_id,
                    'shipping_charge' => $shipping_address->getLocation->deliveryRoute->charge,
                    'total_discount' => $this->cart->total_discount,
                    'payment_status' => '1',
                    'merchant_name' => '',
                    'payment_with' => 'KHALTI',
                    'payment_date' => Carbon::now(),
                    'transaction_ref_id' => $request->idx,
                    'mobile' => $request->mobile,
                    'name' => $shipping_address->name,
                    'email' => $shipping_address->email,
                    'phone' => $shipping_address->phone,
                    'province' => $shipping_address->province,
                    'district' => $shipping_address->district,
                    'area' => $shipping_address->area,
                    'additional_address' => $shipping_address->additional_address,
                    'zip' => $shipping_address->zip,
                    'b_name' => $billing_address->name,
                    'b_email' => $billing_address->email,
                    'b_phone' => $billing_address->phone,
                    'b_province' => $billing_address->province,
                    'b_district' => $billing_address->district,
                    'b_area' => $billing_address->area,
                    'b_additional_address' => $billing_address->additional_address,
                    'b_zip' => $billing_address->zip,
                    'vat_amount'=>$vatamountTotal ?? 0
                ];

                $this->order->fill($order);
                $status = $this->order->save();
                if ($status) {
                    $temp = [];
                    foreach ($this->cart->cartAssets as $product) {
                        $seller_product_id[] = $product->product_id;
                        $seller_product[] = [
                            'product' => [
                                'product_id' => $product->product_id,
                                'qty' => $product->qty,
                                'price' => $product->price,
                                'total' => $product->price,
                                'discount' => $product->discount,
                                'sub_total_price' => $product->sub_total_price,
                                'vatAmount'=>$product->vatamountfield
                            ],
                            'seller' => Product::where('id', $product->product_id)->first()->seller->id ?? null,
                        ];
                        $temp[] = [
                            'order_id' => $this->order->id,
                            'product_id' => $product->product_id,
                            'product_name' => $product->product_name,
                            'price' => $product->price + $product->discount,
                            'qty' => $product->qty,
                            'sub_total_price' => $product->sub_total_price,
                            'color' => $product->color,
                            'image'=>$product->image,
                            'discount' => $product->discount,
                            'options' => $product->options
                        ];
                    }
                    $this->order_asset->insert($temp);
                    $this->seller_order_action->sellerOrder($seller_product_id, $this->order, $temp, $seller_product);
                    $this->cart->delete();
                    UserPaymentId::where('user_id', $user->id)->where('id', $user_payment_id->id)->delete();
                    EmailSetUp::sendMail(MessageSetupEnum::ORDER_PLACE, $data = $this->order, $user);
                    // Mail::to($user->email)->send(new CustomerOrderMail($this->order));
                    if($request->coupon_code)
                    {
                        $coupon_data=CustomerCoupon::where('code',$request->coupon_code)->first();
                        if(!$coupon_data)
                        {
                            $response = [
                                'error' => true,
                                'data' => null,
                                'msg' => 'Sorry !! Invalid Coupon Code !!'
                            ];
                            return response()->json($response, 200);
                        }

                        if($coupon_data->is_for_same==1)
                        {
                            $coupon_data->update([
                                'is_expired'=>1
                            ]);
                        }
                        else
                        {
                            CustomerAllUsedCoupon::create([
                                    'coupon_id'=>$coupon_data->coupon_id,
                                    'customer_coupon_id'=>$coupon_data->id,
                                    'customer_id'=>$user->id,
                                    'coupon_code'=>$coupon_data->code
                                ]);
                        }
                    

                    }
                    $this->walletSendNotification('Khalti','Notification',$this->order);
                    $pdf=(new MobilePdfGenerate($this->order,$this->order->ref_id))->createPdf();
                    DB::commit();
                    $response = [
                        'error' => false,
                        'data' => [
                            'order_details' => $this->order,
                            'pdf_url'=> asset('mobilepdf/'.$this->order->id.'.pdf') ?? ''
                        ],
                        'msg' => 'Your transaction has been completed'
                    ];
                    return response()->json($response, 200);
                } else {
                    $response = [
                        'error' => true,
                        'data' => null,
                        'msg' => 'Sorry !! There Was A Problem While Creating Your Order !!'
                    ];
                    return response()->json($response, 200);
                }
            } catch (\Throwable $th) {
                DB::rollBack(); 
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => $th->getMessage()
                ];
                return response()->json($response, 200);
            }
    
        }
    }

    public function cashPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'shipping_address' => 'nullable|exists:user_shipping_addresses,id',
            'billing_address' => 'nullable|exists:user_billing_addresses,id',
            'same' => 'nullable|in:0,1',
            'coupon_code' => 'nullable|string',
            'coupon_discount_amount' => 'nullable'
        ]);

        if ($validator->fails()) {
            $response = [
                'error' => true,
                'data' => null,
                'message' => $validator->errors()
            ];
            return response()->json($response, 200);
        }

        $today = Carbon::now()->format('l');
        $order_timing_check = OrderTimeSetting::where('day', $today)->exists();
        if($order_timing_check){

            $user = \Auth::user();
            if (!$user) {
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => 'UnAuthorized User !!'
                ];
    
                return response()->json($response, 200);
            }
            $this->cart = $this->cart->where('user_id', $user->id)->with('cartAssets')->first();
            if (!$this->cart) {
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => 'Sorry !! No Item In The Cart !!'
                ];
                return response()->json($response, 200);
            }

            $order_timing = OrderTimeSetting::where('day',$today)->first();
            if($order_timing->day_off == true)
            {   
                return response()->json(['status'=>200, 'message'=>'You cannot place order today beacause it is our off day.']);
            }
            $currentTime = Carbon::now()->format('H:i');
            if($currentTime > $order_timing->end_time || $currentTime < $order_timing->start_time){
                return response()->json(['status'=>200, 'message'=>'Please place your order between ' . $order_timing->start_time . ' and ' .$order_timing->end_time]);
            }
                    


            if ($request->same === null || $request->same <= 0 || $request->same === 0) {
                if ($request->shipping_address == null) {
                    return response()->json($this->response(null, true, 'Select Shipping Address'), 200);
                }                                                                                     
    
                if ($request->billing_address == null) {
                    return response()->json($this->response(null, true, 'Select Billing Address'), 200);
                }   
                
                $shipping_address = $this->getShippingAddress($user->id, $request->shipping_address);
                $billing_address = $this->getBillingAddress($user->id, $request->billing_address);
            } else {
                if ($request->shipping_address != null) {
                    $shipping_address = $this->getShippingAddress($user->id, $request->shipping_address);
                    $billing_address = $shipping_address;
                } else {
                    $billing_address = $this->getBillingAddress($user->id, $request->billing_address);
                    $shipping_address = $billing_address;
                }
            }
            

            
            $mimimum_order_cost = Setting::where('key', 'minimum_order_price')->pluck('value')->first();
            $shipping_chargeNew=0;
            if ($user->wholeseller) {
                if ($this->cart->total_price < $mimimum_order_cost) {
                    $response = [
                        'error' => true,
                        'data' => null,
                        'msg' => 'The minimum order amount should be Rs. ' . $mimimum_order_cost . ' !!'
                    ];
                    return response()->json($response, 200);
                }
                $wholeSellerShippingCharge = Setting::where('key', 'wholseller_shipping_charge')->first()->value;
                $shipping_chargeNew=$wholeSellerShippingCharge ?? 0;
                
            } else {
                foreach($this->cart->cartAssets as $cartAsset){
                    $shipping_chargeNew=$shipping_chargeNew+($cartAsset->product->shipping_charge * $cartAsset->qty);
                }
                
            }
            

            $default_shipping_charge = Setting::where('key','default_shipping_charge')->pluck('value')->first();
            if ($request->coupon_code != null || $request->coupon_code != '') {
                $coupon_data = CustomerCoupon::where('code', $request->coupon_code)->where('is_expired', '0')->first();
                if ($coupon_data) {
                    $coupon_value = $coupon_data->coupon;
                    if ($coupon_value->is_percentage === 'yes') {
                        $discount = $coupon_value->discount;
                        if($shipping_address->getLocation)
                        {
                            $discount_price = (($this->cart->total_price+$shipping_chargeNew) * $discount / 100);
                        }else{
                            $discount_price = (($this->cart->total_price + $shipping_chargeNew) * $discount / 100);
                        }
                    } else {
                        $discount = $coupon_value->discount;
                        $discount_price = $discount;
                    }
    
                    // $cart_price = $this->cart->total_price - $discount_price;
                    $coupon_name = $coupon_value->title;
                    $coupon_code = $coupon_data->code;
                    $coupon_discount_price = $discount_price;
                } else {
                    // $cart_price = $this->cart->total_price;
                    $coupon_name = null;
                    $coupon_code = null;
                    $coupon_discount_price = 0;
                }
            } else {
                // $cart_price = $this->cart->total_price;
                $coupon_name = null;
                $coupon_code = null;
                $coupon_discount_price = 0;
            }
            // dd($shipping_address);
            if($shipping_address == null)
            {
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => 'Sorry !! InValid Shipping Address'
                ];
                return response()->json($response,200);
            }
            $fixed_price = $this->cart->total_price +  $shipping_chargeNew - (int)round($coupon_discount_price);
        //    if($shipping_address->getLocation != null)
        //    {    
        //         $fixed_price =  $this->cart->total_price + $shipping_chargeNew - (int)round($coupon_discount_price);
        //    }else{
        //         $fixed_price = $this->cart->total_price +  $shipping_chargeNew - (int)round($coupon_discount_price);
        //    }
            // if($fixed_price < 500)
            // {
            //     return response()->json(['status'=>200, 'error'=>true,'message'=>'The order price should be above $. 500.']);
            // }
            
            // $cart_price = $cart_price + $shipping_address->getLocation->deliveryRoute->charge;
            $vatamountTotal=0;
            foreach ($this->cart->cartAssets as $cItem) {
                $vatamountTotal=$vatamountTotal+$cItem->vatamountfield;
                
            }
            $default_material_charge = Setting::where('key','materials_price')->pluck('value')->first();
            $ref_id='OD'.rand(100000,999999);
            DB::beginTransaction();
            try {
                $order = [
                    'user_id' => $user->id,
                    'aff_id' => Str::random(10) . rand(100, 1000),
                    'total_quantity' => $this->cart->total_qty,
                    'total_price' => (int)round($fixed_price+$default_material_charge),
                    'coupon_name' => $coupon_name,
                    'coupon_code' => $coupon_code,
                    'coupon_discount_price' => $coupon_discount_price,
                    'ref_id' => $ref_id,
                    'shipping_charge' => $shipping_chargeNew,
                    'total_discount' => $this->cart->total_discount,
                    'additional_charge' => $this->cart->additional_charge,
                    'payment_status' => '0',
                    'merchant_name' => '',
                    'payment_with' => 'Cash On Delivery',

                    'payment_proof' => $request->payment_proof,
                    
                    'payment_date' => Carbon::now(),
                    'transaction_ref_id' => $request->idx,
                    'mobile' => $user->phone,
                    'name' => $shipping_address->name,
                    'email' => $shipping_address->email,
                    'phone' => $shipping_address->phone,
                    'province' => $shipping_address->province,
                    'district' => $shipping_address->district,
                    'area' => $shipping_address->area,
                    'additional_address' => $shipping_address->additional_address,
                    'zip' => $shipping_address->zip,
                    'b_name' => $billing_address->name,
                    'b_email' => $billing_address->email,
                    'b_phone' => $billing_address->phone,
                    'b_province' => $billing_address->province,
                    'b_district' => $billing_address->district,
                    'b_area' => $billing_address->area,
                    'b_additional_address' => $billing_address->additional_address,
                    'b_zip' => $billing_address->zip,
                    'vat_amount'=>$vatamountTotal ?? 0
                ];
                $this->order->fill($order);
                $status = $this->order->save();
                if ($status) {
                    $temp = [];
                    foreach ($this->cart->cartAssets as $product) {
                        $seller_product_id[] = $product->product_id;
                        $seller_product[] = [
                            'product' => [
                                'product_id' => $product->product_id,
                                'qty' => $product->qty,
                                'price' => $product->price,
                                'total' => $product->price,
                                'discount' => $product->discount,
                                'sub_total_price' => $product->sub_total_price,
                                'order_id'=>$this->order->id,
                                'image' => $product->image,
                                'sub_total_price' => $product->sub_total_price,
                                'vatAmount'=>$product->vatamountfield
                            ],
                            'seller' => Product::where('id', $product->product_id)->first()->seller->id ?? null,
                        ];
                        $temp[] = [
                            'order_id' => $this->order->id,
                            'product_id' => $product->product_id,
                            'product_name' => $product->product_name,
                            'price' => $product->price+( $product->discount),
                            // 'price' => $product->price + $product->discount,
                            'qty' => $product->qty,
                            'sub_total_price' => $product->sub_total_price,
                            'color' => $product->color,
                            'image'=>$product->image,
                            'discount' => $product->discount,
                            'options' => $product->options ?? $request->options,
                            'vatamountfield'=>$product->vatamountfield ?? 0
                        ];
                    }
                    // dd($temp);
                    $this->order_asset->insert($temp);
                    // $this->seller_order_action->sellerOrder($seller_product_id, $this->order, $temp, $seller_product);
                    $this->cart->delete();
    
                    EmailSetUp::sendMail(MessageSetupEnum::ORDER_PLACE, $data = $this->order, $user);
                   
                    if($request->coupon_code !=null && !empty($request->coupon_code))
                    {
                        $coupon_data=CustomerCoupon::where('code',$request->coupon_code)->first();
                        if(!$coupon_data)
                        {
                            $response = [
                                'error' => true,
                                'data' => null,
                                'msg' => 'Sorry !! Invalid Coupon Code !!'
                            ];
                            return response()->json($response, 200); 
                        }
    
                        $alreadyCouponExists=CustomerAllUsedCoupon::where('coupon_id',$coupon_data->coupon_id)->where('customer_coupon_id',$coupon_data->id)->where('customer_id',$user->id)->first();
                        
                        if($alreadyCouponExists)
                        {
                            $response = [
                                'error' => true,
                                'data' => null,
                                'msg' => 'Sorry !! Coupon Expires !!'
                            ];
                            return response()->json($response, 200);
                        }
    
                        if($coupon_data->is_for_same==1)
                        {
                            $coupon_data->update([
                                'is_expired'=>1
                            ]);
                        }
                        else
                        {
                            CustomerAllUsedCoupon::create([
                                    'coupon_id'=>$coupon_data->coupon_id,
                                    'customer_coupon_id'=>$coupon_data->id,
                                    'customer_id'=>$user->id,
                                    'coupon_code'=>$coupon_data->code
                                ]);
                        }
                       
    
                    }
                    $pdf=(new MobilePdfGenerate($this->order,$this->order->ref_id))->createPdf();
                    // dd($pdf);
                    DB::commit();
                    $response = [
                        'error' => false,
                        'data' => [
                            'order_details' => $this->order,
                            'pdf_url'=> asset('mobilepdf/mobilepdf'.$this->order->id.'.pdf') ?? ''
                        ],
                        'msg' => 'Your transaction has been completed'
                    ];
                    return response()->json($response, 200);
                } else {
                    $response = [
                        'error' => true,
                        'data' => null,
                        'msg' => 'Sorry !! There Was A Problem While Creating Your Order !!'
                    ];
                    return response()->json($response, 200);
                }
            } catch (\Throwable $th) {
                DB::rollBack();
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => $th->getMessage()
                ];
                return response()->json($response, 200);
            }
        }

    }

    public function fonepayPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'shipping_address' => 'nullable|exists:user_shipping_addresses,id',
            'billing_address' => 'nullable|exists:user_billing_addresses,id',
            'same' => 'nullable|in:0,1',
            'coupon_code' => 'nullable|string',
            'coupon_discount_amount' => 'nullable'
        ]);

        if ($validator->fails()) {
            $response = [
                'error' => true,
                'data' => null,
                'message' => $validator->errors()
            ];
            return response()->json($response, 200);
        }

        $today = Carbon::now()->format('l');
        $order_timing_check = OrderTimeSetting::where('day', $today)->exists();
        if($order_timing_check){

            $user = \Auth::user();
            if (!$user) {
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => 'UnAuthorized User !!'
                ];
    
                return response()->json($response, 200);
            }
            $this->cart = $this->cart->where('user_id', $user->id)->with('cartAssets')->first();
            if (!$this->cart) {
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => 'Sorry !! No Item In The Cart !!'
                ];
                return response()->json($response, 200);
            }

            // May need to shift the code down after selecting the shipping and billing address
            $order_timing = OrderTimeSetting::where('day',$today)->first();
            if($order_timing->day_off == true)
            {   
                return response()->json(['status'=>200, 'message'=>'You cannot place order today beacause it is our off day.']);
            }
            $currentTime = Carbon::now()->format('H:i');
            if($currentTime > $order_timing->end_time || $currentTime < $order_timing->start_time){
                return response()->json(['status'=>200, 'message'=>'Please place your order between ' . $order_timing->start_time . ' and ' .$order_timing->end_time]);
            }
                    


            if ($request->same === null || $request->same <= 0 || $request->same === 0) {
                if ($request->shipping_address == null) {
                    return response()->json($this->response(null, true, 'Select Shipping Address'), 200);
                }                                                                                     
    
                if ($request->billing_address == null) {
                    return response()->json($this->response(null, true, 'Select Billing Address'), 200);
                }   
                
                $shipping_address = $this->getShippingAddress($user->id, $request->shipping_address);
                $billing_address = $this->getBillingAddress($user->id, $request->billing_address);
            } else {
                if ($request->shipping_address != null) {
                    $shipping_address = $this->getShippingAddress($user->id, $request->shipping_address);
                    $billing_address = $shipping_address;
                } else {
                    $billing_address = $this->getBillingAddress($user->id, $request->billing_address);
                    $shipping_address = $billing_address;
                }
            }

            $default_shipping_charge = Setting::where('key','default_shipping_charge')->pluck('value')->first();
            if ($request->coupon_code != null || $request->coupon_code != '') {
                $coupon_data = CustomerCoupon::where('code', $request->coupon_code)->where('is_expired', '0')->first();
                if ($coupon_data) {
                    $coupon_value = $coupon_data->coupon;
                    if ($coupon_value->is_percentage === 'yes') {
                        $discount = $coupon_value->discount;
                        if($shipping_address->getLocation)
                        {
                            $discount_price = (($this->cart->total_price+$shipping_address->getLocation->deliveryRoute->charge) * $discount / 100);
                        }else{
                            $discount_price = (($this->cart->total_price + $default_shipping_charge) * $discount / 100);
                        }
                    } else {
                        $discount = $coupon_value->discount;
                        $discount_price = $discount;
                    }
    
                    // $cart_price = $this->cart->total_price - $discount_price;
                    $coupon_name = $coupon_value->title;
                    $coupon_code = $coupon_data->code;
                    $coupon_discount_price = $discount_price;
                } else {
                    // $cart_price = $this->cart->total_price;
                    $coupon_name = null;
                    $coupon_code = null;
                    $coupon_discount_price = 0;
                }
            } else {
                // $cart_price = $this->cart->total_price;
                $coupon_name = null;
                $coupon_code = null;
                $coupon_discount_price = 0;
            }
           if($shipping_address->getLocation != null)
           {    
                $fixed_price =  $this->cart->total_price + $shipping_address->getLocation->deliveryRoute->charge - (int)round($coupon_discount_price);
           }else{
                $fixed_price = $this->cart->total_price +  $default_shipping_charge - (int)round($coupon_discount_price);
           }
            // if($fixed_price < 500)
            // {
            //     return response()->json(['status'=>200, 'error'=>true,'message'=>'The order price should be above $. 500.']);
            // }
            if($shipping_address->getLocation != null)
            {
                if (!$shipping_address->getLocation->deliveryRoute->charge) {
                    $response = [
                        'error' => true,
                        'data' => null,
                        'msg' => 'Sorry !! Something Went Wrong'
                    ];
                }
            }
            // $cart_price = $cart_price + $shipping_address->getLocation->deliveryRoute->charge;
            $vatamountTotal=0;
            foreach ($this->cart->cartAssets as $cItem) {
                $vatamountTotal=$vatamountTotal+$cItem->vatamountfield;
                
            }
            $default_material_charge = Setting::where('key','materials_price')->pluck('value')->first();
            $ref_id='OD'.rand(100000,999999);
            DB::beginTransaction();
            try {
                $order = [
                    'user_id' => $user->id,
                    'aff_id' => Str::random(10) . rand(100, 1000),
                    'total_quantity' => $this->cart->total_qty,
                    'total_price' => (int)round($fixed_price+$default_material_charge??'0'),
                    'coupon_name' => $coupon_name,
                    'coupon_code' => $coupon_code,
                    'coupon_discount_price' => $coupon_discount_price,
                    'ref_id' => $ref_id,
                    'shipping_charge' => $shipping_address->getLocation != null ? $shipping_address->getLocation->deliveryRoute->charge :  $default_shipping_charge,
                    'total_discount' => $this->cart->total_discount,
                    'additional_charge' => $this->cart->additional_charge,
                    'payment_status' => '0',
                    'merchant_name' => '',
                    'payment_with' => 'Fonepay-QR',
                    'payment_date' => Carbon::now(),
                    'transaction_ref_id' => $request->idx,
                    'mobile' => $user->phone,
                    'name' => $shipping_address->name,
                    'email' => $shipping_address->email,
                    'phone' => $shipping_address->phone,
                    'province' => $shipping_address->province,
                    'district' => $shipping_address->district,
                    'area' => $shipping_address->area,
                    'additional_address' => $shipping_address->additional_address,
                    'zip' => $shipping_address->zip,
                    'b_name' => $billing_address->name,
                    'b_email' => $billing_address->email,
                    'b_phone' => $billing_address->phone,
                    'b_province' => $billing_address->province,
                    'b_district' => $billing_address->district,
                    'b_area' => $billing_address->area,
                    'b_additional_address' => $billing_address->additional_address,
                    'b_zip' => $billing_address->zip,
                    'vat_amount'=>$vatamountTotal ?? 0
                ];
                $this->order->fill($order);
                $status = $this->order->save();
                if ($status) {
                    $temp = [];
                    foreach ($this->cart->cartAssets as $product) {
                        $seller_product_id[] = $product->product_id;
                        $seller_product[] = [
                            'product' => [
                                'product_id' => $product->product_id,
                                'qty' => $product->qty,
                                'price' => $product->price,
                                'total' => $product->price,
                                'discount' => $product->discount,
                                'sub_total_price' => $product->sub_total_price,
                                'order_id'=>$this->order->id,
                                'image' => $product->image,
                                'sub_total_price' => $product->sub_total_price,
                                'vatAmount'=>$product->vatamountfield
                            ],
                            'seller' => Product::where('id', $product->product_id)->first()->seller->id ?? null,
                        ];
                        $temp[] = [
                            'order_id' => $this->order->id,
                            'product_id' => $product->product_id,
                            'product_name' => $product->product_name,
                            'price' => $product->price+( $product->discount),
                            // 'price' => $product->price + $product->discount,
                            'qty' => $product->qty,
                            'sub_total_price' => $product->sub_total_price,
                            'color' => $product->color,
                            'image'=>$product->image,
                            'discount' => $product->discount,
                            'options' => $product->options ?? $request->options,
                            'vatamountfield'=>$product->vatamountfield ?? 0
                        ];
                    }
                    // dd($temp);
                    $this->order_asset->insert($temp);
                    // $this->seller_order_action->sellerOrder($seller_product_id, $this->order, $temp, $seller_product);
                    $this->cart->delete();
    
                    EmailSetUp::sendMail(MessageSetupEnum::ORDER_PLACE, $data = $this->order, $user);
                   
                    if($request->coupon_code !=null && !empty($request->coupon_code))
                    {
                        $coupon_data=CustomerCoupon::where('code',$request->coupon_code)->first();
                        if(!$coupon_data)
                        {
                            $response = [
                                'error' => true,
                                'data' => null,
                                'msg' => 'Sorry !! Invalid Coupon Code !!'
                            ];
                            return response()->json($response, 200); 
                        }
    
                        $alreadyCouponExists=CustomerAllUsedCoupon::where('coupon_id',$coupon_data->coupon_id)->where('customer_coupon_id',$coupon_data->id)->where('customer_id',$user->id)->first();
                        
                        if($alreadyCouponExists)
                        {
                            $response = [
                                'error' => true,
                                'data' => null,
                                'msg' => 'Sorry !! Coupon Expires !!'
                            ];
                            return response()->json($response, 200);
                        }
    
                        if($coupon_data->is_for_same==1)
                        {
                            $coupon_data->update([
                                'is_expired'=>1
                            ]);
                        }
                        else
                        {
                            CustomerAllUsedCoupon::create([
                                    'coupon_id'=>$coupon_data->coupon_id,
                                    'customer_coupon_id'=>$coupon_data->id,
                                    'customer_id'=>$user->id,
                                    'coupon_code'=>$coupon_data->code
                                ]);
                        }
                       
    
                    }
                    $pdf=(new MobilePdfGenerate($this->order,$this->order->ref_id))->createPdf();
                    DB::commit();
                    $response = [
                        'error' => false,
                        'data' => [
                            'order_details' => $this->order,
                            'pdf_url'=> asset('mobilepdf/'.$this->order->id.'.pdf') ?? ''
                        ],
                        'msg' => 'Your transaction has been completed'
                    ];
                    return response()->json($response, 200);
                } else {
                    $response = [
                        'error' => true,
                        'data' => null,
                        'msg' => 'Sorry !! There Was A Problem While Creating Your Order !!'
                    ];
                    return response()->json($response, 200);
                }
            } catch (\Throwable $th) {
                DB::rollBack();
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => $th->getMessage()
                ];
                return response()->json($response, 200);
            }
        }

    }

    public function response($data = null, $error, $msg)
    {
        return $response = [
            'error' => $error,
            'data' => $data,
            'msg' => $msg
        ];
    }

    public function getShippingAddress($user_id, $id)
    {
        return UserShippingAddress::where('user_id', $user_id)->where('id', $id)->first();
    }

    public function getBillingAddress($user_id, $id)
    {
        return UserBillingAddress::where('user_id', $user_id)->where('id', $id)->first();
    }

    public function getDetailsOrder(Request $request,$id)
    {
       
        $item=Order::with('orderAssets.product.images')->find($id);
        $item->setAttribute('cancelReasonStatus',false);
        $item->setAttribute('refundStatus',false);
        $item->setAttribute('refundStatusValue',null);
        $item->setAttribute('refundRejectReason',null);
        if($item->is_new==0 && $item->status==1)
        {
            $item->setAttribute('status_value','PENDING');
            $item->setAttribute('cancelStatus',true);
            switch($item->status)
            {
                case 1:
                   
                    $item->setAttribute('cancelStatus',true);
                    break;
                case 2:
                    
                    $item->setAttribute('cancelStatus',true);
                    break;
                case 3:
                   
                    $item->setAttribute('cancelStatus',true);
                    break;
                case 4:
                   
                    $item->setAttribute('cancelStatus',true);
                    break;
                case 5:
                    
                    $item->setAttribute('cancelStatus',false);
                    break;
                case 6:
                    
                    $item->setAttribute('cancelStatus',false);
                    $item->setAttribute('cancelReasonStatus',true);
                    break;
                case 7:
                  
                    $item->setAttribute('cancelStatus',false);
                    $item->setAttribute('cancelReasonStatus',true);
                    break;
                default:
                
                $item->setAttribute('cancelStatus',false);
                break;

            }
        }
        elseif($item->status==6 || $item->status==7)
        {
            switch($item->status)
            {
                case 1:
                    $item->setAttribute('status_value','SEEN');
                    $item->setAttribute('cancelStatus',true);
                    break;
                case 2:
                    $item->setAttribute('status_value','READY TO SHIP');
                    $item->setAttribute('cancelStatus',true);
                    break;
                case 3:
                    $item->setAttribute('status_value','DISPATCHED');
                    $item->setAttribute('cancelStatus',true);
                    break;
                case 4:
                    $item->setAttribute('status_value','SHPIED');
                    $item->setAttribute('cancelStatus',true);
                    break;
                case 5:
                    $item->setAttribute('status_value','DELIVERED');
                    $item->setAttribute('cancelStatus',false);
                    break;
                case 6:
                    $item->setAttribute('status_value','CANCELED');
                    $item->setAttribute('cancelStatus',false);
                    $item->setAttribute('cancelReasonStatus',true);
                    
                    break;
                case 7:
                    $item->setAttribute('status_value','REJECTED');
                    $item->setAttribute('cancelStatus',false);
                    $item->setAttribute('cancelReasonStatus',true);
                    break;
                default:
                $item->setAttribute('status_value','NOT DEFINE');
                $item->setAttribute('cancelStatus',false);
                break;

            }
        }
        else
        {
            switch($item->status)
            {
                case 1:
                    $item->setAttribute('status_value','SEEN');
                    $item->setAttribute('cancelStatus',true);
                    break;
                case 2:
                    $item->setAttribute('status_value','READY TO SHIP');
                    $item->setAttribute('cancelStatus',true);
                    break;
                case 3:
                    $item->setAttribute('status_value','DISPATCHED');
                    $item->setAttribute('cancelStatus',true);
                    break;
                case 4:
                    $item->setAttribute('status_value','SHPIED');
                    $item->setAttribute('cancelStatus',true);
                    break;
                case 5:
                    $item->setAttribute('status_value','DELIVERED');
                    $item->setAttribute('cancelStatus',false);
                    break;
                case 6:
                    $item->setAttribute('status_value','CANCELED');
                    $item->setAttribute('cancelStatus',false);
                    $item->setAttribute('cancelReasonStatus',true);
                    
                    break;
                case 7:
                    $item->setAttribute('status_value','REJECTED');
                    $item->setAttribute('cancelStatus',false);
                    $item->setAttribute('cancelReasonStatus',true);
                    break;
                default:
                $item->setAttribute('status_value','NOT DEFINE');
                $item->setAttribute('cancelStatus',false);
                break;

            }
        }
        if($item->payment_status==1 && $item->directrefund ==null && $item->status==6 || $item->status==7)
        {
            $item->setAttribute('refundStatus',true);
            
        }

        if($item->directrefund !=null)
        {
            $item->setAttribute('refundStatusValue',($item->directrefund->status==1 ? 'PENDING':($item->directrefund->status==2 ? 'PAID':'REJECTED')));
            if($item->directrefund->status==3)
            {
                $item->setAttribute('refundRejectReason',$item->directrefund->remarks);
            }

        }
        $response = [
            'error' => false,
            'data' => $item,
            'msg' =>'Order Details'
        ];

        return response()->json($response,200);
    }

    public function getDefaultShippingCharge()
    {
        $delivery_charge = Setting::where('key','default_shipping_charge')->pluck('value')->first();
        return response()->json(['status'=>200,'error'=>false,'data'=>$delivery_charge]);
    }
}
