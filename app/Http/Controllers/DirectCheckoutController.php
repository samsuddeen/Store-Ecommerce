<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Admin\VatTax;
use App\Models\New_Customer;
use App\Models\OrderTimeSetting;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\Province;
use App\Models\Setting;
use App\Models\UserBillingAddress;
use App\Models\UserShippingAddress;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PDO;

class DirectCheckoutController extends Controller
{
    public function singleCheckout(Request $request)
    {
        // dd('test');
        $for_order = $request->session()->get('directOrder');
        if($for_order)
        {
            
            if (!$for_order['guestCheckout']) {
                $today = Carbon::now()->format('l');
                $order_timing_check = OrderTimeSetting::where('day', $today)->exists();
                // if ($order_timing_check) {
                //     $order_timing = OrderTimeSetting::where('day', $today)->first();
                //     if ($order_timing->day_off == true) {
                //         $request->session()->flash('error', 'You cannot place order today because it is our day off !!');
                //         return back();
                //     }
                //     $currentTime = Carbon::now()->format('H:i');
                //     if ($currentTime > $order_timing->end_time || $currentTime < $order_timing->start_time) {
                //         $request->session()->flash('error', 'Please place your order between ' . $order_timing->start_time . ' and ' . $order_timing->end_time);
                //         return back();
                //     }
                // }

                // dd($for_order);
                if ($for_order === null) {
                    session()->forget('directOrder');
                    $request->session()->flash('error', 'Plz Try again, Something is wrong');
                    return back();
                }
                $varient_id = $for_order['varient_id'];
                $stock_id = ProductStock::where('product_id', $for_order['product_id'])->first();
                if ($stock_id) {
                    if ((int)$stock_id->quantity <= 0 || $for_order['product_qty'] > (int)$stock_id->quantity) {
                        session()->forget('directOrder');
                        $request->session()->flash('error', 'Sorry !! Product Is Outof Stocks');
                        return redirect()->back();
                    }
                }


                $shipping_address = UserShippingAddress::where('user_id', auth()->guard('customer')->user()->id)->get();
                $billing_address = UserBillingAddress::where('user_id', auth()->guard('customer')->user()->id)->get();
                $product = Product::where('id', $for_order['product_id'])->first();

                $product_stock = ProductStock::where('id', $for_order['varient_id'])->where('product_id', $for_order['product_id'])->first();
                $offer_price = getOfferProduct($product, $product_stock);
                $default_shipping_charge = Setting::where('key', 'default_shipping_charge')->pluck('value')->first();
                $default_material_charge = Setting::where('key', 'materials_price')->pluck('value')->first();
                $default_shipping_address = UserShippingAddress::where('user_id', auth()->guard('customer')->user()->id)->first();
                // // dd($default_shipping_address);
                // if ($default_shipping_address->getLocation != null) {
                //     $default_shipping_charge = $default_shipping_address->getLocation->deliveryRoute->charge;
                // } else {
                //     $default_shipping_charge = Setting::where('key', 'default_shipping_charge')->pluck('value')->first();
                // }
                if ($offer_price != null) {
                    $price = $offer_price;
                    $sub_total = $offer_price * $for_order['product_qty'];
                } elseif ($product_stock->special_price != null) {
                    $price = $product_stock->special_price;
                    $sub_total = $product_stock->special_price * $for_order['product_qty'];
                } else {
                    $price = $product_stock->price;
                    $sub_total = $product_stock->price * $for_order['product_qty'];
                }
                // dd($default_shipping_charge);
                $mimimum_order_cost = Setting::where('key', 'minimum_order_price')->pluck('value')->first();
                $consumer = auth()->guard('customer')->user();
                $total_amount = $sub_total;

                $default_shipping_charge=0;
                if ($consumer->wholeseller) {
                    if ($total_amount < $mimimum_order_cost) {
                        $request->session()->flash('error', 'The minimum order amount should be $. ' . $mimimum_order_cost . ' !!');
                        return back();
                    }
                    $wholeSellerShippingCharge = Setting::where('key', 'wholseller_shipping_charge')->first()->value;
                    $default_shipping_charge=$wholeSellerShippingCharge ?? 0;
                    
                } else {
                    
                    $default_shipping_charge=$default_shipping_charge+($product->shipping_charge * $for_order['product_qty']);
                    
                }


                // if ($consumer->wholeseller) {
                //     if ($total_amount < $mimimum_order_cost) {
                //         $request->session()->flash('error', 'The minimum order amount should be $. ' . $mimimum_order_cost . ' !!');
                //         return back();
                //     }
                //     $wholeSellerMinimumPrice = Setting::where('key', 'whole_seller_minimum_price')->first()->value;
                //     $wholeSellerShippingCharge = Setting::where('key', 'wholseller_shipping_charge')->first()->value;
                //     if ($total_amount >= $wholeSellerMinimumPrice) {
                //         $default_shipping_charge = 0;
                //     } else {
                //         $default_shipping_charge = $wholeSellerShippingCharge;
                //     }
                // } else {
                //     $wholeSellecustomerShippingChargePerKg = Setting::where('key', 'shippping_charge_per_kg')->first()->value;
                //     $packetWeight = $product->package_weight;
                //     $default_shipping_charge = (int)$wholeSellecustomerShippingChargePerKg * (int)$packetWeight;
                // }


                if ($product->vat_percent == 0) {
                    $vatTax = VatTax::first();
                    if ($vatTax) {
                        $vatPercent = (int)$vatTax->vat_percent;
                        $vatAmount = ($sub_total * $vatPercent) / 100;
                        $sub_total = $sub_total + round($vatAmount);
                    }
                    $vatAmount = 0;
                } else {
                    $vatAmount = 0;
                }
                $provinces = Province::where('publishStatus', 1)->get();
                return view('frontend.cart.oneProductCheckout', compact('total_amount'))
                ->with('shipping_address', $shipping_address)
                ->with('billing_address', $billing_address)
                ->with('product', $product)
                ->with('product_stock', $product_stock)
                ->with('sub_total', $sub_total)
                ->with('product_pice', $price)
                ->with('provinces', $provinces)
                ->with('qty', $for_order['product_qty'] ?? 0)
                ->with('vatAmount', round($vatAmount) ?? 0)
                ->with('default_shipping_charge', $default_shipping_charge)
                ->with('material_charge', $default_material_charge);
            }
            else {
                $today = Carbon::now()->format('l');
                $order_timing_check = OrderTimeSetting::where('day', $today)->exists();
                // if ($order_timing_check) {
                //     $order_timing = OrderTimeSetting::where('day', $today)->first();
                //     if ($order_timing->day_off == true) {
                //         $request->session()->flash('error', 'You cannot place order today because it is our day off !!');
                //         return back();
                //     }
                //     $currentTime = Carbon::now()->format('H:i');
                //     if ($currentTime > $order_timing->end_time || $currentTime < $order_timing->start_time) {
                //         $request->session()->flash('error', 'Please place your order between ' . $order_timing->start_time . ' and ' . $order_timing->end_time);
                //         return back();
                //     }
                // }

                // dd($for_order);
                if ($for_order === null) {
                    session()->forget('directOrder');
                    $request->session()->flash('error', 'Plz Try again, Something is wrong');
                    return back();
                }
                $varient_id = $for_order['varient_id'];
                $stock_id = ProductStock::where('product_id', $for_order['product_id'])->first();
                if ($stock_id) {
                    if ((int)$stock_id->quantity <= 0 || $for_order['product_qty'] > (int)$stock_id->quantity) {
                        session()->forget('directOrder');
                        $request->session()->flash('error', 'Sorry !! Product Is Outof Stocks');
                        return redirect()->back();
                    }
                }

                
                $shipping_address = UserShippingAddress::where('user_id', 18)->get();
                $billing_address = UserBillingAddress::where('user_id', 18)->get();
                $product = Product::where('id', $for_order['product_id'])->first();

                $product_stock = ProductStock::where('id', $for_order['varient_id'])->where('product_id', $for_order['product_id'])->first();
                $offer_price = getOfferProduct($product, $product_stock);
                $default_shipping_charge = Setting::where('key', 'default_shipping_charge')->pluck('value')->first();
                $default_material_charge = Setting::where('key', 'materials_price')->pluck('value')->first();
                $default_shipping_address = UserShippingAddress::where('user_id',18)->first();
                if ($offer_price != null) {
                    $price = $offer_price;
                    $sub_total = $offer_price * $for_order['product_qty'];
                } elseif ($product_stock->special_price != null) {
                    $price = $product_stock->special_price;
                    $sub_total = $product_stock->special_price * $for_order['product_qty'];
                } else {
                    $price = $product_stock->price;
                    $sub_total = $product_stock->price * $for_order['product_qty'];
                }
                // dd($default_shipping_charge);
                $mimimum_order_cost = Setting::where('key', 'minimum_order_price')->pluck('value')->first();
                $consumer = New_Customer::where('id',63)->first();
                $total_amount = $sub_total;

                $default_shipping_charge=0;
                if ($consumer->wholeseller) {
                    if ($total_amount < $mimimum_order_cost) {
                        $request->session()->flash('error', 'The minimum order amount should be $. ' . $mimimum_order_cost . ' !!');
                        return back();
                    }
                    $wholeSellerShippingCharge = Setting::where('key', 'wholseller_shipping_charge')->first()->value;
                    $default_shipping_charge=$wholeSellerShippingCharge ?? 0;
                    
                } else {
                    
                    $default_shipping_charge=$default_shipping_charge+($product->shipping_charge * $for_order['product_qty']);
                    
                }


                // if ($consumer->wholeseller) {
                //     if ($total_amount < $mimimum_order_cost) {
                //         $request->session()->flash('error', 'The minimum order amount should be $. ' . $mimimum_order_cost . ' !!');
                //         return back();
                //     }
                //     $wholeSellerMinimumPrice = Setting::where('key', 'whole_seller_minimum_price')->first()->value;
                //     $wholeSellerShippingCharge = Setting::where('key', 'wholseller_shipping_charge')->first()->value;
                //     if ($total_amount >= $wholeSellerMinimumPrice) {
                //         $default_shipping_charge = 0;
                //     } else {
                //         $default_shipping_charge = $wholeSellerShippingCharge;
                //     }
                // } else {
                //     $wholeSellecustomerShippingChargePerKg = Setting::where('key', 'shippping_charge_per_kg')->first()->value;
                //     $packetWeight = $product->package_weight;
                //     $default_shipping_charge = (int)$wholeSellecustomerShippingChargePerKg * (int)$packetWeight;
                // }


                if ($product->vat_percent == 0) {
                    $vatTax = VatTax::first();
                    if ($vatTax) {
                        $vatPercent = (int)$vatTax->vat_percent;
                        $vatAmount = ($sub_total * $vatPercent) / 100;
                        $sub_total = $sub_total + round($vatAmount);
                    }
                    $vatAmount = 0;
                } else {
                    $vatAmount = 0;
                }
                $provinces = Province::where('publishStatus', 1)->get();
                return view('frontend.cart.oneProductCheckoutguest', compact('total_amount'))
                ->with('shipping_address', $shipping_address)
                ->with('billing_address', $billing_address)
                ->with('product', $product)
                ->with('product_stock', $product_stock)
                ->with('sub_total', $sub_total)
                ->with('product_pice', $price)
                ->with('provinces', $provinces)
                ->with('qty', $for_order['product_qty'] ?? 0)
                ->with('vatAmount', round($vatAmount) ?? 0)
                ->with('default_shipping_charge', $default_shipping_charge)
                ->with('material_charge', $default_material_charge)
                ->with('consumer', $consumer);
            }
        }
        else
        {
            return redirect()->route('index');
        }
       
    }
}
