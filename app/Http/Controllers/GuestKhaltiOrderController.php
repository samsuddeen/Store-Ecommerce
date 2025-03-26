<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Color;
use App\Models\Local;
use App\Models\Order;
use App\Models\seller;
use App\Models\Product;
use App\Models\Location;
use App\Models\OrderAsset;
use App\Helpers\EmailSetUp;
use Illuminate\Support\Str;
use App\Events\PaymentEvent;
use App\Models\New_Customer;
use App\Models\ProductImage;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use App\Models\DeliveryRoute;
use App\Models\Refund\Refund;
use App\Actions\Mail\MailSetup;
use App\Events\OrderPlacedEvent;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Models\Order\Seller\SellerOrder;
use App\Data\Form\PaymentHistoryFormData;
use App\Enum\MessageSetup\MessageSetupEnum;
use App\Models\Order\Seller\ProductSellerOrder;
use App\Actions\Notification\NotificationAction;

class GuestKhaltiOrderController extends Controller
{
    protected $order = null;
    protected $order_asset = null;
    protected $seller_order = null;
    protected $product_seller_order = null;
    protected $refund = null;

    public function __construct(Order $order, OrderAsset $order_asset, SellerOrder $seller_order, ProductSellerOrder $product_seller_order, Refund $refund)
    {
        $this->order = $order;
        $this->order_asset = $order_asset;
        $this->seller_order = $seller_order;
        $this->product_seller_order = $product_seller_order;
        $this->refund = $refund;
    }

