<?php
namespace App\Data\KhatiPayment;

use App\Models\Product;
use Illuminate\Support\Str;
use App\Models\Admin\VatTax;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use App\Models\UserBillingAddress;
use App\Models\UserShippingAddress;

class KhaltiPaymentDirectLogin{

    protected $request;

    public function __construct(Request $request)
    {
        $this->request=$request;
    }

    public function khaltiPaymentDirectLogin()
    {
        $user = auth()->guard('customer')->user();
        $for_order =  $this->request->session()->get('directOrder');

        if ($for_order === null) {
            $response = [
                'error' => true,
                'msg' => 'Plz Try again, Something is wrong'
            ];
            return response()->json($response, 200);
        }

        if ($this->request->coupoon_code != null) {
            $coupon_discount_price = $this->request->coupoon_code;
            $coupoun_code_name = $this->request->coupon_code_name;
            $coupoun__name = $this->request->coupon_name;
        } else {
            $coupon_discount_price = 0;
            $coupoun_code_name = null;
            $coupoun__name = null;
        }
        $coupon_discount_price=round($coupon_discount_price);
        $shipping_id = $this->request->shipping_address;
        $billing_id = $this->request->billing_address;
        $shipping_address = UserShippingAddress::where('id', $shipping_id)->where('user_id', $user->id)->first();
        if ($this->request->billng_id != null) {
            $billing_address = UserBillingAddress::where('id', $billing_id)->where('user_id', $user->id)->first();
        } else {
            $billing_address = $shipping_address;
        }
        if($shipping_address && $shipping_address->getLocation){
            $shipping_charge = $shipping_address->getLocation->deliveryRoute->charge;

        }else{
            $shipping_charge =100;
        }
        
        if (!$billing_address) {
            $response = [
                'error' => true,
                'msg' => 'Billing Address Is Not Valid'
            ];
            return response()->json($response, 200);
        }
        $main_product = Product::where('id', $for_order['product_id'])->first();
        $product = ProductStock::where('product_id', $for_order['product_id'])->where('color_id', $for_order['color_id'])->where('id', $for_order['varient_id'])->first();
        
        // $product_image = ProductImage::where('product_id', $for_order['product_id'])->where('color_id', $for_order['color_id'])->first()->image;
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
        $sub_total_price = $price;
        $fixed_price = $total_price + $shipping_charge - (int)$coupon_discount_price;
        if($main_product->vat_percent==0)
        {
            $vatTax=VatTax::first();
            $vatPercent=(int)$vatTax->vat_percent;
            $vatAmount=($total_price*$vatPercent)/100;
            $fixed_price=$fixed_price+round($vatAmount);
        }
        else
        {
            $fixed_price=$fixed_price;
            $vatAmount=0;
        }
        $ref_id='OD'.rand(100000,999999);
        $data['total_quantity']=$total_quantity;
        $data['fixed_price']=$fixed_price;
        $data['coupoun__name']=$coupoun__name;
        $data['coupoun_code_name']=$coupoun_code_name;
        $data['coupon_discount_price']=$coupon_discount_price;
        $data['refId']=$ref_id;
        $data['shipping_charge']=$shipping_charge;
        $data['total_discount']=$total_discount;
        $data['shipping_address']=$shipping_address;
        $data['billing_address']=$billing_address;
        $data['user']=$user;
        $data['product']=$product;
        $data['for_order']=$for_order;
        $data['total_price']=$total_price;
        $data['main_product']=$main_product;
        $data['product_image']=$product_image;
        $data['options']=$option;
        $data['vat_amount']=$vatAmount;
        session()->forget('khalti1-direct-payment-order-data');
        request()->session()->put('khalti1-direct-payment-order-data',$data);
        
        return $fixed_price;
        
    }
}