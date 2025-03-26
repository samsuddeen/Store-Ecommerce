<?php

namespace App\Actions\Order;

use Carbon\Carbon;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Order\OrderStatus;
use App\Enum\Order\OrderStatusEnum;
use App\Enum\Seller\SellerOrderStatusEnum;
use App\Actions\Transaction\TransactionAction;
use App\Models\Order\Seller\SellerOrderStatus;
use App\Observers\Transaction\TransactionObserver;

class OrderStatusAction
{
    protected $order;
    protected $status;
    protected $request;
    function __construct(Request $request, Order $order, $status)
    {
        $this->order = $order;
        $this->status = $status;
        $this->request = $request;
    }
    public function updateStatus()
    {
        switch ($this->status) {
            case 'SEEN':
                $this->order->update([
                    'status' => OrderStatusEnum::SEEN,
                ]);
                OrderStatus::updateOrCreate([
                    'status'=>OrderStatusEnum::SEEN,
                    'order_id'=>$this->order->id,
                ], [
                    'date'=>Carbon::now()->toDateString(),
                    'updated_by'=>auth()->user()->id,
                    'remarks'=>$this->request->remarks,
                ]);
                break;
            case 'READY_TO_SHIP':
                $this->order->update([
                    'status' => OrderStatusEnum::READY_TO_SHIP,
                ]);
                $order_status = OrderStatus::where('status', OrderStatusEnum::SEEN)->where('order_id', $this->order->id)->first();
                if(!$order_status){
                    OrderStatus::create([
                        'status'=>OrderStatusEnum::SEEN,
                        'order_id'=>$this->order->id,
                        'date'=>Carbon::now()->toDateString(),
                        'updated_by'=>auth()->user()->id,
                        'remarks'=>$this->request->remarks,
                    ]);
                }
                OrderStatus::updateOrCreate([
                    'status'=>OrderStatusEnum::READY_TO_SHIP,
                    'order_id'=>$this->order->id,
                ], [
                    'date'=>Carbon::now()->toDateString(),
                    'updated_by'=>auth()->user()->id,
                    'remarks'=>$this->request->remarks,
                ]);
                break;
            case 'DISPATCHED':
                $this->order->update([
                    'status' => OrderStatusEnum::DISPATCHED,
                ]);

                $order_status = OrderStatus::where('status', OrderStatusEnum::SEEN)->where('order_id', $this->order->id)->first();
                if(!$order_status){
                    OrderStatus::create([
                        'status'=>OrderStatusEnum::SEEN,
                        'order_id'=>$this->order->id,
                        'date'=>Carbon::now()->toDateString(),
                        'updated_by'=>auth()->user()->id,
                        'remarks'=>$this->request->remarks,
                    ]);
                }

                $order_status = OrderStatus::where('status', OrderStatusEnum::READY_TO_SHIP)->where('order_id', $this->order->id)->first();
                if(!$order_status){
                    OrderStatus::create([
                        'status'=>OrderStatusEnum::READY_TO_SHIP,
                        'order_id'=>$this->order->id,
                        'date'=>Carbon::now()->toDateString(),
                        'updated_by'=>auth()->user()->id,
                        'remarks'=>$this->request->remarks,
                    ]);
                }

                OrderStatus::updateOrCreate([
                    'status'=>OrderStatusEnum::DISPATCHED,
                    'order_id'=>$this->order->id,
                ], [
                    'date'=>Carbon::now()->toDateString(),
                    'updated_by'=>auth()->user()->id,
                    'remarks'=>$this->request->remarks,
                ]);
                break;
            case 'SHPIED':
                $this->order->update([
                    'status' => OrderStatusEnum::SHPIED,
                ]);

                $order_status = OrderStatus::where('status', OrderStatusEnum::SEEN)->where('order_id', $this->order->id)->first();
                if(!$order_status){
                    OrderStatus::create([
                        'status'=>OrderStatusEnum::SEEN,
                        'order_id'=>$this->order->id,
                        'date'=>Carbon::now()->toDateString(),
                        'updated_by'=>auth()->user()->id,
                        'remarks'=>$this->request->remarks,
                    ]);
                }

                $order_status = OrderStatus::where('status', OrderStatusEnum::READY_TO_SHIP)->where('order_id', $this->order->id)->first();
                if(!$order_status){
                    OrderStatus::create([
                        'status'=>OrderStatusEnum::READY_TO_SHIP,
                        'order_id'=>$this->order->id,
                        'date'=>Carbon::now()->toDateString(),
                        'updated_by'=>auth()->user()->id,
                        'remarks'=>$this->request->remarks,
                    ]);
                }

                $order_status = OrderStatus::where('status',OrderStatusEnum::DISPATCHED)->where('order_id', $this->order->id)->first();
                if(!$order_status){
                    OrderStatus::create([
                        'status'=>OrderStatusEnum::DISPATCHED,
                        'order_id'=>$this->order->id,
                        'date'=>Carbon::now()->toDateString(),
                        'updated_by'=>auth()->user()->id,
                        'remarks'=>$this->request->remarks,
                    ]);
                }



                OrderStatus::updateOrCreate([
                    'status'=>OrderStatusEnum::SHPIED,
                    'order_id'=>$this->order->id,
                ], [
                    'date'=>Carbon::now()->toDateString(),
                    'updated_by'=>auth()->user()->id,
                    'remarks'=>$this->request->remarks,
                ]);
                break;
            case 'DELIVERED':
                $this->order->update([
                    'status' => OrderStatusEnum::DELIVERED,
                    'payment_status'=>1
                ]);


                $order_status = OrderStatus::where('status', OrderStatusEnum::SEEN)->where('order_id', $this->order->id)->first();
                if(!$order_status){
                    OrderStatus::create([
                        'status'=>OrderStatusEnum::SEEN,
                        'order_id'=>$this->order->id,
                        'date'=>Carbon::now()->toDateString(),
                        'updated_by'=>auth()->user()->id,
                        'remarks'=>$this->request->remarks,
                    ]);
                }

                $order_status = OrderStatus::where('status', OrderStatusEnum::READY_TO_SHIP)->where('order_id', $this->order->id)->first();
                if(!$order_status){
                    OrderStatus::create([
                        'status'=>OrderStatusEnum::READY_TO_SHIP,
                        'order_id'=>$this->order->id,
                        'date'=>Carbon::now()->toDateString(),
                        'updated_by'=>auth()->user()->id,
                        'remarks'=>$this->request->remarks,
                    ]);
                }

                $order_status = OrderStatus::where('status', OrderStatusEnum::DISPATCHED)->where('order_id', $this->order->id)->first();
                if(!$order_status){
                    OrderStatus::create([
                        'status'=>OrderStatusEnum::DISPATCHED,
                        'order_id'=>$this->order->id,
                        'date'=>Carbon::now()->toDateString(),
                        'updated_by'=>auth()->user()->id,
                        'remarks'=>$this->request->remarks,
                    ]);
                }

                $order_status = OrderStatus::where('status', OrderStatusEnum::SHPIED)->where('order_id', $this->order->id)->first();
                if(!$order_status){
                    OrderStatus::create([
                        'status'=>OrderStatusEnum::SHPIED,
                        'order_id'=>$this->order->id,
                        'date'=>Carbon::now()->toDateString(),
                        'updated_by'=>auth()->user()->id,
                        'remarks'=>$this->request->remarks,
                    ]);
                }

                OrderStatus::updateOrCreate([
                    'status'=>OrderStatusEnum::DELIVERED,
                    'order_id'=>$this->order->id,
                ], [
                    'date'=>Carbon::now()->toDateString(),
                    'updated_by'=>auth()->user()->id,
                    'remarks'=>$this->request->remarks,
                ]);
                // to store transaction for the order
                (new TransactionAction($this->request))->store($this->order);
                // to stock manage
                (new TransactionObserver($this->order))->observe();


                // to change status of SellerOrder
                $this->changeSellerOrderStatus("DELIVERED");
                break;
            case 'CANCELED':
                $this->order->update([
                    'status' => OrderStatusEnum::CANCELED,
                ]);
                OrderStatus::updateOrCreate([
                    'status'=>OrderStatusEnum::CANCELED,
                    'order_id'=>$this->order->id,
                ], [
                    'date'=>Carbon::now()->toDateString(),
                    'updated_by'=>auth()->user()->id,
                    'remarks'=>$this->request->remarks,
                ]);

                // to change status of SellerOrder
                $this->changeSellerOrderStatus("CANCELED");
                break;
            case 'REJECTED':
                $this->order->update([
                    'status' => OrderStatusEnum::REJECTED,
                ]);
                OrderStatus::updateOrCreate([
                    'status'=>OrderStatusEnum::REJECTED,
                    'order_id'=>$this->order->id,
                ], [
                    'date'=>Carbon::now()->toDateString(),
                    'updated_by'=>auth()->user()->id,
                    'remarks'=>$this->request->remarks,
                ]);
                 // to change status of SellerOrder
                 $this->changeSellerOrderStatus("REJECTED");
                 break;
                break;
        }
    }

