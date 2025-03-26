<?php  
namespace App\Actions\TrackOrder;

use App\Models\Order;
use App\Models\New_Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Order\OrderStatus;

class TrackOrderAction{

    protected $request;

    public function __construct(Request $request)
    {
        $this->request=$request;
    }

    public function getOrderTrack()
    {
        $email = $this->request->email;
        $refId=$this->request->refId;
        $order_detail=Order::where('ref_id',$refId)->first();
        if(!$order_detail)
        {
            return $response = [
                'error' => false,
                'data' => null,
                'msg' => 'Order Not Found'
            ];
        }
        $user_detail=New_Customer::where('id',$order_detail->user_id)->first();
        if($user_detail->id ===1)
        {
            $order=Order::where('email',$email)->where('ref_id',$refId)->first();
        }
        else
        {
            if($user_detail->email !=$email)
            {
                return $response = [
                    'error' => false,
                    'data' => null,
                    'msg' => 'Order Not Found'
                ];
            }
            $order=Order::where('user_id',$user_detail->id)->where('ref_id',$refId)->first();
        }
        if (!isset($order)) {
            return $response = [
                'error' => false,
                'data' => null,
                'msg' => 'Order Not Found'
            ];
        }
        $final_data=[];
        $order_status = OrderStatus::where('order_id', $order->id)->get();
        if(count($order_status) <=0)
        {
            $final_data[]=[
                'created_at' => Carbon::parse($order->created_at)->format('Y-m-d'),
                'time'=>Carbon::parse($order->created_at)->format('h:i A'),
                'items'=>count($order->orderAssets) ?? 0,
                'status_value' =>'PENDING'
            ];
        }
        // dd($order_status);
        $order_status = collect($order_status)->map(function ($item) use ($order){
            return [
                'created_at' => Carbon::parse($item->created_at)->format('Y-m-d'),
                'time'=>Carbon::parse($item->created_at)->format('h:i A'),
                'items'=>count($order->orderAssets) ?? 0,
                'status_value' => ($item->status == 1) ? 'SEEN' : (($item->status == 2) ? 'READY_TO_SHIP' : (($item->status == 3) ? 'DISPATCHED' : (($item->status == 4) ? 'SHIPED' : (($item->status == 5) ? 'DELIVERED' : (($item->status == 6) ? 'CANCELED' : 'REJECTED')))))
            ];
        });
        if(count($order_status) >0)
        {
            $final_data=$order_status;
        }
        

        $response = [
            'error' => false,
            'data' => $final_data,
            'msg' => 'Order Status Details'
        ];

        return $response;
      
        
    }
}