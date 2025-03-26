<?php

namespace App\Http\Controllers\Checkout;

use App\Models\Cart;
use App\Models\Coupon;
use GuzzleHttp\Client;
use App\Models\Country;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Province;
use App\Models\CartAssets;
use App\Models\New_Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\OrderTimeSetting;
use App\Models\UserBillingAddress;
use App\Models\UserShippingAddress;
use App\Http\Controllers\Controller;
use App\Models\Payment\PaymentMethod;
use App\Data\Cart\Item\CartItemCheckoutData;

class CheckoutController extends Controller
{  
  public function getCheckout(Request $request)
  {
    // dd(session()->get('cart_order_item'));
    session()->forget('cart_order_item');
    session()->forget('esewa__order');
    if ($request->items == null) {
      $request->session()->flash('error', 'Plz Select Atleast One Item !!');
      return redirect()->back();
    }

    $today = Carbon::now()->format('l');
    $order_timing_check = OrderTimeSetting::where('day', $today)->exists();
    $mimimum_order_cost = Setting::where('key','minimum_order_price')->pluck('value')->first();
    $default_shipping_charge = Setting::where('key','default_shipping_charge')->pluck('value')->first();
    $default_material_charge = Setting::where('key','materials_price')->pluck('value')->first();

    

    if($order_timing_check)
    {   
        $order_timing = OrderTimeSetting::where('day',$today)->first();
        if($order_timing->day_off == true)
        {   
            $request->session()->flash('error','You cannot place order today because it is our day off !!');
            return back()->with('error','You cannot place order today because it is our day off !!');
        }
        $currentTime = Carbon::now()->format('H:i');
        if($currentTime > $order_timing->end_time || $currentTime < $order_timing->start_time){
            $request->session()->flash('error','Please place your order between ' . $order_timing->start_time . ' and ' .$order_timing->end_time);
            return back()->with('error','Please place your order between ' . Carbon::parse($order_timing->start_time)->format('h:i A') . ' and ' . Carbon::parse($order_timing->end_time)->format('h:i A'));
        }
    }

    $order_item = $request->all();
    session()->put('cart_order_item', $order_item);
    session()->put('esewa__order', $order_item);

    $cart_item = (new CartItemCheckoutData($request))->getData();
    $cart_item = collect($cart_item);
    $total_amount = null;

    foreach ($cart_item as $items) {
      if ($items == null || empty($items)) {
        return redirect()->back();
      }
      $total_amount = $total_amount + $items->sub_total_price;
    }
    $consumer = New_Customer::where('id', auth()->guard('customer')->user()->id)->first();

    if($consumer->wholeseller)
    {
      if($total_amount < $mimimum_order_cost){
        $request->session()->flash('error','The minimum order amount should be $. '.$mimimum_order_cost.' !!');
        return back();
      }
      $wholeSellerMinimumPrice=Setting::where('key','minimum_order_price')->first()->value;
      $wholeSellerShippingCharge=Setting::where('key','wholseller_shipping_charge')->first()->value;
      if($total_amount >=$wholeSellerMinimumPrice)
      {
        $default_shipping_charge=0;
      }
      else
      {
        $default_shipping_charge=$wholeSellerShippingCharge;
      }
    }
    else
    {
      $finalDefaultCharge=0;
      foreach($cart_item as $itemData){
        $productCharge=Product::where('id',$itemData->product_id)->first()->shipping_charge;
        $finalDefaultCharge=$finalDefaultCharge+$productCharge;
      }
      $default_shipping_charge=$finalDefaultCharge;
      // $wholeSellecustomerShippingChargePerKg=Setting::where('key','shippping_charge_per_kg')->first()->value;
      // $productId=collect($cart_item)->pluck('product_id')->toArray();
      // $packetWeight=Product::whereIn('id',$productId)->get()->sum('package_weight');
      // $default_shipping_charge=(int)$wholeSellecustomerShippingChargePerKg * (int)$packetWeight;
    }

    $cart = Cart::where('user_id', auth()->guard('customer')->user()->id)->first();
    if (empty($cart)) {
      return redirect()->back()->with('error', 'add some product on cart');
    }

    
    $countries = Country::where('status', true)->get();
    $provinces = Province::where('publishStatus', 1)->get();

    $shipping_address = UserShippingAddress::where('user_id', auth()->guard('customer')->user()->id)->latest()->take(5)->get();
    $billing_address = UserBillingAddress::where('user_id', auth()->guard('customer')->user()->id)->latest()->take(5)->get();
    $payment_method = PaymentMethod::where('status', '1')->get();
    return view('frontend.cart.checkout', compact('total_amount', 'cart_item', 'provinces', 'consumer', 'default_material_charge' , 'countries', 'shipping_address', 'billing_address', 'payment_method','default_shipping_charge'));
  }



  public function pay(Request $request)
  {
      // Set up the Guzzle client to send the request
      $client = new Client([
          'base_uri' => 'https://api.khalti.com/',
          'headers' => [
              'Authorization' => 'Key YOUR_SECRET_KEY_HERE',
              'Content-Type' => 'application/json',
              'Accept' => 'application/json',
          ],
      ]);

      // Prepare the request data
      $data = [
          'mobile' => $request->mobile,
          'amount' => $request->amount,
          'product_name' => 'Order #' . $request->order_id,
          'product_identity' => $request->order_id,
          'product_url' => 'http://yourwebsite.com/orders/' . $request->order_id,
      ];

      // Send the request to Khalti's ePayment API
      $response = $client->post('v2/payment/initialize', [
          'json' => $data,
      ]);

      // Process the response
      $responseBody = json_decode($response->getBody(), true);
      if ($responseBody['state']['name'] == 'Initialized') {
          // Redirect the user to Khalti's payment page
          return redirect($responseBody['data']['url']);
      } else {
          // Handle the error
          return redirect()->back()->with('error', 'Failed to initiate payment');
      }
  }
}
