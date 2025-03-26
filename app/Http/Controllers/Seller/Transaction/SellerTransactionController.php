<?php

namespace App\Http\Controllers\Seller\Transaction;

use App\Events\LogEvent;
use Illuminate\Http\Request;
use App\Models\Payout\Payout;
use App\Enum\Payment\PayoutEnum;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Order\Seller\SellerOrder;
use Illuminate\Support\Facades\Validator;

class SellerTransactionController extends Controller
{
    //
    public function index(Request $request)
    {
        LogEvent::dispatch('Transaction View', 'Transaction View', route('seller-transaction.index'));
        return view('seller.transaction.index');
    }
    public function statusAction(Request $request)
    {        
        $rules = [
            'order_id' => 'required|exists:seller_orders,id',
            'type' => 'required',
        ];
        if ($request->type == "cancel" || $request->type == "reject") {
            $again_rules = [
                'remarks' => 'required',
            ];
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $validator->errors();
            return back()->withInput()->withErrors($validator->errors());
        }
        DB::beginTransaction();
        try {
            $order = SellerOrder::findOrFail($request->order_id);
            $sellerOrder = new Payout();
            $sellerOrder['seller_order_id'] = $order->id;
            $sellerOrder['seller_id'] = auth()->user()->id;
            $sellerOrder->save();
            $order->update([
                'payment_status'=>(string)PayoutEnum::REQUESTED,
            ]);
            session()->flash('success', 'Successfully order has been Requested');
            DB::commit();
            return back();
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', $th->getMessage());
            return redirect()->route('seller-order-index');
        }
    }
}
