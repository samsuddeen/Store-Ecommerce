<?php
namespace App\Actions\CancelOrder;

use Auth;
use App\Models\User;
use App\Models\Order;
use App\Events\LogEvent;
use App\Models\OrderAsset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ProductCancelReason;
use App\Actions\Notification\NotificationAction;

class CancelOrderAction{

    protected $request;
    public function __construct(Request $request)
    {
        $this->request=$request;
    }

    public function cancelAction()
    {
        $order = Order::where('id', $this->request->order_id)->first();

        $data = [
            'order_id' => $order->id,
            'reason' => $this->request->reason,
            'additional_reason' => $this->request->additional_reason,
            'user_id' =>\Auth::user()->id,
        ];

        $order_asset = OrderAsset::where('id', $this->request->order_id)->first();
        
        $notification_data = [
            'from_model' => get_class(Auth::user()->getModel()),
            'from_id' => \Auth::user()->id,
            'to_model' => get_class(User::getModel()),
            'to_id' => User::first()->id,
            'title' => 'Order Cancelled from' . \Auth::user()->name . '.',
            'summary' => 'The Customer Cancelled Order.',
            'url' => url('admin/view-order', $order->ref_id),
            'is_read' => false,
        ];
        (new NotificationAction($notification_data))->store();

        LogEvent::dispatch('Order Canceled', 'Order Canceled', route('cancel.order', $this->request->order_id));
        $order->update([
            'status' => '6',
            'pending' => '0',
            'ready_to_ship' => '0',
        ]);

        DB::beginTransaction();
        try {
            ProductCancelReason::create($data);
            $order->status = 6;
            $order->save();
            DB::commit();
            $response=[
                'error'=>false,
                'data'=>null,
                'msg'=>'Order Cancelled Successfully !!'
            ];
            return $response;
        } catch (\Throwable $th) {
            DB::rollBack();
            $response=[
                'error'=>true,
                'data'=>null,
                'msg'=>'Something Went Wrong !!'
            ];
            return $response;
        }
    }
}