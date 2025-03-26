<?php

namespace App\Http\Controllers\Guest;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Color;
use App\Models\Local;
use App\Models\Order;
use App\Models\seller;
use App\Models\Product;
use App\Models\OrderAsset;
use App\Helpers\EmailSetUp;
use Illuminate\Support\Str;
use App\Events\PaymentEvent;
use App\Models\New_Customer;
use App\Models\ProductImage;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use App\Models\Refund\Refund;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Models\Order\Seller\SellerOrder;
use App\Data\Form\PaymentHistoryFormData;
use App\Enum\MessageSetup\MessageSetupEnum;
use App\Models\Order\Seller\ProductSellerOrder;
use App\Actions\Notification\NotificationAction;

class GuestEsewaSingleProductController extends Controller
{
    protected $order = null;
    protected $order_asset = null;
    protected $seller_order = null;
    protected $product_seller_order = null;
    protected $refund = null;

    public function __construct(Order $order, OrderAsset $order_asset, SellerOrder $seller_order, ProductSellerOrder $product_seller_order, Refund $refund)
    {
        $this->order = $order;
        $this->refund = $refund;
        $this->order_asset = $order_asset;
        $this->seller_order = $seller_order;
        $this->product_seller_order = $product_seller_order;
    }

    public function singleProductCheckout(Request $request)
    {
        $for_order =  $request->session()->get('guest_order_checkout');
        $guest_info = $request->session()->get('guest_info_forSingleProduct');
        if ($for_order === null) {
            $request->session()->flash('error', 'OOPs, Your Order is null');
            return back();
        }

        $address = Local::where('id', $guest_info['area'])->first();

        $location_detail = $address->getRouteCharge;
        $min_charge = null;

        foreach ($location_detail as $key => $l) {
            $new = $l->deliveryRoute;
            if ($key == 0) {
                $min_charge = $new->charge;
                $charge_array['location_id'] = $new->location_id;
                $charge_array['charge'] = $new->charge;
            } else {
                if ($min_charge > $new->charge) {
                    $min_charge = $new->charge;
                    $charge_array['location_id'] = $new->location_id;
                    $charge_array['charge'] = $new->charge;
                }
            }
        }

        $shipping_charge = $min_charge;
        $main_product = Product::where('id', $for_order['product_id'])->first();
        $product = ProductStock::where('product_id', $for_order['product_id'])->where('color_id', $for_order['color_id'])->where('id', $for_order['varient_id'])->first();
        $product_image = ProductImage::where('product_id', $for_order['product_id'])->where('color_id', $for_order['color_id'])->first()->image ?? '';

        $stock_ways = $product->stockWays;

        $option = [];
        foreach ($stock_ways as $key => $data) {
            $option[$key]['id'] = $data->category->id;
            $option[$key]['title'] = $data->category->title;
            $option[$key]['value'] = $data->value;
        }
        $options = json_encode($option);

        $total_quantity = $for_order['product_qty'];
        $offer_price =  getOfferProduct($main_product, $product);


        if ($offer_price != null) {
            $product_price = $offer_price;
            $total_price = $offer_price * $for_order['product_qty'];
            $sub_discount = $product->price - $offer_price;
        } elseif ($product->special_price != null) {
            $product_price = $product->special_price;
            $total_price = $product->special_price * $for_order['product_qty'];
            $sub_discount = $product->price - $product->special_price;
        } else {
            $product_price = $product->price;
            $total_price = $product->price * $for_order['product_qty'];
            $sub_discount = null;
        }

        $fixed_price = $total_price + $shipping_charge;
        $total_discount = $sub_discount * $for_order['product_qty'];

        $url = "https://uat.esewa.com.np/epay/transrec";
        $data = [
            'amt' => $fixed_price,
            'rid' => $_REQUEST['refId'],
            'pid' => $guest_info['pid_info'],
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

            $color = Color::where('id', $for_order['color_id'])->first();

            // dd($product->image[0]->image);                        

            $order = [
                'user_id' => 1,
                'aff_id' => Str::random(10) . rand(100, 1000),
                'total_quantity' => $total_quantity,
                'total_price' => $fixed_price,
                'ref_id' => Str::random(6) . rand(100, 1000),
                'shipping_charge' => $shipping_charge,
                'total_discount' => $total_discount,
                'payment_status' => '0',
                'merchant_name' => null,
                'payment_with' => 'Esewa/Paid',
                'payment_date' => date('Y-m-d'),
                // 'transaction_ref_id' => $request->refId,
                'name' => $guest_info['name'],
                'email' => $guest_info['email'],
                'phone' => $guest_info['phone'],
                'province' => $guest_info['province'],
                'district' => $guest_info['district'],
                'area' => $address->local_name,
                'additional_address' => $guest_info['additional_address'],
                'zip' => $guest_info['zip'],
                'b_name' => $guest_info['name'],
                'b_email' => $guest_info['email'],
                'b_phone' => $guest_info['phone'],
                'b_province' => $guest_info['province'],
                'b_district' => $guest_info['district'],
                'b_area' => $address->local_name,
                'b_additional_address' => $guest_info['additional_address'],
                'admin_email' => env('MAIL_FROM_ADDRESS'),
                'b_zip' => $guest_info['zip'],
            ];

            $order_id = $this->order->fill($order);
            $status = $this->order->save();

            $product_main = Product::where('id', $product->product_id)->first();
            $seller = [];
            $temp = [
                'order_id' => $this->order->id,
                'product_id' => $product_main->id,
                'product_name' => $product_main->name,
                'price' => $product_price,
                'qty' => $total_quantity,
                'sub_total_price' => $fixed_price,
                'color' => $color->id,
                'image' => $product_image,
                'discount' => $total_discount,
                'options' => $options,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

            $this->order_asset->insert($temp);

            // ----------------------------------Seller Code---------------------------------------     

            $seller_order = [
                'order_id' => $order_id->id,
                'seller_id' => $product_main->seller_id,
                'product_id' => $product_main->id,
                'price' => $total_price,
                'sub_total' => $total_price,
                'user_id' => '1',
                'seller_order_id' => '1',
                'qty' => $for_order['product_qty'],
                'subtotal' => $total_price,
                'total_discount' => $total_discount,
                'total' => $total_price + $total_discount,
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
                    'to_model' => ($product_main->seller_id) ? get_class(seller::getModel()) : get_class(User::getModel()),
                    'to_id' => $product_main->seller_id ?? User::first()->id,
                    'title' => 'New Order From ' . $customer->name,
                    'summary' => 'You Have New Order Request',
                    'url' => url('/admin/view-order/' . $order_id->ref_id),
                    'is_read' => false,
                ];

                (new NotificationAction($notification_data))->store();
            }



            $discountpercent = $total_discount * 100 / $total_price;
            $discount_percent = $discountpercent . '%';

            $product_seller_order = [
                'order_id' => $order_id->id,
                'seller_id' => $product_main->seller_id,
                'product_id' => $product_main->id,
                'price' => $total_price,
                'sub_total' => $total_price,
                'user_id' => '1',
                'seller_order_id' => '1',
                'qty' => $for_order['product_qty'],
                'subtotal' => $total_price,
                'discount' => $total_discount,
                'image' => $product_image,
                'discount_percent' => $discount_percent,
                'total' => $fixed_price,
                'total_discount' => $total_discount,
            ];

            if ($product_main->seller_id != null) {
                $seller_order =  $this->product_seller_order->fill($product_seller_order);
                $this->product_seller_order->save();
                // notification
            }
            // ----------------------------------Seller Code----------------------------------------      

            // $this->sendMailtoAdmin($order);
            // $this->sendMailtoCustomer($order);
            DB::commit();

            $user = New_Customer::where('id', 1)->first();
            $admin = User::first();
            $paymentData = (new PaymentHistoryFormData(
                get_class($user->getModel()),
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
            EmailSetUp::guestOrderMail(MessageSetupEnum::ORDER_PLACE, $data = $this->order, $request->email);
           
            $request->session()->forget('guest_order_checkout');
            $request->session()->flash('success', 'Your Order Has been Created Successfully !!');

            return redirect()->route('invoice',$this->order->id);
            // return redirect('/');
            } catch (\Exception $ex) {
            DB::rollBack();
            $request->session()->flash('error', 'Something is Wrong !!!');
            return redirect()->route('cart.index');
            }
        } else {
            return "Some Thing is Wrong";
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
