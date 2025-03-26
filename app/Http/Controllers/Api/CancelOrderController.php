<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Order\OrderStatus;
use App\Models\ProductCancelReason;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ApiCancelOrderRequest;
use App\Actions\CancelOrder\CancelOrderAction;

class CancelOrderController extends Controller
{
    public function cancelOrder(ApiCancelOrderRequest $request)
    {
        $response=(new CancelOrderAction($request))->cancelAction();
        return response()->json($response,200);
    }
                                                                                                                                                                                                                        
    public function getReason(Request $request)
    {
        
        if(!$request->orderid)
        {
            $response=[
                'error'=>true,
                'msg'=>'Order Id Field Required !!'
            ];

            return response()->json($response,200);
        }

        

        $orderStatus=OrderStatus::where('order_id',$request->orderid)->whereIn('status',[7,6])->first();
        $data=$orderStatus->remarks ?? null;
        if(!$orderStatus)
        {
            $orderStatus=ProductCancelReason::where('order_id',$request->orderid)->first();
            $data=($orderStatus->reason ?? null).' <br> '.($orderStatus->additional_reason ?? null);
        }
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
