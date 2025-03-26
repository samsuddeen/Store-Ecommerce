<?php

namespace App\Http\Controllers;



use App\Models\Cart;
use App\Models\User;
use App\Models\Order;
use App\Models\seller;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Customer;
use App\Models\CartAssets;
use App\Models\OrderAsset;
use App\Helpers\EmailSetUp;
use Illuminate\Support\Str;
use App\Events\PaymentEvent;
use App\Models\Admin\VatTax;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use App\Models\Refund\Refund;
use App\Models\Admin\FoodItem;
use Illuminate\Support\Carbon;
use App\Models\OrderTimeSetting;
use App\Models\UserBillingAddress;
use Illuminate\Support\Facades\DB;
use App\Models\UserShippingAddress;
use Illuminate\Support\Facades\Auth;
use App\Models\CustomerAllUsedCoupon;
use App\Models\Order\Seller\SellerOrder;
use App\Actions\Seller\SellerOrderAction;
use App\Data\Form\PaymentHistoryFormData;
use App\Enum\MessageSetup\MessageSetupEnum;
use App\Models\Order\Seller\ProductSellerOrder;
use App\Actions\Notification\NotificationAction;
use App\Models\Admin\Coupon\Customer\CustomerCoupon;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use App\Models\New_Customer;
class PayPalController extends Controller
{

    protected $order = null;
    protected $order_asset = null;
    protected $seller_order = null;
    protected $seller_order_action = null;
    protected $product_seller_order = null;
    protected $refund = null;
    protected $cart = null;

    public function __construct(Cart $cart, Refund $refund, Order $order, OrderAsset $order_asset, SellerOrderAction $seller_order_action, ProductSellerOrder $product_seller_order, SellerOrder $seller_order)
    {
        $this->order = $order;
        $this->order_asset = $order_asset;
        $this->seller_order = $seller_order;
        $this->seller_order_action = $seller_order_action;
        $this->product_seller_order = $product_seller_order;
        $this->refund = $refund;
        $this->cart = $cart;
    }

    public function paypalCancel()
    {
        return redirect()->route('index')->with('success', 'Payment Cancel Successfull');
    }

    public function paypalSuccess(Request $request)
    {
        $orderData = session()->get('paypalsessiondata');
        $user = auth()->guard('customer')->user();
        if (!$orderData || $orderData == null) {
            $request->session()->flash('error', 'Plz Select Item First !!');
            return redirect()->route('cart.checkout');
        }
        if (!$user || $user == null) {
            $request->session()->flash('error', 'Plz Login First !!');
            return redirect()->route('Clogin');
        }
        $provider = new PayPalClient();
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request['token']);
        try {
            if ($response['status'] === 'COMPLETED') {
                DB::beginTransaction();
                try {
                    $order_id = $this->order->fill($orderData['order']);
                    $status = $this->order->save();
                    $seller = [];
                    $temp = [];
                    $seller_product = [];

                    foreach ($orderData['cart_item'] as $key => $product) {
                        $seller_product_id[] = $product->product_id;
                        $seller_product[] = [
                            'product' => [
                                'product_id' => $product->product_id,
                                'order_id' => $order_id->id,
                                'qty' => $product->qty,
                                'price' => $product->price,
                                'total' => $product->price,
                                'discount' => $product->discount,
                                'image' => $product->image,
                                'sub_total_price' => $product->sub_total_price,
                                'vatAmount' => $product->vatamountfield
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
                            'image' => $product->image,
                            'discount' => $product->discount,
                            'options' => $product->options,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                            'vatamountfield' => $product->vatamountfield ?? 0
                        ];
                    }
                    $this->order_asset->insert($temp);

                    // ----------------------------------Seller Code----------------------------------------                

                    $this->seller_order_action->sellerOrder($seller_product_id, $this->order, $temp, $seller_product);

                    // ----------------------------------Seller Code----------------------------------------

                    foreach ($orderData['cart_item'] as $item) {
                        CartAssets::where('id', $item->id)->delete();
                    }
                    $remainingCartItem = CartAssets::where('cart_id', $this->cart->id)->get();
                    if ($remainingCartItem->count() > 0) {
                        $total_cart_price = null;
                        $total_cart_qty = null;
                        $total_cart_discount = null;
                        foreach ($remainingCartItem as $remainingItem) {
                            $total_cart_price = $total_cart_price + $remainingItem->sub_total_price;
                            $total_cart_qty = $total_cart_qty + $remainingItem->qty;
                            $total_cart_discount = $total_cart_discount + $remainingItem->discount;
                        }
                        $data['total_price'] = $total_cart_price;
                        $data['total_qty'] = $total_cart_qty;
                        $data['total_discount'] = $total_cart_discount;
                        $data['ref_id'] = $this->order->ref_id;
                        $this->cart->fill($data);
                        $this->cart->save();
                    } else {
                        Cart::where('user_id', $user->id)->delete();
                    }
                    // dd($this->order->orderAssets);
                    EmailSetUp::sendMail(MessageSetupEnum::ORDER_PLACE, $data = $this->order, $user);

                    $admin = User::first();
                    $paymentData = (new PaymentHistoryFormData(
                        get_class(auth()->guard('customer')->user()->getModel()),
                        auth()->guard('customer')->user()->id,
                        get_class($admin->getModel()),
                        $admin->id,
                        get_class($this->order->getModel()),
                        $this->order->id,
                        null,
                        null,
                        3,
                        'Refund Paid',
                        route('returnable.show', $this->refund->returnOrder->id),
                        'Refund Paid To the Customer ',
                        false,
                        true
                    ))->getData();
                    PaymentEvent::dispatch($paymentData);
                    if ($request->coupoon_code) {
                        $coupon_data = CustomerCoupon::where('code', $request->coupon_code_name)->first();
                        if (!$coupon_data) {
                            $request->session()->flash('error', 'Something is Wrong !!!');
                            return redirect()->route('cart.index');
                        }

                        if ($coupon_data->is_for_same == 1) {
                            $coupon_data->update([
                                'is_expired' => 1
                            ]);
                        } else {
                            CustomerAllUsedCoupon::create([
                                'coupon_id' => $coupon_data->coupon_id,
                                'customer_coupon_id' => $coupon_data->id,
                                'customer_id' => $user->id,
                                'coupon_code' => $coupon_data->code
                            ]);
                        }
                    }

                    DB::commit();
                    $request->session()->forget('paypalsessiondata');
                    $request->session()->flash('success', 'Your Order Has been Created Successfully !!');
                    return redirect()->route('invoice', $this->order->id);
                    return redirect()->route('order.productDetails', $this->order->id);
                } catch (\Throwable $th) {
                    DB::rollBack();
                    return redirect()->route('cart.checkout')->with('error', 'Something Went Wrong !!');
                }
            } else {
                return redirect()->route('cart.checkout')->with('error', 'Something Went Wrong !!');
            }
        } catch (\Throwable $th) {
            return redirect()->route('cart.checkout')->with('error', 'Something Went Wrong !!');
        }
    }

