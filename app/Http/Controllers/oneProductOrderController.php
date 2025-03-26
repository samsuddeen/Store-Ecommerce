<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\seller;
use App\Models\Product;
use App\Models\OrderAsset;
use App\Helpers\EmailSetUp;
use Illuminate\Support\Str;
use App\Events\PaymentEvent;
use App\Models\ProductImage;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use App\Models\Refund\Refund;
use App\Actions\Mail\MailSetup;
use App\Events\OrderPlacedEvent;
use App\Models\UserBillingAddress;
use Illuminate\Support\Facades\DB;
use App\Models\UserShippingAddress;
use App\Http\Controllers\Controller;
use App\Models\CustomerAllUsedCoupon;
use App\Models\Order\Seller\SellerOrder;
use App\Data\Form\PaymentHistoryFormData;
use App\Enum\MessageSetup\MessageSetupEnum;
use App\Models\Order\Seller\ProductSellerOrder;
use App\Actions\Notification\NotificationAction;
use App\Models\Admin\Coupon\Customer\CustomerCoupon;

class oneProductOrderController extends Controller
{
    protected $order = null;
    protected $order_asset = null;
    protected $seller_order = null;
    protected $product_seller_order = null;
    protected $refund = null;

    public function __construct(Order $order, OrderAsset $order_asset, ProductSellerOrder $product_seller_order, SellerOrder $seller_order, Refund $refund)
    {
        $this->order = $order;
        $this->order_asset = $order_asset;
        $this->seller_order = $seller_order;
        $this->product_seller_order = $product_seller_order;
        $this->refund = $refund;
    }

