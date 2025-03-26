<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use Dotenv\Validator;
use Illuminate\Http\Request;
use App\Models\SellerOrderCancel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Order\Seller\SellerOrder;
use App\Enum\Seller\SellerOrderCancelStatus;
use App\Models\Order\Seller\ProductSellerOrder;
use App\Actions\Notification\NotificationAction;
use App\Models\Order\Seller\SellerOrderStatus;

class SellerOrderCancelController extends Controller
{
    
    public function cancelOrder(Request $request)
    {

        $seller=auth()->guard('seller')->user();
        if(!$seller)
        {
            $request->session()->flash('error','Something Went Wrong !!');
            return redirect()->back();
        }
        $productSellerOrder=ProductSellerOrder::where('id',$request->product_seller_order)->first();
        $sellerOrder=SellerOrder::where('id',$productSellerOrder->seller_order_id)->first();
        $order=Order::where('id',$sellerOrder->order_id)->first();
        if(!$productSellerOrder)
        {
            $request->session()->flash('error','Something Went Wrong !!');
            return redirect()->back();
        }
        
        DB::beginTransaction();
        try{
            $sellerCancel=SellerOrderCancel::create([
                'seller_id'=>$seller->id,
                'product_id'=>$productSellerOrder->product_id,
                'product_seller_order_id'=>$productSellerOrder->id,
                'reason'=>$request->remarks,
                'seller_order_id'=>$productSellerOrder->seller_order_id,
                'order_id'=>$sellerOrder->order_id,
                'cancel_status'=>SellerOrderCancelStatus::PENDING
            ]);
            DB::commit();
            $notification_data = [
                'from_model' => get_class(auth()->guard('seller')->user()->getModel()),
                'from_id' => auth()->guard('seller')->user()->id,
                'to_model' => get_class(User::getModel()),
                'to_id' => User::first()->id,
                'title' => 'Order Cancelled Request from ' . auth()->guard('seller')->user()->name . '.',
                'summary' => 'The Seller Cancelled Order Request.',
                'url' => url('admin/view-order', $order->ref_id),
                'is_read' => false,
            ];
            (new NotificationAction($notification_data))->store();

            $request->session()->flash('success','Cancel Request HasBeen Sent !!');
            return redirect()->back();
        }catch(\Throwable $th)
        {
            DB::rollback();
            $request->session()->flash('error','Something Went Wrong !!');
            return redirect()->back();
        }


    }

    public function getSellerReason(Request $request)
    {
        $orderStatus=SellerOrderStatus::where('seller_order_id',$request->reasonId)->first();;
        $data=$orderStatus->remarks ?? null;
        
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
