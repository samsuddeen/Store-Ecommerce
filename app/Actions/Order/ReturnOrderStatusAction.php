<?php

namespace App\Actions\Order;

use App\Actions\Transaction\TransactionAction;
use App\Enum\Order\OrderStatusEnum;
use App\Enum\Order\ReturnedOrderStatusEnum;
use App\Models\Admin\Returned\ReturnedStatus;
use App\Models\Customer\ReturnOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReturnOrderStatusAction
{
    protected $order;
    protected $status;
    protected $request;
    function __construct(Request $request, ReturnOrder $returnOrder, $status)
    {
        $this->order = $returnOrder;
        $this->status = $status;
        $this->request = $request;
    }

    // mail sent is required
    public function updateStatus()
    {
        switch ($this->status) {
            case 'APPROVED':
                $this->order->update([
                    'status' => ReturnedOrderStatusEnum::APPROVED,
                ]);
                ReturnedStatus::updateOrCreate([
                    'status'=>ReturnedOrderStatusEnum::APPROVED,
                    'return_id'=>$this->order->id,
                ], [
                    'date'=>Carbon::now()->toDateString(),
                    'updated_by'=>auth()->user()->id,
                    'remarks'=>$this->request->remarks,
                ]);
                break;
            case 'RETURNED':
                $this->order->update([
                    'status' => ReturnedOrderStatusEnum::RETURNED,
                ]);
                $return_order_status = ReturnedStatus::where('status', ReturnedOrderStatusEnum::APPROVED)->where('return_id', $this->order->id)->first();
                if(!$return_order_status){
                    ReturnedStatus::create([
                        'status'=>ReturnedOrderStatusEnum::APPROVED,
                        'return_id'=>$this->order->id,
                        'date'=>Carbon::now()->toDateString(),
                        'updated_by'=>auth()->user()->id,
                        'remarks'=>$this->request->remarks,
                    ]);
                }
                ReturnedStatus::updateOrCreate([
                    'status'=>ReturnedOrderStatusEnum::RETURNED,
                    'return_id'=>$this->order->id,
                ], [
                    'date'=>Carbon::now()->toDateString(),
                    'updated_by'=>auth()->user()->id,
                    'remarks'=>$this->request->remarks,
                ]);
                break;
            case 'REJECTED':
                $this->order->update([
                    'status' => ReturnedOrderStatusEnum::REJECTED,
                ]);
                ReturnedStatus::create([
                    'status'=>ReturnedOrderStatusEnum::REJECTED,
                    'return_id'=>$this->order->id,
                    'date'=>Carbon::now()->toDateString(),
                    'updated_by'=>auth()->user()->id,
                    'remarks'=>$this->request->remarks,
                ]);
                break;
            case 'RECEIVED':
                $this->order->update([
                    'status' => ReturnedOrderStatusEnum::RECEIVED,
                ]);
                $return_order_status = ReturnedStatus::where('status', ReturnedOrderStatusEnum::APPROVED)->where('return_id', $this->order->id)->first();
                if(!$return_order_status){
                    ReturnedStatus::create([
                        'status'=>ReturnedOrderStatusEnum::APPROVED,
                        'return_id'=>$this->order->id,
                        'date'=>Carbon::now()->toDateString(),
                        'updated_by'=>auth()->user()->id,
                        'remarks'=>$this->request->remarks,
                    ]);
                }
                $return_order_status = ReturnedStatus::where('status', ReturnedOrderStatusEnum::RETURNED)->where('return_id', $this->order->id)->first();
                if(!$return_order_status){
                    ReturnedStatus::create([
                        'status'=>ReturnedOrderStatusEnum::RETURNED,
                        'return_id'=>$this->order->id,
                        'date'=>Carbon::now()->toDateString(),
                        'updated_by'=>auth()->user()->id,
                        'remarks'=>$this->request->remarks,
                    ]);
                }
                ReturnedStatus::updateOrCreate([
                    'status'=>ReturnedOrderStatusEnum::RECEIVED,
                    'return_id'=>$this->order->id,
                ], [
                    'date'=>Carbon::now()->toDateString(),
                    'updated_by'=>auth()->user()->id,
                    'remarks'=>$this->request->remarks,
                ]);
                break;
        }
    }
}
