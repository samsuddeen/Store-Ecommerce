<?php

namespace App\Http\Controllers\Guest;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Color;
use App\Models\Local;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderAsset;
use Illuminate\Support\Str;
use App\Events\PaymentEvent;
use App\Models\New_Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Data\Customer\CustomerData;
use App\Models\UserShippingAddress;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Models\Order\Seller\SellerOrder;
use App\Data\Form\PaymentHistoryFormData;
use App\Models\Order\Seller\ProductSellerOrder;
use App\Models\Refund\Refund;
use App\Models\Seller;
use App\Helpers\EmailSetUp;
use App\Enum\MessageSetup\MessageSetupEnum;
class GuestCheckoutEsewaController extends Controller
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

    public function successGuestOrderEsewa(Request $request)
    {
        $guestInfo = $request->session()->get('guest_info_forAllProduct');
        $order_item = $request->session()->get('guest_cart');
        if ($order_item == null) {
            $request->session()->flash('error', 'Your Cart Is Empty !! Plz Add Some Project');
            return redirect()->route('index');
        }

        $shipping_address = Local::where('id', $guestInfo['area'])->first();
        $location_detail = $shipping_address->getRouteCharge;
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

        $total_quantity = null;
        $total_price = null;
        $total_discount = null;
        $shipping_charge = $min_charge;

        foreach ($order_item as $cItem) {
            $total_quantity = $total_quantity + $cItem['qty'];
            $total_price = $total_price + $cItem['pprice'];
            $total_discount = $total_discount + $cItem['pdiscount'];
        }

        $area_address = Local::where('id', $guestInfo['area'])->first();
        $fixed_price = $total_price + $shipping_charge;

        $url = "https://uat.esewa.com.np/epay/transrec";
        $data = [
            'amt' => $fixed_price,
            'rid' => $_REQUEST['refId'],
            'pid' => $guestInfo['pid_info'],
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
                'user_id' => 1,
                'aff_id' => Str::random(10) . rand(100, 1000),
                'total_quantity' => $total_quantity,
                'total_price' => $fixed_price,
                // 'coupon_name' => $coupoun__name,
                // 'coupon_code' => $coupoun_code_name,
                // 'coupon_discount_price' => $coupon_discount_price,
                // 'ref_id' => $data['rid'],
                'ref_id' => Str::random(10) . rand(100, 1000),
                // Str::random(6) . rand(100, 1000),
                'shipping_charge' => $shipping_charge,
                'total_discount' => $total_discount,
                'payment_status' => '0',
                'merchant_name' => null,
                'payment_with' => 'Esewa/Paid',
                'payment_date' => date('Y-m-d'),
                // 'transaction_ref_id' => $request->refId,
                'name' => $guestInfo['name'],
                'email' => $guestInfo['email'],
                'phone' => $guestInfo['phone'],
                'province' => $guestInfo['province'],
                'district' => $guestInfo['district'],
                'area' => $area_address->local_name,
                'additional_address' => $guestInfo['additional_address'],
                'zip' => $guestInfo['zip'],
                'b_name' => $guestInfo['name'],
                'b_email' => $guestInfo['email'],
                'b_phone' => $guestInfo['phone'],
                'b_province' => $guestInfo['province'],
                'b_district' => $guestInfo['district'],
                'b_area' => $area_address->local_name,
                'b_additional_address' => $guestInfo['additional_address'],
                'admin_email' => env('MAIL_FROM_ADDRESS'),
                'b_zip' => $guestInfo['zip'],
            ];

            $order_id = $this->order->fill($order);
            $status = $this->order->save();

            $seller = [];
            $temp = [];
            $seller_product = [];

            foreach ($order_item as $key => $product) {
                $product_color = Color::where('id', $product['color_id'])->first();
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
                    'seller' => Product::where('id', $product['product_id'])->first()->seller_id ?? null,
                ];

                $temp[] = [
                    'order_id' => $order_id->id,
                    'product_id' => $product['product_id'],
                    'product_name' => $product['product_name'],
                    'price' => $product['price'],
                    'qty' => $product['qty'],
                    'sub_total_price' => $product['pprice'],
                    'color' => $product_color->id,
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
            // $this->sendMailtoAdmin($order);
            // $this->sendMailtoCustomer($order);
            

            // $filters = [
            //     'title'=>MessageSetupEnum::ORDER_PLACE,
            // ];
            // (new MailSetup($filters))->setToFile();

            // event(new OrderPlacedEvent($this->order,$user));

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
            DB::commit();
            EmailSetUp::guestOrderMail(MessageSetupEnum::ORDER_PLACE, $data = $this->order, $request->email);
            
            $request->session()->forget('guest_cart');
            $request->session()->forget('guest_info_forAllProduct');
            $request->session()->forget('total_cart_amount');
            $request->session()->forget('total_cart_qty');
            $request->session()->forget('cart_order_item');

            $request->session()->flash('success', 'Your Order Has been Created Successfully !!');
            return redirect()->route('invoice',$this->order->id);
            // return redirect('/');
            } catch (\Exception $ex) {
                DB::rollBack();
                $request->session()->flash('error', 'Something is Wrong !!!');
                return redirect()->route('cart.index');
            }    
        } else {
            return "Something is wrong.";
        }
    }

    public function sellerOrder($seller_product_id, $order, $order_asset, $seller_product)
    {
        $seller_id = [];
        $seller_wise_product = [];
        foreach ($seller_product_id as $key => $productId) {
            $productDetail = Product::where('id', $productId)->first();
            $seller_wise_product[$productDetail->seller_id] = $productDetail;
            $seller_ids[$key]['id'] = $productDetail->seller_id;
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

                if ($seller_id != null) {
                    $seller_create->fill($seller_data);
                    $seller_create->save();
                }

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
                if ($seller_id != null) {
                    $this->product_seller_order->insert($seller_temp);
                }
            }
        } else {
            foreach ($total_seller as $key => $seller_id) {
                $seller = Seller::where('id', $key)->first();
            }

            $seller_data['user_id'] = 1;
            $seller_data['order_id'] = $order->id;
            $seller_data['seller_id'] = $seller->id ?? null;
            $seller_data['qty'] = $order->total_quantity;
            // $seller_data['subtotal'] = $order->total_price-$order->shipping_charge;
            $seller_data['subtotal'] = $order->total_price - $order->shipping_charge;
            $seller_data['order_id'] = $order->id;
            $seller_data['delivery_charge'] = $order->shipping_charge;
            $seller_data['total'] = $order->total_discount + $seller_data['subtotal'];
            $seller_data['total_discount'] = $order->total_discount;

            if ($seller != null) {
                $order_id = $this->seller_order->fill($seller_data);
                $this->seller_order->save();
            }

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
                if ($seller != null) {
                    $this->product_seller_order->insert($seller_temp);
                }
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
