<?php

namespace App\Http\Controllers\Frontend;

use Carbon\Carbon;
use App\Models\Cart;
use App\Models\User;
use App\Models\Order;
use App\Models\Seller;
use App\Models\Product;
use App\Models\CartAssets;
use App\Models\OrderAsset;
use Illuminate\Support\Str;
use App\Events\PaymentEvent;
use Dompdf\Positioner\Fixed;
use Illuminate\Http\Request;
// use App\Models\Order\Seller\ProductSellerOrder;
use App\Models\Refund\Refund;
use App\Actions\Mail\MailSetup;
use App\Events\OrderPlacedEvent;
use App\Models\UserBillingAddress;
use Illuminate\Support\Facades\DB;
use App\Models\UserShippingAddress;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Actions\Seller\SellerOrderAction;
use App\Data\Form\PaymentHistoryFormData;
use App\Enum\MessageSetup\MessageSetupEnum;
use App\Actions\Notification\NotificationAction;

class EsewaOrderController extends Controller
{
    protected $order = null;
    protected $order_asset = null;
    protected $seller_order_action = null;
    protected $refund = null;
    protected $cart = null;
    public function __construct(Cart $cart, Order $order, OrderAsset $order_asset, SellerOrderAction $seller_order_action, Refund $refund)
    {
        $this->order = $order;
        $this->order_asset = $order_asset;
        $this->seller_order_action = $seller_order_action;
        $this->refund = $refund;
        $this->cart = $cart;
    }

    public function sessionStore(Request $request)
    {
        $test_session = $request->session()->put('esewa_order_data', $request->all());
    }

    public function successOrderEsewa(Request $request)
    {
        $user = auth()->guard('customer')->user();
        $esewa_order_Info = session()->get('esewa_order_data');
        $esewa_order_data = session()->get('esewa__order');

        if (!$user) {
            $request->session()->flash('error', 'Plz Login First');
            return redirect()->route('cart.index');
        }

        if ($esewa_order_Info == null) {
            $request->session()->flash('error', 'Your Personal Info is not saved.');
            return back();
        }

        if ($esewa_order_data == null) {
            $request->session()->flash('error', 'Yout data is not saved.');
            return back();
        }

        if ($esewa_order_Info != null) {
            $coupon_discount_price = $esewa_order_Info['coupan_code_price'];
            $coupoun_code_name = $esewa_order_Info['coupan_code_name'];
            $coupoun__name = $esewa_order_Info['coupan_code_seriel'];
        } else {
            $coupon_discount_price = 0;
            $coupoun_code_name = null;
            $coupoun__name = null;
        }
        // return $esewa_order_data;        
        $shipping_address = UserShippingAddress::where('id', $esewa_order_Info['ship_id'])->where('user_id', $user->id)->first();
        $shipping_charge = $shipping_address->getLocation->deliveryRoute->charge;
        $billing_address = UserBillingAddress::where('id', $esewa_order_Info['billing_address'])->where('user_id', $user->id)->first();
        if ($esewa_order_Info['billing_address'] == null) {
            $billing_address = $shipping_address;
        }

        $this->cart = Cart::where('user_id', $user->id)->first();
        if (!$this->cart) {
            $request->session()->flash('error', 'Your Cart Is Empty !! Plz Add Some Project');
            return redirect()->route('index');
        }

        $cart_item = [];
        foreach ($esewa_order_data['items'] as $key => $item) {
            $cart_item[] = CartAssets::where('id', $key)->where('cart_id', $this->cart->id)->first();
        }

        $total_quantity = null;
        $total_price = null;
        $total_discount = null;

        foreach ($cart_item as $cItem) {
            $total_quantity = $total_quantity + $cItem->qty;
            $total_price = $total_price + $cItem->sub_total_price;
            $total_discount = $total_discount + $cItem->discount;
        }

        $fixed_price = $total_price + $shipping_charge - (int)$coupon_discount_price;

        $url = "https://uat.esewa.com.np/epay/transrec";
        $data = [
            'amt' => $fixed_price,
            'rid' => $_REQUEST['refId'],
            'pid' => $esewa_order_Info['esewa_pid'],
            'scd' => 'EPAYTEST',
        ];

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);

        if (strpos($response, "Success") !== false) {
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
                    'ref_id' => $data['rid'],
                    // 'ref_id'  => 'test',
                    'shipping_charge' => $shipping_charge,
                    'total_discount' => $total_discount,
                    'payment_status' => '0',
                    'merchant_name' => null,
                    'payment_with' => 'e-Sewa',
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
                    'b_zip' => $billing_address->zip,
                    'admin_email' => env('MAIL_FROM_ADDRESS'),
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
                            'product_id' => $product->product_id,
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

                // $this->sendMailtoAdmin($order);
                // $this->sendMailtoCustomer($order);
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

                DB::commit();
                session()->forget('esewa_order_data');
                session()->forget('esewa__order');
                $request->session()->flash('success', 'Your Order Has been Created Successfully !!');
                return redirect()->route('invoice',$this->order->id);
                // return redirect()->route('Corder');
            } catch (\Throwable $th) {
                $request->session()->flash('error', 'OOPs Please Try Again');
            }
            return $request;
        } else {
            return "Hey Dear !, Failled To Pay, You Are Cheating... !!!";
        }
    }

    public function sendMailtoAdmin($order)
    {
        Mail::send('email.order-product.admin_email', $order, function ($message) use ($order) {
            $message->subject('Ordering From Customer');
            $message->from($order['email']);
            $message->to($order['admin_email']);
        });
    }

    public function sendMailtoCustomer($order)
    {
        Mail::send('email.order-product.customer_email', $order, function ($message) use ($order) {
            $message->subject('Ordering Product');
            $message->from($order['admin_email']);
            $message->to($order['email']);
        });
    }
}
