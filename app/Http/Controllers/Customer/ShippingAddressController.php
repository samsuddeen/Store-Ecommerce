<?php
namespace App\Http\Controllers\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Events\LogEvent;
use App\Models\{
    City,
    UserShippingAddress,
    Location,
    Local,
    Setting,
    UserBillingAddress
};
use Illuminate\Support\Facades\Validator;
class ShippingAddressController extends Controller
{
    protected $shipping_address=null;
    protected $billing_address=null;
    public function __construct(UserShippingAddress $shipping_address,UserBillingAddress $billing_address)
    {
        $this->shipping_address=$shipping_address;
        $this->billing_address=$billing_address;
    }
    public function updateShippingAddress(Request $request)
    {
        $request->validate([
            'name'=>'required|string',
            'email'=>'required|email',
            'phone'=>'required',
            'province'=>'required',
            'district'=>'required',
            'area'=>'required',
            'additional_address'=>'required|exists:locations,id',
            'zip'=>'nullable|string'
        ]);
        $data=$request->all();
        $location=Location::where('id',$request->additional_address)->first();
        $data['additional_address']=$location->title;
        $data['area_id']=$location->id;
        DB::beginTransaction();
        try{
            $this->shipping_address=$this->shipping_address->where('id',$request->shipping_id)->where('user_id',auth()->guard('customer')->user()->id)->first();
            $this->shipping_address->fill($data);
            $this->shipping_address->save($data);
            session()->flash('success',"Shipping Address Updated Successfully !!");
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
    public function getShippingCharge(request $request)
    {
        dd($request->all());
        $customer=auth()->guard('customer')->user();
        if($customer->wholeseller)
        {
            $total_amount=100;

            $wholeSellerMinimumPrice=Setting::where('key','whole_seller_minimum_price')->first()->value;
            $wholeSellerShippingCharge=Setting::where('key','wholseller_shipping_charge')->first()->value;
            if($total_amount >=$wholeSellerMinimumPrice)
            {
                $default_shipping_charge=0;
            }
            else
            {
                $default_shipping_charge=$wholeSellerShippingCharge;
            }
        }
        dd($default_shipping_charge);
        // $shipping_address=UserShippingAddress::where('id',$request->shipping_id)->where('user_id',auth()->guard('customer')->user()->id)->first();
        // $default_shipping_charge = Setting::where('key','default_shipping_charge')->pluck('value')->first();
        // if(!$shipping_address)
        // {
        //     return response()->json([
        //         'error'=>true,
        //         'data'=>null,
        //         'msg'=>'Invalid Shipping Id !'
        //     ],200);
        // }
        // if(!$shipping_address && $shipping_address->getLocation)
        // {
        //     return response()->json([
        //         'error'=>false,
        //         'data'=>[
        //             'charge'=>$shipping_address->getLocation->deliveryRoute->charge == null ? $default_shipping_charge : $shipping_address->getLocation->deliveryRoute->charge,
        //             'shiiping_id'=>$shipping_address->id
        //         ],
        //         'msg'=>'Success !'
        //     ],200);
        // }
        // else
        // {
        //     return response()->json([
        //         'error'=>false,
        //         'data'=>[
        //             'charge'=>100,
        //             'shiiping_id'=>$shipping_address->id
        //         ],
        //         'msg'=>'Success !'
        //     ],200);
        // }
        // $chargeValue=
       
    }
    public function addShippingAddress(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|string',
            'zip' => 'required|string',
            'province' => 'required|string',
            'state' => 'required|string',
            'area' => 'required|string',
            'country'=>'required|string'
        ]);
        if ($validator->fails()) {
            $response = [
                'error' => 'Please Fill All The Field To Add Shipping Address.',
                'msg' => $validator->errors(),
                'validate'=>true
            ];
            return response()->json($response, 200);
        }

        $user=auth()->guard('customer')->user();
        $data=$request->all();
        $data['user_id']=$user->id;
        LogEvent::dispatch('ShippingAddress Created', 'ShippingAddress Created', route('add-shipping-address'));
        $this->shipping_address->fill($data);
        $status=$this->shipping_address->save();
        if($status)
        {
            session()->flash('success',"Shipping Address Successfully !!");
            $response=[
                'error'=>false,
                'msg'=>'Shipping Address Added Successfully !!'
            ];
            return response()->json($response,200);
        }
        else
        {
            $response=[
                'error'=>true,
                'msg'=>'Sorry !! There Was A Problem While Adding Shipping Address'
            ];
            return response()->json($response,200);
        }
    }
    public function addBillingAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|string',
            'zip' => 'required|string',
            'province' => 'required|string',
            'state' => 'required|string',
            'area' => 'required|string',
            'country'=>'required|string'
        ]);
        if ($validator->fails()) {
            $response = [
                'error' => "Please Fill All The Field To Add Billing Address.",
                'msg' => $validator->errors(),
                'validate'=>true
            ];
            return response()->json($response, 200);
        }
        
        $user=auth()->guard('customer')->user();
        $data=$request->all();
        $data['user_id']=$user->id;
        LogEvent::dispatch('BillingAddress Created', 'BillingAddress Created', route('add-billing-address'));
        $this->billing_address->fill($data);
        $status=$this->billing_address->save();
        if($status)
        {
            session()->flash('success',"Billing Address Successfully !!");
            $response=[
                'error'=>false,
                'msg'=>'Billing Address Added Successfully !!'
            ];
            return response()->json($response,200);
        }
        else
        {
            $response=[
                'error'=>true,
                'msg'=>'Sorry !! There Was A Problem While Adding Billing Address'
            ];
            return response()->json($response,200);
        }
    }
}