<?php

namespace App\Actions\Customer;

use App\Actions\Transaction\TransactionAction;
use App\Enum\Customer\CustomerStatusEnum;
use App\Enum\Order\OrderStatusEnum;
use App\Models\New_Customer;
use App\Models\Order\OrderStatus;
use App\Observers\Transaction\TransactionObserver;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderStatusAction
{
    protected $order;
    protected $status;
    protected $request;
    function __construct(Request $request, New_Customer $customer, $status)
    {
        $this->order = $customer;
        $this->status = $status;
        $this->request = $request;
    }
    public function updateStatus()
    {
        switch ($this->status) {
            case 'SEEN':
                $this->order->update([
                    'status' => CustomerStatusEnum::Active,
                ]);
                OrderStatus::updateOrCreate([
                    'status'=>CustomerStatusEnum::Active,
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
                break;
        }
    }
}
