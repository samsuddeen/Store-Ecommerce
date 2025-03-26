<?php

namespace App\Http\Controllers\Guest;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Color;
use App\Models\Local;
use App\Models\Order;
use App\Models\Seller;
use Nette\Utils\Image;
use App\Models\Product;

use App\Models\Location;
use App\Models\Province;
use App\Models\CartAssets;
use App\Models\OrderAsset;
use App\Helpers\EmailSetUp;
use Illuminate\Support\Str;
use App\Events\PaymentEvent;
use App\Mail\GuestOrderMail;
use App\Models\New_Customer;
use App\Models\ProductImage;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use App\Models\Refund\Refund;
use Faker\Provider\ar_EG\Address;
use App\Models\UserBillingAddress;
use Illuminate\Support\Facades\DB;
use App\Models\UserShippingAddress;
use Psy\CodeCleaner\ReturnTypePass;
use App\Http\Controllers\Controller;
use function GuzzleHttp\Promise\all;
use Illuminate\Support\Facades\Mail;
use GrahamCampbell\ResultType\Success;
use App\Models\Order\Seller\SellerOrder;
use App\Actions\Seller\SellerOrderAction;
use App\Data\Form\PaymentHistoryFormData;
use App\Enum\MessageSetup\MessageSetupEnum;
use App\Models\Order\Seller\ProductSellerOrder;
use App\Actions\Notification\NotificationAction;
use App\Data\KhatiPayment\KhaltiGuestAllProductPayment;
use App\Data\KhatiPayment\KhaltiGuestPaymentData;
use GuzzleHttp\Client;
class GuestCheckoutController extends Controller
{
    protected $folder_name = "frontend.cart.";
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

    public function storeInSession(Request $request)
    {
        $request->session()->put('guest_order_checkout', $request->all());
        $response = [
            'error' => false,
            'msg' => 'Succesfull',
        ];
        return response()->json($response, 200);
    }

    public function GuestCheckout(Request $request)
    {
        
        $provinces = Province::where('publishStatus', true)->get();
        $shipping_address = UserShippingAddress::get();
        $billing_address = UserBillingAddress::get();
        $for_order = $request->session()->get('guest_order_checkout');

        if ($for_order === null) {
            $request->session()->flash('error', 'Plz Try again, Something is wrong');
            return back();
        }
        $product = Product::where('id', $for_order['product_id'])->first();
        $product_stock = ProductStock::where('product_id', $product->id)->where('color_id', $for_order['color_id'])->first();
        $offer_price = getOfferProduct($product, $product_stock);

        if ($offer_price != null) {
            $sub_total = $offer_price * $for_order['product_qty'];
        } else {
            if ($product_stock->special_price != null) {
                $sub_total = $product_stock->special_price * $for_order['product_qty'];
            } else {
                $sub_total = $product_stock->price * $for_order['product_qty'];
            }
        }

        return view($this->folder_name . 'guest-oneProductCheckout',)
            ->with('shipping_address', $shipping_address)
            ->with('billing_address', $billing_address)
            ->with('provinces', $provinces)
            ->with('product', $product)
            ->with('offer_price', $offer_price)
            ->with('for_order', $for_order)
            ->with('sub_total', $sub_total)
            ->with('product_stock', $product_stock)
            ->with('for_order', $for_order);
    }


