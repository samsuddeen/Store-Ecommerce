<?php

namespace App\Http\Controllers\Customer;

use Carbon\Carbon;
use App\Models\Local;
use App\Models\Order;
use App\Models\Product;
use App\Events\LogEvent;
use App\Models\OrderAsset;
use Illuminate\Http\Request;
use App\Models\DeliveryRoute;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Data\Dashboard\DashboardData;
use App\Enum\Order\OrderStatusEnum;
use App\Models\Delivery\DeliveryFeedback;
use App\Models\New_Customer;
use App\Models\Transaction\Transaction;
use App\Models\Order\Seller\SellerOrder;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
    
        $total_orders = Order::where('user_id', auth()->guard('customer')->user()->id)->where('deleted_by_customer', '0')->orderBy('created_at','DESC')->get();
        $total_order = count($total_orders);
        $total_amount = 0;
        foreach ($total_orders as $order) {
            $amount = $order->total_price;
            $total_amount += $amount;
        }
        $shipping_amount = 0;
        foreach ($total_orders as $order) {
            $charge = $order->shipping_charge;
            $shipping_amount += $charge;
        }
        $coupan_discount = 0;
        foreach ($total_orders as $order) {
            $discount = $order->coupon_discount_price;
            $coupan_discount += $discount;
        }
        $total_products = [];
        $all_products = [];
        
        foreach ($total_orders as $order) {
            $order_assets = OrderAsset::where('order_id', $order->id)->get();
            foreach ($order_assets as $order_ass) {
                array_push($all_products, $order_ass);
                if (!in_array($order_ass->product_id, $total_products)) {
                    array_push($total_products, $order_ass->product_id);
                }
            }
        }
        $total_orders = Order::where('user_id', auth()->guard('customer')->user()->id)->where('deleted_by_customer', '0')->orderBy('created_at','DESC')->paginate(10);
        
        $total_products = count($total_products);
        LogEvent::dispatch('Dashboard View', 'Dashboard View', route('Cdashboard'));
        return view('frontend.customer.dashboard', compact('total_orders','total_order', 'total_products', 'total_amount', 'all_products', 'shipping_amount', 'coupan_discount'));
    }

    
    public function reviewLatestOrder()
    {
        $latestOrder = Order::where('user_id',auth()->guard('customer')->user()->id)->where('status',OrderStatusEnum::DELIVERED)->latest()->first();
        $delivery_feedback = DeliveryFeedback::where('order_id',$latestOrder->id)->where('customer_id',auth()->guard('customer')->user()->id)->exists();
        if($delivery_feedback)
        {
            return response()->json(['message'=>'Already Reviewed']);
        }

        if(!$latestOrder)
        {
            return response()->json(['order_id' => null]);
        }

        return response()->json(['latest_order' => $latestOrder]);
    }

    public function deliveryFeedback(Request $request)
    {
        $request->validate([
            'rating' => 'required|numeric|max:5',
            'image.*' => 'image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $input = [
            'order_id' => $request->order_id,
            'customer_id' => auth()->guard('customer')->user()->id,
            'rating' => $request->rating,
            'message' => $request->message, 
        ];

        $uploadImages = [];
        
        if($request->hasFile('image'))
        {
            foreach ($request->file('image') as $image) {
                $filename = Str::random(20) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $filename);
                $uploadedImages[] = $filename;
            }

            $imageFilenames = implode('|', $uploadedImages);
            $input['image'] = $imageFilenames;
        }

        DeliveryFeedback::create($input);

        return redirect()->route('Cdashboard')->with('success','Review Submitted successfully!');
    }

    public function sellerDashboard()
    {
        // $permissions = Permission::orderBy('id')->get();
        // $seller_permissions = Permission::orderBy('id')->where('guard_name', 'seller')->get();
        // $seller=auth()->user()->getPermissions;
        // foreach($permissions as $permission){
        //     $data1 = [
        //         'permission_id'=>$permission->id,
        //         'role_id'=>2
        //     ];
        //     DB::table('role_has_permissions')->insert($data1);
           
        // }
        // foreach($seller_permissions as $permission){
        //     $data2 = [
        //         'permission_id'=>$permission->id,
        //         'role_id'=>3
        //     ];
        //     DB::table('role_has_permissions')->insert($data2);
        // }
        // dd($seller_permissions);
        LogEvent::dispatch('Dashboard View', 'Dashboard View', route('sellerDashboard'));

        $total_products = Product::where('seller_id', auth()->guard('seller')->user()->id)->get();
        $total_categories = $total_products->groupBy('category_id');
        $new_orders = SellerOrder::where('seller_id', auth()->guard('seller')->user()->id)->where('status', '1')->get();
        $total_delivered = SellerOrder::where('seller_id', auth()->guard('seller')->user()->id)->where('status', '6')->get();
        $total_orders = SellerOrder::where('seller_id', auth()->guard('seller')->user()->id)->get();
        $orders_in_progress = SellerORder::where('seller_id', auth()->guard('seller')->user()->id)->whereNot('status', '6')->WhereNot('status', '1')->get();
        $priceSum = null;
        foreach ($total_delivered as $key => $value) {
            $priceSum += $value->subtotal;
        }

        $data = [
            'total_products' => $total_products,
            'total_categories' => $total_categories,
            'new_orders' => $new_orders,
            'total_delivered' => $total_delivered,
            'total_orders' => $total_orders,
            'total_income' => $priceSum,
            'orders_in_progress' => $orders_in_progress,
        ];
        $data['transactions']=(new DashboardData)->getSellerData();
        $user_transaction=Transaction::get();
        $user_tran_order=$user_transaction->map(function($item){
            return $item->order_id;
        });

        $seller_Order=SellerOrder::where('seller_id',auth()->guard('seller')->user()->id)->whereIn('order_id',$user_tran_order)->get();
        $user_id=$seller_Order->map(function($user){
            return $user->user_id;
        });
       
        $user_id=collect($user_id)->unique();
        
        $customer=New_Customer::whereIn('id',$user_id)->get();
        $seller = auth()->guard('seller')->user();
                
        if ($seller->status == 1) {        
            return view('frontend.seller.dashboard', $data,compact('customer'));
        }
        else
        {
            return view('frontend.seller.inactive-dashboard');
        }
    }

    public function sellerupdateModeColor(Request $request)
    {
      if($request->darkCheck==='true')
      {
        $darkModeArray=[
            'htmlValue'=>'dark-layout',
            'navValue'=>'navbar-dark',
            'mainValue'=>'menu-dark',
        ];

      }
      else
      {
        $darkModeArray=[
            'htmlValue'=>'',
            'navValue'=>'',
            'mainValue'=>'',
        ];
      }
      $request->session()->put('DarkModeValue',$darkModeArray);
      return response()->json( $request->session()->get('DarkModeValue'),200);

        
    }
}