    public function oneProductCheckout(Request $request)
    {

        // dd('ok direct test new');
        $data = session()->get('khalti1-direct-payment-order-data');

        // khali server intgratin----------------------------------------------------------------------------------------                               
        $refId = $request->pid;
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
        // Response
        $response = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        // dd($status_code);
        curl_close($ch);
        // khali server intgratin----------------------------------------------------------------------------------------
        $ref_id='OD'.rand(100000,999999);
        if ($status_code == 200)
        {
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
                    'admin_email' => env('MAIL_FROM_ADDRESS'),
                    'b_zip' => $data['billing_address']->zip,
                    'vat_amount'=>$data['vat_amount'] ?? 0
                ];
                $order_id = $this->order->fill($order);
                $status = $this->order->save();
                $product_main = Product::where('id', $data['product']->product_id)->first();
                $seller = [];

                $temp = [
                    'order_id' => $this->order->id,
                    'product_id' => $data['product']->product_id,
                    'product_name' => $product_main->name,
                    'price' => $data['product']->price,
                    'qty' => $data['total_quantity'],
                    'sub_total_price' => (int)$data['total_price'],
                    'image' => $data['product_image'],
                    'color' => $data['for_order']['color_id'],
                    'discount' => (int)$data['total_discount']/(int)$data['total_quantity'],
                    'options' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'vatamountfield'=>$data['vat_amount'] ?? 0
                ];

                $this->order_asset->insert($temp);
                // dd('ok');
                // dd($data);
                // ----------------------------------Seller Code----------------------------------------
                $seller_order = [
                    'order_id' => $order_id->id,
                    'seller_id' => $product_main->seller_id ?? '',
                    'product_id' => $product_main->id,
                    'price' => $data['total_price'],
                    'sub_total' => $data['total_price'],
                    'user_id' => auth()->guard('customer')->user()->id,
                    'qty' => $data['for_order']['product_qty'],
                    'subtotal' => $data['total_price'],
                    'total_discount' => $data['total_discount'],
                    'total' => $data['total_price'] + $data['total_discount'],
                    'vatAmount'=>$order_id->vat_amount
                ];
                if ($data['main_product']->seller_id != null) {
                    $this->seller_order->fill($seller_order);
                    $this->seller_order->save();
                }
                $discountpercent = $data['total_discount'] * 100 /  $data['total_price'];
                $discount_percent = $discountpercent . '%';
                $product_seller_order = [
                    'order_id' => $order_id->id,
                    'seller_id' => $product_main->seller_id,
                    'product_id' => $product_main->id,
                    'price' =>  $data['total_price'],
                    'sub_total' =>  $data['total_price'],
                    'user_id' => auth()->guard('customer')->user()->id,
                    'seller_order_id' => $this->seller_order->id ?? '',
                    'qty' => $data['for_order']['product_qty'],
                    'subtotal' =>  $data['total_price'],
                    'discount' => $data['total_discount'],
                    'image' => $data['product_image'],
                    'discount_percent' => $discount_percent,
                    'total' => $data['fixed_price'],
                    'total_discount' => $data['total_discount'],
                ];
                // ----------------------------------Seller Code----------------------------------------
                if ($data['main_product'] != null) {
                    $this->product_seller_order->fill($product_seller_order);
                    $this->product_seller_order->save();

                    $notification_data = [
                        'from_model' => get_class(auth()->guard('customer')->user()->getModel()),
                        'from_id' => auth()->guard('customer')->user()->id,
                        'to_model' => get_class(seller::getModel()),
                        'to_id' => $product_main->seller_id,
                        'title' => 'New Order From ' . ucfirst(auth()->guard('customer')->user()->name),
                        'summary' => 'You Have New Order Request',
                        'url' => url('/seller/seller-order/' . $this->seller_order->id),
                        'is_read' => false,
                    ];

                    (new NotificationAction($notification_data))->store();
                }
                $notification_data = [
                    'from_model' => get_class(auth()->guard('customer')->user()->getModel()),
                    'from_id' => auth()->guard('customer')->user()->id,
                    'to_model' => get_class(User::getModel()),
                    'to_id' => User::first()->id,
                    'title' => 'New Order From ' . ucfirst(auth()->guard('customer')->user()->name),
                    'summary' => 'You Have New Order Request',
                    'url' => url('/admin/view-order/' . $this->order->ref_id),
                    'is_read' => false,
                ];
                (new NotificationAction($notification_data))->store();
                $notification_data = [
                    'from_model' => get_class(auth()->guard('customer')->user()->getModel()),
                    'from_id' => auth()->guard('customer')->user()->id,
                    'to_model' => ($product_main->seller_id) ? get_class(seller::getModel()) : get_class(User::getModel()),
                    'to_id' => $product_main->seller_id ?? User::first()->id,
                    'title' => 'New Order',
                    'summary' => 'You Have New Order Request',
                    'url' => route('admin.viewOrder', $this->order->ref_id),
                    'is_read' => false,
                ];
                (new NotificationAction($notification_data))->store();
                // $this->sendMailtoAdmin($order);
                // $this->sendMailtoCustomer($order);
                $filters = [
                    'title' => MessageSetupEnum::ORDER_PLACE,
                ];
                (new MailSetup($filters))->setToFile();
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
                    'Refund Paid To the Admin ',
                    false,
                    true
                ))->getData();
                PaymentEvent::dispatch($paymentData);
                if($data['coupoun_code_name'])
                {
                    $coupon_data=CustomerCoupon::where('code',$data['coupoun_code_name'])->first();
                    if(!$coupon_data)
                    {
                        $request->session()->flash('error', 'Something is Wrong !!!');
                        return redirect()->route('index'); 
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
                                'customer_id'=>$data['user']->id,
                                'coupon_code'=>$coupon_data->code
                            ]);
                    }

                }
                DB::commit();
                EmailSetUp::sendMail(MessageSetupEnum::ORDER_PLACE, $data = $this->order, $data['user']);
                $request->session()->forget('directOrder');

                return redirect()->route('invoice', $this->order->id);
            } catch (\Exception $ex) {
                DB::rollBack();
                $request->session()->flash('error', 'Something Went Wrong !!');
                return redirect()->route('index');
            }
        }
        else
        {
            $request->session()->flash('error','Something Went Wrong !!');
               return redirect()->route('index');
        }
            
        
    }

    public function oneProductEsewaCheckout(Request $request)
    {

       
        if($request->q=='su')
        {
            $data = session()->get('khalti1-direct-payment-order-data');
            // dd($data);
            // esewa server intgratin----------------------------------------------------------------------------------------                               
            $refId = $request->oid;
            $url = "https://esewa.com.np/epay/transrec";
            $data1 = [
                'amt'=> (int)$data['fixed_price'],
                'rid'=> $request->refId,
                'pid'=>$request->oid,
                'scd'=> 'NP-ES-ULTRASOFT'
            ];
    
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data1);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
            curl_close($curl);
            // dd($data);
            $ref_id='OD'.rand(100000,999999);
            if (strpos($response, "Success") !== false)
            {
                DB::beginTransaction();
                try {
                    $order = [
                        'user_id' => $data['user']->id,
                        'aff_id' => $request->oid,
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
                        'admin_email' => env('MAIL_FROM_ADDRESS'),
                        'b_zip' => $data['billing_address']->zip,
                        'vat-amount'=>$data['vat_amount'] ?? 0
                    ];
                    $order_id = $this->order->fill($order);
                    $status = $this->order->save();
                    $product_main = Product::where('id', $data['product']->product_id)->first();
                    $seller = [];
    
                    $temp = [
                        'order_id' => $this->order->id,
                        'product_id' => $data['product']->product_id,
                        'product_name' => $product_main->name,
                        'price' => $data['product']->price,
                        'qty' => $data['total_quantity'],
                        'sub_total_price' => (int)$data['total_price'],
                        'image' => $data['product_image'],
                        'color' => $data['for_order']['color_id'],
                        'discount' => (int)$data['total_discount']/(int)$data['total_quantity'],
                        'options' => 1,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'vatamountfield'=>$data['vat_amount'] ?? 0

                    ];
                    // dd($temp);
    
                    $this->order_asset->insert($temp);
                    // dd('ok');
                    // dd($data);
                    // ----------------------------------Seller Code----------------------------------------
                    $seller_order = [
                        'order_id' => $order_id->id,
                        'seller_id' => $product_main->seller_id ?? '',
                        'product_id' => $product_main->id,
                        'price' => $data['total_price'],
                        'sub_total' => $data['total_price'],
                        'user_id' => auth()->guard('customer')->user()->id,
                        'qty' => $data['for_order']['product_qty'],
                        'subtotal' => $data['total_price'],
                        'total_discount' => $data['total_discount'],
                        'total' => $data['total_price'] + $data['total_discount'],
                        'vatAmount'=>$order_id->vat_amount
                    ];
                    if ($data['main_product']->seller_id != null) {
                        $this->seller_order->fill($seller_order);
                        $this->seller_order->save();
                    }
                    $discountpercent = $data['total_discount'] * 100 /  $data['total_price'];
                    $discount_percent = $discountpercent . '%';
                    $product_seller_order = [
                        'order_id' => $order_id->id,
                        'seller_id' => $product_main->seller_id,
                        'product_id' => $product_main->id,
                        'price' =>  $data['total_price'],
                        'sub_total' =>  $data['total_price'],
                        'user_id' => auth()->guard('customer')->user()->id,
                        'seller_order_id' => $this->seller_order->id ?? '',
                        'qty' => $data['for_order']['product_qty'],
                        'subtotal' =>  $data['total_price'],
                        'discount' => $data['total_discount'],
                        'image' => $data['product_image'],
                        'discount_percent' => $discount_percent,
                        'total' => $data['fixed_price'],
                        'total_discount' => $data['total_discount'],
                    ];
                    // ----------------------------------Seller Code----------------------------------------
                    if ($data['main_product'] != null) {
                        $this->product_seller_order->fill($product_seller_order);
                        $this->product_seller_order->save();
    
                        $notification_data = [
                            'from_model' => get_class(auth()->guard('customer')->user()->getModel()),
                            'from_id' => auth()->guard('customer')->user()->id,
                            'to_model' => get_class(seller::getModel()),
                            'to_id' => $product_main->seller_id,
                            'title' => 'New Order From ' . ucfirst(auth()->guard('customer')->user()->name),
                            'summary' => 'You Have New Order Request',
                            'url' => url('/seller/seller-order/' . $this->seller_order->id),
                            'is_read' => false,
                        ];
    
                        (new NotificationAction($notification_data))->store();
                    }
                    $notification_data = [
                        'from_model' => get_class(auth()->guard('customer')->user()->getModel()),
                        'from_id' => auth()->guard('customer')->user()->id,
                        'to_model' => get_class(User::getModel()),
                        'to_id' => User::first()->id,
                        'title' => 'New Order From ' . ucfirst(auth()->guard('customer')->user()->name),
                        'summary' => 'You Have New Order Request',
                        'url' => url('/admin/view-order/' . $this->order->ref_id),
                        'is_read' => false,
                    ];
                    (new NotificationAction($notification_data))->store();
                    $notification_data = [
                        'from_model' => get_class(auth()->guard('customer')->user()->getModel()),
                        'from_id' => auth()->guard('customer')->user()->id,
                        'to_model' => ($product_main->seller_id) ? get_class(seller::getModel()) : get_class(User::getModel()),
                        'to_id' => $product_main->seller_id ?? User::first()->id,
                        'title' => 'New Order',
                        'summary' => 'You Have New Order Request',
                        'url' => route('admin.viewOrder', $this->order->ref_id),
                        'is_read' => false,
                    ];
                    (new NotificationAction($notification_data))->store();
                    // $this->sendMailtoAdmin($order);
                    // $this->sendMailtoCustomer($order);
                    $filters = [
                        'title' => MessageSetupEnum::ORDER_PLACE,
                    ];
                    (new MailSetup($filters))->setToFile();
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
                        'Refund Paid To the Admin ',
                        false,
                        true
                    ))->getData();
                    PaymentEvent::dispatch($paymentData);
                    if($data['coupoun_code_name'])
                {
                    $coupon_data=CustomerCoupon::where('code',$data['coupoun_code_name'])->first();
                    if(!$coupon_data)
                    {
                        $request->session()->flash('error', 'Something is Wrong !!!');
                        return redirect()->route('index'); 
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
                                'customer_id'=>$data['user']->id,
                                'coupon_code'=>$coupon_data->code
                            ]);
                    }

                }
                    DB::commit();
                    EmailSetUp::sendMail(MessageSetupEnum::ORDER_PLACE, $data = $this->order, $data['user']);
                    $request->session()->forget('directOrder');
    
                    return redirect()->route('invoice', $this->order->id);
                } catch (\Exception $ex) {
                    DB::rollBack();
                    $request->session()->flash('error', 'Something Went Wrong !!');
                    return redirect()->route('index');
                }
            }
            else
            {
                $request->session()->flash('error','Something Went Wrong !!');
                   return redirect()->route('index');
            }
        }
        else
        {
            $request->session()->flash('error','Something Went Wrong !!');
            return redirect()->route('index');
        }
        
            
        
    }

    public function oneProductEsewa(Request $request)
    {

        
        $user = auth()->guard('customer')->user();
        $for_order =  $request->session()->get('directOrder');
        if ($for_order === null) {
            $request->session()->flash('error', 'Plz Try again, Something is wrong');
            return back();
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

        $user_info = $request->session()->get('esewa_order_data');

        $shipping_id = $user_info['ship_id'];
        $billing_id = $user_info['billing_address'];

        $shipping_address = UserShippingAddress::where('id', $shipping_id)->where('user_id', $user->id)->first();

        if ($billing_id != null) {
            $billing_address = UserBillingAddress::where('id', $billing_id)->where('user_id', $user->id)->first();
        } else {
            $billing_address = $shipping_address;
        }

        $shipping_charge = $shipping_address->getLocation->deliveryRoute->charge;

        $main_product = Product::where('id', $for_order['product_id'])->first();
        $product = ProductStock::where('product_id', $for_order['product_id'])->where('color_id', $for_order['color_id'])->where('id', $for_order['varient_id'])->first();
        $product_image = ProductImage::where('product_id', $for_order['product_id'])->where('color_id', $for_order['color_id'])->first()->image;
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

        $fixed_price = $total_price + $shipping_charge - (int)$coupon_discount_price;
        $url = "https://uat.esewa.com.np/epay/transrec";
        $data = [
            'amt' => $fixed_price,
            'rid' => $_REQUEST['refId'],
            'pid' => $user_info['esewa_pid'],
            'scd' => 'EPAYTEST',
        ];

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);

        if (strpos($response, "Success") !== false) {
            // DB::beginTransaction();
            // try {

            $order = [
                'user_id' => $user->id,
                'aff_id' => Str::random(10) . rand(100, 1000),
                'total_quantity' => $total_quantity,
                'total_price' => $fixed_price,
                'coupon_name' => $coupoun__name,
                'coupon_code' => $coupoun_code_name,
                'coupon_discount_price' => $coupon_discount_price,
                'ref_id' => Str::random(6) . rand(100, 1000),
                'shipping_charge' => $shipping_charge,
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
                'b_zip' => $billing_address->zip
            ];

            $order_id = $this->order->fill($order);
            $status = $this->order->save();

            $product_main = Product::where('id', $product->product_id)->first();
            $seller = [];
            $temp = [
                'order_id' => $this->order->id,
                'product_id' => $product->product_id,
                'product_name' => $product_main->name,
                'price' => $fixed_price,
                'qty' => $total_quantity,
                'sub_total_price' => $fixed_price,
                'image' => $product_image,
                'color' => $product->color,
                'discount' => $total_discount,
                'options' => $options,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            $this->order_asset->insert($temp);
            // ----------------------------------Seller Code----------------------------------------

            // $seller_product[] = [
            //     'product' => [
            //         'product_id' => $product->product_id,
            //         'qty' => $for_order['product_qty'],
            //         'price' => $product->price,
            //         'total' => $product->price,
            //         'discount' => $product->discount,
            //         'sub_total_price' => $product->sub_total_price
            //     ],
            //     'seller' => Product::where('id', $product->product_id)->first()->user->id ?? null,
            // ];

            // $this->sellerOrder($product['product_id'], $this->order, $temp, $seller_product);  


            $seller_order = [
                'order_id' => $order_id->id,
                'seller_id' => $product_main->seller_id ?? null,
                'product_id' => $product_main->id,
                'price' => $total_price,
                'sub_total' => $total_price,
                'user_id' => auth()->guard('customer')->user()->id,
                'seller_order_id' => '1',
                'qty' => $for_order['product_qty'],
                'subtotal' => $total_price,
                'total_discount' => $total_discount,
                'total' => $total_price + $total_discount,
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
            }

            $notification_data = [
                'from_model' => get_class(auth()->guard('customer')->user()->getModel()),
                'from_id' => auth()->guard('customer')->user()->id,
                'to_model' =>  get_class(User::getModel()),
                'to_id' =>  User::first()->id,
                'title' => 'New Order From ' . ucfirst(auth()->guard('customer')->user()->name),
                'summary' => 'You Have New Order Request',
                'url' => url('/admin/view-order/' . $this->order->ref_id),
                'is_read' => false,
            ];

            (new NotificationAction($notification_data))->store();

            $discountpercent = $total_discount * 100 / $total_price;
            $discount_percent = $discountpercent . '%';

            $product_seller_order = [
                'order_id' => $order_id->id,
                'seller_id' => $product_main->seller_id ?? null,
                'product_id' => $product_main->id,
                'price' => $total_price,
                'sub_total' => $total_price,
                'user_id' => auth()->guard('customer')->user()->id,
                'seller_order_id' => $this->seller_order->id,
                'qty' => $for_order['product_qty'],
                'subtotal' => $total_price,
                'discount' => $total_discount,
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

            // Notification---

            // $this->sendMailtoAdmin($order);
            // $this->sendMailtoCustomer($order);
            // DB::commit();

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
            PaymentEvent::dispatch($paymentData);

            $request->session()->forget('directOrder');
            $request->session()->forget('esewa_order_data');
            $request->session()->flash('success', 'Your Order Has been Created Successfully !!');
            return redirect()->route('Corder');
            // } catch (\Exception $ex) {
            // DB::rollBack();
            // $request->session()->flash('error', 'Something is Wrong !!!');
            // return redirect()->route('cart.index');
            // }
        } else {
            return "Hey Dear !, Failled To Pay, You Are Cheating... !!!";
        }
    }
}
