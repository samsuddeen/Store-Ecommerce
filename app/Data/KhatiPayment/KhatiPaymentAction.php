<?php
namespace App\Data\KhatiPayment;

use App\Models\Cart;
use GuzzleHttp\Client;
use App\Models\CartAssets;
use Illuminate\Http\Request;
use App\Models\UserBillingAddress;
use App\Models\UserShippingAddress;

class KhatiPaymentAction{

    protected $request;
    public function __construct(Request $request)
    {
        $this->request=$request;
    }

    public function khaltiPayment()
    {
        $user = auth()->guard('customer')->user();
        if ($this->request->shipping_address === null) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Plz Select Shipping Address !!'
            ];
            return response()->json($response, 200);
        }
        if ($this->request->session()->get('cart_order_item') === null) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Plz Select Item First !!'
            ];
            return response()->json($response, 200);
        }
        $this->request->validate([
            'shipping_address' => 'required|exists:user_shipping_addresses,id',
            'billing_address' => 'nullable|exists:user_billing_addresses,id',
            'is_same' => 'nullable:in:0,1'
        ]);

        if (!$user) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Plz Login First'
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

        if ($this->request->billing_address != null) {
            $billing_address = UserBillingAddress::where('id', $this->request->billing_address)->where('user_id', $user->id)->first();
        } else {
            $billing_address = $shipping_address;
        }

        if (!$shipping_address) {
            $this->request->session()->flash('error', 'Shipping Address Is Not Valid');
            return redirect()->route('cart.index');
        }
        $shipping_charge = $shipping_address->getLocation->deliveryRoute->charge;
        $order_item = session()->get('cart_order_item');

        $cart = Cart::where('user_id', $user->id)->first();
        if (!$cart) {
            $this->request->session()->flash('error', 'Your Cart Is Empty !! Plz Add Some Project');
            return redirect()->route('index');
        }

        $cart_item = [];
        foreach ($order_item['items'] as $key => $item) {
            $cart_item[] = CartAssets::where('id', $key)->where('cart_id', $cart->id)->first();
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
        $data['cart_item']=$cart_item;
        $data['cart']=$cart;
        $data['user']=$user;
        session()->forget('khalti1-order-data');
        request()->session()->put('khalti1-order-data',$data);
        return $fixed_price;

    }
}