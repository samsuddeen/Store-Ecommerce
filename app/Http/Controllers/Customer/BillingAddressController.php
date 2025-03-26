<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    UserBillingAddress
};
use Illuminate\Support\Facades\DB;

class BillingAddressController extends Controller
{
    protected $billing_address=null;

    public function __construct(UserBillingAddress $billing_address)
    {
        $this->billing_address=$billing_address;
    }
    public function updateBillingAddress(request $request)
    {        
        $request->validate([
            'name'=>'required|string',
            'email'=>'required|email',
            'phone'=>'required',
            'province'=>'required',
            'state'=>'required',
            'area'=>'required',
            'additional_address'=>'nullable',
            'zip'=>'nullable|string'
        ]);

        $data=$request->all();
        DB::beginTransaction();
        try{
            $this->billing_address=$this->billing_address->where('id',$request->billing_id)->where('user_id',auth()->guard('customer')->user()->id)->first();
            $this->billing_address->fill($data);
            $this->billing_address->save($data);
            session()->flash('success',"Billing Address Updated Successfully !!");
            DB::commit();        
            $response=[
                'error'=>false
            ];            
            return response()->json($response,200);
        } catch (\Throwable $th) {
            session()->flash('error',$th->getMessage());
            DB::rollback();
            $response=[
                'error'=>true
            ];
            return response()->json($response,200);
            
        }
    }
}