    public function checkoutSingle(Request $request)
    {        
        if($request->payment==='khalti')
        {
            try{
                $amount=(new KhaltiGuestPaymentData($request))->khaltiPaymentGuestData();
                $data =[
                    "return_url"=>route('guest.khaltiSingleProduct'),
                    "website_url"=>"https://jhigu.store/",
                    "amount"=>(int)$amount*100,
                    "purchase_order_id"=>"test12",
                    "purchase_order_name"=>"Test Product"
                ];
                $client = new Client([
                    // 'base_uri' => 'https://a.khalti.com/',
                    'headers' => [
                        'Authorization' => 'Key '.'live_secret_key_28c3653d1899474790859e2dc02a67a6',
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                    ],
                ]);
                $response = $client->post('https://khalti.com/api/v2/epayment/initiate/', [
                    'json' => $data,
                ]);
                return redirect(json_decode($response->getBody()->getContents(), true)['payment_url']);
            }catch(\Throwable $th)
            {
                $request->session()->flash('Something Went Wrong With Khalti Payment !!');
                return redirect()->back();
            }
        }elseif($request->payment==='COD')
        {
            $for_order =  $request->session()->get('guest_order_checkout');
            if ($for_order === null) {
                $request->session()->flash('error', 'OOPs, Your Order is null');
                return back();
            }
    
            $address = Local::where('id', $request->area)->first();
            $additional_address = Location::where('id', $request->additional_address)->first();
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
            // $product_image = ProductImage::where('product_id', $for_order['product_id'])->where('color_id', $for_order['color_id'])->first()->image;
            $product_image = $main_product->images->first()->image;
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
    
            DB::beginTransaction();
            try {  
    
            $color = Color::where('id', $for_order['color_id'])->first();
    
            // dd($product->image[0]->image);            
    
            $order = [
                'user_id' => 1,
                'aff_id' => Str::random(10) . rand(100, 1000),
                'total_quantity' => $total_quantity,
                'total_price' => $fixed_price,
                'ref_id' =>  strtoupper(Str::random(6) . rand(100, 1000)),
                'shipping_charge' => $shipping_charge,
                'total_discount' => $total_discount,
                'payment_status' => '0',
                'merchant_name' => null,
                'payment_with' => 'Cash On Delivery',
                'payment_date' => date('Y-m-d'),
                // 'transaction_ref_id' => $request->refId,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'province' => $request->province,
                'district' => $request->district,
                'area' => $address->local_name,
                'additional_address' => $additional_address->title,
                'zip' => $request->zip,
                'b_name' => $request->name,
                'b_email' => $request->email,
                'b_phone' => $request->phone,
                'b_province' => $request->province,
                'b_district' => $request->district,
                'b_area' => $address->local_name,
                'b_additional_address' => $additional_address->title,
                'admin_email' => env('MAIL_FROM_ADDRESS'),
                'b_zip' => $request->zip
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
                'seller_order_id' => $order_id->id,
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
                'seller_order_id' => $order_id->id,
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
    
            // $this->sendMailtoAdmin($order);
            // $this->sendMailtoCustomer($order);
    
            DB::commit();
    
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
                3,
                'Refund Unpaid',
                route('returnable.show', $this->refund->returnOrder->id),
                'Refund Unpaid',
                false,
                true
            ))->getData();
            PaymentEvent::dispatch($paymentData);
            EmailSetUp::guestOrderMail(MessageSetupEnum::ORDER_PLACE, $data = $this->order, $request->email);
            
            $request->session()->forget('guest_order_checkout');
            $request->session()->flash('success', 'Your Order Has been Created Successfully !!');
            return redirect()->route('invoice',$this->order->id);
            } catch (\Exception $ex) {
            DB::rollBack();
            $request->session()->flash('error', 'Something is Wrong !!!');
            return redirect()->route('cart.index');
            }
        }else
        {
            $request->session()->flash('error','Something Went Wrong !!');
            return redirect()->back();
        }
        
    }

    public function guestCheckoutAll(Request $request)
    {
        $provinces = Province::where('publishStatus', true)->get();
        $cart_products = $request->session()->get('guest_cart');
        $cart_total_item = $request->session()->get('total_cart_qty');
        $cart_total_amount = $request->session()->get('total_cart_amount');
        return view($this->folder_name . 'guest-allProdcutCheckout')
            ->with('provinces', $provinces)
            ->with('guest_cart_products', $cart_products)
            ->with('guest_cart_total_item', $cart_total_item)
            ->with('guest_cart_total_amount', $cart_total_amount);
    }

    // Storing all product of guest
    public function checkoutAllProduct(Request $request)
    {
       
       if($request->payment==='COD')
       {
        $order_item = $request->session()->get('guest_cart');
        if ($order_item == null) {
            $request->session()->flash('error', 'Your Cart Is Empty !! Plz Add Some Project');
            return redirect()->route('index');
        }

        $shipping_address = Local::where('id', $request->area)->first();
        $additional_address = Location::where('id', $request->additional_address)->first();        
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

        $area_address = Local::where('id', $request->area)->first();
        $fixed_price = $total_price + $shipping_charge;

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
            'ref_id' =>  strtoupper(Str::random(6) . rand(100, 1000)),
            'shipping_charge' => $shipping_charge,
            'total_discount' => $total_discount,
            'payment_status' => '0',
            'merchant_name' => null,
            'payment_with' => 'Cash On Delivery',
            'payment_date' => date('Y-m-d'),
            // 'transaction_ref_id' => $request->refId,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'province' => $request->province,
            'district' => $request->district,
            'area' => $area_address->local_name,
            'additional_address' => $additional_address->title,
            'zip' => $request->zip,
            'b_name' => $request->name,
            'b_email' => $request->email,
            'b_phone' => $request->phone,
            'b_province' => $request->province,
            'b_district' => $request->district,
            'b_area' => $area_address->local_name,
            'b_additional_address' => $additional_address->title,
            'admin_email' => env('MAIL_FROM_ADDRESS'),
            'b_zip' => $request->zip
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
                'color' => $product_color->id ?? '',
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

        $request->session()->forget('guest_cart');
        $request->session()->forget('total_cart_amount');
        $request->session()->forget('total_cart_qty');

        DB::commit();


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
            3,
            'Refund Unpaid',
            route('returnable.show', $this->refund->returnOrder->id),
            'Refund Unpaid',
            false,
            true
        ))->getData();

        PaymentEvent::dispatch($paymentData);

        EmailSetUp::guestOrderMail(MessageSetupEnum::ORDER_PLACE, $data = $this->order, $request->email);

        $request->session()->forget('cart_order_item');
        $request->session()->flash('success', 'Your Order Has been Created Successfully !!');
        // dd('yes sumit');
        return redirect()->route('invoice',$this->order->id);
        } catch (\Exception $ex) {
            DB::rollBack();
            $request->session()->flash('error', 'Something is Wrong !!!');
            return redirect()->route('cart.index');
        }          
       }elseif($request->payment==='khalti')
       {
           try{
            $amount=(new KhaltiGuestAllProductPayment($request))->khaltiGuestAllProduct();
            // dd(session()->get('khalti-payment-guest-all-detail'));
            $data =[
                "return_url"=>route('guest.khaltiAllProduct'),
                "website_url"=>"https://jhigu.store/",
                "amount"=>(int)$amount*100,
                "purchase_order_id"=>"test12",
                "purchase_order_name"=>"Test Product"
            ];
    
    
            $client = new Client([
                // 'base_uri' => 'https://a.khalti.com/',
                'headers' => [
                    'Authorization' => 'Key '.'live_secret_key_28c3653d1899474790859e2dc02a67a6',
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
            ]);
            $response = $client->post('https://khalti.com/api/v2/epayment/initiate/', [
                'json' => $data,
            ]);
            return redirect(json_decode($response->getBody()->getContents(), true)['payment_url']);
           }catch(\Throwable $th)
           {
                $request->session()->flash('error','Something Went Wrong With Khalti Payment');
                return redirect()->back();
           }
       }
       else
       {
        $request->session()->flash('error','Something Went Wrong !!');
        return redirect()->back();
       }
          
    }



    // Seller Function
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

            // dd($seller_data['seller_id']);
            if ($seller_data['seller_id'] != null) {
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
                if ($seller_data['seller_id'] != null) {
                    $this->product_seller_order->insert($seller_temp);
                }
            }
        }
    }

    public function sendMailtoAdmin($order)
    {
        $data=$order;
        $productList=$data->orderAssets;
        Mail::send('email.order-product.admin_email', $order, function ($message) use ($order) {
            $message->subject('Ordering From Customer');
            $message->from($order['email']);
            $message->to($order['admin_email']);
        });
    }

    
}