    public function changeStatus()
    {

    }
    private function changeSellerOrderStatus($status)
    {
        if($status == "DELIVERED"){
            $this->order->sellerOrder()->update([
                'status'=> SellerOrderStatusEnum::DELIVERED
            ]);

            foreach($this->order->sellerOrder as $order){
                $order_status = SellerOrderStatus::where('status', SellerOrderStatusEnum::SEEN)->where('seller_order_id', $order->id)->first();
                if(!$order_status){
                    SellerOrderStatus::create([
                        'status'=>SellerOrderStatusEnum::SEEN,
                        'seller_order_id'=>$order->id,
                        'date'=>Carbon::now()->toDateString(),
                        'updated_by'=>auth()->user()->id,
                        'remarks'=>$this->request->remarks,
                    ]);
                }

                $order_status = SellerOrderStatus::where('status', SellerOrderStatusEnum::READY_TO_SHIP)->where('seller_order_id', $order->id)->first();
                if(!$order_status){
                    SellerOrderStatus::create([
                        'status'=>SellerOrderStatusEnum::READY_TO_SHIP,
                        'seller_order_id'=>$order->id,
                        'date'=>Carbon::now()->toDateString(),
                        'updated_by'=>auth()->user()->id,
                        'remarks'=>$this->request->remarks,
                    ]);
                }

                $order_status = SellerOrderStatus::where('status', SellerOrderStatusEnum::DISPATCHED)->where('seller_order_id', $order->id)->first();
                if(!$order_status){
                    SellerOrderStatus::create([
                        'status'=>SellerOrderStatusEnum::DISPATCHED,
                        'seller_order_id'=>$order->id,
                        'date'=>Carbon::now()->toDateString(),
                        'updated_by'=>auth()->user()->id,
                        'remarks'=>$this->request->remarks,
                    ]);
                }

                $order_status = SellerOrderStatus::where('status', SellerOrderStatusEnum::SHIPED)->where('seller_order_id', $order->id)->first();
                if(!$order_status){
                    SellerOrderStatus::create([
                        'status'=>SellerOrderStatusEnum::SHIPED,
                        'seller_order_id'=>$order->id,
                        'date'=>Carbon::now()->toDateString(),
                        'updated_by'=>auth()->user()->id,
                        'remarks'=>$this->request->remarks,
                    ]);
                }


                $order_status = SellerOrderStatus::where('status', SellerOrderStatusEnum::DELIVERED_TO_HUB)->where('seller_order_id', $order->id)->first();
                if(!$order_status){
                    SellerOrderStatus::create([
                        'status'=>SellerOrderStatusEnum::DELIVERED_TO_HUB,
                        'seller_order_id'=>$order->id,
                        'date'=>Carbon::now()->toDateString(),
                        'updated_by'=>auth()->user()->id,
                        'remarks'=>$this->request->remarks,
                    ]);
                }

                SellerOrderStatus::updateOrCreate([
                    'status'=>SellerOrderStatusEnum::DELIVERED,
                    'seller_order_id'=>$order->id,
                ], [
                    'date'=>Carbon::now()->toDateString(),
                    'updated_by'=>auth()->user()->id,
                    'remarks'=>$this->request->remarks,
                ]);                
            }
        }
        if($status == "CANCELED"){
            $this->order->sellerOrder()->update([
                'status'=> SellerOrderStatusEnum::CANCELED
            ]);
            foreach($this->order->sellerOrder as $order){
                SellerOrderStatus::updateOrCreate([
                    'status'=>SellerOrderStatusEnum::CANCELED,
                    'seller_order_id'=>$order->id,
                ], [
                    'date'=>Carbon::now()->toDateString(),
                    'updated_by'=>auth()->user()->id,
                    'remarks'=>$this->request->remarks,
                ]);     
            }
        }
        if($status == "REJECTED"){
            $this->order->sellerOrder()->update([
                'status'=> SellerOrderStatusEnum::REJECTED
            ]);
            foreach($this->order->sellerOrder as $order){
                SellerOrderStatus::updateOrCreate([
                    'status'=>SellerOrderStatusEnum::REJECTED,
                    'seller_order_id'=>$order->id,
                ], [
                    'date'=>Carbon::now()->toDateString(),
                    'updated_by'=>auth()->user()->id,
                    'remarks'=>$this->request->remarks,
                ]);     
            }
        }
    }
}
