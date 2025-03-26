<?php
namespace App\Data\KhatiPayment;

use App\Models\Product;
use App\Models\Location;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use App\Models\DeliveryRoute;

class KhaltiGuestPaymentData{

    protected $request;

    public function __construct(Request $request)
    {
        $this->request=$request;
    }

    public function khaltiPaymentGuestData()
    { 
        $for_order =  $this->request->session()->get('guest_order_checkout');
        if ($for_order === null) {
            $response=[
                'error'=>true,
                'msg'=>'OOPs, Your Order is null'
            ];
            return response()->json($response,200);
        }
        $address = DeliveryRoute::where('id', $this->request->additional_address)->first();
        $additional_address = Location::where('id', $this->request->additional_address)->first();
        $min_charge = $address->charge;
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
        $data['for_order']=$for_order;
        $data['total_quantity']=$total_quantity;
        $data['fixed_price']=$fixed_price;
        $data['shipping_charge']=$shipping_charge;
        $data['total_discount']=$total_discount;
        $data['address']=$address;
        $data['additional_address']=$additional_address;
        $data['product']=$product;
        $data['product_price']=$product_price;
        $data['product_image']=$product_image;
        $data['options']=$option;
        $data['total_price']=$total_price;
        $data['guest_info']=$this->request->all();
        request()->session()->put('khalti-payment-guest-single',$data);
        return $fixed_price;
    }
}