<?php

namespace App\Http\Controllers\Customer;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Refund\Refund;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Customer\RefundOrder;
use App\Models\Customer\ReturnOrder;
use App\Models\DirectRefund;
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
    public function refundRequest(Request $request,$id)
    {
        if($request->return_type=='bank')
        {
            $request->validate([
                'return_type'=>'required|in:bank',
                'name'=>'required|string',
                'payment_method'=>'required|string',
                'branch'=>'required|string',
                'acc_no'=>'required|string',
                'contact_no'=>'required|string',
                'account_type'=>'required|in:current,saving'
            ]);

        }
        elseif($request->return=='esewa' || $request->return=='khalti')
        {
            $request->validate([
                'name'=>'required|in:esewa,khalti',
                'contact_no'=>'required|string',
                'wallet_id'=>'required|string'
            ]);

        }
        
        $user=auth()->guard('customer')->user();
        if(!$user)
        {
            $request->session()->flash('error','Plz Login First !!');
            return redirect()->route('Clogin');
        }
        $this->return_order=ReturnOrder::where('id',$id)->where('user_id',$user->id)->first();
        
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
            $request->session()->flash('success','Refund Apply Successfully !! We Will Get You Soon');
            return redirect()->back();
        }catch(\Throwable $th){
            DB::rollBack();
            $request->session()->flash('error',$th->getMessage());
            return redirect()->back();
        }

        // if(!$return_order)
        // {
        //     $request->session()->flash('error','Sorry Something Went Wrong !!');
        //     return redirect()->back();
        // }
        // dd($return_order);
        // dd($request->all());
    }

    public function refundDirectRequest(Request $request,$id)
    {
        
        if($request->return_type=='bank')
        {
            $request->validate([
                'return_type'=>'required|in:bank',
                'name'=>'required|string',
                'payment_method'=>'required|string',
                'branch'=>'required|string',
                'acc_no'=>'required|string',
                'contact_no'=>'required|string',
                'account_type'=>'required|in:current,saving'
            ]);

        }
        elseif($request->return=='esewa' || $request->return=='khalti')
        {
            $request->validate([
                'name'=>'required|in:esewa,khalti',
                'contact_no'=>'required|string',
                'wallet_id'=>'required|string'
            ]);

        }
        
        $user=auth()->guard('customer')->user();
        if(!$user)
        {
            $request->session()->flash('error','Plz Login First !!');
            return redirect()->route('Clogin');
        }

        
        $this->order=Order::where('id',$id)->where('user_id',$user->id)->first();
       
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
            $request->session()->flash('success','Refund Apply Successfully !! We Will Get You Soon');
            return redirect()->back();
        }catch(\Throwable $th){
            DB::rollBack();
            $request->session()->flash('error',$th->getMessage());
            return redirect()->back();
        }
    }
}
