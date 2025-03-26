<?php

namespace App\Actions\Seller;

use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Carbon;
use App\Models\Order\Seller\SellerOrder;
use App\Models\Order\Seller\ProductSellerOrder;
use App\Actions\Notification\NotificationAction;
use App\Models\New_Customer;
use App\models\Seller;

class SellerOrderAction
{
    protected $seller_order = null;
    protected $product_seller_order = null;
    public function __construct(SellerOrder $seller_order, ProductSellerOrder $product_seller_order)
    {
        $this->seller_order = $seller_order;
        $this->product_seller_order = $product_seller_order;
    }

    public function sellerOrder($seller_product_id, $order, $order_asset, $seller_product)
    {
        $seller_id = [];
        $seller_wise_product = [];
        foreach ($seller_product_id as $key => $productId) {
            $productDetail = Product::where('id', $productId)->first();
            // dd($productDetail->seller_id);
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
                $totalVatAmount=0;
                foreach ($s_product as $p) {
                    $total_price = $total_price + $p['product']['price'];
                    $total_qty = $total_qty + $p['product']['qty'];
                    $discount = $discount + $p['product']['discount'];
                    $seller_id = $p['seller'];
                    $sub_total_price = $sub_total_price + $p['product']['sub_total_price'];
                    $totalVatAmount=$totalVatAmount+$p['product']['vatAmount'];
                }
                if ($seller_id != null) {
                    $seller_data['user_id'] = $order->user_id;
                    $seller_data['order_id'] = $order->id;
                    $seller_data['seller_id'] = $seller_id;
                    $seller_data['qty'] = $total_qty;
                    $seller_data['subtotal'] = $sub_total_price;
                    $seller_data['total'] = $total_price;
                    $seller_data['total_discount'] = $discount;
                    $seller_data['total_vat_amount'] = $totalVatAmount;

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
                            'price' => $p['product']['price'] + ($p['product']['discount']),
                            'sub_total' => $p['product']['price'] * $p['product']['qty'],
                            'order_id' => $p['product']['order_id'],
                            'discount_percent' => $discount_percent,
                            'discount' => $p['product']['discount'],
                            'image' => $p['product']['image'],
                            'seller_order_id' => $seller_create->id,
                            // 'product_vat_amount'=>$
                        ];
                        // dd($seller_temp);
                    }
                    $this->product_seller_order->insert($seller_temp);

                    $notification_data = [
                        'from_model' => get_class(auth()->guard('customer')->user()->getModel()),
                        'from_id' => auth()->guard('customer')->user()->id,
                        'to_model' => get_class(User::getModel()) ?? null,
                        'to_id' => User::first()->id,
                        'title' => 'New Order From ' . ucfirst(auth()->guard('customer')->user()->name),
                        'summary' => 'You Have New Order Request',
                        'url' => route('admin.viewOrder', $order->ref_id),
                        'is_read' => false,
                    ];


                    (new NotificationAction($notification_data))->store();
                    $notification_data = [
                        'from_model' => get_class(auth()->guard('customer')->user()->getModel()),
                        'from_id' => auth()->guard('customer')->user()->id,
                        'to_model' => get_class(Seller::getModel()) ?? null,
                        'to_id' => $seller_id,
                        'title' => 'New Order From ' . ucfirst(auth()->guard('customer')->user()->name),
                        'summary' => 'You Have New Order Request',
                        'url' => url('seller/seller-order/' . $seller_create->id),
                        // 'url' => route('admin.viewOrder', $order->ref_id),
                        'is_read' => false,
                    ];
                    (new NotificationAction($notification_data))->store();
                } else {
                    $notification_data = [
                        'from_model' => get_class(auth()->guard('customer')->user()->getModel()),
                        'from_id' => auth()->guard('customer')->user()->id,
                        'to_model' => get_class(User::getModel()) ?? null,
                        'to_id' => User::first()->id,
                        'title' => 'New Order From' . ucfirst(auth()->guard('customer')->user()->name),
                        'summary' => 'You Have New Order Request',
                        'url' => route('admin.viewOrder', $order->ref_id),
                        'is_read' => false,
                    ];


                    (new NotificationAction($notification_data))->store();
                }
            }
        } else {
            foreach ($total_seller as $key => $seller_id) {
                $seller = Seller::where('id', $key)->first();
            }
            if (!$seller) {
                $notification_data = [
                    'from_model' => get_class(auth()->guard('customer')->user()->getModel()),
                    'from_id' => auth()->guard('customer')->user()->id,
                    'to_model' => get_class(User::getModel()) ?? null,
                    'to_id' => User::first()->id,
                    'title' => 'New Order From' . ucfirst(auth()->guard('customer')->user()->name),
                    'summary' => 'You Have New Order Request',
                    'url' => route('admin.viewOrder', $order->ref_id),
                    'is_read' => false,
                ];
                (new NotificationAction($notification_data))->store();
                return true;
            }
            $seller_data['user_id'] = $order->user_id;
            $seller_data['order_id'] = $order->id;
            $seller_data['seller_id'] = $seller->id;
            $seller_data['qty'] = $order->total_quantity;
            $seller_data['subtotal'] = $order->total_price - $order->shipping_charge;
            $seller_data['order_id'] = $order->id;
            $seller_data['total'] = $order->total_discount + $seller_data['subtotal'];
            $seller_data['total_discount'] = $order->total_discount;
            $seller_data['total_vat_amount'] = $order->vat_amount;
            $this->seller_order->fill($seller_data);
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
                // dd('0',$seller_temp);
                $this->product_seller_order->insert($seller_temp);
                // dd('ok');
                $notification_data = [
                    'from_model' => get_class(auth()->guard('customer')->user()->getModel()),
                    'from_id' => auth()->guard('customer')->user()->id,
                    'to_model' => get_class(User::getModel()) ?? null,
                    'to_id' => User::first()->id,
                    'title' => 'New Order From ' . ucfirst(auth()->guard('customer')->user()->name),
                    'summary' => 'You Have New Order Request',
                    'url' => route('admin.viewOrder', $order->ref_id),
                    'is_read' => false,
                ];
                (new NotificationAction($notification_data))->store();
                $notification_data = [
                    'from_model' => get_class(auth()->guard('customer')->user()->getModel()),
                    'from_id' => auth()->guard('customer')->user()->id,
                    'to_model' => get_class(Seller::getModel()) ?? null,
                    'to_id' => $seller->id,
                    'title' => 'New Order From ' . ucfirst(auth()->guard('customer')->user()->name),
                    'summary' => 'You Have New Order Request',
                    'url' => url('seller/seller-order/' . $this->seller_order->id),
                    'is_read' => false,
                ];
                (new NotificationAction($notification_data))->store();
            }
        }
    }

    public function sellerOrderGuest($seller_product_id, $order, $order_asset, $seller_product)
    {
        $seller_id = [];
        $seller_wise_product = [];
        foreach ($seller_product_id as $key => $productId) {
            $productDetail = Product::where('id', $productId)->first();
            // dd($productDetail->seller_id);
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
                $totalVatAmount=0;
                foreach ($s_product as $p) {
                    $total_price = $total_price + $p['product']['price'];
                    $total_qty = $total_qty + $p['product']['qty'];
                    $discount = $discount + $p['product']['discount'];
                    $seller_id = $p['seller'];
                    $sub_total_price = $sub_total_price + $p['product']['sub_total_price'];
                    $totalVatAmount=$totalVatAmount+$p['product']['vatAmount'];
                }
                if ($seller_id != null) {
                    $seller_data['user_id'] = $order->user_id;
                    $seller_data['order_id'] = $order->id;
                    $seller_data['seller_id'] = $seller_id;
                    $seller_data['qty'] = $total_qty;
                    $seller_data['subtotal'] = $sub_total_price;
                    $seller_data['total'] = $total_price;
                    $seller_data['total_discount'] = $discount;
                    $seller_data['total_vat_amount'] = $totalVatAmount;

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
                            'price' => $p['product']['price'] + ($p['product']['discount']),
                            'sub_total' => $p['product']['price'] * $p['product']['qty'],
                            'order_id' => $p['product']['order_id'],
                            'discount_percent' => $discount_percent,
                            'discount' => $p['product']['discount'],
                            'image' => $p['product']['image'],
                            'seller_order_id' => $seller_create->id,
                            // 'product_vat_amount'=>$
                        ];
                        // dd($seller_temp);
                    }
                    $this->product_seller_order->insert($seller_temp);

                    $notification_data = [
                        'from_model' => get_class(auth()->guard('customer')->user()->getModel()),
                        'from_id' => auth()->guard('customer')->user()->id,
                        'to_model' => get_class(User::getModel()) ?? null,
                        'to_id' => User::first()->id,
                        'title' => 'New Order From ' . ucfirst(auth()->guard('customer')->user()->name),
                        'summary' => 'You Have New Order Request',
                        'url' => route('admin.viewOrder', $order->ref_id),
                        'is_read' => false,
                    ];


                    (new NotificationAction($notification_data))->store();
                    $notification_data = [
                        'from_model' => get_class(auth()->guard('customer')->user()->getModel()),
                        'from_id' => auth()->guard('customer')->user()->id,
                        'to_model' => get_class(Seller::getModel()) ?? null,
                        'to_id' => $seller_id,
                        'title' => 'New Order From ' . ucfirst(auth()->guard('customer')->user()->name),
                        'summary' => 'You Have New Order Request',
                        'url' => url('seller/seller-order/' . $seller_create->id),
                        // 'url' => route('admin.viewOrder', $order->ref_id),
                        'is_read' => false,
                    ];
                    (new NotificationAction($notification_data))->store();
                } else {
                    $notification_data = [
                        'from_model' => get_class(auth()->guard('customer')->user()->getModel()),
                        'from_id' => auth()->guard('customer')->user()->id,
                        'to_model' => get_class(User::getModel()) ?? null,
                        'to_id' => User::first()->id,
                        'title' => 'New Order From' . ucfirst(auth()->guard('customer')->user()->name),
                        'summary' => 'You Have New Order Request',
                        'url' => route('admin.viewOrder', $order->ref_id),
                        'is_read' => false,
                    ];


                    (new NotificationAction($notification_data))->store();
                }
            }
        } else {
            foreach ($total_seller as $key => $seller_id) {
                $seller = Seller::where('id', $key)->first();
            }
            if (!$seller) {
                $notification_data = [
                    'from_model' => get_class(New_Customer::where('id',63)->first()),
                    'from_id' => New_Customer::where('id',63)->first()->id,
                    'to_model' => get_class(User::getModel()) ?? null,
                    'to_id' => User::first()->id,
                    'title' => 'New Order From' . ucfirst(New_Customer::where('id',63)->first()->name),
                    'summary' => 'You Have New Order Request',
                    'url' => route('admin.viewOrder', $order->ref_id),
                    'is_read' => false,
                ];
                (new NotificationAction($notification_data))->store();
                return true;
            }
            $seller_data['user_id'] = $order->user_id;
            $seller_data['order_id'] = $order->id;
            $seller_data['seller_id'] = $seller->id;
            $seller_data['qty'] = $order->total_quantity;
            $seller_data['subtotal'] = $order->total_price - $order->shipping_charge;
            $seller_data['order_id'] = $order->id;
            $seller_data['total'] = $order->total_discount + $seller_data['subtotal'];
            $seller_data['total_discount'] = $order->total_discount;
            $seller_data['total_vat_amount'] = $order->vat_amount;
            $this->seller_order->fill($seller_data);
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
                // dd('0',$seller_temp);
                $this->product_seller_order->insert($seller_temp);
                // dd('ok');
                $notification_data = [
                    'from_model' => get_class(New_Customer::where('id',63)->first()->getModel()),
                    'from_id' => New_Customer::where('id',63)->first()->id,
                    'to_model' => get_class(User::getModel()) ?? null,
                    'to_id' => User::first()->id,
                    'title' => 'New Order From ' . ucfirst(New_Customer::where('id',63)->first()->name),
                    'summary' => 'You Have New Order Request',
                    'url' => route('admin.viewOrder', $order->ref_id),
                    'is_read' => false,
                ];
                (new NotificationAction($notification_data))->store();
                $notification_data = [
                    'from_model' => get_class(New_Customer::where('id',63)->first()->getModel()),
                    'from_id' => New_Customer::where('id',63)->first()->id,
                    'to_model' => get_class(Seller::getModel()) ?? null,
                    'to_id' => $seller->id,
                    'title' => 'New Order From ' . ucfirst(New_Customer::where('id',63)->first()->name),
                    'summary' => 'You Have New Order Request',
                    'url' => url('seller/seller-order/' . $this->seller_order->id),
                    'is_read' => false,
                ];
                (new NotificationAction($notification_data))->store();
            }
        }
    }
}
