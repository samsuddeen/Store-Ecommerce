<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SellerOrderCancel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Actions\OrderCancel\OrderCancelAction;

class SellerCancelOrderReasonController extends Controller
{
    public function getReason(Request $request)
    {
        $sellerReason=SellerOrderCancel::where('id',$request->sellerOrderCancelId)->first();
        if($sellerReason)
        {
            $response=[
                'error'=>true,
                'data'=>$sellerReason->reason
            ];
        }
        else
        {
            $response=[
                'error'=>false,
                'data'=>null
            ];
        }
        

        return response()->json($response,200);
    }

    public function changeOrderStatus(Request $request)
    {
        DB::beginTransaction();
        try{
            $sellerOrderCancel=SellerOrderCancel::where('id',$request->seller_order_cancel_id)->first();
            (new OrderCancelAction($sellerOrderCancel))->cancelOrder($request);
            DB::commit();
            $request->session()->flash('success','Cancel Status Updated');
            return redirect()->back();
        }catch(\Throwable $th)
        {
            DB::rollBack();
            $request->session()->flash('error','Something Went Wrong !!');
            return redirect()->back();
        }
        
    }
}