    public function paypalSuccessDirect(Request $request)
    {
        $sessionData = session()->get('paypalsessiondatadirect');
        // dd($sessionData);
        if (!$sessionData && $sessionData == null) {
            $request->session()->flash('error', 'Something Went Wrong !!');
            return redirect()->route('cart.checkout');
        }


        $user = auth()->guard('customer')->user();

        if (!$user || $user == null) {
            $request->session()->flash('error', 'Plz Login First !!');
            return redirect()->route('Clogin');
        }


        try {
            $provider = new PayPalClient();
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();
            $response = $provider->capturePaymentOrder($request['token']);
            if ($response['status'] === 'COMPLETED') {
                try {
                    $requestOriginalData = $sessionData['requestData'];
                    $user = auth()->guard('customer')->user();
                    $default_shipping_charge = Setting::where('key', 'default_shipping_charge')->pluck('value')->first();
                    $default_material_charge = Setting::where('key', 'materials_price')->pluck('value')->first();

                    $for_order =  $sessionData['for_order'];
                    if ($for_order === null) {
                        return redirect()->route('cart.checkout')->with('error', 'Plz Try again, Something is wrong');
                    }

                    if ($requestOriginalData['shipping_address'] === null) {
                        return redirect()->route('cart.index')->with('error', 'Plz Select Shipping Address !!');
                    }



                    $requestOriginalData['billing_address'] = $requestOriginalData['billing_address'] ?? null;
                    if ($requestOriginalData['billing_address'] == null) {
                        if ($requestOriginalData['same_address'] == null) {
                            return redirect()->route('cart.index')->with('error', 'Plz Select Billing Or Same As Shipping Fields');
                        }
                    }

                    if (!$user) {
                        return redirect()->route('cart.index')->with('error', 'Plz Login First');
                    }

                    if ($requestOriginalData['coupoon_code'] != null) {
                        $coupon_discount_price = $requestOriginalData['coupoon_code'];
                        $coupoun_code_name = $requestOriginalData['coupon_code_name'];
                        $coupoun__name = $requestOriginalData['coupon_name'];
                    } else {
                        $coupon_discount_price = 0;
                        $coupoun_code_name = null;
                        $coupoun__name = null;
                    }

                    $coupon_discount_price = round($coupon_discount_price);


                    $shipping_id = $requestOriginalData['shipping_address'];
                    $billing_id = $requestOriginalData['billing_address'];
                    $shipping_address = UserShippingAddress::where('id', $shipping_id)->where('user_id', $user->id)->first();
                    $requestOriginalData['same_address']=$requestOriginalData['same_address'] ?? null;
                    if ($requestOriginalData['same_address'] === null || $requestOriginalData['same_address'] <= 0 || $requestOriginalData['same_address'] === 0) {
                        if ($requestOriginalData['billing_address'] == null) {
                            return redirect()->route('cart.index')->with('error', 'Plz Select Billing Address');
                        }
                        $billing_address = UserBillingAddress::where('id', $requestOriginalData['billing_address'])->where('user_id', $user->id)->first();
                    } else {
                        $billing_address = $shipping_address;
                    }
                    if (!$shipping_address) {
                        return redirect()->route('cart.index')->with('error', 'Shipping Address Is Not Valid');
                    }
                    if ($shipping_address->getLocation != null) {
                        $shipping_charge = $shipping_address->getLocation->deliveryRoute->charge;
                    } else {
                        $shipping_charge = $default_shipping_charge;
                    }

                    if (!$billing_address) {
                        return redirect()->route('cart.index')->with('error', 'Billing Address Is Not Valid');
                    }
                    // dd($for_order['product_id']);

                    $main_product = Product::where('id', $for_order['product_id'])->first();
                    $product = ProductStock::where('product_id', $for_order['product_id'])->where('id', $for_order['varient_id'])->first();
                    // $product_image = ProductImage::where('product_id', $for_order['product_id'])->where('color_id', $for_order['color_id'])->get();
                    $product_image = $main_product->images->first()->image;

                    $offer_price = getOfferProduct($main_product, $product);
                    $total_quantity = $for_order['product_qty'];


                    $stock_ways = $product->stockWays;

                    $option = [];
                    foreach ($stock_ways as $key => $data) {
                        $option[$key]['id'] = $data->category->id;
                        $option[$key]['title'] = $data->category->title;
                        $option[$key]['value'] = $data->value;
                    }
                    $options = json_encode($option);


                    if ($offer_price != null) {
                        $price = $offer_price;
                        $total_price = $offer_price * $total_quantity;
                        $discount = $product->price - $offer_price;
                        $total_discount = $discount * $total_quantity;
                    } elseif ($product->special_price != null) {
                        $price = $product->special_price;
                        $total_price = $product->special_price * $total_quantity;
                        $discount = $product->price - $product->special_price;
                        $total_discount = $discount * $total_quantity;
                    } else {
                        $price = $product->price;
                        $total_price = $product->price * $total_quantity;
                        $discount = 0;
                        $total_discount = 0;
                    }
                    $mimimum_order_cost = Setting::where('key','minimum_order_price')->pluck('value')->first();
               

                    if($user->wholeseller)
                    {
                        
                        if($total_price < $mimimum_order_cost){
                            $request->session()->flash('error','The minimum order amount should be $. '.$mimimum_order_cost.' !!');
                            return back();
                        }
                        $wholeSellerMinimumPrice=Setting::where('key','whole_seller_minimum_price')->first()->value;
                        $wholeSellerShippingCharge=Setting::where('key','wholseller_shipping_charge')->first()->value;
                        if($total_price >=$wholeSellerMinimumPrice)
                        {
                            $shipping_charge=0;
                        }
                        else
                        {
                            $shipping_charge=$wholeSellerShippingCharge;
                        }
                    }
                    else{
                        $wholeSellecustomerShippingChargePerKg=Setting::where('key','shippping_charge_per_kg')->first()->value;
                        $packetWeight=$main_product->package_weight;
                        $shipping_charge=(int)$wholeSellecustomerShippingChargePerKg * (int)$packetWeight;
                    }
                    $fixed_price = $total_price + $shipping_charge + $default_material_charge - (int)$coupon_discount_price;
                    if ($main_product->vat_percent == 0) {
                        $vatTax = VatTax::first();
                        if ($vatTax) {
                            $vatPercent = (int)$vatTax->vat_percent;
                            $vatAmount = ($total_price * $vatPercent) / 100;
                            $fixed_price = $fixed_price + round($vatAmount);
                        } else {
                            $fixed_price = $fixed_price;
                            $vatAmount = 0;
                        }
                    } else {
                        $fixed_price = $fixed_price;
                        $vatAmount = 0;
                    }
                    $ref_id = 'OD' . rand(100000, 999999);

                    if ($shipping_address->area == null) {
                        return redirect()->route('Cprofile')->with('error', 'Your shipping address cannot be empty. Please update your shipping address');
                    }
                    DB::beginTransaction();
                    try {

                        $order = [
                            'user_id' => $user->id,
                            'aff_id' => Str::random(10) . rand(100, 1000),
                            'total_quantity' => $total_quantity,
                            'total_price' => $fixed_price,
                            'coupon_name' => $coupoun__name,
                            'coupon_code' => $coupoun_code_name,
                            'coupon_discount_price' => $coupon_discount_price,
                            'ref_id' => $ref_id,
                            'shipping_charge' => $shipping_charge,
                            'material_charge' => $default_material_charge,
                            'total_discount' => $total_discount,
                            'payment_status' => '0',
                            'merchant_name' => null,
                            'payment_with' => 'Paypal',
                            'payment_date' => date('Y-m-d'),
                            // 'transaction_ref_id' => $requestOriginalData->refId,
                            'name' => $shipping_address->name,
                            'email' => $shipping_address->email,
                            'phone' => $shipping_address->phone,
                            'province' => $shipping_address->province,
                            'district' => $shipping_address->state,
                            'area' => $shipping_address->area,
                            'additional_address' => $shipping_address->additional_address,
                            'zip' => $shipping_address->zip,
                            'b_name' => $billing_address->name,
                            'b_email' => $billing_address->email,
                            'b_phone' => $billing_address->phone,
                            'b_province' => $billing_address->province,
                            'b_district' => $billing_address->state,
                            'b_area' => $billing_address->area,
                            'b_additional_address' => $billing_address->additional_address,
                            'admin_email' => env('MAIL_FROM_ADDRESS'),
                            'b_zip' => $billing_address->zip,
                            'vat_amount' => round($vatAmount) ?? 0
                        ];

                        if ($order['area'] == null) {
                            return redirect()->route('Cprofile')->with('error', 'Your shipping address cannot be empty. Please update your shipping address');
                        }

                        $order_id = $this->order->fill($order);
                        $status = $this->order->save();


                        $product_main = Product::where('id', $product->product_id)->first();
                        $seller = [];
                        $temp = [
                            'order_id' => $this->order->id,
                            'product_id' => $product->product_id,
                            'product_name' => $product_main->name,
                            'price' => $product->price,
                            'qty' => $total_quantity,
                            'sub_total_price' => ($product->price * $total_quantity) + round($vatAmount),
                            'image' => $product_image,
                            'color' => $product->color,
                            'discount' => $discount,
                            'options' => $options,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                            'vatamountfield' => round($vatAmount) ?? 0
                        ];
                        $order_asset = OrderAsset::insert($temp);
                        // ----------------------------------Seller Code----------------------------------------

                        $seller_order = [
                            'order_id' => $order_id->id,
                            'seller_id' => $product_main->seller_id ?? null,
                            'product_id' => $product_main->id,
                            'price' => $total_price,
                            'sub_total' => $total_price,
                            'user_id' => auth()->guard('customer')->user()->id,
                            'seller_order_id' => $this->seller_order->id,
                            'qty' => $for_order['product_qty'],
                            'subtotal' => $total_price,
                            'total_discount' => $total_discount,
                            'total' => $total_price + $total_discount,
                            'vatAmount' => $order_id->vat_amount
                        ];
                        if ($product_main->seller_id != null) {
                            $this->seller_order->fill($seller_order);
                            $this->seller_order->save();




                            $notification_data = [
                                'from_model' => get_class(auth()->guard('customer')->user()->getModel()),
                                'from_id' => auth()->guard('customer')->user()->id,
                                'to_model' => ($product_main->seller_id) ? get_class(seller::getModel()) : get_class(User::getModel()),
                                'to_id' => $product_main->seller_id ?? User::first()->id,
                                'title' => 'New Order From ' . ucfirst(auth()->guard('customer')->user()->name),
                                'summary' => 'You Have New Order Request',
                                'url' => url('/seller/seller-order/' . $this->seller_order->id),
                                'is_read' => false,
                            ];

                            (new NotificationAction($notification_data))->store();
                        } else {
                            $notification_data = [
                                'from_model' => get_class(auth()->guard('customer')->user()->getModel()),
                                'from_id' => auth()->guard('customer')->user()->id,
                                'to_model' => ($product_main->seller_id) ? get_class(seller::getModel()) : get_class(User::getModel()),
                                'to_id' => $product_main->seller_id ?? User::first()->id,
                                'title' => 'New Order From ' . ucfirst(auth()->guard('customer')->user()->name),
                                'summary' => 'You Have New Order Request',
                                'url' => url('/admin/view-order/' . $this->order->ref_id),
                                'is_read' => false,
                            ];

                            (new NotificationAction($notification_data))->store();
                        }

                        $discountpercent = $total_discount * 100 / $total_price;
                        $discount_percent = $discountpercent . '%';

                        $product_seller_order = [
                            'order_id' => $order_id->id,
                            'seller_id' => $product_main->seller_id ?? null,
                            'product_id' => $product_main->id,
                            'price' => $product->price,
                            'sub_total' => $total_price,
                            'user_id' => auth()->guard('customer')->user()->id ?? 1,
                            'seller_order_id' => $this->seller_order->id,
                            'qty' => $for_order['product_qty'],
                            'subtotal' => $total_price,
                            'discount' => $total_discount / $for_order['product_qty'],
                            'image' => $product_image,
                            'discount_percent' => $discount_percent,
                            'total' => $fixed_price,
                            'total_discount' => $total_discount,
                        ];

                        if ($product_main->seller_id != null) {
                            $this->product_seller_order->fill($product_seller_order);
                            $this->product_seller_order->save();
                        }

                        // ----------------------------------Seller Code----------------------------------------

                        $admin = User::first();
                        $paymentData = (new PaymentHistoryFormData(
                            get_class(auth()->guard('customer')->user()->getModel()),
                            auth()->guard('customer')->user()->id,
                            get_class($admin->getModel()),
                            $admin->id,
                            get_class($this->order->getModel()),
                            $this->order->id,
                            null,
                            null,
                            3,
                            'Refund Unpaid',
                            route('returnable.show', $this->refund->returnOrder->id),
                            'Refund Unpaid To the Amin',
                            false,
                            true
                        ))->getData();
                        if ($requestOriginalData['coupoon_code']) {
                            $coupon_data = CustomerCoupon::where('code', $requestOriginalData['coupon_code_name'])->first();
                            if (!$coupon_data) {
                                return redirect()->route('cart.index')->with('error', 'Something is Wrong !!!');
                            }

                            if ($coupon_data->is_for_same == 1) {
                                $coupon_data->update([
                                    'is_expired' => 1
                                ]);
                            } else {
                                CustomerAllUsedCoupon::create([
                                    'coupon_id' => $coupon_data->coupon_id,
                                    'customer_coupon_id' => $coupon_data->id,
                                    'customer_id' => $user->id,
                                    'coupon_code' => $coupon_data->code
                                ]);
                            }
                        }

                        DB::commit();
                        PaymentEvent::dispatch($paymentData);
                        EmailSetUp::sendMail(MessageSetupEnum::ORDER_PLACE, $data = $this->order, $user);
                        session()->forget('directOrder');
                        return redirect()->route('invoice', $this->order->id);
                        return redirect()->route('Corder')->with('error', 'Your Order Has been Created Successfully !!');
                    } catch (\Exception $ex) {
                        DB::rollBack();
                        return redirect()->route('cart.index')->with('error', 'Something is Wrong !!!');
                    }
                } catch (\Throwable $th) {
                    return redirect()->route('cart.checkout')->with('error', 'Something Went Wrong !!');
                }
            } else {
                return redirect()->route('index')->with('error', 'Something Went Wrong !!');
            }
        } catch (\Throwable $th) {
            return redirect()->route('index')->with('error', 'Something Went Wrong !!');
        }
    }