    public function singleProductOrder(Request $request)
    {
        $data=session()->get('khalti-payment-guest-single');
            
        $refId = strtoupper(Str::random(6) . rand(100, 1000));
        $args = http_build_query(array(
            "pidx"=>$request->pidx
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
        // dd($data['guest_info']['email']);     
        curl_close($ch);
        // ------------------------------------------------------------------------------------------------------------------------

        $color = Color::where('id', $data['for_order']['color_id'])->first();
        // dd($product->image[0]->image);               

        if ($status_code == 200) {
            DB::beginTransaction();
            try {  
            $order = [
                'user_id' => 1,
                'aff_id' => $request->pidx,
                'total_quantity' => $data['total_quantity'],
                'total_price' => $data['fixed_price'],
                'ref_id' => strtoupper(Str::random(6) . rand(100, 1000)),
                'shipping_charge' => $data['shipping_charge'],
                'total_discount' => $data['total_discount'],
                'payment_status' => '0',
                'merchant_name' => null,
                'payment_with' => 'Khalti',
                'payment_date' => date('Y-m-d'),
                // 'transaction_ref_id' => $request->refId,
                'name' => $data['guest_info']['name'],
                'email' => $data['guest_info']['email'],
                'phone' => $data['guest_info']['phone'],
                'province' => $data['guest_info']['province'],
                'district' => $data['guest_info']['district'],
                'area' => $data['address']->local_name,
                'additional_address' => $data['additional_address']->title,
                'zip' => 1,
                'b_name' => $data['guest_info']['name'],
                'b_email' => $data['guest_info']['email'],
                'b_phone' => $data['guest_info']['phone'],
                'b_province' => $data['guest_info']['province'],
                'b_district' => $data['guest_info']['district'],
                'b_area' => $data['address']->local_name,
                'b_additional_address' => $data['additional_address']->title,
                'admin_email' => env('MAIL_FROM_ADDRESS'),
                'b_zip' => 1
            ];
            

            $order_id = $this->order->fill($order);
            $status = $this->order->save();
            $product_main = Product::where('id', $data['product']->product_id)->first();
            $seller = [];
            $temp = [
                'order_id' => $this->order->id,
                'product_id' => $product_main->id,
                'product_name' => $product_main->name,
                'price' => $data['product_price'],
                'qty' => $data['total_quantity'],
                'sub_total_price' => $data['product_price']*$data['total_quantity'],
                'color' => $color->id,
                'image' => $data['product_image'],
                'discount' => $data['total_discount'],
                'options' => json_encode($data['options']),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            // dd($temp);

            $this->order_asset->insert($temp);

            // ----------------------------------Seller Code---------------------------------------     
            $seller_order = [
                'order_id' => $order_id->id,
                'seller_id' => $product_main->seller_id ?? 6,
                'product_id' => $product_main->id,
                'price' => $data['total_price'],
                'sub_total' => $data['total_price'],
                'user_id' => '1',
                'seller_order_id' => '1',
                'qty' => $data['for_order']['product_qty'],
                'subtotal' => $data['total_price'],
                'total_discount' => $data['total_discount'],
                'total' => $data['total_price'] + $data['total_discount'],
            ];
            if ($product_main->seller_id != null) {
                $this->seller_order->fill($seller_order);
                $this->seller_order->save();
                $customer = New_Customer::first();

                $notification_data = [
                    'from_model' => get_class($customer->getModel()),
                    'from_id' => $customer->id,
                    'to_model' => ($product_main->seller_id) ? get_class(seller::getModel()) : get_class(User::getModel()),
                    'to_id' => $product_main->seller_id ?? User::first()->id,
                    'title' => 'New Order From ' . $customer->name,
                    'summary' => 'You Have New Order Request',
                    'url' => url('/seller/seller-order/' . $this->seller_order->id),
                    'is_read' => false,
                ];
                (new NotificationAction($notification_data))->store();
            } else {
                $customer = New_Customer::first();
                $notification_data = [
                    'from_model' => get_class($customer->getModel()),
                    'from_id' => $customer->id,
                    'to_model' => get_class(User::getModel()),
                    'to_id' => User::first()->id,
                    'title' => 'New Order From ' . $customer->name,
                    'summary' => 'You Have New Order Request',
                    'url' => url('/admin/view-order/' . $this->order->ref_id),
                    'is_read' => false,
                ];

                (new NotificationAction($notification_data))->store();
            }
            $discountpercent = $data['total_discount'] * 100 / $data['total_price'];
            $discount_percent = $discountpercent . '%';
            $product_seller_order = [
                'order_id' => $order_id->id,
                'seller_id' => $product_main->seller_id ?? 6,
                'product_id' => $product_main->id,
                'price' => $data['total_price'],
                'sub_total' => $data['total_price'],
                'user_id' => '1',
                'seller_order_id' => '1',
                'qty' => $data['for_order']['product_qty'],
                'subtotal' => $data['total_price'],
                'discount' =>$data['total_discount'],
                'image' => $data['product_image'],
                'discount_percent' => $discount_percent,
                'total' => $data['fixed_price'],
                'total_discount' => $data['total_discount'],
            ];
            if ($product_main->user_id != null) {
                $this->product_seller_order->fill($product_seller_order);
                $this->product_seller_order->save();
            }

            // ----------------------------------Seller Code----------------------------------------
            $user_model = New_Customer::where('id', 1)->first();
            $admin = User::first();
            $paymentData = (new PaymentHistoryFormData(
                get_class($user_model->getModel()),
                1,
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
            EmailSetUp::guestOrderMail(MessageSetupEnum::ORDER_PLACE, $data = $this->order,$this->order->email);
            $request->session()->forget('guest_order_checkout');
            DB::commit();
            return redirect()-> route('invoice',$this->order->id);
            } catch (\Exception $ex) {
            DB::rollBack();
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



    // allProduct Order 
    public function allProductORder(Request $request)
    {
        $data=session()->get('khalti-payment-guest-all-detail');
       
        $args = http_build_query(array(
            'pidx'=>$request->pidx
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
        curl_close($ch);

        // khali server intgratin----------------------------------------------------------------------------------------        
        // dd($data);
        if ($status_code == 200) {
            DB::beginTransaction();
            try {                        

            $order = [
                'user_id' => 1,
                'aff_id' => $request->pidx,
                'total_quantity' => $data['total_quantity'],
                'total_price' => $data['fixed_price'],
                // 'coupon_name' => $coupoun__name,
                // 'coupon_code' => $coupoun_code_name,
                // 'coupon_discount_price' => $coupon_discount_price,
                'ref_id' => strtoupper(Str::random(6) . rand(100, 1000)),
                'shipping_charge' => $data['shipping_charge'],
                'total_discount' => $data['total_discount'],
                'payment_status' => '0',
                'merchant_name' => null,
                'payment_with' => 'Khalti/Test-Paid',
                'payment_date' => date('Y-m-d'),
                // 'transaction_ref_id' => $request->refId,
                'name' => $data['guest_info']['name'],
                'email' => $data['guest_info']['email'],
                'phone' => $data['guest_info']['phone'],
                'province' => $data['guest_info']['province'],
                'district' => $data['guest_info']['district'],
                'area' => $data['area_address']->local_name,
                'additional_address' => $data['additional_address']->title,
                'zip' => 1,
                'b_name' => $data['guest_info']['name'],
                'b_email' => $data['guest_info']['email'],
                'b_phone' => $data['guest_info']['phone'],
                'b_province' => $data['guest_info']['province'],
                'b_district' => $data['guest_info']['district'],
                'b_area' => $data['area_address']->local_name,
                'b_additional_address' => $data['additional_address']->title,
                'admin_email' => env('MAIL_FROM_ADDRESS'),
                'b_zip' => 1
            ];

            $order_id = $this->order->fill($order);
            $status = $this->order->save();

            $seller = [];
            $temp = [];
            $seller_product = [];

            
            foreach ($data['order_item'] as $key => $product) {
                $productstock=ProductStock::where('id',$product['varient_id'])->first();
                $color_id=$productstock->color_id;
                
                $seller_product_id[] = $product['product_id'];
                $seller_product[] = [
                    'product' => [
                        'product_id' => $product['product_id'],
                        'order_id' => $order_id->id,
                        'qty' => $product['qty'],
                        'price' => $product['price'],
                        'total' => $product['price'],
                        'discount' => $product['discount'],
                        'sub_total_price' => $product['pprice']
                    ],
                    'seller' => Product::where('id', $product['product_id'])->first()->seller_id ?? 6,
                ];

                $temp[] = [
                    'order_id' => $order_id->id,
                    'product_id' => $product['product_id'],
                    'product_name' => $product['product_name'],
                    'price' => $product['price'],
                    'qty' => $product['qty'],
                    'sub_total_price' => $product['pprice'],
                    'color' => $color_id,
                    'image' => $product['image'],
                    'discount' => $product['discount'] * $product['qty'],
                    'options' => $product['options'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            
            }



            $this->order_asset->insert($temp);

            // ----------------------------------Seller Code----------------------------------------                

            $this->sellerOrder($seller_product_id, $this->order, $temp, $seller_product);

            // ----------------------------------Seller Code----------------------------------------          
          
            

            // $filters = [
            //     'title'=>MessageSetupEnum::ORDER_PLACE,
            // ];
            // (new MailSetup($filters))->setToFile();

            // event(new OrderPlacedEvent($this->order,$user));

            DB::commit();
            // $request->session()->forget('cart_order_item');
            // $request->session()->flash('success', 'Your Order Has been Created Successfully !!');
            $request->session()->forget('guest_cart');
            $request->session()->forget('total_cart_amount');
            $request->session()->forget('total_cart_qty');

            EmailSetUp::guestOrderMail(MessageSetupEnum::ORDER_PLACE, $data = $this->order, $this->order->email);
            

            $admin = User::first();
            $user_model = New_Customer::where('id', 1)->first();
            $paymentData = (new PaymentHistoryFormData(
                get_class($user_model->getModel()),
                1,
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
            return redirect()->route('invoice',$this->order->id);
            } catch (\Exception $ex) {
                DB::rollBack();
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

    public function sellerOrder($seller_product_id, $order, $order_asset, $seller_product)
    {

        $seller_id = [];
        $seller_wise_product = [];
        foreach ($seller_product_id as $key => $productId) {
            $productDetail = Product::where('id', $productId)->first();
            $seller_wise_product[$productDetail->user_id] = $productDetail;
            $seller_ids[$key]['id'] = $productDetail->user_id;
        }
        $total_seller = collect($seller_ids)->groupBy('id');

        if (count($total_seller) > 1) {
            foreach (collect($seller_product)->groupBy('seller') as $key => $s_product) {
                $total_price = null;
                $total_qty = null;
                $seller_id = null;
                $discount = null;
                $sub_total_price = null;
                $order_id = null;
                $discount_percent = null;
                $seller_temp = [];
                foreach ($s_product as $p) {

                    $total_price = $total_price + $p['product']['price'];
                    $total_qty = $total_qty + $p['product']['qty'];
                    $discount = $discount + $p['product']['discount'];
                    $seller_id = $p['seller'];
                    $sub_total_price = $sub_total_price + $p['product']['sub_total_price'];
                }
                $seller_data['user_id'] = $order->user_id;
                $seller_data['order_id'] = $order->id;
                $seller_data['seller_id'] = $seller_id;
                $seller_data['qty'] = $total_qty;
                $seller_data['subtotal'] = $sub_total_price;
                // $seller_data['delivery_charge'] = $order->shipping_charge;
                $seller_data['total'] = $total_price;
                $seller_data['total_discount'] = $discount;
                $seller_create = new SellerOrder();
                $seller_create->fill($seller_data);
                $seller_create->save();
                foreach ($s_product as $p) {

                    $discount_per_pcs = $p['product']['discount'] / $p['product']['qty'];
                    $original_price_for_discount = ($p['product']['price'] + $discount_per_pcs) * $p['product']['qty'];
                    $discountpercent = $p['product']['discount'] * 100 / $original_price_for_discount;
                    $discount_percent = $discountpercent . '%';

                    $seller_temp[] = [
                        'product_id' => $p['product']['product_id'],
                        'qty' => $p['product']['qty'],
                        'price' => $p['product']['sub_total_price'],
                        'sub_total' => $p['product']['price'],
                        'order_id' => $p['product']['order_id'],
                        'discount_percent' => $discount_percent,
                        'discount' => $p['product']['discount'],
                        // 'image'=>$p['product']['image'],
                        'seller_order_id' => $seller_create->id
                    ];
                }
                $this->product_seller_order->insert($seller_temp);
            }
        } else {
            foreach ($total_seller as $key => $seller_id) {
                $seller = User::where('id', $key)->first();
            }
            $seller_data['user_id'] = 1;
            $seller_data['order_id'] = $order->id;
            $seller_data['seller_id'] = $seller->id;
            $seller_data['qty'] = $order->total_quantity;
            // $seller_data['subtotal'] = $order->total_price-$order->shipping_charge;
            $seller_data['subtotal'] = $order->total_price - $order->shipping_charge;
            $seller_data['order_id'] = $order->id;
            $seller_data['delivery_charge'] = $order->shipping_charge;
            $seller_data['total'] = $order->total_discount + $seller_data['subtotal'];
            $seller_data['total_discount'] = $order->total_discount;

            $order_id = $this->seller_order->fill($seller_data);
            $this->seller_order->save();

            if ($this->seller_order) {
                $seller_temp = [];
                foreach ($order_asset as $asset) {

                    $discount_per_pcs = $asset['discount'] / $asset['qty'];
                    $original_price_for_discount = ($asset['price'] + $discount_per_pcs) * $asset['qty'];
                    $discountpercent = $asset['discount'] * 100 / $original_price_for_discount;
                    $discount_percent = $discountpercent . '%';

                    $seller_temp[] = [
                        'seller_order_id' => $this->seller_order->id,
                        'product_id' => $asset['product_id'],
                        'qty' => $asset['qty'],
                        'price' => $asset['price'],
                        'sub_total' => $asset['sub_total_price'],
                        'order_id' => $asset['order_id'],
                        'discount' => $asset['discount'],
                        'discount_percent' => $discount_percent,
                        'image' => $asset['image'],
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];
                }
                $this->product_seller_order->insert($seller_temp);
            }
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
