<?php
namespace App\Data\KhatiPayment;

use App\Models\Local;
use App\Models\Location;
use Illuminate\Http\Request;
use App\Models\DeliveryRoute;


class KhaltiGuestAllProductPayment{

    protected $request;
    public function __construct(Request $request)
    {
        $this->request=$request;
    }

    public function khaltiGuestAllProduct()
    {
        $order_item = $this->request->session()->get('guest_cart');
        
        if ($order_item == null) {
            $response=[
                'error'=>true,
                'msg'=>'Your Cart Is Empty !! Plz Add Some Project'
            ];
            return response()->json($response,200);
        }
        $shipping_address = DeliveryRoute::where('id', $this->request->additional_address)->first();
        $total_quantity = null;
        $total_price = null;
        $total_discount = null;
        $shipping_charge = $shipping_address->charge;
        foreach ($order_item as $cItem) {
            $total_quantity = $total_quantity + $cItem['qty'];
            $total_price = $total_price + $cItem['pprice'];
            $total_discount = $total_discount + $cItem['pdiscount'];
        }
        $area_address = Local::where('id', $this->request->area)->first();
        $additional_address = Location::where('id', $this->request->additional_address)->first();
        $fixed_price = $total_price + $shipping_charge;
        $data['total_quantity']=$total_quantity;
        $data['fixed_price']=$fixed_price;
        $data['shipping_charge']=$shipping_charge;
        $data['total_discount']=$total_discount;
        $data['guest_info']=$this->request->all();
        $data['order_item']=$order_item;
        $data['area_address']=$area_address;
        $data['additional_address']=$additional_address;
        request()->session()->forget('khalti-payment-guest-all-detail');
        request()->session()->put('khalti-payment-guest-all-detail',$data);
        return $fixed_price;
        // khali server intgratin----------------------------------------------------------------------------------------    
    }
}