    public function paypalGuestSuccessDirect(Request $request){
        $sessionData = session()->get('paypalsessiondatadirectguest');
        
        if (!$sessionData && $sessionData == null) {
            $request->session()->flash('error', 'Something Went Wrong !!');
            return redirect()->route('cart.checkout');
        }


        $user = New_Customer::where('id', 63)->first();
       
        if (!$user || $user == null) {
            $request->session()->flash('error', 'Plz Login First !!');
            return redirect()->route('Clogin');
        }

        try {
            $provider = new PayPalClient();
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();
            $response = $provider->capturePaymentOrder($request['token']);
            if ($response['status'] === 'COMPLETED') {
                try {
                    // dd($sessionData);
                    $requestOriginalData = $sessionData['requestData'];
                    $user = New_Customer::where('id', 63)->first();
                    $requestOriginalData['shipping_address']=$sessionData['userShipAddressId'];
                    $requestOriginalData['billing_address']=$sessionData['userBillAddressId'];
                    $default_shipping_charge = Setting::where('key', 'default_shipping_charge')->pluck('value')->first();
                    $default_material_charge = Setting::where('key', 'materials_price')->pluck('value')->first();

                    $for_order =  $sessionData['for_order'];
                    if ($for_order === null) {
                        return redirect()->route('cart.checkout')->with('error', 'Plz Try again, Something is wrong');
                    }

                    if ($requestOriginalData['shipping_address'] === null) {
                        return redirect()->route('cart.index')->with('error', 'Plz Select Shipping Address !!');
                    }



                    $requestOriginalData['billing_address'] = $requestOriginalData['billing_address'] ?? null;
                    if ($requestOriginalData['billing_address'] == null) {
                        if ($requestOriginalData['same_address'] == null) {
                            return redirect()->route('cart.index')->with('error', 'Plz Select Billing Or Same As Shipping Fields');
                        }
                    }
                    if (!$user) {
                        return redirect()->route('cart.index')->with('error', 'Plz Login First');
                    }
                    

                    if ($requestOriginalData['coupoon_code'] != null) {
                        $coupon_discount_price = $requestOriginalData['coupoon_code'];
                        $coupoun_code_name = $requestOriginalData['coupon_code_name'];
                        $coupoun__name = $requestOriginalData['coupon_name'];
                    } else {
                        $coupon_discount_price = 0;
                        $coupoun_code_name = null;
                        $coupoun__name = null;
                    }

                    $coupon_discount_price = round($coupon_discount_price);

                    
                    $shipping_id = $requestOriginalData['shipping_address'];
                    
                    $billing_id = $requestOriginalData['billing_address'];
                    $shipping_address = UserShippingAddress::where('id', $shipping_id)->where('user_id', $user->id)->first();
                    $requestOriginalData['same_address']=$requestOriginalData['same_address'] ?? null;
                   
                    if ($requestOriginalData['same_address'] === null || $requestOriginalData['same_address'] <= 0 || $requestOriginalData['same_address'] === 0) {
                        if ($requestOriginalData['billing_address'] == null) {
                            return redirect()->route('cart.index')->with('error', 'Plz Select Billing Address');
                        }
                        $billing_address = UserBillingAddress::where('id', $requestOriginalData['billing_address'])->where('user_id', $user->id)->first();
                    } else {
                        $billing_address = $shipping_address;
                    }
                    if (!$shipping_address) {
                        return redirect()->route('cart.index')->with('error', 'Shipping Address Is Not Valid');
                    }
                    if ($shipping_address->getLocation != null) {
                        $shipping_charge = $shipping_address->getLocation->deliveryRoute->charge;
                    } else {
                        $shipping_charge = $default_shipping_charge;
                    }

                    if (!$billing_address) {
                        return redirect()->route('cart.index')->with('error', 'Billing Address Is Not Valid');
                    }
                    // dd($for_order['product_id']);

                    $main_product = Product::where('id', $for_order['product_id'])->first();
                    $product = ProductStock::where('product_id', $for_order['product_id'])->where('id', $for_order['varient_id'])->first();
                    // $product_image = ProductImage::where('product_id', $for_order['product_id'])->where('color_id', $for_order['color_id'])->get();
                    $product_image = $main_product->images->first()->image;

                    $offer_price = getOfferProduct($main_product, $product);
                    $total_quantity = $for_order['product_qty'];


                    $stock_ways = $product->stockWays;

                    $option = [];
                    foreach ($stock_ways as $key => $data) {
                        $option[$key]['id'] = $data->category->id;
                        $option[$key]['title'] = $data->category->title;
                        $option[$key]['value'] = $data->value;
                    }
                    $options = json_encode($option);


                    if ($offer_price != null) {
                        $price = $offer_price;
                        $total_price = $offer_price * $total_quantity;
                        $discount = $product->price - $offer_price;
                        $total_discount = $discount * $total_quantity;
                    } elseif ($product->special_price != null) {
                        $price = $product->special_price;
                        $total_price = $product->special_price * $total_quantity;
                        $discount = $product->price - $product->special_price;
                        $total_discount = $discount * $total_quantity;
                    } else {
                        $price = $product->price;
                        $total_price = $product->price * $total_quantity;
                        $discount = 0;
                        $total_discount = 0;
                    }
                    $mimimum_order_cost = Setting::where('key','minimum_order_price')->pluck('value')->first();
               

                    if($user->wholeseller)
                    {
                        
                        if($total_price < $mimimum_order_cost){
                            $request->session()->flash('error','The minimum order amount should be $. '.$mimimum_order_cost.' !!');
                            return back();
                        }
                        $wholeSellerMinimumPrice=Setting::where('key','whole_seller_minimum_price')->first()->value;
                        $wholeSellerShippingCharge=Setting::where('key','wholseller_shipping_charge')->first()->value;
                        if($total_price >=$wholeSellerMinimumPrice)
                        {
                            $shipping_charge=0;
                        }
                        else
                        {
                            $shipping_charge=$wholeSellerShippingCharge;
                        }
                    }
                    else{
                        $wholeSellecustomerShippingChargePerKg=Setting::where('key','shippping_charge_per_kg')->first()->value;
                        $packetWeight=$main_product->package_weight;
                        $shipping_charge=(int)$wholeSellecustomerShippingChargePerKg * (int)$packetWeight;
                    }
                    $fixed_price = $total_price + $shipping_charge + $default_material_charge - (int)$coupon_discount_price;
                    if ($main_product->vat_percent == 0) {
                        $vatTax = VatTax::first();
                        if ($vatTax) {
                            $vatPercent = (int)$vatTax->vat_percent;
                            $vatAmount = ($total_price * $vatPercent) / 100;
                            $fixed_price = $fixed_price + round($vatAmount);
                        } else {
                            $fixed_price = $fixed_price;
                            $vatAmount = 0;
                        }
                    } else {
                        $fixed_price = $fixed_price;
                        $vatAmount = 0;
                    }
                    $ref_id = 'OD' . rand(100000, 999999);

                    if ($shipping_address->area == null) {
                        return redirect()->route('Cprofile')->with('error', 'Your shipping address cannot be empty. Please update your shipping address');
                    }
                    DB::beginTransaction();
                    try {

                        $order = [
                            'user_id' => $user->id,
                            'aff_id' => Str::random(10) . rand(100, 1000),
                            'total_quantity' => $total_quantity,
                            'total_price' => $fixed_price,
                            'coupon_name' => $coupoun__name,
                            'coupon_code' => $coupoun_code_name,
                            'coupon_discount_price' => $coupon_discount_price,
                            'ref_id' => $ref_id,
                            'shipping_charge' => $shipping_charge,
                            'material_charge' => $default_material_charge,
                            'total_discount' => $total_discount,
                            'payment_status' => '0',
                            'merchant_name' => null,
                            'payment_with' => 'Paypal',
                            'payment_date' => date('Y-m-d'),
                            // 'transaction_ref_id' => $requestOriginalData->refId,
                            'name' => $shipping_address->name,
                            'email' => $shipping_address->email,
                            'phone' => $shipping_address->phone,
                            'province' => $shipping_address->province,
                            'district' => $shipping_address->state,
                            'area' => $shipping_address->area,
                            'additional_address' => $shipping_address->additional_address,
                            'zip' => $shipping_address->zip,
                            'b_name' => $billing_address->name,
                            'b_email' => $billing_address->email,
                            'b_phone' => $billing_address->phone,
                            'b_province' => $billing_address->province,
                            'b_district' => $billing_address->state,
                            'b_area' => $billing_address->area,
                            'b_additional_address' => $billing_address->additional_address,
                            'admin_email' => env('MAIL_FROM_ADDRESS'),
                            'b_zip' => $billing_address->zip,
                            'vat_amount' => round($vatAmount) ?? 0,
                            'guest_ref_id'=>$sessionData['randomRefId']
                        ];
                        
                        if ($order['area'] == null) {
                            return redirect()->route('Cprofile')->with('error', 'Your shipping address cannot be empty. Please update your shipping address');
                        }

                        $order_id = $this->order->fill($order);
                        $status = $this->order->save();


                        $product_main = Product::where('id', $product->product_id)->first();
                        $seller = [];
                        $temp = [
                            'order_id' => $this->order->id,
                            'product_id' => $product->product_id,
                            'product_name' => $product_main->name,
                            'price' => $product->price,
                            'qty' => $total_quantity,
                            'sub_total_price' => ($product->price * $total_quantity) + round($vatAmount),
                            'image' => $product_image,
                            'color' => $product->color,
                            'discount' => $discount,
                            'options' => $options,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                            'vatamountfield' => round($vatAmount) ?? 0
                        ];
                        $order_asset = OrderAsset::create($temp);
                        // dd($order_asset);
                        // ----------------------------------Seller Code----------------------------------------

                        $seller_order = [
                            'order_id' => $order_id->id,
                            'seller_id' => $product_main->seller_id ?? null,
                            'product_id' => $product_main->id,
                            'price' => $total_price,
                            'sub_total' => $total_price,
                            'user_id' => $user->id,
                            'seller_order_id' => $this->seller_order->id,
                            'qty' => $for_order['product_qty'],
                            'subtotal' => $total_price,
                            'total_discount' => $total_discount,
                            'total' => $total_price + $total_discount,
                            'vatAmount' => $order_id->vat_amount
                        ];
                        if ($product_main->seller_id != null) {
                            $this->seller_order->fill($seller_order);
                            $this->seller_order->save();




                            $notification_data = [
                                'from_model' => get_class($user->getModel()),
                                'from_id' => $user->id,
                                'to_model' => ($product_main->seller_id) ? get_class(seller::getModel()) : get_class(User::getModel()),
                                'to_id' => $product_main->seller_id ?? User::first()->id,
                                'title' => 'New Order From ' . ucfirst($user->name),
                                'summary' => 'You Have New Order Request',
                                'url' => url('/seller/seller-order/' . $this->seller_order->id),
                                'is_read' => false,
                            ];

                            (new NotificationAction($notification_data))->store();
                        } else {
                            $notification_data = [
                                'from_model' => get_class($user->getModel()),
                                'from_id' => $user->id,
                                'to_model' => ($product_main->seller_id) ? get_class(seller::getModel()) : get_class(User::getModel()),
                                'to_id' => $product_main->seller_id ?? User::first()->id,
                                'title' => 'New Order From ' . ucfirst($user->name),
                                'summary' => 'You Have New Order Request',
                                'url' => url('/admin/view-order/' . $this->order->ref_id),
                                'is_read' => false,
                            ];

                            (new NotificationAction($notification_data))->store();
                        }

                        $discountpercent = $total_discount * 100 / $total_price;
                        $discount_percent = $discountpercent . '%';

                        $product_seller_order = [
                            'order_id' => $order_id->id,
                            'seller_id' => $product_main->seller_id ?? null,
                            'product_id' => $product_main->id,
                            'price' => $product->price,
                            'sub_total' => $total_price,
                            'user_id' => $user->id ?? 1,
                            'seller_order_id' => $this->seller_order->id,
                            'qty' => $for_order['product_qty'],
                            'subtotal' => $total_price,
                            'discount' => $total_discount / $for_order['product_qty'],
                            'image' => $product_image,
                            'discount_percent' => $discount_percent,
                            'total' => $fixed_price,
                            'total_discount' => $total_discount,
                        ];

                        if ($product_main->seller_id != null) {
                            $this->product_seller_order->fill($product_seller_order);
                            $this->product_seller_order->save();
                        }

                        // ----------------------------------Seller Code----------------------------------------

                        $admin = User::first();
                        $paymentData = (new PaymentHistoryFormData(
                            get_class($user->getModel()),
                            $user->id,
                            get_class($admin->getModel()),
                            $admin->id,
                            get_class($this->order->getModel()),
                            $this->order->id,
                            null,
                            null,
                            3,
                            'Refund Unpaid',
                            route('returnable.show', $this->refund->returnOrder->id),
                            'Refund Unpaid To the Amin',
                            false,
                            true
                        ))->getData();
                        if ($requestOriginalData['coupoon_code']) {
                            $coupon_data = CustomerCoupon::where('code', $requestOriginalData['coupon_code_name'])->first();
                            if (!$coupon_data) {
                                return redirect()->route('cart.index')->with('error', 'Something is Wrong !!!');
                            }

                            if ($coupon_data->is_for_same == 1) {
                                $coupon_data->update([
                                    'is_expired' => 1
                                ]);
                            } else {
                                CustomerAllUsedCoupon::create([
                                    'coupon_id' => $coupon_data->coupon_id,
                                    'customer_coupon_id' => $coupon_data->id,
                                    'customer_id' => $user->id,
                                    'coupon_code' => $coupon_data->code
                                ]);
                            }
                        }

                        DB::commit();
                        PaymentEvent::dispatch($paymentData);
                        EmailSetUp::sendMail(MessageSetupEnum::ORDER_PLACE, $data = $this->order, $user);
                        session()->forget('directOrder');
                        return redirect()->route('invoice.new', $this->order->id);
                        return redirect()->route('Corder')->with('error', 'Your Order Has been Created Successfully !!');
                    } catch (\Exception $ex) {
                        DB::rollBack();
                        return redirect()->route('cart.index')->with('error', 'Something is Wrong !!!');
                    }
                } catch (\Throwable $th) {
                    return redirect()->route('cart.checkout')->with('error', 'Something Went Wrong !!');
                }
            } else {
                return redirect()->route('index')->with('error', 'Something Went Wrong !!');
            }
        } catch (\Throwable $th) {
            return redirect()->route('index')->with('error', 'Something Went Wrong !!');
        }
    }

