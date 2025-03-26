<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\DirectRefund;
use Illuminate\Http\Request;
use App\Models\Refund\Refund;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Customer\RefundOrder;
use App\Models\Customer\ReturnOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RefundController extends Controller
{

    protected $return_order=null;
    protected $refund_order=null;
    protected $refund=null;
    protected $order=null;
    protected $directRefund=null;

    public function __construct(ReturnOrder $return_order,RefundOrder $refund_order,Refund $refund,Order $order,DirectRefund $directRefund)
    {
        $this->return_order=$return_order;
        $this->refund_order=$refund_order;
        $this->refund=$refund;
        $this->order=$order;
        $this->directRefund=$directRefund;
    }
    
    public function refundApply(Request $request)
    {
        if($request->return_type=='bank')
        {
            $validator = Validator::make($request->all(), [
                'return_type'=>'required',
                'name'=>'required|string',
                'payment_method'=>'required|string',
                'branch'=>'required|string',
                'acc_no'=>'required|string',
                'contact_no'=>'required|string',
                'account_type'=>'required|in:current,saving',
                'id'=>'required'
            ]);
    
            if ($validator->fails()) {
                $response = [
                    'error' => true,
                    'data' => null,
                    'message' => $validator->errors()
                ];
                return response()->json($response, 500);
            }

        }
        elseif($request->return_type=='esewa' || $request->return_type=='khalti')
        {
            $validator = Validator::make($request->all(), [
                'name'=>'required',
                'contact_no'=>'required|string',
                'wallet_id'=>'required|string',
                'id'=>'required'
            ]);
    
            if ($validator->fails()) {
                $response = [
                    'error' => true,
                    'data' => null,
                    'message' => $validator->errors()
                ];
                return response()->json($response, 500);
            }

        }
        else
        {
            $validator = Validator::make($request->all(), [
                'id'=>'required'
            ]);
    
            if ($validator->fails()) {
                $response = [
                    'error' => true,
                    'data' => null,
                    'message' => $validator->errors()
                ];
                return response()->json($response, 500);
            }
        }
        
        $user=Auth::user();
        if(!$user)
        {
            
            $response=[
                'error'=>true,
                'msg'=>'UnAuthorized Access !!'
            ];
            return response()->json($response,200);
        }
        $this->return_order=ReturnOrder::where('id',$request->id)->where('user_id',$user->id)->first();
        // dd($this->return_order);
        
        $data['return_id']=$this->return_order->id;
        $data['user_id']=$user->id;
        $data['is_new']=1;
        $data['status']=1;
        DB::beginTransaction();
        try{
            $this->refund->fill($data);
            $status=$this->refund->save();
            if($status)
            {
                $value=[];
               foreach($request->except(['_token','_method']) as $key=>$data)
               {
                   $value[][$key]=$data;
               }
               
                $refund_data['refund_id']=$this->refund->id;
                $refund_data['refund_detail']=json_encode($value);
                $this->refund_order->fill($refund_data);
                $this->refund_order->save();
            }
            DB::commit();
            $response=[
                'error'=>false,
                'msg'=>'Refund Applied Sucessfully !!'
            ];
            return response()->json($response,200);
        }catch(\Throwable $th){
            DB::rollBack();
            $response=[
                'error'=>true,
                'msg'=>'Something Went Wrong !!'
            ];
            return response()->json($response,200);
        }
    }

    public function apiRefundApply(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'return_type'=>'required|in:bank,esewa,khalti',
            'order_id'=>'required|exists:orders,id'
        ]);

        if ($validator->fails()) {
            $response = [
                'error' => true,
                'data' => null,
                'message' => $validator->errors()
            ];
            return response()->json($response, 500);
        }
        if($request->return_type=='bank')
        {
            $validator = Validator::make($request->all(), [
                'return_type'=>'required|in:bank',
                'name'=>'required|string',
                'payment_method'=>'required|string',
                'branch'=>'required|string',
                'acc_no'=>'required|string',
                'contact_no'=>'required|string',
                'account_type'=>'required|in:current,saving'
            ]);
    
            if ($validator->fails()) {
                $response = [
                    'error' => true,
                    'data' => null,
                    'message' => $validator->errors()
                ];
                return response()->json($response, 500);
            }

        }
        elseif($request->return_type=='esewa' || $request->return_type=='khalti')
        {
            $validator = Validator::make($request->all(), [
                'name'=>'required|string',
                'contact_no'=>'required|string',
                'wallet_id'=>'required|string'
            ]);
    
            if ($validator->fails()) {
                $response = [
                    'error' => true,
                    'data' => null,
                    'message' => $validator->errors()
                ];
                return response()->json($response, 500);
            }

        }
        
        $user = \Auth::user();
        if (!$user) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'UnAuthorized User !!'
            ];

            return response()->json($response, 200);
        }

        $this->order=Order::where('id',$request->order_id)->where('user_id',$user->id)->first();
        $data['order_id']=$this->order->id;
        $data['user_id']=$user->id;
        $data['is_new']=1;
        $data['status']=1;
        $data['paid_from']=$this->order->payment_with;
        DB::beginTransaction();
        try{
            $value=[];
            foreach($request->except(['_token','_method']) as $key=>$datavalue)
            {
                $value[][$key]=$datavalue;
            }
            $data['refund_details']=json_encode($value);
            $this->directRefund->fill($data);
            $this->directRefund->save();
            DB::commit();
            $response = [
                'error' => false,
                'data' => null,
                'msg' => 'Refund Applied Sucessfully !!'
            ];

            return response()->json($response, 200);
        }catch(\Throwable $th){
            DB::rollBack();
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Something Went Wrong !!'
            ];

            return response()->json($response, 200);
        }
    }
}
