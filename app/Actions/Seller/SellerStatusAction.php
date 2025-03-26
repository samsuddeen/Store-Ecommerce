<?php

namespace App\Actions\Seller;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Order\OrderStatus;
use App\Enum\Seller\SellerStatusEnum;
use App\Models\Order\Seller\SellerOrder;
use App\Actions\Transaction\TransactionAction;
use App\Observers\Transaction\TransactionObserver;

class SellerStatusAction
{
    protected $order;
    protected $status;
    protected $request;
    function __construct(Request $request, SellerOrder $order, $status)
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
                    'status' => SellerStatusEnum::SEEN,
                ]);
                OrderStatus::updateOrCreate([
                    'status'=>SellerStatusEnum::SEEN,
                    'order_id'=>$this->order->id,
                ], [
                    'date'=>Carbon::now()->toDateString(),
                    'updated_by'=>auth()->user()->id,
                    'remarks'=>$this->request->remarks,
                ]);
                break;
            case 'READY_TO_SHIP':
                $this->order->update([
                    'status' => SellerStatusEnum::READY_TO_SHIP,
                ]);
                $order_status = OrderStatus::where('status', SellerStatusEnum::SEEN)->where('order_id', $this->order->id)->first();
                if(!$order_status){
                    OrderStatus::create([
                        'status'=>SellerStatusEnum::SEEN,
                        'order_id'=>$this->order->id,
                        'date'=>Carbon::now()->toDateString(),
                        'updated_by'=>auth()->user()->id,
                        'remarks'=>$this->request->remarks,
                    ]);
                }
                OrderStatus::updateOrCreate([
                    'status'=>SellerStatusEnum::READY_TO_SHIP,
                    'order_id'=>$this->order->id,
                ], [
                    'date'=>Carbon::now()->toDateString(),
                    'updated_by'=>auth()->user()->id,
                    'remarks'=>$this->request->remarks,
                ]);
                break;
            case 'DISPATCHED':
                $this->order->update([
                    'status' => SellerStatusEnum::DISPATCHED,
                ]);

                $order_status = OrderStatus::where('status', SellerStatusEnum::SEEN)->where('order_id', $this->order->id)->first();
                if(!$order_status){
                    OrderStatus::create([
                        'status'=>SellerStatusEnum::SEEN,
                        'order_id'=>$this->order->id,
                        'date'=>Carbon::now()->toDateString(),
                        'updated_by'=>auth()->user()->id,
                        'remarks'=>$this->request->remarks,
                    ]);
                }

                $order_status = OrderStatus::where('status', SellerStatusEnum::READY_TO_SHIP)->where('order_id', $this->order->id)->first();
                if(!$order_status){
                    OrderStatus::create([
                        'status'=>SellerStatusEnum::READY_TO_SHIP,
                        'order_id'=>$this->order->id,
                        'date'=>Carbon::now()->toDateString(),
                        'updated_by'=>auth()->user()->id,
                        'remarks'=>$this->request->remarks,
                    ]);
                }

                OrderStatus::updateOrCreate([
                    'status'=>SellerStatusEnum::DISPATCHED,
                    'order_id'=>$this->order->id,
                ], [
                    'date'=>Carbon::now()->toDateString(),
                    'updated_by'=>auth()->user()->id,
                    'remarks'=>$this->request->remarks,
                ]);
                break;
            case 'SHPIED':
                $this->order->update([
                    'status' => SellerStatusEnum::SHIPED,
                ]);

                $order_status = OrderStatus::where('status', SellerStatusEnum::SEEN)->where('order_id', $this->order->id)->first();
                if(!$order_status){
                    OrderStatus::create([
                        'status'=>SellerStatusEnum::SEEN,
                        'order_id'=>$this->order->id,
                        'date'=>Carbon::now()->toDateString(),
                        'updated_by'=>auth()->user()->id,
                        'remarks'=>$this->request->remarks,
                    ]);
                }

                $order_status = OrderStatus::where('status', SellerStatusEnum::READY_TO_SHIP)->where('order_id', $this->order->id)->first();
                if(!$order_status){
                    OrderStatus::create([
                        'status'=>SellerStatusEnum::READY_TO_SHIP,
                        'order_id'=>$this->order->id,
                        'date'=>Carbon::now()->toDateString(),
                        'updated_by'=>auth()->user()->id,
                        'remarks'=>$this->request->remarks,
                    ]);
                }

                $order_status = OrderStatus::where('status',SellerStatusEnum::DISPATCHED)->where('order_id', $this->order->id)->first();
                if(!$order_status){
                    OrderStatus::create([
                        'status'=>SellerStatusEnum::DISPATCHED,
                        'order_id'=>$this->order->id,
                        'date'=>Carbon::now()->toDateString(),
                        'updated_by'=>auth()->user()->id,
                        'remarks'=>$this->request->remarks,
                    ]);
                }



                OrderStatus::updateOrCreate([
                    'status'=>SellerStatusEnum::SHIPED,
                    'order_id'=>$this->order->id,
                ], [
                    'date'=>Carbon::now()->toDateString(),
                    'updated_by'=>auth()->user()->id,
                    'remarks'=>$this->request->remarks,
                ]);
                break;
            case 'DELIVERED':
                $this->order->update([
                    'status' => SellerStatusEnum::DELIVERED,
                ]);


                $order_status = OrderStatus::where('status', SellerStatusEnum::SEEN)->where('order_id', $this->order->id)->first();
                if(!$order_status){
                    OrderStatus::create([
                        'status'=>SellerStatusEnum::SEEN,
                        'order_id'=>$this->order->id,
                        'date'=>Carbon::now()->toDateString(),
                        'updated_by'=>auth()->user()->id,
                        'remarks'=>$this->request->remarks,
                    ]);
                }

                $order_status = OrderStatus::where('status', SellerStatusEnum::READY_TO_SHIP)->where('order_id', $this->order->id)->first();
                if(!$order_status){
                    OrderStatus::create([
                        'status'=>SellerStatusEnum::READY_TO_SHIP,
                        'order_id'=>$this->order->id,
                        'date'=>Carbon::now()->toDateString(),
                        'updated_by'=>auth()->user()->id,
                        'remarks'=>$this->request->remarks,
                    ]);
                }

                $order_status = OrderStatus::where('status', SellerStatusEnum::DISPATCHED)->where('order_id', $this->order->id)->first();
                if(!$order_status){
                    OrderStatus::create([
                        'status'=>SellerStatusEnum::DISPATCHED,
                        'order_id'=>$this->order->id,
                        'date'=>Carbon::now()->toDateString(),
                        'updated_by'=>auth()->user()->id,
                        'remarks'=>$this->request->remarks,
                    ]);
                }

                $order_status = OrderStatus::where('status', SellerStatusEnum::SHIPED)->where('order_id', $this->order->id)->first();
                if(!$order_status){
                    OrderStatus::create([
                        'status'=>SellerStatusEnum::SHIPED,
                        'order_id'=>$this->order->id,
                        'date'=>Carbon::now()->toDateString(),
                        'updated_by'=>auth()->user()->id,
                        'remarks'=>$this->request->remarks,
                    ]);
                }

                OrderStatus::updateOrCreate([
                    'status'=>SellerStatusEnum::DELIVERED,
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
                break;
            case 'CANCELED':
                $this->order->update([
                    'status' => SellerStatusEnum::CANCELED,
                ]);
                OrderStatus::updateOrCreate([
                    'status'=>SellerStatusEnum::CANCELED,
                    'order_id'=>$this->order->id,
                ], [
                    'date'=>Carbon::now()->toDateString(),
                    'updated_by'=>auth()->user()->id,
                    'remarks'=>$this->request->remarks,
                ]);
                break;
            case 'REJECTED':
                $this->order->update([
                    'status' => SellerStatusEnum::REJECTED,
                ]);
                OrderStatus::updateOrCreate([
                    'status'=>SellerStatusEnum::REJECTED,
                    'order_id'=>$this->order->id,
                ], [
                    'date'=>Carbon::now()->toDateString(),
                    'updated_by'=>auth()->user()->id,
                    'remarks'=>$this->request->remarks,
                ]);
                break;
        }
    }
}
