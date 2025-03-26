<?php

namespace App\Actions\Seller\Order;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Order\Seller\SellerOrder;
use App\Enum\Seller\SellerOrderStatusEnum;
use App\Models\Order\Seller\SellerOrderStatus;
use App\Models\Payout\Payout;

class SellerOrderStatusAction
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
                    'status' => SellerOrderStatusEnum::SEEN,
                ]);
                SellerOrderStatus::updateOrCreate([
                    'status' => SellerOrderStatusEnum::SEEN,
                    'seller_order_id' => $this->order->id,
                ], [
                    'date' => Carbon::now()->toDateString(),
                    'updated_by' => auth()->user()->id,
                    'remarks' => $this->request->remarks,
                ]);
                break;
            case 'READY_TO_SHIP':
                $this->order->update([
                    'status' => SellerOrderStatusEnum::READY_TO_SHIP,
                ]);
                $order_status = SellerOrderStatus::where('status', SellerOrderStatusEnum::SEEN)->where('seller_order_id', $this->order->id)->first();
                if (!$order_status) {
                    SellerOrderStatus::create([
                        'status' => SellerOrderStatusEnum::SEEN,
                        'seller_order_id' => $this->order->id,
                        'date' => Carbon::now()->toDateString(),
                        'updated_by' => auth()->user()->id,
                        'remarks' => $this->request->remarks,
                    ]);
                }
                SellerOrderStatus::updateOrCreate([
                    'status' => SellerOrderStatusEnum::READY_TO_SHIP,
                    'seller_order_id' => $this->order->id,
                ], [
                    'date' => Carbon::now()->toDateString(),
                    'updated_by' => auth()->user()->id,
                    'remarks' => $this->request->remarks,
                ]);
                break;
            case 'DISPATCHED':
                $this->order->update([
                    'status' => SellerOrderStatusEnum::DISPATCHED,
                ]);

                $order_status = SellerOrderStatus::where('status', SellerOrderStatusEnum::SEEN)->where('seller_order_id', $this->order->id)->first();
                if (!$order_status) {
                    SellerOrderStatus::create([
                        'status' => SellerOrderStatusEnum::SEEN,
                        'seller_order_id' => $this->order->id,
                        'date' => Carbon::now()->toDateString(),
                        'updated_by' => auth()->user()->id,
                        'remarks' => $this->request->remarks,
                    ]);
                }

                $order_status = SellerOrderStatus::where('status', SellerOrderStatusEnum::READY_TO_SHIP)->where('seller_order_id', $this->order->id)->first();
                if (!$order_status) {
                    SellerOrderStatus::create([
                        'status' => SellerOrderStatusEnum::READY_TO_SHIP,
                        'seller_order_id' => $this->order->id,
                        'date' => Carbon::now()->toDateString(),
                        'updated_by' => auth()->user()->id,
                        'remarks' => $this->request->remarks,
                    ]);
                }

                SellerOrderStatus::updateOrCreate([
                    'status' => SellerOrderStatusEnum::DISPATCHED,
                    'seller_order_id' => $this->order->id,
                ], [
                    'date' => Carbon::now()->toDateString(),
                    'updated_by' => auth()->user()->id,
                    'remarks' => $this->request->remarks,
                ]);
                break;
            case 'SHIPED':
                $this->order->update([
                    'status' => SellerOrderStatusEnum::SHIPED,
                ]);

                $order_status = SellerOrderStatus::where('status', SellerOrderStatusEnum::SEEN)->where('seller_order_id', $this->order->id)->first();
                if (!$order_status) {
                    SellerOrderStatus::create([
                        'status' => SellerOrderStatusEnum::SEEN,
                        'seller_order_id' => $this->order->id,
                        'date' => Carbon::now()->toDateString(),
                        'updated_by' => auth()->user()->id,
                        'remarks' => $this->request->remarks,
                    ]);
                }

                $order_status = SellerOrderStatus::where('status', SellerOrderStatusEnum::READY_TO_SHIP)->where('seller_order_id', $this->order->id)->first();
                if (!$order_status) {
                    SellerOrderStatus::create([
                        'status' => SellerOrderStatusEnum::READY_TO_SHIP,
                        'seller_order_id' => $this->order->id,
                        'date' => Carbon::now()->toDateString(),
                        'updated_by' => auth()->user()->id,
                        'remarks' => $this->request->remarks,
                    ]);
                }

                $order_status = SellerOrderStatus::where('status', SellerOrderStatusEnum::DISPATCHED)->where('seller_order_id', $this->order->id)->first();
                if (!$order_status) {
                    SellerOrderStatus::create([
                        'status' => SellerOrderStatusEnum::DISPATCHED,
                        'seller_order_id' => $this->order->id,
                        'date' => Carbon::now()->toDateString(),
                        'updated_by' => auth()->user()->id,
                        'remarks' => $this->request->remarks,
                    ]);
                }




                SellerOrderStatus::updateOrCreate([
                    'status' => SellerOrderStatusEnum::SHIPED,
                    'seller_order_id' => $this->order->id,
                ], [
                    'date' => Carbon::now()->toDateString(),
                    'updated_by' => auth()->user()->id,
                    'remarks' => $this->request->remarks,
                ]);
                break;
            case 'DELIVERED_TO_HUB':
                $this->order->update([
                    'status' => SellerOrderStatusEnum::DELIVERED_TO_HUB,
                ]);

                $order_status = SellerOrderStatus::where('status', SellerOrderStatusEnum::SEEN)->where('seller_order_id', $this->order->id)->first();
                if (!$order_status) {
                    SellerOrderStatus::create([
                        'status' => SellerOrderStatusEnum::SEEN,
                        'seller_order_id' => $this->order->id,
                        'date' => Carbon::now()->toDateString(),
                        'updated_by' => auth()->user()->id,
                        'remarks' => $this->request->remarks,
                    ]);
                }

                $order_status = SellerOrderStatus::where('status', SellerOrderStatusEnum::READY_TO_SHIP)->where('seller_order_id', $this->order->id)->first();
                if (!$order_status) {
                    SellerOrderStatus::create([
                        'status' => SellerOrderStatusEnum::READY_TO_SHIP,
                        'seller_order_id' => $this->order->id,
                        'date' => Carbon::now()->toDateString(),
                        'updated_by' => auth()->user()->id,
                        'remarks' => $this->request->remarks,
                    ]);
                }



                $order_status = SellerOrderStatus::where('status', SellerOrderStatusEnum::DISPATCHED)->where('seller_order_id', $this->order->id)->first();
                if (!$order_status) {
                    SellerOrderStatus::create([
                        'status' => SellerOrderStatusEnum::DISPATCHED,
                        'seller_order_id' => $this->order->id,
                        'date' => Carbon::now()->toDateString(),
                        'updated_by' => auth()->user()->id,
                        'remarks' => $this->request->remarks,
                    ]);
                }

                $order_status = SellerOrderStatus::where('status', SellerOrderStatusEnum::SHIPED)->where('seller_order_id', $this->order->id)->first();
                if (!$order_status) {
                    SellerOrderStatus::create([
                        'status' => SellerOrderStatusEnum::SHIPED,
                        'seller_order_id' => $this->order->id,
                        'date' => Carbon::now()->toDateString(),
                        'updated_by' => auth()->user()->id,
                        'remarks' => $this->request->remarks,
                    ]);
                }

                SellerOrderStatus::updateOrCreate([
                    'status' => SellerOrderStatusEnum::DELIVERED_TO_HUB,
                    'seller_order_id' => $this->order->id,
                ], [
                    'date' => Carbon::now()->toDateString(),
                    'updated_by' => auth()->user()->id,
                    'remarks' => $this->request->remarks,
                ]);
                break;
                $this->order->update([
                    'status' => SellerOrderStatusEnum::REJECTED,
                ]);
                SellerOrderStatus::updateOrCreate([
                    'status' => SellerOrderStatusEnum::REJECTED,
                    'seller_order_id' => $this->order->id,
                ], [
                    'date' => Carbon::now()->toDateString(),
                    'updated_by' => auth()->user()->id,
                    'remarks' => $this->request->remarks,
                ]);
                break;
            
        }
    }
    public function seen(SellerOrder $sellerOrder)
    {
        $sellerOrderStatus = SellerOrderStatus::where('seller_seller_order_id', $sellerOrder->id)->where('status', SellerSellerOrderStatusEnum::SEEN)->first();
        if (!$sellerOrderStatus) {
            SellerOrderStatus::create([
                'seller_seller_order_id' => $sellerOrder->id,
                'date' => Carbon::now(),
                'remarks' => $this->request->remarks,
                'updated_by' => auth()->user()->id,
            ]);
        }
    }
}