    public function hblSuccess(Request $request)
    {
        $orderData = session()->get('hblsessiondata');
        $user = auth()->guard('customer')->user();
        if (!$orderData || $orderData == null) {
            $request->session()->flash('error', 'Plz Select Item First !!');
            return redirect()->route('cart.checkout');
        }
        if (!$user || $user == null) {
            $request->session()->flash('error', 'Plz Login First !!');
            return redirect()->route('Clogin');
        }
        try {
            if ($request['payment'] === 'success') {
                DB::beginTransaction();
                try {
                    $order_id = $this->order->fill($orderData['order']);
                    $status = $this->order->save();
                    $seller = [];
                    $temp = [];
                    $seller_product = [];

                    foreach ($orderData['cart_item'] as $key => $product) {
                        $seller_product_id[] = $product->product_id;
                        $seller_product[] = [
                            'product' => [
                                'product_id' => $product->product_id,
                                'order_id' => $order_id->id,
                                'qty' => $product->qty,
                                'price' => $product->price,
                                'total' => $product->price,
                                'discount' => $product->discount,
                                'image' => $product->image,
                                'sub_total_price' => $product->sub_total_price,
                                'vatAmount' => $product->vatamountfield
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
                            'image' => $product->image,
                            'discount' => $product->discount,
                            'options' => $product->options,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                            'vatamountfield' => $product->vatamountfield ?? 0
                        ];
                    }
                    $this->order_asset->insert($temp);

                    // ----------------------------------Seller Code----------------------------------------                

                    $this->seller_order_action->sellerOrder($seller_product_id, $this->order, $temp, $seller_product);

                    // ----------------------------------Seller Code----------------------------------------

                    foreach ($orderData['cart_item'] as $item) {
                        CartAssets::where('id', $item->id)->delete();
                    }
                    $remainingCartItem = CartAssets::where('cart_id', $this->cart->id)->get();
                    if ($remainingCartItem->count() > 0) {
                        $total_cart_price = null;
                        $total_cart_qty = null;
                        $total_cart_discount = null;
                        foreach ($remainingCartItem as $remainingItem) {
                            $total_cart_price = $total_cart_price + $remainingItem->sub_total_price;
                            $total_cart_qty = $total_cart_qty + $remainingItem->qty;
                            $total_cart_discount = $total_cart_discount + $remainingItem->discount;
                        }
                        $data['total_price'] = $total_cart_price;
                        $data['total_qty'] = $total_cart_qty;
                        $data['total_discount'] = $total_cart_discount;
                        $data['ref_id'] = $this->order->ref_id;
                        $this->cart->fill($data);
                        $this->cart->save();
                    } else {
                        Cart::where('user_id', $user->id)->delete();
                    }
                    // dd($this->order->orderAssets);
                    EmailSetUp::sendMail(MessageSetupEnum::ORDER_PLACE, $data = $this->order, $user);

                    $admin = User::first();
                    $paymentData = (new PaymentHistoryFormData(
                        get_class(auth()->guard('customer')->user()->getModel()),
                        auth()->guard('customer')->user()->id,
                        get_class($admin->getModel()),
                        $admin->id,
                        get_class($this->order->getModel()),
                        $this->order->id,
                        null,
                        null,
                        3,
                        'Refund Paid',
                        route('returnable.show', $this->refund->returnOrder->id),
                        'Refund Paid To the Customer ',
                        false,
                        true
                    ))->getData();
                    PaymentEvent::dispatch($paymentData);
                    if ($request->coupoon_code) {
                        $coupon_data = CustomerCoupon::where('code', $request->coupon_code_name)->first();
                        if (!$coupon_data) {
                            $request->session()->flash('error', 'Something is Wrong !!!');
                            return redirect()->route('cart.index');
                        }

                        if ($coupon_data->is_for_same == 1) {
                            $coupon_data->update([
                                'is_expired' => 1
                            ]);
                        } else {
                            CustomerAllUsedCoupon::create([
                                'coupon_id' => $coupon_data->coupon_id,
                                'customer_coupon_id' => $coupon_data->id,
                                'customer_id' => $user->id,
                                'coupon_code' => $coupon_data->code
                            ]);
                        }
                    }

                    DB::commit();
                    $request->session()->forget('paypalsessiondata');
                    $request->session()->flash('success', 'Your Order Has been Created Successfully !!');
                    return redirect()->route('invoice', $this->order->id);
                    return redirect()->route('order.productDetails', $this->order->id);
                } catch (\Throwable $th) {
                    DB::rollBack();
                    return redirect()->route('cart.checkout')->with('error', 'Something Went Wrong !!');
                }
            } else {
                return redirect()->route('cart.checkout')->with('error', 'Something Went Wrong !!');
            }
        } catch (\Throwable $th) {
            return redirect()->route('cart.checkout')->with('error', 'Something Went Wrong !!');
        }
    }

    public function hblSuccessGuest(Request $request)
    {
        $orderData = session()->get('hblsessiondata');
        $user = New_Customer::where('id',63)->first();
        if (!$orderData || $orderData == null) {
            $request->session()->flash('error', 'Plz Select Item First !!');
            return redirect()->route('cart.checkout');
        }
        
        if (!$user || $user == null) {
            $request->session()->flash('error', 'Plz Login First !!');
            return redirect()->route('Clogin');
        }
        try {
            if ($request['payment'] === 'success') {
                DB::beginTransaction();
                try {
                    $order_id = $this->order->fill($orderData['order']);
                    $status = $this->order->save();
                    $seller = [];
                    $temp = [];
                    $seller_product = [];
                    foreach ($orderData['cart_item'] as $key => $product) {
                        $seller_product_id[] = $product['product_id'];
                        $seller_product[] = [
                            'product' => [
                                'product_id' => $product['product_id'],
                                'order_id' => $order_id->id,
                                'qty' => $product['qty'],
                                'price' => $product['price'],
                                'total' => $product['price'],
                                'discount' => $product['discount'],
                                'image' => $product['image'],
                                'sub_total_price' => $product['sub_total_price'],
                                'vatAmount' => $product['vatamountfield'] ?? 0
                            ],
                            'seller' => Product::where('id', $product['product_id'])->first()->seller->id ?? null,
                        ];
                        $temp[] = [
                            'order_id' => $this->order->id,
                            'product_id' => $product['product_id'],
                            'product_name' => $product['product_name'],
                            'price' => $product['price'] + $product['discount'],
                            'qty' => $product['qty'],
                            'sub_total_price' => $product['sub_total_price'],
                            'color' => $product['color'],
                            'image' => $product['image'],
                            'discount' => $product['discount'],
                            'options' => $product['options'],
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                            'vatamountfield' => $product['vatamountfield'] ?? 0
                        ];
                    }
                    $this->order_asset->insert($temp);
                    // ----------------------------------Seller Code----------------------------------------                

                    $this->seller_order_action->sellerOrderGuest($seller_product_id, $this->order, $temp, $seller_product);

                    // ----------------------------------Seller Code----------------------------------------
                    
                    
                    
                    // dd($this->order->orderAssets);
                    EmailSetUp::sendMail(MessageSetupEnum::ORDER_PLACE, $data = $this->order, $user);

                    $admin = User::first();
                    $paymentData = (new PaymentHistoryFormData(
                        get_class(New_Customer::where('id',63)->first()->getModel()),
                        New_Customer::where('id',63)->first()->id,
                        get_class($admin->getModel()),
                        $admin->id,
                        get_class($this->order->getModel()),
                        $this->order->id,
                        null,
                        null,
                        3,
                        'Refund Paid',
                        route('returnable.show', $this->refund->returnOrder->id),
                        'Refund Paid To the Customer ',
                        false,
                        true
                    ))->getData();
                    PaymentEvent::dispatch($paymentData);
                    if ($request->coupoon_code) {
                        $coupon_data = CustomerCoupon::where('code', $request->coupon_code_name)->first();
                        if (!$coupon_data) {
                            $request->session()->flash('error', 'Something is Wrong !!!');
                            return redirect()->route('cart.index');
                        }

                        if ($coupon_data->is_for_same == 1) {
                            $coupon_data->update([
                                'is_expired' => 1
                            ]);
                        } else {
                            CustomerAllUsedCoupon::create([
                                'coupon_id' => $coupon_data->coupon_id,
                                'customer_coupon_id' => $coupon_data->id,
                                'customer_id' => $user->id,
                                'coupon_code' => $coupon_data->code
                            ]);
                        }
                    }
                    DB::commit();
                    $request->session()->forget('hblsessiondata');
                    $request->session()->forget('guest_cart');
                    $request->session()->flash('success', 'Your Order Has been Created Successfully !!');
                    return redirect()->route('invoiceGuest', $this->order->id);
                    return redirect()->route('order.productDetails', $this->order->id);
                } catch (\Throwable $th) {
                    DB::rollBack();
                    return redirect()->route('cart.checkout')->with('error', 'Something Went Wrong !!');
                }
            } else {
                return redirect()->route('cart.checkout')->with('error', 'Something Went Wrong !!');
            }
        } catch (\Throwable $th) {
            return redirect()->route('cart.checkout')->with('error', 'Something Went Wrong !!');
        }
    }

    public function hblSuccessGuestDirect(Request $request)
    {
        $orderData = session()->get('hblsessiondata');
        $user = New_Customer::where('id',63)->first();
        if (!$orderData || $orderData == null) {
            $request->session()->flash('error', 'Plz Select Item First !!');
            return redirect()->route('cart.checkout');
        }
        dd($user);
        if (!$user || $user == null) {
            $request->session()->flash('error', 'Plz Login First !!');
            return redirect()->route('Clogin');
        }
        // try {
            if ($request['payment'] === 'success') {
                DB::beginTransaction();
                // try {
                    $order_id = $this->order->fill($orderData['order']);
                    $status = $this->order->save();
                    $seller = [];
                    $temp = [];
                    $seller_product = [];

                    foreach ($orderData['cart_item'] as $key => $product) {
                        $seller_product_id[] = $product->product_id;
                        $seller_product[] = [
                            'product' => [
                                'product_id' => $product->product_id,
                                'order_id' => $order_id->id,
                                'qty' => $product->qty,
                                'price' => $product->price,
                                'total' => $product->price,
                                'discount' => $product->discount,
                                'image' => $product->image,
                                'sub_total_price' => $product->sub_total_price,
                                'vatAmount' => $product->vatamountfield
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
                            'image' => $product->image,
                            'discount' => $product->discount,
                            'options' => $product->options,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                            'vatamountfield' => $product->vatamountfield ?? 0
                        ];
                    }
                    $this->order_asset->insert($temp);

                    // ----------------------------------Seller Code----------------------------------------                

                    $this->seller_order_action->sellerOrderGuest($seller_product_id, $this->order, $temp, $seller_product);

                    // ----------------------------------Seller Code----------------------------------------

                    foreach ($orderData['cart_item'] as $item) {
                        CartAssets::where('id', $item->id)->delete();
                    }
                    $remainingCartItem = CartAssets::where('cart_id', $this->cart->id)->get();
                    if ($remainingCartItem->count() > 0) {
                        $total_cart_price = null;
                        $total_cart_qty = null;
                        $total_cart_discount = null;
                        foreach ($remainingCartItem as $remainingItem) {
                            $total_cart_price = $total_cart_price + $remainingItem->sub_total_price;
                            $total_cart_qty = $total_cart_qty + $remainingItem->qty;
                            $total_cart_discount = $total_cart_discount + $remainingItem->discount;
                        }
                        $data['total_price'] = $total_cart_price;
                        $data['total_qty'] = $total_cart_qty;
                        $data['total_discount'] = $total_cart_discount;
                        $data['ref_id'] = $this->order->ref_id;
                        $this->cart->fill($data);
                        $this->cart->save();
                    } else {
                        Cart::where('user_id', $user->id)->delete();
                    }
                    // dd($this->order->orderAssets);
                    EmailSetUp::sendMail(MessageSetupEnum::ORDER_PLACE, $data = $this->order, $user);

                    $admin = User::first();
                    $paymentData = (new PaymentHistoryFormData(
                        get_class(New_Customer::where('id',63)->first()->getModel()),
                        New_Customer::where('id',63)->first()->id,
                        get_class($admin->getModel()),
                        $admin->id,
                        get_class($this->order->getModel()),
                        $this->order->id,
                        null,
                        null,
                        3,
                        'Refund Paid',
                        route('returnable.show', $this->refund->returnOrder->id),
                        'Refund Paid To the Customer ',
                        false,
                        true
                    ))->getData();
                    PaymentEvent::dispatch($paymentData);
                    if ($request->coupoon_code) {
                        $coupon_data = CustomerCoupon::where('code', $request->coupon_code_name)->first();
                        if (!$coupon_data) {
                            $request->session()->flash('error', 'Something is Wrong !!!');
                            return redirect()->route('cart.index');
                        }

                        if ($coupon_data->is_for_same == 1) {
                            $coupon_data->update([
                                'is_expired' => 1
                            ]);
                        } else {
                            CustomerAllUsedCoupon::create([
                                'coupon_id' => $coupon_data->coupon_id,
                                'customer_coupon_id' => $coupon_data->id,
                                'customer_id' => $user->id,
                                'coupon_code' => $coupon_data->code
                            ]);
                        }
                    }
                    dd('ok');
                    DB::commit();
                    $request->session()->forget('paypalsessiondata');
                    $request->session()->flash('success', 'Your Order Has been Created Successfully !!');
                    return redirect()->route('invoice', $this->order->id);
                    return redirect()->route('order.productDetails', $this->order->id);
                // } catch (\Throwable $th) {
                //     DB::rollBack();
                //     return redirect()->route('cart.checkout')->with('error', 'Something Went Wrong !!');
                // }
            } else {
                return redirect()->route('cart.checkout')->with('error', 'Something Went Wrong !!');
            }
        // } catch (\Throwable $th) {
        //     return redirect()->route('cart.checkout')->with('error', 'Something Went Wrong !!');
        // }
    }


    
}
