<?php

namespace App\Http\Controllers\Customer;

use App\Models\User;
use App\Models\Order;
use App\Models\seller;
use App\Models\Product;
use App\Events\LogEvent;
use App\Models\OrderAsset;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Order\OrderStatus;
use App\Models\CancellationReason;
use Illuminate\Support\Facades\DB;
use App\Enum\Order\OrderStatusEnum;
use App\Models\ProductCancelReason;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Order\Seller\SellerOrder;
use App\Enum\Seller\SellerOrderStatusEnum;
use App\Models\Order\Seller\SellerOrderStatus;
use App\Actions\Notification\NotificationAction;
use App\Models\Delivery\DeliveryFeedback;

class OrderController extends Controller
{
    public function order()
    {
        $orders = Order::where('user_id', Auth::guard('customer')->user()->id)->where('deleted_by_customer', '0')->orderBy('created_at', 'DESC')->paginate(10);
        $cancellation_reasons = CancellationReason::where('status',1)->orderBy('title', 'ASC')->get();
        return view('frontend.customer.order', compact('orders', 'cancellation_reasons'));
    }

    public function orderProduct(Request $request, $id)
    {
        $user = auth()->guard('customer')->user();
        if (!$user) {
            $request->session()->flash('error', 'UnAuthorized Access !!');
            return redirect()->route('Clogin');
        }
        $order = Order::where('user_id', $user->id)->where('id', $id)->first();
        if (!$order) {
            $request->session()->flash('error', 'Something Went Wrong !!');
            return redirect()->route('Corder');
        }
        $data['order'] = $order;
        $data['order_asset'] = $order->orderAssets;
        $data['feedback'] = DeliveryFeedback::where('order_id',$order->id)->where('customer_id',$user->id)->first();
        return view('frontend.customer.orderproductdetail', $data);
    }

    public function getAdminOrderdetails(Request $request,$orderStatus)
    {
       
        $seller_order=SellerOrder::where('id',$orderStatus)->get();
        $order=Order::findOrFail($orderStatus);
        $seller_order_details=$order->orderAssets;
        $seller_details=Seller::findOrFail($seller_order->seller_id);
        $order_status = SellerOrderStatus::where('seller_order_id', $seller_order->id)->get();
        $order_status = collect($order_status)->map(function ($item) {
            return [
                'created_at' => $item->created_at,
                'status_value' => ($item->status == 1) ? 'SEEN' : (($item->status == 2) ? 'READY_TO_SHIP' : (($item->status == 3) ? 'DISPATCHED' : (($item->status == 4) ? 'SHIPED' : (($item->status == 5) ? 'DELIVERED' : (($item->status == 6) ? 'DELIVERED_TO_HUB' : (($item->status == 6) ? 'CANCELED' : 'REJECTED'))))))
            ];
        });
        return view('seller.bill.billdetail',compact('seller_order','seller_order_details','seller_details','order','order_status'));
    }
    public function deleted($ref_id)
    {
        $order = Order::where('ref_id', $ref_id)->first();

        try {
            $order->update([
                'deleted_by_customer' => '1',
            ]);
            return redirect()->back()->with('success', 'Order Deleted Successcully');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'OOPs, Please Try Again');
        }
    }

    public function cancelOrder(Request $request, $id)
    {
        

        DB::beginTransaction();
        try {
            $order = Order::where('id', $id)->first();
        $request->validate([
            'reason' => 'required',
            'aggree' => 'required',
            'additional_reason' => 'required',
        ]);

        $data = [
            'order_id' => $order->id,
            'reason' => $request->reason,
            'additional_reason' => $request->additional_reason,
            'user_id' => Auth::guard('customer')->user()->id,
        ];

        $order_asset = OrderAsset::where('id', $id)->first();

        $notification_data = [
            'from_model' => get_class(auth()->guard('customer')->user()->getModel()),
            'from_id' => auth()->guard('customer')->user()->id,
            'to_model' => get_class(User::getModel()),
            'to_id' => User::first()->id,
            'title' => 'Order Cancelled from' . auth()->guard('customer')->user()->name . '.',
            'summary' => 'The Customer Cancelled Order.',
            'url' => url('admin/view-order', $order->ref_id),
            'is_read' => false,
        ];
        (new NotificationAction($notification_data))->store();

        LogEvent::dispatch('Order Canceled', 'Order Canceled', route('cancel.order', $id));
        $order->update([
            'status' => '6',
            'pending' => '0',
            'ready_to_ship' => '0',
        ]);
            ProductCancelReason::create($data);
            $order->status = 6;
            $order->save();

            $order->update([
                'status' => OrderStatusEnum::CANCELED,
            ]);
            OrderStatus::updateOrCreate([
                'status'=>OrderStatusEnum::CANCELED,
                'order_id'=>$order->id,
            ], [
                'date'=>Carbon::now()->toDateString(),
                'updated_by'=>auth()->guard('customer')->user()->id,
                'remarks'=>$request->reason.'<br>'.$request->additional_reason,
            ]);

           

            $order->sellerOrder()->update([
                        'status'=> SellerOrderStatusEnum::CANCELED
                    ]);
                    foreach($order->sellerOrder as $order){
                        SellerOrderStatus::updateOrCreate([
                            'status'=>SellerOrderStatusEnum::CANCELED,
                            'seller_order_id'=>$order->id,
                        ], [
                            'date'=>Carbon::now()->toDateString(),
                            'updated_by'=>auth()->user()->id,
                            'remarks'=>$request->reason.'<br>'.$request->additional_reason,
                        ]);     
                    }

            DB::commit();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
        return redirect()->back()->with('success', 'Successfully order cancelled');
    }

    public function orderAgain($id)
    {
        $order = Order::where('id', $id)->first();
        $order->update([
            'pending' => '1',
            'cancelled' => '0',
        ]);
        return redirect()->route('Corder');
    }

    public function allOrderProduct(Request $request)
    {
        $ref_id = $request->ref_id;
        $order = Order::where('ref_id', $ref_id)->first();
        $products = $order->orderAssets;
        return view('frontend.customer.order-product', compact('products'));
    }

    public function getReason(Request $request)
    {
        $orderStatus=OrderStatus::where('order_id',$request->reasonId)->where('status',6)->first();
        $data=$orderStatus->remarks ?? null;
        if(!$orderStatus)
        {
            $orderStatus=ProductCancelReason::where('order_id',$request->reasonId)->first();
            $data=($orderStatus->reason ?? null).' <br> '.($orderStatus->additional_reason ?? null);

        }
        if($orderStatus)
        {
            $response=[
                'error'=>false,
                'data'=>$data,
            ];
            return response()->json($response,200);
        }
        else
        {
            $response=[
                'error'=>true,
                'data'=>null
            ];
            return response()->json($response,200);
        }
    }

    public function getReject(Request $request)
    {
        $orderStatus=OrderStatus::where('order_id',$request->rejectId)->where('status',7)->first();
        $data=$orderStatus->remarks ?? null;
        if(!$orderStatus)
        {
            $orderStatus=ProductCancelReason::where('order_id',$request->reasonId)->first();
            $data=($orderStatus->reason ?? null).' <br> '.($orderStatus->additional_reason ?? null);

        }
        if($orderStatus)
        {
            $response=[
                'error'=>false,
                'data'=>$data,
            ];
            return response()->json($response,200);
        }
        else
        {
            $response=[
                'error'=>true,
                'data'=>null
            ];
            return response()->json($response,200);
        }
    }
}
