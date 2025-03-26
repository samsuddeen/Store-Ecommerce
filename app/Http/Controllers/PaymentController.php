<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Cart;
use App\Models\User;
use App\Models\Local;
use App\Models\Order;
use App\Models\Coupon;
use GuzzleHttp\Client;
use App\Models\Product;
use App\Models\Setting;
use App\Events\LogEvent;
use App\Models\District;
use App\Models\Location;
use App\Models\Province;
use App\Mail\OrderConfirm;
use App\Models\CartAssets;
use App\Models\OrderAsset;
use App\Helpers\EmailSetUp;
use App\Models\ProductSize;
use Illuminate\Support\Str;
use App\Events\PaymentEvent;
use App\Models\Admin\VatTax;
use App\Models\ProductImage;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use App\Models\DeliveryRoute;
use App\Models\Refund\Refund;
use Illuminate\Support\Carbon;
use App\Actions\Mail\MailSetup;
use App\Events\OrderPlacedEvent;
use App\Models\OrderTimeSetting;
use App\Models\seller as Seller;
use App\Models\UserBillingAddress;
use Illuminate\Support\Facades\DB;
use App\Models\UserShippingAddress;
use Illuminate\Support\Facades\Mail;
use App\Models\CustomerAllUsedCoupon;
use App\Data\Payment\PaymentMethodData;
use App\Models\Order\Seller\SellerOrder;
use App\Actions\Seller\SellerOrderAction;
use App\Data\Form\PaymentHistoryFormData;
use App\Enum\MessageSetup\MessageSetupEnum;
use App\Actions\MobilePdf\MobilePdfGenerate;
use App\Data\KhatiPayment\KhatiPaymentAction;
use App\Models\Order\Seller\ProductSellerOrder;
use App\Actions\Notification\NotificationAction;
use App\Data\KhatiPayment\KhaltiPaymentDirectLogin;
use App\Models\Admin\Coupon\Customer\CustomerCoupon;
use App\Models\New_Customer;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaymentController extends Controller
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
    public function coupon(Request $request)
    {
        $coupon = Coupon::where('coupon_code', $request->coupon_code)->first();
        if ($coupon == null) {
            return response()->json([
                'not_found' => "Coupon Code Not Found",
            ]);
        }
        $total_price = Cart::where('user_id', auth()->guard('customer')->user()->id)->value('total_price');
        $discount_type = $coupon->is_percentage;
        $discount = $coupon->discount;
        if ($discount_type == 'yes') {
            $new_total_price = (int) round($total_price - (($total_price * $discount) / 100));
        } else {
            $currency_id = $coupon->currency_id;
            if ($currency_id == 1) {
                $new_total_price = $total_price - $discount;
                if ($new_total_price < 10) {
                    return response()->json([
                        "low_amount" => 'Buy More product to get discount',
                    ]);
                }
            }
            // Nepali currency ko lagi matra vako xa
        }
        return response()->json([
            'new_total_price' => $new_total_price,
            'coupon_code' => $request->coupon_code,
        ]);
    }

    public function couponOne(Request $request)
    {
        $coupon = Coupon::where('coupon_code', $request->coupon_code)->first();
        if ($coupon == null) {
            return response()->json([
                'not_found' => "Coupon Code Not Found",
            ]);
        }
        $product = Product::where('id', $request->product_id)->first();
        foreach ($product->stocks as $key => $stock) {
            if ($key == 0) {
                if ($stock->special_price) {
                    $price = $stock->special_price;
                } else {
                    $price = $stock->price;
                }
            }
        }
        $total_price = $price;
        $discount_type = $coupon->is_percentage;
        $discount = $coupon->discount;
        if ($discount_type == 'yes') {
            $new_total_price = (int) round($total_price - (($total_price * $discount) / 100));
        } else {
            $currency_id = $coupon->currency_id;
            if ($currency_id == 1) {
                $new_total_price = $total_price - $discount;
                if ($new_total_price < 10) {
                    return response()->json([
                        "low_amount" => 'Buy More product to get discount',
                    ]);
                }
            }
            // Nepali currency ko lagi matra vako xa
        }
        return response()->json([
            'new_total_price' => $new_total_price,
            'coupon_code' => $request->coupon_code,
        ]);
    }



    public function success(Request $request)
    {
        $refId = $request->refId;
        if ($request->oid && $request->amt && $request->refId) {
            $url = "https://uat.esewa.com.np/epay/transrec";
            $data = [
                'amt' => $request->amt,
                'rid' => $request->refId,
                'pid' => $request->refId,
                'scd' => 'epay_payment'
            ];

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
            curl_close($curl);
            $response_code = $this->get_response('response_code', $response);
            if (trim($response_code) == 'Success') {
                $cart = Cart::where('user_id', auth()->guard('customer')->user()->id)->first();
                $total_quantity = $cart->total_qty;
                $total_price = $cart->total_price;
                $total_discount = $cart->total_discount;

                // update order values according to coupon
                $coupon = Coupon::where('coupon_code', $request->coupoon_code)->first();
                if ($request->coupon_code) {
                    $discount_type = $coupon->is_percentage;
                    $discount = $coupon->discount;
                    if ($discount_type == 'yes') {
                        $coupon_discount = (int) round(($total_price * $discount) / 100);
                        $total_price = (int) round($total_price - (($total_price * $discount) / 100));
                        $total_price = $total_price + $data['shipping_charge'];
                    } else {
                        $currency_id = $coupon->currency_id;
                        if ($currency_id == 1) {
                            $coupon_discount = $discount;
                            $total_price = $total_price - $discount;
                            $total_price = $total_price + $data['shipping_charge'];
                        }

                        // Nepali currency ko lagi matra vako xa
                    }

                    $total_discount = $total_discount + $coupon_discount;
                } else {
                    $total_price = $total_price + $data['shipping_charge'];
                }

                // create order
                $variable = ['user_id' => auth()->guard('customer')->user()->id, 'shipping_charge' => $data['shipping_charge'], 'total_quantity' => $total_quantity, 'total_price' => $total_price, 'total_discount' => $total_discount, 'ref_id' => $refId, 'pending' => 1, 'payment_status' => 1];
                $final_data = array_merge($data, $variable);
                Order::create($final_data);

                // create order assets
                $order_assets = Order::where('ref_id', $refId)->first();
                $order_id = $order_assets->id;
                $cart_assets = CartAssets::where('cart_id', $cart->id)->get();
                foreach ($cart_assets as $cart_asset) {
                    $product_id = $cart_asset->product_id;
                    $product_name = $cart_asset->product_name;
                    $price = $cart_asset->price;
                    $qty = $cart_asset->qty;
                    $sub_total_price = $cart_asset->sub_total_price;
                    $color = $cart_asset->color;
                    $discount = $cart_asset->discount;

                    // update stock
                    $product_quantity = ProductStock::where(['product_id' => $product_id])->value('quantity');
                    $latest_quantity = $product_quantity - $qty;
                    $product = ProductStock::where(['product_id' => $product_id])->first();
                    $product->update(['quantity' => $latest_quantity]);

                    // calculation of product total sell and update it to main product table
                    $product_total_sell = Product::where('id', $product_id)->value('total_sell');
                    $new_total_sell = $product_total_sell + $qty;
                    $raw_product = Product::where('id', $product_id)->first();
                    $raw_product->update(['total_sell' => $new_total_sell]);


                    OrderAsset::create(['order_id' => $order_id, 'product_id' => $product_id, 'product_name' => $product_name, 'price' => $price, 'qty' => $qty, 'sub_total_price' => $sub_total_price, 'color' => $color, 'discount' => $discount]);
                }

                // create order Stock Here


                // remove cart
                $cart->delete();

                // mail to customer
                // $pdf = PDF::loadView('emails.customer.customercheckoutmailpdf', compact('orders', 'payment', 'setting'));
                $info = ['ref_id' => $refId, 'total_price' => $total_price, 'payment_method' => 'khalti'];
                Mail::to($data['email'])->send(new OrderConfirm($data, $info));
                return response()->json([
                    'redirectTo' => "Corder",
                    'message' => "your order is placed",
                ]);
            } else {
                return response()->json([
                    'message' => "Some error",
                ]);
            }
        }
    }

    public function failure()
    {
        return redirect()->back()->with('error', 'something error');
    }

    public function khaltiOrder(Request $request)
    {
        $refId = $request->oid;
        $args = http_build_query(array(
            'token' => $request->token,
            // 'amount'  => $fixed_price,
            'amount'  => $request->amount,
        ));

        $url = "https://khalti.com/api/v2/payment/verify/";

        # Make the call using API.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $headers = ['Authorization: Key  test_secret_key_17506ac3d2984e37a593236233b598f8'];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);

        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // $res = json_decode($response, true); //decode the response
        if ($status_code == 200) {
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
                    'ref_id' => $refId,
                    'shipping_charge' => $shipping_charge,
                    'total_discount' => $total_discount,
                    'payment_status' => '1',
                    'merchant_name' => null,
                    'payment_with' => 'Khalti',
                    'payment_date' => date('Y-m-d'),
                    // 'transaction_ref_id' => $request->refId,
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
                    'b_zip' => $billing_address->zip
                ];
                $this->order->fill($order);
                $status = $this->order->save();
                $seller = [];
                $temp = [];
                $seller_product = [];
                foreach ($cart_item as $key => $product) {
                    $seller_product_id[] = $product->product_id;
                    $seller_product[] = [
                        'product' => [
                            'order_id' => $this->order->id,
                            'product_id' => $product->product_id,
                            'image' => $product->image,
                            'qty' => $product->qty,
                            'price' => $product->price,
                            'total' => $product->price,
                            'discount' => $product->discount,
                            'sub_total_price' => $product->sub_total_price
                        ],
                        'seller' => Product::where('id', $product->product_id)->first()->seller->id ?? null,
                    ];
                    $temp[] = [
                        'order_id' => $this->order->id,
                        'product_id' => $product->product_id,
                        'product_name' => $product->product_name,
                        'price' => $product->price,
                        'qty' => $product->qty,
                        'sub_total_price' => $product->sub_total_price,
                        'color' => $product->color,
                        'image' => $product->image,
                        'discount' => $product->discount,
                        'options' => $product->options,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];
                }

                $this->order_asset->insert($temp);
                // ----------------------------------Seller Code----------------------------------------

                $this->seller_order_action->sellerOrder($seller_product_id, $this->order, $temp, $seller_product);

                // ----------------------------------Seller Code----------------------------------------

                foreach ($cart_item as $item) {
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
                    $this->cart->fill($data);
                    $this->cart->save();
                } else {
                    Cart::where('user_id', $user->id)->delete();
                }
                DB::commit();

                $filters = [
                    'title' => MessageSetupEnum::ORDER_PLACE,
                ];
                (new MailSetup($filters))->setToFile();

                event(new OrderPlacedEvent($this->order, $user));

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
                    1,
                    'Refund Paid',
                    route('returnable.show', $this->refund->returnOrder->id),
                    'Refund Paid To the Customer ',
                    false,
                    true
                ))->getData();
                PaymentEvent::dispatch($paymentData);

                $request->session()->forget('cart_order_item');
                $request->session()->flash('success', 'Your Order Has been Created Successfully !!');
                $response = [
                    'error' => false,
                    'data' => $this->order,
                    'msg' => 'Success !!',
                    'order_id' => $this->order->ref_id,
                    'url' => route('invoice', $this->order->id)
                ];
                return response()->json($response, 200);
            } catch (\Exception $ex) {
                DB::rollBack();
                $request->session()->flash('error', $ex->getMessage());
                return back();
            }
        } else {
            return response()->json([
                'error' => true,
                'data' => null,
                'msg' => "Some error",
            ], 200);
        }
    }

    public function khaltiCallBackAction(Request $request)
    {
        $data = session()->get('khalti1-order-data');
        // dd($data);
        $refId = '1234';
        $args = http_build_query(array(
            "pidx" => $request->pidx
        ));
        $url = "https://khalti.com/api/v2/epayment/lookup/";
        # Make the call using API.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $headers = ['Authorization: Key live_secret_key_28c3653d1899474790859e2dc02a67a6'];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        // dd($status_code);
        curl_close($ch);
        $ref_id = 'OD' . rand(100000, 999999);
        // $res = json_decode($response, true); //decode the response
        if ($status_code == 200) {
            DB::beginTransaction();
            try {
                $order = [
                    'user_id' => $data['user']->id,
                    'aff_id' => $request->pidx,
                    'total_quantity' => $data['total_quantity'],
                    'total_price' => $data['fixed_price'],
                    'coupon_name' => $data['coupoun__name'],
                    'coupon_code' => $data['coupoun_code_name'],
                    'coupon_discount_price' => $data['coupon_discount_price'],
                    'ref_id' => $ref_id,
                    'shipping_charge' => $data['shipping_charge'],
                    'total_discount' => $data['total_discount'],
                    'payment_status' => '1',
                    'merchant_name' => null,
                    'payment_with' => 'Khalti',
                    'payment_date' => date('Y-m-d'),
                    // 'transaction_ref_id' => $request->refId,
                    'name' => $data['shipping_address']->name,
                    'email' => $data['shipping_address']->email,
                    'phone' => $data['shipping_address']->phone,
                    'province' => $data['shipping_address']->province,
                    'district' => $data['shipping_address']->district,
                    'area' => $data['shipping_address']->area,
                    'additional_address' => $data['shipping_address']->additional_address,
                    'zip' => $data['shipping_address']->zip,
                    'b_name' => $data['billing_address']->name,
                    'b_email' => $data['billing_address']->email,
                    'b_phone' => $data['billing_address']->phone,
                    'b_province' => $data['billing_address']->province,
                    'b_district' => $data['billing_address']->district,
                    'b_area' => $data['billing_address']->area,
                    'b_additional_address' => $data['billing_address']->additional_address,
                    'b_zip' => $data['billing_address']->zip
                ];
                $this->order->fill($order);
                $status = $this->order->save();
                $seller = [];
                $temp = [];
                $seller_product = [];
                foreach ($data['cart_item'] as $key => $product) {
                    $seller_product_id[] = $product->product_id;
                    $seller_product[] = [
                        'product' => [
                            'order_id' => $this->order->id,
                            'product_id' => $product->product_id,
                            'image' => $product->image,
                            'qty' => $product->qty,
                            'price' => $product->price,
                            'total' => $product->price,
                            'discount' => $product->discount,
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
                    ];
                }

                $this->order_asset->insert($temp);
                // ----------------------------------Seller Code----------------------------------------

                $this->seller_order_action->sellerOrder($seller_product_id, $this->order, $temp, $seller_product);

                // ----------------------------------Seller Code----------------------------------------

                foreach ($data['cart_item'] as $item) {
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
                    $this->cart->fill($data);
                    $this->cart->save();
                } else {
                    Cart::where('user_id', $data['user']->id)->delete();
                }
                if ($data['coupoun_code_name'] != null) {
                    $coupon_data = CustomerCoupon::where('code', $data['coupoun_code_name'])->first();
                    if (!$coupon_data) {
                        $request->session()->flash('error', 'Something is Wrong !!!');
                        return redirect()->route('index');
                    }


                    if ($coupon_data->is_for_same == 1) {
                        $coupon_data->update([
                            'is_expired' => 1
                        ]);
                    } else {
                        CustomerAllUsedCoupon::create([
                            'coupon_id' => $coupon_data->coupon_id,
                            'customer_coupon_id' => $coupon_data->id,
                            'customer_id' => $data['user']->id,
                            'coupon_code' => $coupon_data->code
                        ]);
                    }
                }
                DB::commit();

                $filters = [
                    'title' => MessageSetupEnum::ORDER_PLACE,
                ];
                (new MailSetup($filters))->setToFile();

                event(new OrderPlacedEvent($this->order, $data['user']));

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
                    1,
                    'Refund Paid',
                    route('returnable.show', $this->refund->returnOrder->id),
                    'Refund Paid To the Customer ',
                    false,
                    true
                ))->getData();
                PaymentEvent::dispatch($paymentData);

                $request->session()->forget('cart_order_item');
                $request->session()->flash('success', 'Your Order Has been Created Successfully !!');
                return redirect()->route('invoice', $this->order->id);
                // $response = [
                //     'error' => false,
                //     'data' => $this->order,
                //     'msg' => 'Success !!',
                //     'order_id' => $this->order->ref_id,
                //     'url'=>route('invoice',$this->order->id)
                // ];
                // return response()->json($response, 200);
            } catch (\Exception $ex) {
                DB::rollBack();
                $request->session()->flash('error', 'Something Went Wrong !!');
                return redirect()->route('index');
            }
        } else {
            $request->session()->flash('error', 'Something Went Wrong !!');
            return redirect()->route('index');
        }
    }

    public function EsewaCallBackAction(Request $request)
    {
        // dd('ok esewa');
        if ($request->q == 'su') {
            $data = session()->get('khalti1-order-data');
            $refId = '1234';

            $url = "https://esewa.com.np/epay/transrec";
            $data1 = [
                'amt' => (int)$data['fixed_price'],
                'rid' => $request->refId,
                'pid' => $request->oid,
                'scd' => 'NP-ES-ULTRASOFT'
            ];

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data1);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
            curl_close($curl);
            $ref_id = 'OD' . rand(100000, 999999);
            if (strpos($response, "Success") !== false) {
                DB::beginTransaction();
                try {
                    $order = [
                        'user_id' => $data['user']->id,
                        'aff_id' => $request->pidx,
                        'total_quantity' => $data['total_quantity'],
                        'total_price' => $data['fixed_price'],
                        'coupon_name' => $data['coupoun__name'],
                        'coupon_code' => $data['coupoun_code_name'],
                        'coupon_discount_price' => $data['coupon_discount_price'],
                        'ref_id' => $ref_id,
                        'shipping_charge' => $data['shipping_charge'],
                        'total_discount' => $data['total_discount'],
                        'payment_status' => '1',
                        'merchant_name' => null,
                        'payment_with' => 'Esewa',
                        'payment_date' => date('Y-m-d'),
                        // 'transaction_ref_id' => $request->refId,
                        'name' => $data['shipping_address']->name,
                        'email' => $data['shipping_address']->email,
                        'phone' => $data['shipping_address']->phone,
                        'province' => $data['shipping_address']->province,
                        'district' => $data['shipping_address']->district,
                        'area' => $data['shipping_address']->area,
                        'additional_address' => $data['shipping_address']->additional_address,
                        'zip' => $data['shipping_address']->zip,
                        'b_name' => $data['billing_address']->name,
                        'b_email' => $data['billing_address']->email,
                        'b_phone' => $data['billing_address']->phone,
                        'b_province' => $data['billing_address']->province,
                        'b_district' => $data['billing_address']->district,
                        'b_area' => $data['billing_address']->area,
                        'b_additional_address' => $data['billing_address']->additional_address,
                        'b_zip' => $data['billing_address']->zip
                    ];
                    $this->order->fill($order);
                    $status = $this->order->save();
                    $seller = [];
                    $temp = [];
                    $seller_product = [];
                    foreach ($data['cart_item'] as $key => $product) {
                        $seller_product_id[] = $product->product_id;
                        $seller_product[] = [
                            'product' => [
                                'order_id' => $this->order->id,
                                'product_id' => $product->product_id,
                                'image' => $product->image,
                                'qty' => $product->qty,
                                'price' => $product->price,
                                'total' => $product->price,
                                'discount' => $product->discount,
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
                        ];
                    }

                    $this->order_asset->insert($temp);
                    // ----------------------------------Seller Code----------------------------------------

                    $this->seller_order_action->sellerOrder($seller_product_id, $this->order, $temp, $seller_product);

                    // ----------------------------------Seller Code----------------------------------------

                    foreach ($data['cart_item'] as $item) {
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
                        $this->cart->fill($data);
                        $this->cart->save();
                    } else {
                        Cart::where('user_id', $data['user']->id)->delete();
                    }

                    if ($data['coupoun_code_name'] != null) {
                        $coupon_data = CustomerCoupon::where('code', $data['coupoun_code_name'])->first();
                        if (!$coupon_data) {
                            $request->session()->flash('error', 'Something is Wrong !!!');
                            return redirect()->route('index');
                        }

                        if ($coupon_data->is_for_same == 1) {
                            $coupon_data->update([
                                'is_expired' => 1
                            ]);
                        } else {
                            CustomerAllUsedCoupon::create([
                                'coupon_id' => $coupon_data->coupon_id,
                                'customer_coupon_id' => $coupon_data->id,
                                'customer_id' => $data['user']->id,
                                'coupon_code' => $coupon_data->code
                            ]);
                        }
                    }
                    DB::commit();

                    $filters = [
                        'title' => MessageSetupEnum::ORDER_PLACE,
                    ];
                    (new MailSetup($filters))->setToFile();

                    event(new OrderPlacedEvent($this->order, $data['user']));

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
                        1,
                        'Refund Paid',
                        route('returnable.show', $this->refund->returnOrder->id),
                        'Refund Paid To the Customer ',
                        false,
                        true
                    ))->getData();
                    PaymentEvent::dispatch($paymentData);

                    $request->session()->forget('cart_order_item');
                    $request->session()->flash('success', 'Your Order Has been Created Successfully !!');
                    return redirect()->route('invoice', $this->order->id);
                    // $response = [
                    //     'error' => false,
                    //     'data' => $this->order,
                    //     'msg' => 'Success !!',
                    //     'order_id' => $this->order->ref_id,
                    //     'url'=>route('invoice',$this->order->id)
                    // ];
                    // return response()->json($response, 200);
                } catch (\Exception $ex) {
                    DB::rollBack();
                    $request->session()->flash('error', 'Something Went Wrong !!');
                    return redirect()->route('index');
                }
            } else {
                $request->session()->flash('error', 'Something Went Wrong !!');
                return redirect()->route('index');
            }
        } else {
            $request->session()->flash('error', 'Something Went Wrong !!');
            return redirect()->route('index');
        }
    }



    public function cashOnDelivery(Request $request)
    {
        // dd($request->all());
        // dd($request->payment);

        $today = Carbon::now()->format('l');
        $order_timing_check = OrderTimeSetting::where('day', $today)->exists();
        if ($order_timing_check) {
            $order_timing = OrderTimeSetting::where('day', $today)->first();
            if ($order_timing->day_off == true) {
                $request->session()->flash('error', 'You cannot place order today because it is our day off !!');
                return back();
            }
            $currentTime = Carbon::now()->format('H:i');
            if ($currentTime > $order_timing->end_time || $currentTime < $order_timing->start_time) {
                $request->session()->flash('error', 'Please place your order between ' . $order_timing->start_time . ' and ' . $order_timing->end_time);
                return back();
            }
            $default_shipping_charge = Setting::where('key', 'default_shipping_charge')->pluck('value')->first();
            $default_material_charge = Setting::where('key', 'materials_price')->pluck('value')->first();

            if ($request->payment === 'khalti') {
                $amount = (new KhatiPaymentAction($request))->khaltiPayment();

                try {
                    $data = [
                        "return_url" => route('khalti-callback-return'),
                        "website_url" => "https://jhigu.store/",
                        "amount" => (int)$amount * 100,
                        "purchase_order_id" => "test12",
                        "purchase_order_name" => "Test Product"
                    ];
                    $client = new Client([
                        // 'base_uri' => 'https://a.khalti.com/',
                        'headers' => [
                            'Authorization' => 'Key ' . 'live_secret_key_28c3653d1899474790859e2dc02a67a6',
                            'Content-Type' => 'application/json',
                            'Accept' => 'application/json',
                        ],
                    ]);
                    $response = $client->post('https://khalti.com/api/v2/epayment/initiate/', [
                        'json' => $data,
                    ]);
                    return redirect(json_decode($response->getBody()->getContents(), true)['payment_url']);
                } catch (\Throwable $th) {
                    $request->session()->flash('error', 'Something Went Wrong With Khalti Payment !!');
                    return redirect()->back();
                }
                // return $response;
                // dd(json_decode($response->getBody()->getContents(), true)['payment_url']);
            } elseif ($request->payment === 'COD') {
                $user = auth()->guard('customer')->user();

                if ($request->shipping_address === null) {
                    $request->session()->flash('error', 'Plz Select Shipping Address !!');
                    return redirect()->route('cart.index');
                }

                if ($request->session()->get('cart_order_item') === null) {
                    $request->session()->flash('error', 'Plz Select Item First !!');
                    return redirect()->route('cart.index');
                }
                $request->validate([
                    'shipping_address' => 'required|exists:user_shipping_addresses,id',
                    'billing_address' => 'nullable|exists:user_billing_addresses,id',
                    'same_address' => 'nullable:in:0,1'
                ]);

                if ($request->billing_address == null) {
                    if ($request->same_address == null) {
                        $request->session()->flash('error', 'Plz Select Billing Or Same As Shipping Fields');
                        return redirect()->route('cart.index');
                    }
                }

                if (!$user) {
                    $request->session()->flash('error', 'Plz Login First');
                    return redirect()->route('cart.index');
                }

                if ($request->coupoon_code != null) {
                    $coupon_discount_price = $request->coupoon_code;
                    $coupoun_code_name = $request->coupon_code_name;
                    $coupoun__name = $request->coupon_name;
                } else {
                    $coupon_discount_price = 0;
                    $coupoun_code_name = null;
                    $coupoun__name = null;
                }

                $shipping_id = $request->shipping_address;
                $billing_id = $request->billing_address;
                $shipping_address = UserShippingAddress::where('id', $shipping_id)->where('user_id', $user->id)->first();
                if ($request->same_address === null || $request->same_address <= 0 || $request->same_address === 0) {
                    if ($request->billing_address == null) {
                        $request->session()->flash('error', 'Plz Select Billing Address');
                        return redirect()->route('cart.index');
                    }
                    $billing_address = UserBillingAddress::where('id', $request->billing_address)->where('user_id', $user->id)->first();
                } else {
                    $billing_address = $shipping_address;
                }
                if (!$shipping_address) {
                    $request->session()->flash('error', 'Shipping Address Is Not Valid');
                    return redirect()->route('cart.index');
                }
                $getLocation = Location::where('title', 'LIKE', '%' . $shipping_address->additional_address . '%')->first();
                if ($getLocation && $getLocation->deliveryRoute->charge != null) {
                    $shipping_charge = $getLocation->deliveryRoute->charge;
                } else {
                    $shipping_charge = $default_shipping_charge;
                }



                if (!$billing_address) {
                    $request->session()->flash('error', 'Billing Address Is Not Valid');
                    return redirect()->route('cart.index');
                }

                $order_item = session()->get('cart_order_item');
                $this->cart = Cart::where('user_id', $user->id)->first();

                if (!$this->cart) {
                    $request->session()->flash('error', 'Your Cart Is Empty !! Plz Add Some Project');
                    return redirect()->route('index');
                }

                $cart_item = [];
                foreach ($order_item['items'] as $key => $item) {
                    $cart_item[] = CartAssets::where('id', $key)->where('cart_id', $this->cart->id)->first();
                }
                $total_quantity = null;
                $total_price = null;
                $total_discount = null;
                $vatamountTotal = 0;
                foreach ($cart_item as $cItem) {
                    $vatamountTotal = $vatamountTotal + $cItem->vatamountfield;
                    $total_quantity = $total_quantity + $cItem->qty;
                    $total_price = $total_price + $cItem->sub_total_price;
                    $total_discount = $total_discount + $cItem->discount * $cItem->qty;
                }
                $mimimum_order_cost = Setting::where('key', 'minimum_order_price')->pluck('value')->first();


                if ($user->wholeseller) {
                    if ($total_price < $mimimum_order_cost) {
                        $request->session()->flash('error', 'The minimum order amount should be Rs. ' . $mimimum_order_cost . ' !!');
                        return back();
                    }
                    $wholeSellerMinimumPrice = Setting::where('key', 'minimum_order_price')->first()->value;
                    $wholeSellerShippingCharge = Setting::where('key', 'wholseller_shipping_charge')->first()->value;
                    if ($total_price >= $wholeSellerMinimumPrice) {
                        $shipping_charge = 0;
                    } else {
                        $shipping_charge = $wholeSellerShippingCharge;
                    }
                } else {
                    // $wholeSellecustomerShippingChargePerKg = Setting::where('key', 'shippping_charge_per_kg')->first()->value;
                    // $productId = collect($cart_item)->pluck('product_id')->toArray();
                    // $packetWeight = Product::whereIn('id', $productId)->get()->sum('package_weight');
                    // $shipping_charge = (int)$wholeSellecustomerShippingChargePerKg * (int)$packetWeight;
                    $shipping_charge=0;
                    foreach($cart_item as $item){
                        $product=Product::where('id',$item['product_id'])->first();
                        $shipping_chargeNew=$shipping_charge+($product->shipping_charge * $item['qty']);
                    }
                }


                $fixed_price = $total_price + $shipping_charge + $default_material_charge - (int)$coupon_discount_price;

                $ref_id = 'OD' . rand(100000, 999999);
                // ------------------Vat Calculation--------------------

                // $vatAmount=0;
                // $vatAmount=collect($cart_item)->each(function($cartItemValue)
                // {
                //     $product=Product::where('id',$cartItemValue->product_id)->first();
                //     if($product->vat_percent==0 && $product->vat_percent!=1)
                //     {

                //     }
                //     dd($product);
                // });
                // ------------------Vat Calculation--------------------
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
                    'payment_with' => 'Cash On Delivery',
                    'payment_date' => date('Y-m-d'),
                    // 'transaction_ref_id' => $request->refId,
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
                    'admin_email' => env('MAIL_FROM_ADDRESS'),
                    'b_zip' => $billing_address->zip,
                    'vat_amount' => $vatamountTotal ?? 0

                ];
                

                if ($order['area'] == null) {
                    $request->session()->flash('error', 'Your shipping  address cannot be empty. Please update your address to continue');
                    return redirect()->route('index');
                }

                $order_id = $this->order->fill($order);
                $status = $this->order->save();
                $seller = [];
                $temp = [];
                $seller_product = [];

                foreach ($cart_item as $key => $product) {
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

                foreach ($cart_item as $item) {
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
                $request->session()->forget('cart_order_item');
                $request->session()->flash('success', 'Your Order Has been Created Successfully !!');

                return redirect()->route('invoice', $this->order->id);
                // return redirect()->route('order.productDetails', $this->order->id);
                } catch (\Exception $ex) {
                    DB::rollBack();
                    $request->session()->flash('error', 'Something is Wrong !!!');
                    return redirect()->route('cart.index');
                }



            } elseif ($request->payment === 'QRCode') {

                // dd($request->payment);

                if ($request->hasFile('payment_proof')) {
                 
                    $paymentProofFile = $request->file('payment_proof');
                    $paymentProofPath = $paymentProofFile->storeAs('payment_proofs', time() . '.' . $paymentProofFile->getClientOriginalExtension(), 'public');
                } else {
                  
                    $paymentProofPath = null;
                }

                $user = auth()->guard('customer')->user();

                if ($request->shipping_address === null) {
                    $request->session()->flash('error', 'Plz Select Shipping Address !!');
                    return redirect()->route('cart.index');
                }

                if ($request->session()->get('cart_order_item') === null) {
                    $request->session()->flash('error', 'Plz Select Item First !!');
                    return redirect()->route('cart.index');
                }
                $request->validate([
                    'shipping_address' => 'required|exists:user_shipping_addresses,id',
                    'billing_address' => 'nullable|exists:user_billing_addresses,id',
                    'same_address' => 'nullable:in:0,1',
                    'payment_proof'=> 'required'
                ]);

                if ($request->billing_address == null) {
                    if ($request->same_address == null) {
                        $request->session()->flash('error', 'Plz Select Billing Or Same As Shipping Fields');
                        return redirect()->route('cart.index');
                    }
                }

                if (!$user) {
                    $request->session()->flash('error', 'Plz Login First');
                    return redirect()->route('cart.index');
                }

                if ($request->coupoon_code != null) {
                    $coupon_discount_price = $request->coupoon_code;
                    $coupoun_code_name = $request->coupon_code_name;
                    $coupoun__name = $request->coupon_name;
                } else {
                    $coupon_discount_price = 0;
                    $coupoun_code_name = null;
                    $coupoun__name = null;
                }

                $shipping_id = $request->shipping_address;
                $billing_id = $request->billing_address;
                $shipping_address = UserShippingAddress::where('id', $shipping_id)->where('user_id', $user->id)->first();
                if ($request->same_address === null || $request->same_address <= 0 || $request->same_address === 0) {
                    if ($request->billing_address == null) {
                        $request->session()->flash('error', 'Plz Select Billing Address');
                        return redirect()->route('cart.index');
                    }
                    $billing_address = UserBillingAddress::where('id', $request->billing_address)->where('user_id', $user->id)->first();
                } else {
                    $billing_address = $shipping_address;
                }
                if (!$shipping_address) {
                    $request->session()->flash('error', 'Shipping Address Is Not Valid');
                    return redirect()->route('cart.index');
                }
                $getLocation = Location::where('title', 'LIKE', '%' . $shipping_address->additional_address . '%')->first();
                if ($getLocation && $getLocation->deliveryRoute->charge != null) {
                    $shipping_charge = $getLocation->deliveryRoute->charge;
                } else {
                    $shipping_charge = $default_shipping_charge;
                }



                if (!$billing_address) {
                    $request->session()->flash('error', 'Billing Address Is Not Valid');
                    return redirect()->route('cart.index');
                }

                $order_item = session()->get('cart_order_item');
                $this->cart = Cart::where('user_id', $user->id)->first();

                if (!$this->cart) {
                    $request->session()->flash('error', 'Your Cart Is Empty !! Plz Add Some Project');
                    return redirect()->route('index');
                }

                $cart_item = [];
                foreach ($order_item['items'] as $key => $item) {
                    $cart_item[] = CartAssets::where('id', $key)->where('cart_id', $this->cart->id)->first();
                }
                $total_quantity = null;
                $total_price = null;
                $total_discount = null;
                $vatamountTotal = 0;
                foreach ($cart_item as $cItem) {
                    $vatamountTotal = $vatamountTotal + $cItem->vatamountfield;
                    $total_quantity = $total_quantity + $cItem->qty;
                    $total_price = $total_price + $cItem->sub_total_price;
                    $total_discount = $total_discount + $cItem->discount * $cItem->qty;
                }
                $mimimum_order_cost = Setting::where('key', 'minimum_order_price')->pluck('value')->first();


                if ($user->wholeseller) {
                    if ($total_price < $mimimum_order_cost) {
                        $request->session()->flash('error', 'The minimum order amount should be Rs. ' . $mimimum_order_cost . ' !!');
                        return back();
                    }
                    $wholeSellerMinimumPrice = Setting::where('key', 'minimum_order_price')->first()->value;
                    $wholeSellerShippingCharge = Setting::where('key', 'wholseller_shipping_charge')->first()->value;
                    if ($total_price >= $wholeSellerMinimumPrice) {
                        $shipping_charge = 0;
                    } else {
                        $shipping_charge = $wholeSellerShippingCharge;
                    }
                } else {
                  
                    $shipping_charge=0;
                    foreach($cart_item as $item){
                        $product=Product::where('id',$item['product_id'])->first();
                        $shipping_chargeNew=$shipping_charge+($product->shipping_charge * $item['qty']);
                    }
                }


                $fixed_price = $total_price + $shipping_charge + $default_material_charge - (int)$coupon_discount_price;

                $ref_id = 'OD' . rand(100000, 999999);
             
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
                    'payment_status' => '1',
                    'merchant_name' => null,
                    'payment_with' => 'Qrcode',
                    'payment_date' => date('Y-m-d'),
                    // 'transaction_ref_id' => $request->refId,
                    'name' => $shipping_address->name,
                    'email' => $shipping_address->email,
                    // 'payment_proof' => $request->payment_proof,
                    'payment_proof' => $paymentProofPath,
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
                    'admin_email' => env('MAIL_FROM_ADDRESS'),
                    'b_zip' => $billing_address->zip,
                    'vat_amount' => $vatamountTotal ?? 0

                ];
                

                if ($order['area'] == null) {
                    $request->session()->flash('error', 'Your shipping  address cannot be empty. Please update your address to continue');
                    return redirect()->route('index');
                }

                $order_id = $this->order->fill($order);
                $status = $this->order->save();
                $seller = [];
                $temp = [];
                $seller_product = [];

                foreach ($cart_item as $key => $product) {
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

                foreach ($cart_item as $item) {
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
                $request->session()->forget('cart_order_item');
                $request->session()->flash('success', 'Your Order Has been Created Successfully !!');

                return redirect()->route('invoice', $this->order->id);
                // return redirect()->route('order.productDetails', $this->order->id);
                } catch (\Exception $ex) {
                    DB::rollBack();
                    $request->session()->flash('error', 'Something is Wrong !!!');
                    return redirect()->route('cart.index');
                }

                
            } elseif ($request->payment === 'esewa') {
                $amount = (new KhatiPaymentAction($request))->khaltiPayment();
                $poid = Str::random(6) . rand(100, 1000);
                $returnURL = route('esewa-callback-return');
                return view('frontend.esewa.form2', [
                    'tAmt' => (int)$amount,
                    'amt' => (int)$amount,
                    'txAmt' => 0,
                    'psc' => 0,
                    'pdc' => 0,
                    'pid' => $poid,
                    'su' => $returnURL,
                    'fu' => $returnURL,
                ]);
            } elseif ($request->payment === 'Fonepay') {
                $user = auth()->guard('customer')->user();
                if ($request->shipping_address === null) {
                    $request->session()->flash('error', 'Plz Select Shipping Address !!');
                    return redirect()->route('cart.index');
                }

                if ($request->session()->get('cart_order_item') === null) {
                    $request->session()->flash('error', 'Plz Select Item First !!');
                    return redirect()->route('cart.index');
                }
                $request->validate([
                    'shipping_address' => 'required|exists:user_shipping_addresses,id',
                    'billing_address' => 'nullable|exists:user_billing_addresses,id',
                    'same_address' => 'nullable:in:0,1'
                ]);

                if ($request->billing_address == null) {
                    if ($request->same_address == null) {
                        $request->session()->flash('error', 'Plz Select Billing Or Same As Shipping Fields');
                        return redirect()->route('cart.index');
                    }
                }

                if (!$user) {
                    $request->session()->flash('error', 'Plz Login First');
                    return redirect()->route('cart.index');
                }

                if ($request->coupoon_code != null) {
                    $coupon_discount_price = $request->coupoon_code;
                    $coupoun_code_name = $request->coupon_code_name;
                    $coupoun__name = $request->coupon_name;
                } else {
                    $coupon_discount_price = 0;
                    $coupoun_code_name = null;
                    $coupoun__name = null;
                }

                $shipping_id = $request->shipping_address;
                $billing_id = $request->billing_address;
                $shipping_address = UserShippingAddress::where('id', $shipping_id)->where('user_id', $user->id)->first();
                if ($request->same_address === null || $request->same_address <= 0 || $request->same_address === 0) {
                    if ($request->billing_address == null) {
                        $request->session()->flash('error', 'Plz Select Billing Address');
                        return redirect()->route('cart.index');
                    }
                    $billing_address = UserBillingAddress::where('id', $request->billing_address)->where('user_id', $user->id)->first();
                } else {
                    $billing_address = $shipping_address;
                }
                if (!$shipping_address) {
                    $request->session()->flash('error', 'Shipping Address Is Not Valid');
                    return redirect()->route('cart.index');
                }

                $getLocation = Location::where('title', 'LIKE', '%' . $request->additional_address . '%')->first();
                if ($getLocation && $getLocation->deliveryRoute->charge != null) {
                    $shipping_charge = $getLocation->deliveryRoute->charge;
                } else {
                    $shipping_charge = $default_shipping_charge;
                }

                if (!$billing_address) {
                    $request->session()->flash('error', 'Billing Address Is Not Valid');
                    return redirect()->route('cart.index');
                }

                $order_item = session()->get('cart_order_item');

                $this->cart = Cart::where('user_id', $user->id)->first();
                if (!$this->cart) {
                    $request->session()->flash('error', 'Your Cart Is Empty !! Plz Add Some Project');
                    return redirect()->route('index');
                }

                $cart_item = [];
                foreach ($order_item['items'] as $key => $item) {
                    $cart_item[] = CartAssets::where('id', $key)->where('cart_id', $this->cart->id)->first();
                }
                $total_quantity = null;
                $total_price = null;
                $total_discount = null;
                $vatamountTotal = 0;
                foreach ($cart_item as $cItem) {
                    $vatamountTotal = $vatamountTotal + $cItem->vatamountfield;
                    $total_quantity = $total_quantity + $cItem->qty;
                    $total_price = $total_price + $cItem->sub_total_price;
                    $total_discount = $total_discount + $cItem->discount * $cItem->qty;
                }
                $fixed_price = $total_price + $shipping_charge + $default_material_charge - (int)$coupon_discount_price;

                $ref_id = 'OD' . rand(100000, 999999);
                // ------------------Vat Calculation--------------------

                // $vatAmount=0;
                // $vatAmount=collect($cart_item)->each(function($cartItemValue)
                // {
                //     $product=Product::where('id',$cartItemValue->product_id)->first();
                //     if($product->vat_percent==0 && $product->vat_percent!=1)
                //     {

                //     }
                //     dd($product);
                // });
                // ------------------Vat Calculation--------------------
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
                        'payment_status' => '1',
                        'merchant_name' => null,
                        'payment_with' => 'Fonepay-QR',
                        'payment_date' => date('Y-m-d'),
                        // 'transaction_ref_id' => $request->refId,
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
                        'admin_email' => env('MAIL_FROM_ADDRESS'),
                        'b_zip' => $billing_address->zip,
                        'vat_amount' => $vatamountTotal ?? 0

                    ];

                    if ($order['area'] == null) {
                        $request->session()->flash('error', 'Your shipping  address cannot be empty. Please update your address to continue');
                        return redirect()->route('index');
                    }

                    $order_id = $this->order->fill($order);
                    $status = $this->order->save();
                    $seller = [];
                    $temp = [];
                    $seller_product = [];

                    foreach ($cart_item as $key => $product) {
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

                    foreach ($cart_item as $item) {
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
                    $request->session()->forget('cart_order_item');
                    $request->session()->flash('success', 'Your Order Has been Created Successfully !!');

                    return redirect()->route('invoice', $this->order->id);
                    // return redirect()->route('order.productDetails', $this->order->id);
                } catch (\Exception $ex) {
                    DB::rollBack();
                    $request->session()->flash('error', 'Something is Wrong !!!');
                    return redirect()->route('cart.index');
                }
            } elseif ($request->payment === 'Hbl') {
                $user = auth()->guard('customer')->user();
                if ($request->shipping_address === null) {
                    $request->session()->flash('error', 'Plz Select Shipping Address !!');
                    return redirect()->route('cart.index');
                }

                if ($request->session()->get('cart_order_item') === null) {
                    $request->session()->flash('error', 'Plz Select Item First !!');
                    return redirect()->route('cart.index');
                }
                $request->validate([
                    'shipping_address' => 'required|exists:user_shipping_addresses,id',
                    'billing_address' => 'nullable|exists:user_billing_addresses,id',
                    'same_address' => 'nullable:in:0,1'
                ]);

                if ($request->billing_address == null) {
                    if ($request->same_address == null) {
                        $request->session()->flash('error', 'Plz Select Billing Or Same As Shipping Fields');
                        return redirect()->route('cart.index');
                    }
                }

                if (!$user) {
                    $request->session()->flash('error', 'Plz Login First');
                    return redirect()->route('cart.index');
                }

                if ($request->coupoon_code != null) {
                    $coupon_discount_price = $request->coupoon_code;
                    $coupoun_code_name = $request->coupon_code_name;
                    $coupoun__name = $request->coupon_name;
                } else {
                    $coupon_discount_price = 0;
                    $coupoun_code_name = null;
                    $coupoun__name = null;
                }

                $shipping_id = $request->shipping_address;
                $billing_id = $request->billing_address;
                $shipping_address = UserShippingAddress::where('id', $shipping_id)->where('user_id', $user->id)->first();
                if ($request->same_address === null || $request->same_address <= 0 || $request->same_address === 0) {
                    if ($request->billing_address == null) {
                        $request->session()->flash('error', 'Plz Select Billing Address');
                        return redirect()->route('cart.index');
                    }
                    $billing_address = UserBillingAddress::where('id', $request->billing_address)->where('user_id', $user->id)->first();
                } else {
                    $billing_address = $shipping_address;
                }
                if (!$shipping_address) {
                    $request->session()->flash('error', 'Shipping Address Is Not Valid');
                    return redirect()->route('cart.index');
                }
                $getLocation = Location::where('title', 'LIKE', '%' . $shipping_address->additional_address . '%')->first();
                if ($getLocation && $getLocation->deliveryRoute->charge != null) {
                    $shipping_charge = $getLocation->deliveryRoute->charge;
                } else {
                    $shipping_charge = $default_shipping_charge;
                }

                if (!$billing_address) {
                    $request->session()->flash('error', 'Billing Address Is Not Valid');
                    return redirect()->route('cart.index');
                }

                $order_item = session()->get('cart_order_item');
                $this->cart = Cart::where('user_id', $user->id)->first();

                if (!$this->cart) {
                    $request->session()->flash('error', 'Your Cart Is Empty !! Plz Add Some Project');
                    return redirect()->route('index');
                }

                $cart_item = [];
                foreach ($order_item['items'] as $key => $item) {
                    $cart_item[] = CartAssets::where('id', $key)->where('cart_id', $this->cart->id)->first();
                }
                $total_quantity = null;
                $total_price = null;
                $total_discount = null;
                $vatamountTotal = 0;
                foreach ($cart_item as $cItem) {
                    $vatamountTotal = $vatamountTotal + $cItem->vatamountfield;
                    $total_quantity = $total_quantity + $cItem->qty;
                    $total_price = $total_price + $cItem->sub_total_price;
                    $total_discount = $total_discount + $cItem->discount * $cItem->qty;
                }

                $mimimum_order_cost = Setting::where('key', 'minimum_order_price')->pluck('value')->first();


                if ($user->wholeseller) {
                    if ($total_price < $mimimum_order_cost) {
                        $request->session()->flash('error', 'The minimum order amount should be $. ' . $mimimum_order_cost . ' !!');
                        return back();
                    }
                    $wholeSellerMinimumPrice = Setting::where('key', 'whole_seller_minimum_price')->first()->value;
                    $wholeSellerShippingCharge = Setting::where('key', 'wholseller_shipping_charge')->first()->value;
                    if ($total_price >= $wholeSellerMinimumPrice) {
                        $shipping_charge = 0;
                    } else {
                        $shipping_charge = $wholeSellerShippingCharge;
                    }
                } else {
                    // dd($cart_item);
                    $wholeSellecustomerShippingChargePerKg = Setting::where('key', 'shippping_charge_per_kg')->first()->value;
                    $productId = collect($cart_item)->pluck('product_id')->toArray();
                    $packetWeight = Product::whereIn('id', $productId)->get()->sum('package_weight');
                    $shipping_charge = (int)$wholeSellecustomerShippingChargePerKg * (int)$packetWeight;
                }

                $fixed_price = $total_price + $shipping_charge + $default_material_charge - (int)$coupon_discount_price;

                $ref_id = 'OD' . rand(100000, 999999);
                // ------------------Vat Calculation--------------------

                // $vatAmount=0;
                // $vatAmount=collect($cart_item)->each(function($cartItemValue)
                // {
                //     $product=Product::where('id',$cartItemValue->product_id)->first();
                //     if($product->vat_percent==0 && $product->vat_percent!=1)
                //     {

                //     }
                //     dd($product);
                // });
                // ------------------Vat Calculation--------------------
                //  dd('fixed price',$fixed_price);
                $setting = Setting::get();
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
                        'payment_status' => '1',
                        'merchant_name' => null,
                        'payment_with' => 'HBl',
                        'payment_date' => date('Y-m-d'),
                        // 'transaction_ref_id' => $request->refId,
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
                        'admin_email' => env('MAIL_FROM_ADDRESS'),
                        'b_zip' => $billing_address->zip,
                        'vat_amount' => $vatamountTotal ?? 0
                    ];
                    if ($order['area'] == null) {
                        $request->session()->flash('error', 'Your shipping  address cannot be empty. Please update your address to continue');
                        return redirect()->route('index');
                    }
                    $finalData = null;
                    $finalData['order'] = $order;
                    $finalData['cart_item'] = $cart_item;
                    session()->put('hblsessiondata', '');
                    session()->put('hblsessiondata', $finalData);
                    return view('payment', compact('fixed_price', 'setting'));
                } catch (\Exception $ex) {
                    DB::rollBack();
                    $request->session()->flash('error', 'Something is Wrong !!!');
                    return redirect()->route('cart.index');
                }
            } elseif ($request->payment === 'paypal') {

                $user = auth()->guard('customer')->user();
                if ($request->shipping_address === null) {
                    $request->session()->flash('error', 'Plz Select Shipping Address !!');
                    return redirect()->route('cart.index');
                }

                if ($request->session()->get('cart_order_item') === null) {
                    $request->session()->flash('error', 'Plz Select Item First !!');
                    return redirect()->route('cart.index');
                }
                $request->validate([
                    'shipping_address' => 'required|exists:user_shipping_addresses,id',
                    'billing_address' => 'nullable|exists:user_billing_addresses,id',
                    'same_address' => 'nullable:in:0,1'
                ]);

                if ($request->billing_address == null) {
                    if ($request->same_address == null) {
                        $request->session()->flash('error', 'Plz Select Billing Or Same As Shipping Fields');
                        return redirect()->route('cart.index');
                    }
                }

                if (!$user) {
                    $request->session()->flash('error', 'Plz Login First');
                    return redirect()->route('cart.index');
                }

                if ($request->coupoon_code != null) {
                    $coupon_discount_price = $request->coupoon_code;
                    $coupoun_code_name = $request->coupon_code_name;
                    $coupoun__name = $request->coupon_name;
                } else {
                    $coupon_discount_price = 0;
                    $coupoun_code_name = null;
                    $coupoun__name = null;
                }

                $shipping_id = $request->shipping_address;
                $billing_id = $request->billing_address;
                $shipping_address = UserShippingAddress::where('id', $shipping_id)->where('user_id', $user->id)->first();
                if ($request->same_address === null || $request->same_address <= 0 || $request->same_address === 0) {
                    if ($request->billing_address == null) {
                        $request->session()->flash('error', 'Plz Select Billing Address');
                        return redirect()->route('cart.index');
                    }
                    $billing_address = UserBillingAddress::where('id', $request->billing_address)->where('user_id', $user->id)->first();
                } else {
                    $billing_address = $shipping_address;
                }
                if (!$shipping_address) {
                    $request->session()->flash('error', 'Shipping Address Is Not Valid');
                    return redirect()->route('cart.index');
                }
                $getLocation = Location::where('title', 'LIKE', '%' . $shipping_address->additional_address . '%')->first();
                if ($getLocation && $getLocation->deliveryRoute->charge != null) {
                    $shipping_charge = $getLocation->deliveryRoute->charge;
                } else {
                    $shipping_charge = $default_shipping_charge;
                }

                if (!$billing_address) {
                    $request->session()->flash('error', 'Billing Address Is Not Valid');
                    return redirect()->route('cart.index');
                }

                $order_item = session()->get('cart_order_item');
                $this->cart = Cart::where('user_id', $user->id)->first();

                if (!$this->cart) {
                    $request->session()->flash('error', 'Your Cart Is Empty !! Plz Add Some Project');
                    return redirect()->route('index');
                }

                $cart_item = [];
                foreach ($order_item['items'] as $key => $item) {
                    $cart_item[] = CartAssets::where('id', $key)->where('cart_id', $this->cart->id)->first();
                }
                $total_quantity = null;
                $total_price = null;
                $total_discount = null;
                $vatamountTotal = 0;
                foreach ($cart_item as $cItem) {
                    $vatamountTotal = $vatamountTotal + $cItem->vatamountfield;
                    $total_quantity = $total_quantity + $cItem->qty;
                    $total_price = $total_price + $cItem->sub_total_price;
                    $total_discount = $total_discount + $cItem->discount * $cItem->qty;
                }

                $mimimum_order_cost = Setting::where('key', 'minimum_order_price')->pluck('value')->first();


                if ($user->wholeseller) {
                    if ($total_price < $mimimum_order_cost) {
                        $request->session()->flash('error', 'The minimum order amount should be $. ' . $mimimum_order_cost . ' !!');
                        return back();
                    }
                    $wholeSellerMinimumPrice = Setting::where('key', 'whole_seller_minimum_price')->first()->value;
                    $wholeSellerShippingCharge = Setting::where('key', 'wholseller_shipping_charge')->first()->value;
                    if ($total_price >= $wholeSellerMinimumPrice) {
                        $shipping_charge = 0;
                    } else {
                        $shipping_charge = $wholeSellerShippingCharge;
                    }
                } else {
                    // dd($cart_item);
                    $wholeSellecustomerShippingChargePerKg = Setting::where('key', 'shippping_charge_per_kg')->first()->value;
                    $productId = collect($cart_item)->pluck('product_id')->toArray();
                    $packetWeight = Product::whereIn('id', $productId)->get()->sum('package_weight');
                    $shipping_charge = (int)$wholeSellecustomerShippingChargePerKg * (int)$packetWeight;
                }

                $fixed_price = $total_price + $shipping_charge + $default_material_charge - (int)$coupon_discount_price;

                $ref_id = 'OD' . rand(100000, 999999);
                // ------------------Vat Calculation--------------------

                // $vatAmount=0;
                // $vatAmount=collect($cart_item)->each(function($cartItemValue)
                // {
                //     $product=Product::where('id',$cartItemValue->product_id)->first();
                //     if($product->vat_percent==0 && $product->vat_percent!=1)
                //     {

                //     }
                //     dd($product);
                // });
                // ------------------Vat Calculation--------------------
                //  dd('fixed price',$fixed_price);
                $setting = Setting::get();

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
                        'payment_status' => '1',
                        'merchant_name' => null,
                        'payment_with' => 'Paypal',
                        'payment_date' => date('Y-m-d'),
                        // 'transaction_ref_id' => $request->refId,
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
                        'admin_email' => env('MAIL_FROM_ADDRESS'),
                        'b_zip' => $billing_address->zip,
                        'vat_amount' => $vatamountTotal ?? 0

                    ];

                    if ($order['area'] == null) {
                        $request->session()->flash('error', 'Your shipping  address cannot be empty. Please update your address to continue');
                        return redirect()->route('index');
                    }
                    $finalData = null;
                    $finalData['order'] = $order;
                    $finalData['cart_item'] = $cart_item;
                    session()->put('paypalsessiondata', '');
                    session()->put('paypalsessiondata', $finalData);
                    $provider = new PayPalClient();

                    $token = $provider->getAccessToken();
                    $provider->setAccessToken($token);

                    $order = $provider->createOrder([
                        "intent" => "CAPTURE",
                        "purchase_units" => [
                            [
                                "amount" => [
                                    "currency_code" => "USD",
                                    "value" => $fixed_price
                                ]
                            ]
                        ],
                        "application_context" => [
                            "cancel_url" => route('paypal.cancel'),
                            "return_url" => route('paypal.success')
                        ]
                    ]);
                    // dd($order['status'] );
                    if ($order['status'] === 'CREATED') {
                        $request->session()->put('requestData', null);
                        $request->session()->put('requestData', $request->all());
                        return redirect($order['links'][1]['href']);
                    } else {
                        throw new Exception();
                    }
                } catch (\Exception $ex) {
                    DB::rollBack();
                    $request->session()->flash('error', 'Something is Wrong !!!');
                    return redirect()->route('cart.index');
                }
            } else {
                $request->session()->flash('error', 'Something Went Wrong');
                return redirect()->back();
            }
        }
        return back()->with('error', 'Invalid day');
    }




    public function getShippingCharge(Request $request)
    {
        try {
            $delivery_route = DeliveryRoute::where(['from' => 9, 'to' => $request->area_id])->first();
            if ($delivery_route) {
                $delivery_charge = $delivery_route->charge->branch_delivery;
            } else {
                $delivery_charge = Setting::where('key', 'default_shipping_charge')->pluck('value')->first();
            }
            return response()->json([
                'delivery_charge' => $delivery_charge,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Shipping not available for this area',
                'delivery_charge' => 0,
            ]);
        }
    }


    public function storeOrderInSession(Request $request)
    {
        if ($request->guestOrder != 'true') {
            $stock = ProductStock::where('id', $request->varient_id)->first();
            $quantity = $request->product_qty;
            $mimimum_order_cost = Setting::where('key', 'minimum_order_price')->pluck('value')->first();

            if ($stock->special_price) {
                $price = $stock->special_price;
            } else {
                $price = $stock->price;
            }
            $customer = auth()->guard('customer')->user();
            if ($customer->wholeseller) {
                if ($price * $quantity < $mimimum_order_cost) {
                    $request->session()->flash('error', 'The order amount should be greater than $. ' . $mimimum_order_cost . '!!');
                    return response()->json(['status' => 200, 'min_order_message' => 'Your minimum order amount should be $.' . $mimimum_order_cost]);
                } else {
                    $user = auth()->guard('customer')->user();
                    if (!$user) {
                        $request->session()->flash('error', 'Plz Login !!');
                        $response = [
                            'login' => true,
                        ];
                        return response()->json($response, 200);
                    }
                    $request['guestCheckout'] = false;
                    session()->put('directOrder', $request->all());
                }
            } else {
                $user = auth()->guard('customer')->user();
                if (!$user) {
                    $request->session()->flash('error', 'Plz Login !!');
                    $response = [
                        'login' => true,
                    ];
                    return response()->json($response, 200);
                }
                $request['guestCheckout'] = false;
                session()->put('directOrder', $request->all());
            }
        } else {
            $stock = ProductStock::where('id', $request->varient_id)->first();
            $quantity = $request->product_qty;
            $mimimum_order_cost = Setting::where('key', 'minimum_order_price')->pluck('value')->first();

            if ($stock->special_price) {
                $price = $stock->special_price;
            } else {
                $price = $stock->price;
            }
            $customer = New_Customer::where('id', 63)->first();
            if ($customer->wholeseller) {
                if ($price * $quantity < $mimimum_order_cost) {
                    $request->session()->flash('error', 'The order amount should be greater than $. ' . $mimimum_order_cost . '!!');
                    return response()->json(['status' => 200, 'min_order_message' => 'Your minimum order amount should be $.' . $mimimum_order_cost]);
                } else {
                    $user = $customer;
                    if (!$user) {
                        $request->session()->flash('error', 'Plz Login !!');
                        $response = [
                            'login' => true,
                        ];
                        return response()->json($response, 200);
                    }
                    $request['guestCheckout'] = true;
                    session()->put('directOrder', $request->all());
                }
            } else {

                $user = $customer;
                if (!$user) {
                    $request->session()->flash('error', 'Plz Login !!');
                    $response = [
                        'login' => true,
                    ];
                    return response()->json($response, 200);
                }
                $request['guestCheckout'] = true;
                session()->put('directOrder', $request->all());
            }
        }
    }

    // Direct Checkout Single Product
    public function directCheckout(Request $request)
    {

        // dd($request->payment);

        $today = Carbon::now()->format('l');
        $order_timing_check = OrderTimeSetting::where('day', $today)->exists();
        if ($order_timing_check) {
            $order_timing = OrderTimeSetting::where('day', $today)->first();
            if ($order_timing->day_off == true) {
                $request->session()->flash('error', 'You cannot place order today because it is our day off !!');
                return back();
            }
            $currentTime = Carbon::now()->format('H:i');
            if ($currentTime > $order_timing->end_time || $currentTime < $order_timing->start_time) {
                $request->session()->flash('error', 'Please place your order between ' . $order_timing->start_time . ' and ' . $order_timing->end_time);
                return back();
            }
            $default_shipping_charge = Setting::where('key', 'default_shipping_charge')->pluck('value')->first();
            $default_material_charge = Setting::where('key', 'materials_price')->pluck('value')->first();

            if ($request->payment === 'COD') {
                $user = auth()->guard('customer')->user();

                $for_order =  $request->session()->get('directOrder');

                if ($for_order === null) {
                    $request->session()->flash('error', 'Plz Try again, Something is wrong');
                    return back();
                }

                if ($request->shipping_address === null) {
                    $request->session()->flash('error', 'Plz Select Shipping Address !!');
                    return redirect()->route('cart.index');
                }

                $request->validate([
                    'shipping_address' => 'required|exists:user_shipping_addresses,id',
                    'billing_address' => 'nullable|exists:user_billing_addresses,id',
                    'same_address' => 'nullable:in:0,1'
                ]);


                if ($request->billing_address == null) {
                    if ($request->same_address == null) {
                        $request->session()->flash('error', 'Plz Select Billing Or Same As Shipping Fields');
                        return redirect()->route('cart.index');
                    }
                }

                if (!$user) {
                    $request->session()->flash('error', 'Plz Login First');
                    return redirect()->route('cart.index');
                }

                if ($request->coupoon_code != null) {
                    $coupon_discount_price = $request->coupoon_code;
                    $coupoun_code_name = $request->coupon_code_name;
                    $coupoun__name = $request->coupon_name;
                } else {
                    $coupon_discount_price = 0;
                    $coupoun_code_name = null;
                    $coupoun__name = null;
                }

                $coupon_discount_price = round($coupon_discount_price);


                $shipping_id = $request->shipping_address;
                $billing_id = $request->billing_address;
                $shipping_address = UserShippingAddress::where('id', $shipping_id)->where('user_id', $user->id)->first();
                if ($request->same_address === null || $request->same_address <= 0 || $request->same_address === 0) {
                    if ($request->billing_address == null) {
                        $request->session()->flash('error', 'Plz Select Billing Address');
                        return redirect()->route('cart.index');
                    }
                    $billing_address = UserBillingAddress::where('id', $request->billing_address)->where('user_id', $user->id)->first();
                } else {
                    $billing_address = $shipping_address;
                }
                if (!$shipping_address) {
                    $request->session()->flash('error', 'Shipping Address Is Not Valid');
                    return redirect()->route('cart.index');
                }
                if ($shipping_address->getLocation != null) {
                    $shipping_charge = $shipping_address->getLocation->deliveryRoute->charge;
                } else {
                    $shipping_charge = $default_shipping_charge;
                }

                if (!$billing_address) {
                    $request->session()->flash('error', 'Billing Address Is Not Valid');
                    return redirect()->route('cart.index');
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
                    $request->session()->flash('error', 'Your shipping address cannot be empty. Please update your shipping address');
                    return redirect()->route('Cprofile');
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
                        'payment_status' => '1',
                        'merchant_name' => null,
                        'payment_with' => 'Cash On Delivery',
                        'payment_date' => date('Y-m-d'),
                        // 'transaction_ref_id' => $request->refId,
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
                        'admin_email' => env('MAIL_FROM_ADDRESS'),
                        'b_zip' => $billing_address->zip,
                        'vat_amount' => round($vatAmount) ?? 0
                    ];

                    if ($order['area'] == null) {
                        $request->session()->flash('error', 'Your shipping address cannot be empty. Please update your shipping address');
                        return redirect()->route('Cprofile');
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
                    $this->order_asset->insert($temp);
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
                    PaymentEvent::dispatch($paymentData);
                    EmailSetUp::sendMail(MessageSetupEnum::ORDER_PLACE, $data = $this->order, $user);

                    $request->session()->forget('directOrder');
                    $request->session()->flash('success', 'Your Order Has been Created Successfully !!');
                    return redirect()->route('invoice', $this->order->id);
                    return redirect()->route('Corder');
                } catch (\Exception $ex) {
                    DB::rollBack();
                    $request->session()->flash('error', 'Something is Wrong !!!');
                    // return redirect()->route('invoice',$this->order->id);
                    return redirect()->route('cart.index');
                }
            } 
            

            elseif ($request->payment === 'QRCode') {

                // dd($request->payment);

                if ($request->hasFile('payment_proof')) {
                 
                    $paymentProofFile = $request->file('payment_proof');
                    $paymentProofPath = $paymentProofFile->storeAs('payment_proofs', time() . '.' . $paymentProofFile->getClientOriginalExtension(), 'public');
                } else {
                  
                    $paymentProofPath = null;
                }

                $user = auth()->guard('customer')->user();

                if ($request->shipping_address === null) {
                    $request->session()->flash('error', 'Plz Select Shipping Address !!');
                    return redirect()->route('cart.index');
                }

                if ($request->session()->get('cart_order_item') === null) {
                    $request->session()->flash('error', 'Plz Select Item First !!');
                    return redirect()->route('cart.index');
                }
                $request->validate([
                    'shipping_address' => 'required|exists:user_shipping_addresses,id',
                    'billing_address' => 'nullable|exists:user_billing_addresses,id',
                    'same_address' => 'nullable:in:0,1',
                    'payment_proof'=> 'required'
                ]);

                if ($request->billing_address == null) {
                    if ($request->same_address == null) {
                        $request->session()->flash('error', 'Plz Select Billing Or Same As Shipping Fields');
                        return redirect()->route('cart.index');
                    }
                }

                if (!$user) {
                    $request->session()->flash('error', 'Plz Login First');
                    return redirect()->route('cart.index');
                }

                if ($request->coupoon_code != null) {
                    $coupon_discount_price = $request->coupoon_code;
                    $coupoun_code_name = $request->coupon_code_name;
                    $coupoun__name = $request->coupon_name;
                } else {
                    $coupon_discount_price = 0;
                    $coupoun_code_name = null;
                    $coupoun__name = null;
                }

                $shipping_id = $request->shipping_address;
                $billing_id = $request->billing_address;
                $shipping_address = UserShippingAddress::where('id', $shipping_id)->where('user_id', $user->id)->first();
                if ($request->same_address === null || $request->same_address <= 0 || $request->same_address === 0) {
                    if ($request->billing_address == null) {
                        $request->session()->flash('error', 'Plz Select Billing Address');
                        return redirect()->route('cart.index');
                    }
                    $billing_address = UserBillingAddress::where('id', $request->billing_address)->where('user_id', $user->id)->first();
                } else {
                    $billing_address = $shipping_address;
                }
                if (!$shipping_address) {
                    $request->session()->flash('error', 'Shipping Address Is Not Valid');
                    return redirect()->route('cart.index');
                }
                $getLocation = Location::where('title', 'LIKE', '%' . $shipping_address->additional_address . '%')->first();
                if ($getLocation && $getLocation->deliveryRoute->charge != null) {
                    $shipping_charge = $getLocation->deliveryRoute->charge;
                } else {
                    $shipping_charge = $default_shipping_charge;
                }



                if (!$billing_address) {
                    $request->session()->flash('error', 'Billing Address Is Not Valid');
                    return redirect()->route('cart.index');
                }

                $order_item = session()->get('cart_order_item');
                $this->cart = Cart::where('user_id', $user->id)->first();

                if (!$this->cart) {
                    $request->session()->flash('error', 'Your Cart Is Empty !! Plz Add Some Project');
                    return redirect()->route('index');
                }

                $cart_item = [];
                foreach ($order_item['items'] as $key => $item) {
                    $cart_item[] = CartAssets::where('id', $key)->where('cart_id', $this->cart->id)->first();
                }
                $total_quantity = null;
                $total_price = null;
                $total_discount = null;
                $vatamountTotal = 0;
                foreach ($cart_item as $cItem) {
                    $vatamountTotal = $vatamountTotal + $cItem->vatamountfield;
                    $total_quantity = $total_quantity + $cItem->qty;
                    $total_price = $total_price + $cItem->sub_total_price;
                    $total_discount = $total_discount + $cItem->discount * $cItem->qty;
                }
                $mimimum_order_cost = Setting::where('key', 'minimum_order_price')->pluck('value')->first();


                if ($user->wholeseller) {
                    if ($total_price < $mimimum_order_cost) {
                        $request->session()->flash('error', 'The minimum order amount should be Rs. ' . $mimimum_order_cost . ' !!');
                        return back();
                    }
                    $wholeSellerMinimumPrice = Setting::where('key', 'minimum_order_price')->first()->value;
                    $wholeSellerShippingCharge = Setting::where('key', 'wholseller_shipping_charge')->first()->value;
                    if ($total_price >= $wholeSellerMinimumPrice) {
                        $shipping_charge = 0;
                    } else {
                        $shipping_charge = $wholeSellerShippingCharge;
                    }
                } else {
                  
                    $shipping_charge=0;
                    foreach($cart_item as $item){
                        $product=Product::where('id',$item['product_id'])->first();
                        $shipping_chargeNew=$shipping_charge+($product->shipping_charge * $item['qty']);
                    }
                }


                $fixed_price = $total_price + $shipping_charge + $default_material_charge - (int)$coupon_discount_price;

                $ref_id = 'OD' . rand(100000, 999999);
             
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
                    'payment_status' => '1',
                    'merchant_name' => null,
                    'payment_with' => 'Qrcode',
                    'payment_date' => date('Y-m-d'),
                    // 'transaction_ref_id' => $request->refId,
                    'name' => $shipping_address->name,
                    'email' => $shipping_address->email,
                    // 'payment_proof' => $request->payment_proof,
                    'payment_proof' => $paymentProofPath,
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
                    'admin_email' => env('MAIL_FROM_ADDRESS'),
                    'b_zip' => $billing_address->zip,
                    'vat_amount' => $vatamountTotal ?? 0

                ];
                

                if ($order['area'] == null) {
                    $request->session()->flash('error', 'Your shipping  address cannot be empty. Please update your address to continue');
                    return redirect()->route('index');
                }

                $order_id = $this->order->fill($order);
                $status = $this->order->save();
                $seller = [];
                $temp = [];
                $seller_product = [];

                foreach ($cart_item as $key => $product) {
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

                foreach ($cart_item as $item) {
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
                $request->session()->forget('cart_order_item');
                $request->session()->flash('success', 'Your Order Has been Created Successfully !!');

                return redirect()->route('invoice', $this->order->id);
                // return redirect()->route('order.productDetails', $this->order->id);
                } catch (\Exception $ex) {
                    DB::rollBack();
                    $request->session()->flash('error', 'Something is Wrong !!!');
                    return redirect()->route('cart.index');
                }

                
            }
            
            
            elseif ($request->payment == 'Hbl') {

                $user = auth()->guard('customer')->user();
                $for_order =  $request->session()->get('directOrder');

                if ($for_order === null) {
                    $request->session()->flash('error', 'Plz Try again, Something is wrong');
                    return back();
                }

                if ($request->shipping_address === null) {
                    $request->session()->flash('error', 'Plz Select Shipping Address !!');
                    return redirect()->route('cart.index');
                }

                $request->validate([
                    'shipping_address' => 'required|exists:user_shipping_addresses,id',
                    'billing_address' => 'nullable|exists:user_billing_addresses,id',
                    'same_address' => 'nullable:in:0,1'
                ]);


                if ($request->billing_address == null) {
                    if ($request->same_address == null) {
                        $request->session()->flash('error', 'Plz Select Billing Or Same As Shipping Fields');
                        return redirect()->route('cart.index');
                    }
                }

                if (!$user) {
                    $request->session()->flash('error', 'Plz Login First');
                    return redirect()->route('cart.index');
                }

                if ($request->coupoon_code != null) {
                    $coupon_discount_price = $request->coupoon_code;
                    $coupoun_code_name = $request->coupon_code_name;
                    $coupoun__name = $request->coupon_name;
                } else {
                    $coupon_discount_price = 0;
                    $coupoun_code_name = null;
                    $coupoun__name = null;
                }

                $coupon_discount_price = round($coupon_discount_price);


                $shipping_id = $request->shipping_address;
                $billing_id = $request->billing_address;
                $shipping_address = UserShippingAddress::where('id', $shipping_id)->where('user_id', $user->id)->first();
                if ($request->same_address === null || $request->same_address <= 0 || $request->same_address === 0) {
                    if ($request->billing_address == null) {
                        $request->session()->flash('error', 'Plz Select Billing Address');
                        return redirect()->route('cart.index');
                    }
                    $billing_address = UserBillingAddress::where('id', $request->billing_address)->where('user_id', $user->id)->first();
                } else {
                    $billing_address = $shipping_address;
                }
                if (!$shipping_address) {
                    $request->session()->flash('error', 'Shipping Address Is Not Valid');
                    return redirect()->route('cart.index');
                }
                if ($shipping_address->getLocation != null) {
                    $shipping_charge = $shipping_address->getLocation->deliveryRoute->charge;
                } else {
                    $shipping_charge = $default_shipping_charge;
                }

                if (!$billing_address) {
                    $request->session()->flash('error', 'Billing Address Is Not Valid');
                    return redirect()->route('cart.index');
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
                    $request->session()->flash('error', 'Your shipping address cannot be empty. Please update your shipping address');
                    return redirect()->route('Cprofile');
                }
                DB::beginTransaction();

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
                    'payment_status' => '1',
                    'merchant_name' => null,
                    'payment_with' => 'Hbl',
                    'payment_date' => date('Y-m-d'),
                    // 'transaction_ref_id' => $request->refId,
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
                    'admin_email' => env('MAIL_FROM_ADDRESS'),
                    'b_zip' => $billing_address->zip,
                    'vat_amount' => round($vatAmount) ?? 0
                ];

                if ($order['area'] == null) {
                    $request->session()->flash('error', 'Your shipping address cannot be empty. Please update your shipping address');
                    return redirect()->route('Cprofile');
                }
                $finalData = null;
                $finalData['order'] = $order;
                $finalData['requestData'] = $request->all();
                $directCheckout = true;
                $setting = Setting::get();
                session()->put('hblsessiondata', '');
                session()->put('hblsessiondata', $finalData);
                return view('payment', compact('fixed_price', 'setting', 'directCheckout'));
            } elseif ($request->payment == 'paypal') {
                $user = auth()->guard('customer')->user();
                $for_order =  $request->session()->get('directOrder');

                if ($for_order === null) {
                    $request->session()->flash('error', 'Plz Try again, Something is wrong');
                    return back();
                }

                if ($request->shipping_address === null) {
                    $request->session()->flash('error', 'Plz Select Shipping Address !!');
                    return redirect()->route('cart.index');
                }

                $request->validate([
                    'shipping_address' => 'required|exists:user_shipping_addresses,id',
                    'billing_address' => 'nullable|exists:user_billing_addresses,id',
                    'same_address' => 'nullable:in:0,1'
                ]);


                if ($request->billing_address == null) {
                    if ($request->same_address == null) {
                        $request->session()->flash('error', 'Plz Select Billing Or Same As Shipping Fields');
                        return redirect()->route('cart.index');
                    }
                }

                if (!$user) {
                    $request->session()->flash('error', 'Plz Login First');
                    return redirect()->route('cart.index');
                }

                if ($request->coupoon_code != null) {
                    $coupon_discount_price = $request->coupoon_code;
                    $coupoun_code_name = $request->coupon_code_name;
                    $coupoun__name = $request->coupon_name;
                } else {
                    $coupon_discount_price = 0;
                    $coupoun_code_name = null;
                    $coupoun__name = null;
                }

                $coupon_discount_price = round($coupon_discount_price);


                $shipping_id = $request->shipping_address;
                $billing_id = $request->billing_address;
                $shipping_address = UserShippingAddress::where('id', $shipping_id)->where('user_id', $user->id)->first();
                if ($request->same_address === null || $request->same_address <= 0 || $request->same_address === 0) {
                    if ($request->billing_address == null) {
                        $request->session()->flash('error', 'Plz Select Billing Address');
                        return redirect()->route('cart.index');
                    }
                    $billing_address = UserBillingAddress::where('id', $request->billing_address)->where('user_id', $user->id)->first();
                } else {
                    $billing_address = $shipping_address;
                }
                if (!$shipping_address) {
                    $request->session()->flash('error', 'Shipping Address Is Not Valid');
                    return redirect()->route('cart.index');
                }
                if ($shipping_address->getLocation != null) {
                    $shipping_charge = $shipping_address->getLocation->deliveryRoute->charge;
                } else {
                    $shipping_charge = $default_shipping_charge;
                }

                if (!$billing_address) {
                    $request->session()->flash('error', 'Billing Address Is Not Valid');
                    return redirect()->route('cart.index');
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


                $mimimum_order_cost = Setting::where('key', 'minimum_order_price')->pluck('value')->first();


                if ($user->wholeseller) {
                    if ($total_price < $mimimum_order_cost) {
                        $request->session()->flash('error', 'The minimum order amount should be $. ' . $mimimum_order_cost . ' !!');
                        return back();
                    }
                    $wholeSellerMinimumPrice = Setting::where('key', 'whole_seller_minimum_price')->first()->value;
                    $wholeSellerShippingCharge = Setting::where('key', 'wholseller_shipping_charge')->first()->value;
                    if ($total_price >= $wholeSellerMinimumPrice) {
                        $shipping_charge = 0;
                    } else {
                        $shipping_charge = $wholeSellerShippingCharge;
                    }
                } else {
                    $wholeSellecustomerShippingChargePerKg = Setting::where('key', 'shippping_charge_per_kg')->first()->value;
                    $packetWeight = $main_product->package_weight;
                    $shipping_charge = (int)$wholeSellecustomerShippingChargePerKg * (int)$packetWeight;
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
                    $request->session()->flash('error', 'Your shipping address cannot be empty. Please update your shipping address');
                    return redirect()->route('Cprofile');
                }
                DB::beginTransaction();

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
                    'payment_status' => '1',
                    'merchant_name' => null,
                    'payment_with' => 'Paypal',
                    'payment_date' => date('Y-m-d'),
                    // 'transaction_ref_id' => $request->refId,
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
                    'b_district' => $billing_address->district,
                    'b_area' => $billing_address->area,
                    'b_additional_address' => $billing_address->additional_address,
                    'admin_email' => env('MAIL_FROM_ADDRESS'),
                    'b_zip' => $billing_address->zip,
                    'vat_amount' => round($vatAmount) ?? 0
                ];
                // dd($order);

                if ($order['area'] == null) {
                    $request->session()->flash('error', 'Your shipping address cannot be empty. Please update your shipping address');
                    return redirect()->route('Cprofile');
                }

                $finalData = null;
                $finalData['order'] = $order;
                $finalData['requestData'] = $request->except('_token');
                $finalData['for_order'] = $for_order;


                $directCheckout = true;
                $setting = Setting::get();
                session()->put('paypalsessiondatadirect', null);
                session()->put('paypalsessiondatadirect', $finalData);
                $provider = new PayPalClient();

                $token = $provider->getAccessToken();
                $provider->setAccessToken($token);

                $order = $provider->createOrder([
                    "intent" => "CAPTURE",
                    "purchase_units" => [
                        [
                            "amount" => [
                                "currency_code" => "USD",
                                "value" => $fixed_price
                            ]
                        ]
                    ],
                    "application_context" => [
                        "cancel_url" => route('paypal.cancel'),
                        "return_url" => route('paypal.success-direct')
                    ]
                ]);
                // dd($order['status'] );
                if ($order['status'] === 'CREATED') {
                    $request->session()->put('requestData', null);
                    $request->session()->put('requestData', $request->all());
                    return redirect($order['links'][1]['href']);
                } else {
                    throw new Exception();
                }
            } else {
                $request->session()->flash('error', 'Something Went Wrong !!');
                return redirect()->back();
            }
        } else {
            return back()->with('error', 'Invalid day');
        }
    }

    public function guestdirectCheckout(Request $request)
    {
        $default_shipping_charge = Setting::where('key', 'default_shipping_charge')->pluck('value')->first();
        $default_material_charge = Setting::where('key', 'materials_price')->pluck('value')->first();
        if ($request->payment == 'Hbl') {
            $user = New_Customer::where('id', 63)->first();
            $for_order =  $request->session()->get('directOrder');

            if ($for_order === null) {
                $request->session()->flash('error', 'Plz Try again, Something is wrong');
                return back();
            }
            $shippingAddressData = [
                'name' => $request->name,
                'user_id' => $user->id,
                'email' => $request->email,
                'phone' => $request->phone,
                'province' => $request->province,
                'area' => $request->area,
                'zip' => $request->zip,
                'country' => $request->country
            ];
            $userShippingAddress = UserShippingAddress::create($shippingAddressData);
            if ($userShippingAddress) {
                $request['shipping_address'] = $userShippingAddress->id;
            }
            if ($request->same_address === '1') {
                $userBillingAddress = UserBillingAddress::create($shippingAddressData);
                if ($userBillingAddress) {
                    $request['billing_address'] = $userBillingAddress->id;
                }
            } else {
                $billingAddressData = [
                    'name' => $request->nameB,
                    'user_id' => $user->id,
                    'email' => $request->emailB,
                    'phone' => $request->phoneB,
                    'province' => $request->provinceB,
                    'area' => $request->areaB,
                    'zip' => $request->zipB,
                    'country' => $request->countryB
                ];
                $userBillingAddress = UserBillingAddress::create($billingAddressData);
                if ($userBillingAddress) {
                    $request['billing_address'] = $userBillingAddress->id;
                }
            }

            if ($request->shipping_address === null) {
                $request->session()->flash('error', 'Plz Select Shipping Address !!');
                return redirect()->route('cart.index');
            }
            $request->validate([
                'shipping_address' => 'required|exists:user_shipping_addresses,id',
                'billing_address' => 'nullable|exists:user_billing_addresses,id',
                'same_address' => 'nullable:in:0,1'
            ]);


            if ($request->billing_address == null) {
                if ($request->same_address == null) {
                    $request->session()->flash('error', 'Plz Select Billing Or Same As Shipping Fields');
                    return redirect()->route('cart.index');
                }
            }

            if (!$user) {
                $request->session()->flash('error', 'Plz Login First');
                return redirect()->route('cart.index');
            }

            if ($request->coupoon_code != null) {
                $coupon_discount_price = $request->coupoon_code;
                $coupoun_code_name = $request->coupon_code_name;
                $coupoun__name = $request->coupon_name;
            } else {
                $coupon_discount_price = 0;
                $coupoun_code_name = null;
                $coupoun__name = null;
            }

            $coupon_discount_price = round($coupon_discount_price);


            $shipping_id = $request->shipping_address;
            $billing_id = $request->billing_address;
            $shipping_address = UserShippingAddress::where('id', $shipping_id)->where('user_id', $user->id)->first();
            if ($request->same_address === null || $request->same_address <= 0 || $request->same_address === 0) {
                if ($request->billing_address == null) {
                    $request->session()->flash('error', 'Plz Select Billing Address');
                    return redirect()->route('cart.index');
                }
                $billing_address = UserBillingAddress::where('id', $request->billing_address)->where('user_id', $user->id)->first();
            } else {
                $billing_address = $shipping_address;
            }
            if (!$shipping_address) {
                $request->session()->flash('error', 'Shipping Address Is Not Valid');
                return redirect()->route('cart.index');
            }
            if ($shipping_address->getLocation != null) {
                $shipping_charge = $shipping_address->getLocation->deliveryRoute->charge;
            } else {
                $shipping_charge = $default_shipping_charge;
            }

            if (!$billing_address) {
                $request->session()->flash('error', 'Billing Address Is Not Valid');
                return redirect()->route('cart.index');
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
            $wholeSellecustomerShippingChargePerKg = Setting::where('key', 'shippping_charge_per_kg')->first()->value;
            $packetWeight = $main_product->package_weight;
            $default_shipping_charge = (int)$wholeSellecustomerShippingChargePerKg * (int)$packetWeight;

            // $fixed_price = $total_price + $shipping_charge + $default_material_charge - (int)$coupon_discount_price;
            $fixed_price = $total_price + $default_shipping_charge + $default_material_charge - (int)$coupon_discount_price;
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
                $request->session()->flash('error', 'Your shipping address cannot be empty. Please update your shipping address');
                return redirect()->route('Cprofile');
            }
            DB::beginTransaction();
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
                'payment_status' => '1',
                'merchant_name' => null,
                'payment_with' => 'Hbl',
                'payment_date' => date('Y-m-d'),
                // 'transaction_ref_id' => $request->refId,
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
                'admin_email' => env('MAIL_FROM_ADDRESS'),
                'b_zip' => $billing_address->zip,
                'vat_amount' => round($vatAmount) ?? 0
            ];

            if ($order['area'] == null) {
                $request->session()->flash('error', 'Your shipping address cannot be empty. Please update your shipping address');
                return redirect()->route('Cprofile');
            }
            $cartItemData = [
                "product_id" => $main_product->id,
                "product_name" => $main_product->name,
                "price" => $total_price / $total_quantity,
                "qty" => $total_quantity,
                "sub_total_price" => $total_price * $total_quantity
            ];
            $finalData = null;
            $finalData['order'] = $order;
            $finalData['cart_item'] = $cartItemData;
            $finalData['requestData'] = $request->all();
            $directCheckout = true;
            $setting = Setting::get();
            session()->put('hblsessiondataDirect', '');
            session()->put('hblsessiondataDirect', $finalData);
            return view('paymentDirect', compact('fixed_price', 'setting', 'directCheckout'));
        } elseif ($request->payment == 'paypal') {
            $user = New_Customer::where('id', 63)->first();
            $for_order =  $request->session()->get('directOrder');
            if ($for_order === null) {
                $request->session()->flash('error', 'Plz Try again, Something is wrong');
                return back();
            }
            if (!$user) {
                $request->session()->flash('error', 'Plz Login First');
                return redirect()->route('cart.index');
            }
            $randomRefId = Str::random(10);
            $shippingAddressData = [
                'name' => $request->name,
                'user_id' => $user->id,
                'email' => $request->email,
                'phone' => $request->phone,
                'province' => $request->province,
                'area' => $request->area,
                'zip' => $request->zip,
                'country' => $request->country
            ];
            $userShippingAddress = UserShippingAddress::create($shippingAddressData);
            if ($userShippingAddress) {
                $request['shipping_address'] = $userShippingAddress->id;
            }
            if ($request->same_address === '1') {
                $userBillingAddress = UserBillingAddress::create($shippingAddressData);
                if ($userBillingAddress) {
                    $request['billing_address'] = $userBillingAddress->id;
                }
            } else {
                $billingAddressData = [
                    'name' => $request->nameB,
                    'user_id' => $user->id,
                    'email' => $request->emailB,
                    'phone' => $request->phoneB,
                    'province' => $request->provinceB,
                    'area' => $request->areaB,
                    'zip' => $request->zipB,
                    'country' => $request->countryB
                ];
                $userBillingAddress = UserBillingAddress::create($billingAddressData);
                if ($userBillingAddress) {
                    $request['billing_address'] = $userBillingAddress->id;
                }
            }
            DB::beginTransaction();

            $shipping_id = $userShippingAddress->id;
            $billing_id = $userBillingAddress->id;
            $shipping_address = $userShippingAddress;

            $billing_address = $userBillingAddress;

            if (!$shipping_address) {
                $request->session()->flash('error', 'Shipping Address Is Not Valid');
                return redirect()->route('cart.index');
            }
            if (!$billing_address) {
                $request->session()->flash('error', 'Billing Address Is Not Valid');
                return redirect()->route('cart.index');
            }

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


            $mimimum_order_cost = Setting::where('key', 'minimum_order_price')->pluck('value')->first();


            if ($user->wholeseller) {
                if ($total_price < $mimimum_order_cost) {
                    $request->session()->flash('error', 'The minimum order amount should be $. ' . $mimimum_order_cost . ' !!');
                    return back();
                }
                $wholeSellerMinimumPrice = Setting::where('key', 'whole_seller_minimum_price')->first()->value;
                $wholeSellerShippingCharge = Setting::where('key', 'wholseller_shipping_charge')->first()->value;
                if ($total_price >= $wholeSellerMinimumPrice) {
                    $shipping_charge = 0;
                } else {
                    $shipping_charge = $wholeSellerShippingCharge;
                }
            } else {
                $wholeSellecustomerShippingChargePerKg = Setting::where('key', 'shippping_charge_per_kg')->first()->value;
                $packetWeight = $main_product->package_weight;
                $shipping_charge = (int)$wholeSellecustomerShippingChargePerKg * (int)$packetWeight;
            }
            $fixed_price = $total_price + $shipping_charge + $default_material_charge;
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
                $request->session()->flash('error', 'Your shipping address cannot be empty. Please update your shipping address');
                return redirect()->route('Cprofile');
            }

            $order = [
                'user_id' => $user->id,
                'aff_id' => Str::random(10) . rand(100, 1000),
                'total_quantity' => $total_quantity,
                'total_price' => $fixed_price,
                'ref_id' => $ref_id,
                'shipping_charge' => $shipping_charge,
                'material_charge' => $default_material_charge,
                'total_discount' => $total_discount,
                'payment_status' => '1',
                'merchant_name' => null,
                'payment_with' => 'Paypal',
                'payment_date' => date('Y-m-d'),
                // 'transaction_ref_id' => $request->refId,
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
                'admin_email' => env('MAIL_FROM_ADDRESS'),
                'b_zip' => $billing_address->zip,
                'vat_amount' => round($vatAmount) ?? 0,
                'guest_ref_id' => $randomRefId
            ];
            if ($order['area'] == null) {
                $request->session()->flash('error', 'Your shipping address cannot be empty. Please update your shipping address');
                return redirect()->route('Cprofile');
            }

            $finalData = null;
            $finalData['order'] = $order;
            $finalData['requestData'] = $request->except('_token');
            $finalData['for_order'] = $for_order;

            $finalData['userShipAddressId'] = $userShippingAddress->id;
            $finalData['userBillAddressId'] = $userBillingAddress->id;
            $finalData['randomRefId'] = $randomRefId;


            $directCheckout = true;
            $setting = Setting::get();
            // dd($finalData);
            session()->put('paypalsessiondatadirectguest', null);
            session()->put('paypalsessiondatadirectguest', $finalData);
            $provider = new PayPalClient();

            $token = $provider->getAccessToken();
            $provider->setAccessToken($token);

            $order = $provider->createOrder([
                "intent" => "CAPTURE",
                "purchase_units" => [
                    [
                        "amount" => [
                            "currency_code" => "USD",
                            "value" => $fixed_price
                        ]
                    ]
                ],
                "application_context" => [
                    "cancel_url" => route('paypal.cancel'),
                    "return_url" => route('paypal.guest-success-direct')
                ]
            ]);
            if ($order['status'] === 'CREATED') {
                $request->session()->put('requestData', null);
                $request->session()->put('requestData', $request->all());
                DB::commit();
                return redirect($order['links'][1]['href']);
            } else {
                throw new Exception();
            }
        } elseif ($request->payment == 'cod') {
            $user = New_Customer::where('id', 63)->first();
            $for_order =  $request->session()->get('directOrder');
            if ($for_order === null) {
                $request->session()->flash('error', 'Plz Try again, Something is wrong');
                return back();
            }
            if (!$user) {
                $request->session()->flash('error', 'Plz Login First');
                return redirect()->route('cart.index');
            }
            $randomRefId = Str::random(10);
            $shippingAddressData = [
                'name' => $request->name,
                'user_id' => $user->id,
                'email' => $request->email,
                'phone' => $request->phone,
                'province' => $request->province,
                'area' => $request->area,
                'zip' => $request->zip,
                'country' => $request->country
            ];
            $userShippingAddress = UserShippingAddress::create($shippingAddressData);
            if ($userShippingAddress) {
                $request['shipping_address'] = $userShippingAddress->id;
            }
            if ($request->same_address === '1') {
                $userBillingAddress = UserBillingAddress::create($shippingAddressData);
                if ($userBillingAddress) {
                    $request['billing_address'] = $userBillingAddress->id;
                }
            } else {
                $billingAddressData = [
                    'name' => $request->nameB,
                    'user_id' => $user->id,
                    'email' => $request->emailB,
                    'phone' => $request->phoneB,
                    'province' => $request->provinceB,
                    'area' => $request->areaB,
                    'zip' => $request->zipB,
                    'country' => $request->countryB
                ];
                $userBillingAddress = UserBillingAddress::create($billingAddressData);
                if ($userBillingAddress) {
                    $request['billing_address'] = $userBillingAddress->id;
                }
            }
            DB::beginTransaction();

            $shipping_id = $userShippingAddress->id;
            $billing_id = $userBillingAddress->id;
            $shipping_address = $userShippingAddress;

            $billing_address = $userBillingAddress;

            if (!$shipping_address) {
                $request->session()->flash('error', 'Shipping Address Is Not Valid');
                return redirect()->route('cart.index');
            }
            if (!$billing_address) {
                $request->session()->flash('error', 'Billing Address Is Not Valid');
                return redirect()->route('cart.index');
            }

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


            $mimimum_order_cost = Setting::where('key', 'minimum_order_price')->pluck('value')->first();


            if ($user->wholeseller) {
                if ($total_price < $mimimum_order_cost) {
                    $request->session()->flash('error', 'The minimum order amount should be $. ' . $mimimum_order_cost . ' !!');
                    return back();
                }
                $wholeSellerMinimumPrice = Setting::where('key', 'whole_seller_minimum_price')->first()->value;
                $wholeSellerShippingCharge = Setting::where('key', 'wholseller_shipping_charge')->first()->value;
                if ($total_price >= $wholeSellerMinimumPrice) {
                    $shipping_charge = 0;
                } else {
                    $shipping_charge = $wholeSellerShippingCharge;
                }
            } else {
                // $wholeSellecustomerShippingChargePerKg = Setting::where('key', 'shippping_charge_per_kg')->first()->value;
                // $packetWeight = $main_product->package_weight;
                // $shipping_charge = (int)$wholeSellecustomerShippingChargePerKg * (int)$packetWeight;
                $shipping_charge=0;
                $shipping_charge=$main_product->shipping_charge * $total_quantity;
            }

            // if ($user->wholeseller) {
            //     if ($this->cart->total_price < $mimimum_order_cost) {
            //         $response = [
            //             'error' => true,
            //             'data' => null,
            //             'msg' => 'The minimum order amount should be Rs. ' . $mimimum_order_cost . ' !!'
            //         ];
            //         return response()->json($response, 200);
            //     }
            //     $wholeSellerShippingCharge = Setting::where('key', 'wholseller_shipping_charge')->first()->value;
            //     $shipping_chargeNew=$wholeSellerShippingCharge ?? 0;
                
            // } else {
            //     foreach($this->cart->cartAssets as $cartAsset){
            //         $shipping_chargeNew=$shipping_chargeNew+($cartAsset->product->shipping_charge * $cartAsset->qty);
            //     }
                
            // }


            $fixed_price = $total_price + $shipping_charge + $default_material_charge;
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
                $request->session()->flash('error', 'Your shipping address cannot be empty. Please update your shipping address');
                return redirect()->route('Cprofile');
            }

            $order = [
                'user_id' => $user->id,
                'aff_id' => Str::random(10) . rand(100, 1000),
                'total_quantity' => $total_quantity,
                'total_price' => $fixed_price,
                'ref_id' => $ref_id,
                'shipping_charge' => $shipping_charge,
                'material_charge' => $default_material_charge,
                'total_discount' => $total_discount,
                'payment_status' => '0',
                'merchant_name' => null,
                'payment_with' => 'COD',
                'payment_date' => date('Y-m-d'),
                // 'transaction_ref_id' => $request->refId,
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
                'admin_email' => env('MAIL_FROM_ADDRESS'),
                'b_zip' => $billing_address->zip,
                'vat_amount' => round($vatAmount) ?? 0,
                'guest_ref_id' => $randomRefId
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
            DB::commit();
            PaymentEvent::dispatch($paymentData);
            EmailSetUp::sendMail(MessageSetupEnum::ORDER_PLACE, $data = $this->order, $user);
            session()->forget('directOrder');
            return redirect()->route('invoice.new', $this->order->id);
            return redirect()->route('Corder')->with('error', 'Your Order Has been Created Successfully !!');
        } 
        elseif ($request->payment === 'QRCode') {

            // dd($request->payment);

            if ($request->hasFile('payment_proof')) {
             
                $paymentProofFile = $request->file('payment_proof');
                $paymentProofPath = $paymentProofFile->storeAs('payment_proofs', time() . '.' . $paymentProofFile->getClientOriginalExtension(), 'public');
            } else {
              
                $paymentProofPath = null;
            }

            $user = auth()->guard('customer')->user();

            if ($request->shipping_address === null) {
                $request->session()->flash('error', 'Plz Select Shipping Address !!');
                return redirect()->route('cart.index');
            }

            if ($request->session()->get('cart_order_item') === null) {
                $request->session()->flash('error', 'Plz Select Item First !!');
                return redirect()->route('cart.index');
            }
            $request->validate([
                'shipping_address' => 'required|exists:user_shipping_addresses,id',
                'billing_address' => 'nullable|exists:user_billing_addresses,id',
                'same_address' => 'nullable:in:0,1',
                'payment_proof'=> 'required'
            ]);

            if ($request->billing_address == null) {
                if ($request->same_address == null) {
                    $request->session()->flash('error', 'Plz Select Billing Or Same As Shipping Fields');
                    return redirect()->route('cart.index');
                }
            }

            if (!$user) {
                $request->session()->flash('error', 'Plz Login First');
                return redirect()->route('cart.index');
            }

            if ($request->coupoon_code != null) {
                $coupon_discount_price = $request->coupoon_code;
                $coupoun_code_name = $request->coupon_code_name;
                $coupoun__name = $request->coupon_name;
            } else {
                $coupon_discount_price = 0;
                $coupoun_code_name = null;
                $coupoun__name = null;
            }

            $shipping_id = $request->shipping_address;
            $billing_id = $request->billing_address;
            $shipping_address = UserShippingAddress::where('id', $shipping_id)->where('user_id', $user->id)->first();
            if ($request->same_address === null || $request->same_address <= 0 || $request->same_address === 0) {
                if ($request->billing_address == null) {
                    $request->session()->flash('error', 'Plz Select Billing Address');
                    return redirect()->route('cart.index');
                }
                $billing_address = UserBillingAddress::where('id', $request->billing_address)->where('user_id', $user->id)->first();
            } else {
                $billing_address = $shipping_address;
            }
            if (!$shipping_address) {
                $request->session()->flash('error', 'Shipping Address Is Not Valid');
                return redirect()->route('cart.index');
            }
            $getLocation = Location::where('title', 'LIKE', '%' . $shipping_address->additional_address . '%')->first();
            if ($getLocation && $getLocation->deliveryRoute->charge != null) {
                $shipping_charge = $getLocation->deliveryRoute->charge;
            } else {
                $shipping_charge = $default_shipping_charge;
            }



            if (!$billing_address) {
                $request->session()->flash('error', 'Billing Address Is Not Valid');
                return redirect()->route('cart.index');
            }

            $order_item = session()->get('cart_order_item');
            $this->cart = Cart::where('user_id', $user->id)->first();

            if (!$this->cart) {
                $request->session()->flash('error', 'Your Cart Is Empty !! Plz Add Some Project');
                return redirect()->route('index');
            }

            $cart_item = [];
            foreach ($order_item['items'] as $key => $item) {
                $cart_item[] = CartAssets::where('id', $key)->where('cart_id', $this->cart->id)->first();
            }
            $total_quantity = null;
            $total_price = null;
            $total_discount = null;
            $vatamountTotal = 0;
            foreach ($cart_item as $cItem) {
                $vatamountTotal = $vatamountTotal + $cItem->vatamountfield;
                $total_quantity = $total_quantity + $cItem->qty;
                $total_price = $total_price + $cItem->sub_total_price;
                $total_discount = $total_discount + $cItem->discount * $cItem->qty;
            }
            $mimimum_order_cost = Setting::where('key', 'minimum_order_price')->pluck('value')->first();


            if ($user->wholeseller) {
                if ($total_price < $mimimum_order_cost) {
                    $request->session()->flash('error', 'The minimum order amount should be Rs. ' . $mimimum_order_cost . ' !!');
                    return back();
                }
                $wholeSellerMinimumPrice = Setting::where('key', 'minimum_order_price')->first()->value;
                $wholeSellerShippingCharge = Setting::where('key', 'wholseller_shipping_charge')->first()->value;
                if ($total_price >= $wholeSellerMinimumPrice) {
                    $shipping_charge = 0;
                } else {
                    $shipping_charge = $wholeSellerShippingCharge;
                }
            } else {
              
                $shipping_charge=0;
                foreach($cart_item as $item){
                    $product=Product::where('id',$item['product_id'])->first();
                    $shipping_chargeNew=$shipping_charge+($product->shipping_charge * $item['qty']);
                }
            }


            $fixed_price = $total_price + $shipping_charge + $default_material_charge - (int)$coupon_discount_price;

            $ref_id = 'OD' . rand(100000, 999999);
         
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
                'payment_status' => '1',
                'merchant_name' => null,
                'payment_with' => 'Qrcode',
                'payment_date' => date('Y-m-d'),
                // 'transaction_ref_id' => $request->refId,
                'name' => $shipping_address->name,
                'email' => $shipping_address->email,
                // 'payment_proof' => $request->payment_proof,
                'payment_proof' => $paymentProofPath,
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
                'admin_email' => env('MAIL_FROM_ADDRESS'),
                'b_zip' => $billing_address->zip,
                'vat_amount' => $vatamountTotal ?? 0

            ];
            

            if ($order['area'] == null) {
                $request->session()->flash('error', 'Your shipping  address cannot be empty. Please update your address to continue');
                return redirect()->route('index');
            }

            $order_id = $this->order->fill($order);
            $status = $this->order->save();
            $seller = [];
            $temp = [];
            $seller_product = [];

            foreach ($cart_item as $key => $product) {
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

            foreach ($cart_item as $item) {
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
            $request->session()->forget('cart_order_item');
            $request->session()->flash('success', 'Your Order Has been Created Successfully !!');

            return redirect()->route('invoice', $this->order->id);
            // return redirect()->route('order.productDetails', $this->order->id);
            } catch (\Exception $ex) {
                DB::rollBack();
                $request->session()->flash('error', 'Something is Wrong !!!');
                return redirect()->route('cart.index');
            }

            
        }
        
        else {
            $request->session()->flash('error', 'Something Went Wrong !!');
            return redirect()->back();
        }
    }

    public function guestalldirectCheckout(Request $request)
    {
        $default_shipping_charge = Setting::where('key', 'default_shipping_charge')->pluck('value')->first();
        $default_material_charge = Setting::where('key', 'materials_price')->pluck('value')->first();
        if ($request->payment == 'Hbl') {
            $user = New_Customer::where('id', 63)->first();
            $for_order =  $request->session()->get('guest_cart');

            if ($for_order === null) {
                $request->session()->flash('error', 'Plz Try again, Something is wrong');
                return back();
            }
            $shippingAddressData = [
                'name' => $request->name,
                'user_id' => $user->id,
                'email' => $request->email,
                'phone' => $request->phone,
                'province' => $request->province,
                'area' => $request->area,
                'zip' => $request->zip,
                'country' => $request->country
            ];
            $userShippingAddress = UserShippingAddress::create($shippingAddressData);
            if ($userShippingAddress) {
                $request['shipping_address'] = $userShippingAddress->id;
            }
            if ($request->same_address === '1') {
                $userBillingAddress = UserBillingAddress::create($shippingAddressData);
                if ($userBillingAddress) {
                    $request['billing_address'] = $userBillingAddress->id;
                }
            } else {
                $billingAddressData = [
                    'name' => $request->nameB,
                    'user_id' => $user->id,
                    'email' => $request->emailB,
                    'phone' => $request->phoneB,
                    'province' => $request->provinceB,
                    'area' => $request->areaB,
                    'zip' => $request->zipB,
                    'country' => $request->countryB
                ];
                $userBillingAddress = UserBillingAddress::create($billingAddressData);
                if ($userBillingAddress) {
                    $request['billing_address'] = $userBillingAddress->id;
                }
            }
            if ($request->shipping_address === null) {
                $request->session()->flash('error', 'Plz Select Shipping Address !!');
                return redirect()->route('cart.index');
            }
            $request->validate([
                'shipping_address' => 'required|exists:user_shipping_addresses,id',
                'billing_address' => 'nullable|exists:user_billing_addresses,id',
                'same_address' => 'nullable:in:0,1'
            ]);
            if ($request->billing_address == null) {
                if ($request->same_address == null) {
                    $request->session()->flash('error', 'Plz Select Billing Or Same As Shipping Fields');
                    return redirect()->route('cart.index');
                }
            }
            if (!$user) {
                $request->session()->flash('error', 'Plz Login First');
                return redirect()->route('cart.index');
            }
            if ($request->coupoon_code != null) {
                $coupon_discount_price = $request->coupoon_code;
                $coupoun_code_name = $request->coupon_code_name;
                $coupoun__name = $request->coupon_name;
            } else {
                $coupon_discount_price = 0;
                $coupoun_code_name = null;
                $coupoun__name = null;
            }
            $coupon_discount_price = round($coupon_discount_price);
            $shipping_id = $request->shipping_address;
            $billing_id = $request->billing_address;
            $shipping_address = UserShippingAddress::where('id', $shipping_id)->where('user_id', $user->id)->first();
            if ($request->same_address === null || $request->same_address <= 0 || $request->same_address === 0) {
                if ($request->billing_address == null) {
                    $request->session()->flash('error', 'Plz Select Billing Address');
                    return redirect()->route('cart.index');
                }
                $billing_address = UserBillingAddress::where('id', $request->billing_address)->where('user_id', $user->id)->first();
            } else {
                $billing_address = $shipping_address;
            }
            if (!$shipping_address) {
                $request->session()->flash('error', 'Shipping Address Is Not Valid');
                return redirect()->route('cart.index');
            }
            if ($shipping_address->getLocation != null) {
                $shipping_charge = $shipping_address->getLocation->deliveryRoute->charge;
            } else {
                $shipping_charge = $default_shipping_charge;
            }
            if (!$billing_address) {
                $request->session()->flash('error', 'Billing Address Is Not Valid');
                return redirect()->route('cart.index');
            }
            $order_item = session()->get('guest_cart');
            $this->cart = $order_item['cart_item'] ?? null;
            if (!$this->cart) {
                $request->session()->flash('error', 'Your Cart Is Empty !! Plz Add Some Project');
                return redirect()->route('index');
            }
            $cart_item = $order_item['cart_item'];
            
            $total_quantity = null;
            $total_price = null;
            $total_discount = null;
            $vatamountTotal = 0;
            foreach ($cart_item as $cItem) {
                $vatamountTotal = $vatamountTotal + $cItem['vatamountfield'];
                $total_quantity = $total_quantity + $cItem['qty'];
                $total_price = $total_price + $cItem['sub_total_price'];
                $total_discount = $total_discount + $cItem['discount'] * $cItem['qty'];
            }
            $mimimum_order_cost = Setting::where('key', 'minimum_order_price')->pluck('value')->first();
            if ($user->wholeseller) {
                if ($total_price < $mimimum_order_cost) {
                    $request->session()->flash('error', 'The minimum order amount should be $. ' . $mimimum_order_cost . ' !!');
                    return back();
                }
                $wholeSellerMinimumPrice = Setting::where('key', 'whole_seller_minimum_price')->first()->value;
                $wholeSellerShippingCharge = Setting::where('key', 'wholseller_shipping_charge')->first()->value;
                if ($total_price >= $wholeSellerMinimumPrice) {
                    $shipping_charge = 0;
                } else {
                    $shipping_charge = $wholeSellerShippingCharge;
                }
            } else {
                // dd($cart_item);
                $wholeSellecustomerShippingChargePerKg = Setting::where('key', 'shippping_charge_per_kg')->first()->value;
                $productId = collect($cart_item)->pluck('product_id')->toArray();
                $packetWeight = Product::whereIn('id', $productId)->get()->sum('package_weight');
                $shipping_charge = (int)$wholeSellecustomerShippingChargePerKg * (int)$packetWeight;
            }
            $fixed_price = $total_price + $shipping_charge + $default_material_charge - (int)$coupon_discount_price;
            $ref_id = 'OD' . rand(100000, 999999);
            $setting = Setting::get();
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
                    'payment_status' => '1',
                    'merchant_name' => null,
                    'payment_with' => 'HBl',
                    'payment_date' => date('Y-m-d'),
                    // 'transaction_ref_id' => $request->refId,
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
                    'admin_email' => env('MAIL_FROM_ADDRESS'),
                    'b_zip' => $billing_address->zip,
                    'vat_amount' => $vatamountTotal ?? 0
                ];
                if ($order['area'] == null) {
                    $request->session()->flash('error', 'Your shipping  address cannot be empty. Please update your address to continue');
                    return redirect()->route('index');
                }
                $finalData = null;
                $finalData['order'] = $order;
                $finalData['cart_item'] = $cart_item;
                session()->put('hblsessiondata', '');
                session()->put('hblsessiondata', collect($finalData));
                return view('paymentguest', compact('fixed_price', 'setting'));
            } catch (\Exception $ex) {
                DB::rollBack();
                $request->session()->flash('error', 'Something is Wrong !!!');
                return redirect()->route('cart.index');
            }
        } elseif ($request->payment == 'paypal') {
            $user = New_Customer::where('id', 63)->first();
            $for_order =  $request->session()->get('directOrder');
            if ($for_order === null) {
                $request->session()->flash('error', 'Plz Try again, Something is wrong');
                return back();
            }
            if (!$user) {
                $request->session()->flash('error', 'Plz Login First');
                return redirect()->route('cart.index');
            }
            $randomRefId = Str::random(10);
            $shippingAddressData = [
                'name' => $request->name,
                'user_id' => $user->id,
                'email' => $request->email,
                'phone' => $request->phone,
                'province' => $request->province,
                'area' => $request->area,
                'zip' => $request->zip,
                'country' => $request->country
            ];
            $userShippingAddress = UserShippingAddress::create($shippingAddressData);
            if ($userShippingAddress) {
                $request['shipping_address'] = $userShippingAddress->id;
            }
            if ($request->same_address === '1') {
                $userBillingAddress = UserBillingAddress::create($shippingAddressData);
                if ($userBillingAddress) {
                    $request['billing_address'] = $userBillingAddress->id;
                }
            } else {
                $billingAddressData = [
                    'name' => $request->nameB,
                    'user_id' => $user->id,
                    'email' => $request->emailB,
                    'phone' => $request->phoneB,
                    'province' => $request->provinceB,
                    'area' => $request->areaB,
                    'zip' => $request->zipB,
                    'country' => $request->countryB
                ];
                $userBillingAddress = UserBillingAddress::create($billingAddressData);
                if ($userBillingAddress) {
                    $request['billing_address'] = $userBillingAddress->id;
                }
            }
            DB::beginTransaction();

            $shipping_id = $userShippingAddress->id;
            $billing_id = $userBillingAddress->id;
            $shipping_address = $userShippingAddress;

            $billing_address = $userBillingAddress;

            if (!$shipping_address) {
                $request->session()->flash('error', 'Shipping Address Is Not Valid');
                return redirect()->route('cart.index');
            }
            if (!$billing_address) {
                $request->session()->flash('error', 'Billing Address Is Not Valid');
                return redirect()->route('cart.index');
            }

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


            $mimimum_order_cost = Setting::where('key', 'minimum_order_price')->pluck('value')->first();


            if ($user->wholeseller) {
                if ($total_price < $mimimum_order_cost) {
                    $request->session()->flash('error', 'The minimum order amount should be $. ' . $mimimum_order_cost . ' !!');
                    return back();
                }
                $wholeSellerMinimumPrice = Setting::where('key', 'whole_seller_minimum_price')->first()->value;
                $wholeSellerShippingCharge = Setting::where('key', 'wholseller_shipping_charge')->first()->value;
                if ($total_price >= $wholeSellerMinimumPrice) {
                    $shipping_charge = 0;
                } else {
                    $shipping_charge = $wholeSellerShippingCharge;
                }
            } else {
                $wholeSellecustomerShippingChargePerKg = Setting::where('key', 'shippping_charge_per_kg')->first()->value;
                $packetWeight = $main_product->package_weight;
                $shipping_charge = (int)$wholeSellecustomerShippingChargePerKg * (int)$packetWeight;
            }
            $fixed_price = $total_price + $shipping_charge + $default_material_charge;
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
                $request->session()->flash('error', 'Your shipping address cannot be empty. Please update your shipping address');
                return redirect()->route('Cprofile');
            }

            $order = [
                'user_id' => $user->id,
                'aff_id' => Str::random(10) . rand(100, 1000),
                'total_quantity' => $total_quantity,
                'total_price' => $fixed_price,
                'ref_id' => $ref_id,
                'shipping_charge' => $shipping_charge,
                'material_charge' => $default_material_charge,
                'total_discount' => $total_discount,
                'payment_status' => '1',
                'merchant_name' => null,
                'payment_with' => 'Paypal',
                'payment_date' => date('Y-m-d'),
                // 'transaction_ref_id' => $request->refId,
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
                'admin_email' => env('MAIL_FROM_ADDRESS'),
                'b_zip' => $billing_address->zip,
                'vat_amount' => round($vatAmount) ?? 0,
                'guest_ref_id' => $randomRefId
            ];
            if ($order['area'] == null) {
                $request->session()->flash('error', 'Your shipping address cannot be empty. Please update your shipping address');
                return redirect()->route('Cprofile');
            }

            $finalData = null;
            $finalData['order'] = $order;
            $finalData['requestData'] = $request->except('_token');
            $finalData['for_order'] = $for_order;

            $finalData['userShipAddressId'] = $userShippingAddress->id;
            $finalData['userBillAddressId'] = $userBillingAddress->id;
            $finalData['randomRefId'] = $randomRefId;


            $directCheckout = true;
            $setting = Setting::get();
            // dd($finalData);
            session()->put('paypalsessiondatadirectguest', null);
            session()->put('paypalsessiondatadirectguest', $finalData);
            $provider = new PayPalClient();

            $token = $provider->getAccessToken();
            $provider->setAccessToken($token);

            $order = $provider->createOrder([
                "intent" => "CAPTURE",
                "purchase_units" => [
                    [
                        "amount" => [
                            "currency_code" => "USD",
                            "value" => $fixed_price
                        ]
                    ]
                ],
                "application_context" => [
                    "cancel_url" => route('paypal.cancel'),
                    "return_url" => route('paypal.guest-success-direct')
                ]
            ]);
            if ($order['status'] === 'CREATED') {
                $request->session()->put('requestData', null);
                $request->session()->put('requestData', $request->all());
                DB::commit();
                return redirect($order['links'][1]['href']);
            } else {
                throw new Exception();
            }
        } elseif ($request->payment == 'cod') {
            $user = New_Customer::where('id', 63)->first();
            $for_order =  $request->session()->get('guest_cart');

            if ($for_order === null) {
                $request->session()->flash('error', 'Plz Try again, Something is wrong');
                return back();
            }
            $shippingAddressData = [
                'name' => $request->name,
                'user_id' => $user->id,
                'email' => $request->email,
                'phone' => $request->phone,
                'province' => $request->province,
                'area' => $request->area,
                'zip' => $request->zip,
                'country' => $request->country
            ];
            $userShippingAddress = UserShippingAddress::create($shippingAddressData);
            if ($userShippingAddress) {
                $request['shipping_address'] = $userShippingAddress->id;
            }
            if ($request->same_address === '1') {
                $userBillingAddress = UserBillingAddress::create($shippingAddressData);
                if ($userBillingAddress) {
                    $request['billing_address'] = $userBillingAddress->id;
                }
            } else {
                $billingAddressData = [
                    'name' => $request->nameB,
                    'user_id' => $user->id,
                    'email' => $request->emailB,
                    'phone' => $request->phoneB,
                    'province' => $request->provinceB,
                    'area' => $request->areaB,
                    'zip' => $request->zipB,
                    'country' => $request->countryB
                ];
                $userBillingAddress = UserBillingAddress::create($billingAddressData);
                if ($userBillingAddress) {
                    $request['billing_address'] = $userBillingAddress->id;
                }
            }
            if ($request->shipping_address === null) {
                $request->session()->flash('error', 'Plz Select Shipping Address !!');
                return redirect()->route('cart.index');
            }
            $request->validate([
                'shipping_address' => 'required|exists:user_shipping_addresses,id',
                'billing_address' => 'nullable|exists:user_billing_addresses,id',
                'same_address' => 'nullable:in:0,1'
            ]);
            if ($request->billing_address == null) {
                if ($request->same_address == null) {
                    $request->session()->flash('error', 'Plz Select Billing Or Same As Shipping Fields');
                    return redirect()->route('cart.index');
                }
            }
            if (!$user) {
                $request->session()->flash('error', 'Plz Login First');
                return redirect()->route('cart.index');
            }
            if ($request->coupoon_code != null) {
                $coupon_discount_price = $request->coupoon_code;
                $coupoun_code_name = $request->coupon_code_name;
                $coupoun__name = $request->coupon_name;
            } else {
                $coupon_discount_price = 0;
                $coupoun_code_name = null;
                $coupoun__name = null;
            }
            $coupon_discount_price = round($coupon_discount_price);
            $shipping_id = $request->shipping_address;
            $billing_id = $request->billing_address;
            $shipping_address = UserShippingAddress::where('id', $shipping_id)->where('user_id', $user->id)->first();
            if ($request->same_address === null || $request->same_address <= 0 || $request->same_address === 0) {
                if ($request->billing_address == null) {
                    $request->session()->flash('error', 'Plz Select Billing Address');
                    return redirect()->route('cart.index');
                }
                $billing_address = UserBillingAddress::where('id', $request->billing_address)->where('user_id', $user->id)->first();
            } else {
                $billing_address = $shipping_address;
            }
            if (!$shipping_address) {
                $request->session()->flash('error', 'Shipping Address Is Not Valid');
                return redirect()->route('cart.index');
            }
            if ($shipping_address->getLocation != null) {
                $shipping_charge = $shipping_address->getLocation->deliveryRoute->charge;
            } else {
                $shipping_charge = $default_shipping_charge;
            }
            if (!$billing_address) {
                $request->session()->flash('error', 'Billing Address Is Not Valid');
                return redirect()->route('cart.index');
            }
            $order_item = session()->get('guest_cart');
            $this->cart = $order_item['cart_item'] ?? null;
            if (!$this->cart) {
                $request->session()->flash('error', 'Your Cart Is Empty !! Plz Add Some Project');
                return redirect()->route('index');
            }
            $cart_item = $order_item['cart_item'];
            
            $total_quantity = null;
            $total_price = null;
            $total_discount = null;
            $vatamountTotal = 0;
            foreach ($cart_item as $cItem) {
                $vatamountTotal = $vatamountTotal + $cItem['vatamountfield'];
                $total_quantity = $total_quantity + $cItem['qty'];
                $total_price = $total_price + $cItem['sub_total_price'];
                $total_discount = $total_discount + $cItem['discount'] * $cItem['qty'];
            }
            $mimimum_order_cost = Setting::where('key', 'minimum_order_price')->pluck('value')->first();
            if ($user->wholeseller) {
                if ($total_price < $mimimum_order_cost) {
                    $request->session()->flash('error', 'The minimum order amount should be $. ' . $mimimum_order_cost . ' !!');
                    return back();
                }
                $wholeSellerMinimumPrice = Setting::where('key', 'whole_seller_minimum_price')->first()->value;
                $wholeSellerShippingCharge = Setting::where('key', 'wholseller_shipping_charge')->first()->value;
                if ($total_price >= $wholeSellerMinimumPrice) {
                    $shipping_charge = 0;
                } else {
                    $shipping_charge = $wholeSellerShippingCharge;
                }
            } else {
                // dd($cart_item);
                // $wholeSellecustomerShippingChargePerKg = Setting::where('key', 'shippping_charge_per_kg')->first()->value;
                // $productId = collect($cart_item)->pluck('product_id')->toArray();
                // $packetWeight = Product::whereIn('id', $productId)->get()->sum('package_weight');
                // $shipping_charge = (int)$wholeSellecustomerShippingChargePerKg * (int)$packetWeight;
                $shipping_charge=0;
                foreach($cart_item as $item){
                    $product=Product::where('id',$item['product_id'])->first();
                    $shipping_charge=$shipping_charge+($product->shipping_charge * $item['qty']);
                }
            }
            $fixed_price = $total_price + $shipping_charge + $default_material_charge - (int)$coupon_discount_price;
            $ref_id = 'OD' . rand(100000, 999999);
            $setting = Setting::get();
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
                    'payment_with' => 'COD',
                    'payment_date' => date('Y-m-d'),
                    // 'transaction_ref_id' => $request->refId,
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
                    'admin_email' => env('MAIL_FROM_ADDRESS'),
                    'b_zip' => $billing_address->zip,
                    'vat_amount' => $vatamountTotal ?? 0
                ];
                if ($order['area'] == null) {
                    $request->session()->flash('error', 'Your shipping  address cannot be empty. Please update your address to continue');
                    return redirect()->route('index');
                }
                $order_id = $this->order->fill($order);
                $status = $this->order->save();
                $seller = [];
                $temp = [];
                $seller_product = [];
                foreach ($cart_item as $key => $product) {
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
                            'vatAmount' => $product['vatamountfield']
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
                        'options' => json_encode($product['options']),
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
                

                DB::commit();
                PaymentEvent::dispatch($paymentData);
                EmailSetUp::sendMail(MessageSetupEnum::ORDER_PLACE, $data = $this->order, $user);
                session()->forget('guest_cart');
                return redirect()->route('invoice.newguest', $this->order->id);
            } catch (\Exception $ex) {
                DB::rollBack();
                $request->session()->flash('error', 'Something is Wrong !!!');
                return redirect()->route('cart.index');
            }

        }


        elseif ($request->payment == 'QRCode') {
            // dd($request);
            if ($request->hasFile('payment_proof')) {
             
                $paymentProofFile = $request->file('payment_proof');
                $paymentProofPath = $paymentProofFile->storeAs('payment_proofs', time() . '.' . $paymentProofFile->getClientOriginalExtension(), 'public');
            } else {
              
                $paymentProofPath = null;
            }

            $user = auth()->guard('customer')->user();

            if ($request->shipping_address === null) {
                $request->session()->flash('error', 'Plz Select Shipping Address !!');
                return redirect()->route('cart.index');
            }

            if ($request->session()->get('cart_order_item') === null) {
                $request->session()->flash('error', 'Plz Select Item First !!');
                return redirect()->route('cart.index');
            }
            $request->validate([
                'shipping_address' => 'required|exists:user_shipping_addresses,id',
                'billing_address' => 'nullable|exists:user_billing_addresses,id',
                'same_address' => 'nullable:in:0,1',
                'payment_proof'=> 'required'
            ]);

            if ($request->billing_address == null) {
                if ($request->same_address == null) {
                    $request->session()->flash('error', 'Plz Select Billing Or Same As Shipping Fields');
                    return redirect()->route('cart.index');
                }
            }

            if (!$user) {
                $request->session()->flash('error', 'Plz Login First');
                return redirect()->route('cart.index');
            }

            if ($request->coupoon_code != null) {
                $coupon_discount_price = $request->coupoon_code;
                $coupoun_code_name = $request->coupon_code_name;
                $coupoun__name = $request->coupon_name;
            } else {
                $coupon_discount_price = 0;
                $coupoun_code_name = null;
                $coupoun__name = null;
            }

            $shipping_id = $request->shipping_address;
            $billing_id = $request->billing_address;
            $shipping_address = UserShippingAddress::where('id', $shipping_id)->where('user_id', $user->id)->first();
            if ($request->same_address === null || $request->same_address <= 0 || $request->same_address === 0) {
                if ($request->billing_address == null) {
                    $request->session()->flash('error', 'Plz Select Billing Address');
                    return redirect()->route('cart.index');
                }
                $billing_address = UserBillingAddress::where('id', $request->billing_address)->where('user_id', $user->id)->first();
            } else {
                $billing_address = $shipping_address;
            }
            if (!$shipping_address) {
                $request->session()->flash('error', 'Shipping Address Is Not Valid');
                return redirect()->route('cart.index');
            }
            $getLocation = Location::where('title', 'LIKE', '%' . $shipping_address->additional_address . '%')->first();
            if ($getLocation && $getLocation->deliveryRoute->charge != null) {
                $shipping_charge = $getLocation->deliveryRoute->charge;
            } else {
                $shipping_charge = $default_shipping_charge;
            }



            if (!$billing_address) {
                $request->session()->flash('error', 'Billing Address Is Not Valid');
                return redirect()->route('cart.index');
            }

            $order_item = session()->get('cart_order_item');
            $this->cart = Cart::where('user_id', $user->id)->first();

            if (!$this->cart) {
                $request->session()->flash('error', 'Your Cart Is Empty !! Plz Add Some Project');
                return redirect()->route('index');
            }

            $cart_item = [];
            foreach ($order_item['items'] as $key => $item) {
                $cart_item[] = CartAssets::where('id', $key)->where('cart_id', $this->cart->id)->first();
            }
            $total_quantity = null;
            $total_price = null;
            $total_discount = null;
            $vatamountTotal = 0;
            foreach ($cart_item as $cItem) {
                $vatamountTotal = $vatamountTotal + $cItem->vatamountfield;
                $total_quantity = $total_quantity + $cItem->qty;
                $total_price = $total_price + $cItem->sub_total_price;
                $total_discount = $total_discount + $cItem->discount * $cItem->qty;
            }
            $mimimum_order_cost = Setting::where('key', 'minimum_order_price')->pluck('value')->first();


            if ($user->wholeseller) {
                if ($total_price < $mimimum_order_cost) {
                    $request->session()->flash('error', 'The minimum order amount should be Rs. ' . $mimimum_order_cost . ' !!');
                    return back();
                }
                $wholeSellerMinimumPrice = Setting::where('key', 'minimum_order_price')->first()->value;
                $wholeSellerShippingCharge = Setting::where('key', 'wholseller_shipping_charge')->first()->value;
                if ($total_price >= $wholeSellerMinimumPrice) {
                    $shipping_charge = 0;
                } else {
                    $shipping_charge = $wholeSellerShippingCharge;
                }
            } else {
              
                $shipping_charge=0;
                foreach($cart_item as $item){
                    $product=Product::where('id',$item['product_id'])->first();
                    $shipping_chargeNew=$shipping_charge+($product->shipping_charge * $item['qty']);
                }
            }


            $fixed_price = $total_price + $shipping_charge + $default_material_charge - (int)$coupon_discount_price;

            $ref_id = 'OD' . rand(100000, 999999);
         
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
                'payment_status' => '1',
                'merchant_name' => null,
                'payment_with' => 'Qrcode',
                'payment_date' => date('Y-m-d'),
                // 'transaction_ref_id' => $request->refId,
                'name' => $shipping_address->name,
                'email' => $shipping_address->email,
                // 'payment_proof' => $request->payment_proof,
                'payment_proof' => $paymentProofPath,
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
                'admin_email' => env('MAIL_FROM_ADDRESS'),
                'b_zip' => $billing_address->zip,
                'vat_amount' => $vatamountTotal ?? 0

            ];
            

            if ($order['area'] == null) {
                $request->session()->flash('error', 'Your shipping  address cannot be empty. Please update your address to continue');
                return redirect()->route('index');
            }

            $order_id = $this->order->fill($order);
            $status = $this->order->save();
            $seller = [];
            $temp = [];
            $seller_product = [];

            foreach ($cart_item as $key => $product) {
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

            foreach ($cart_item as $item) {
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
            $request->session()->forget('cart_order_item');
            $request->session()->flash('success', 'Your Order Has been Created Successfully !!');

            return redirect()->route('invoice', $this->order->id);
            // return redirect()->route('order.productDetails', $this->order->id);
            } catch (\Exception $ex) {
                DB::rollBack();
                $request->session()->flash('error', 'Something is Wrong !!!');
                return redirect()->route('cart.index');
            }

            
        }

        
        
        else {
            $request->session()->flash('error', 'Something Went Wrong !!');
            return redirect()->back();
        }
    }

    public function esewaResponse(Request $request)
    {
        if ($request->q == 'su') {
            dd('ok', $request->all());
        } else {
            $request->session()->flash('error', 'Something Went Wrong !!');
            return redirect()->back();
        }
    }



    public function pay(Request $request)
    {
        // Set up the Guzzle client to send the request

        //   dd($request->all());

        $client = new Client([
            'base_uri' => 'https://a.khalti.com/',
            'headers' => [
                'Authorization' => 'Key ' . '0da2fb83be5e45ffb9421816fac186e8',
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);

        //   dd($client);



        // Prepare the request data
        $ord_id = rand(5, 15);
        $data = [
            'mobile' => "9819813330",
            'amount' => 1200,
            'product_name' => 'Order #' . rand(5, 10),
            'product_identity' => $ord_id,
            'product_url' => 'http://yourwebsite.com/orders/' . $ord_id,
        ];


        // Send the request to Khalti's ePayment API
        $response = $client->post('/api/v2/epayment/initiate', [
            'json' => $data,
        ]);

        //   dd($response);

        // Process the response
        $responseBody = json_decode($response->getBody(), true);
        if ($responseBody['state']['name'] == 'Initialized') {
            // Redirect the user to Khalti's payment page
            return redirect($responseBody['data']['url']);
        } else {
            // Handle the error
            return redirect()->back()->with('error', 'Failed to initiate payment');
        }
    }
}
