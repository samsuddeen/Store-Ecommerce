<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\{
    UserBillingAddress,
    User
};

class UserBillingAddressController extends Controller
{
    protected $userbillingaddress=null;

    public function __construct(UserBillingAddress $userbillingaddress)
    {
        $this->userbillingaddress=$userbillingaddress;
    }

    public function addBillingAddress(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email',
            'province' => 'required',
            'district' => 'required',
            'phone'=>'required',
            'area' => 'required',
            'additional_address' => 'nullable',
            // 'zip' => 'nullable',
        ]);

        if ($validator->fails()) {
            $response = [
                'error' => true,
                'data' => null,
                'message' => $validator->errors()
            ];

            return response()->json($response, 500);
        }



        $user = \Auth::user();

        if (!$user) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Sorry !! User Not Found'
            ];

            return response()->json($response, 500);
        }

        $data = $request->all();
        $data['user_id'] = $user->id;

        try {
            $status=$this->userbillingaddress->create($data);

            $response = [
                'error' => true,
                'data' => $status,
                'msg' => 'Billing Address Added Successfully !!'
            ];

            return response()->json($response, 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json($th->getMessage(), 500);
        }
    }

    public function getUserBillingAddress(Request $request)
    {


        $user=\Auth::user();
        if(!$user)
        {
            $response=[
                'error'=>true,
                'data'=>null,
                'msg'=>'Sorry !! User Not Found'
            ];
            return response()->json($response,200);
        }

        $billingAddress=UserBillingAddress::where('user_id',$user->id)->get();

        if($billingAddress==null)
        {
            $billingAddress=User::where('id',$user->id)->get();
        }

        $response=[
            'error'=>false,
            'data'=>$billingAddress,
            'msg'=>'User Billing Address'
        ];

        return response()->json($response,200);
    }

    public function updateBillingAddress(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email',
            'province' => 'required',
            'phone' => 'required',
            'district' => 'required',
            'area' => 'required',
            'additional_address' => 'nullable',
            'zip' => 'nullable',
        ]);
        if ($validator->fails()) {
            $response = [
                'error' => true,
                'data' => null,
                'message' => $validator->errors()
            ];
            return response()->json($response, 500);
        }
        $user = \Auth::user();
        if (!$user) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Sorry !! User Not Found'
            ];
            return response()->json($response, 500);
        }
        $this->userbillingaddress=UserBillingAddress::where('user_id',$user->id)->where('id',$id)->first();
        $data = $request->all();
        $data['user_id'] = $user->id;
        try {
            $this->userbillingaddress->fill($data);
            $status=$this->userbillingaddress->save();
            $response = [
                'error' => false,
                'data' => $status,
                'msg' => 'Billing Address Updated Successfully !!'
            ];
            return response()->json($response, 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json($th->getMessage(), 500);
        }
    }

    public function deleteBillingAddress(Request $request,$id)
    {
        $user=\Auth::user();

        if($id ===null)
        {
            $response=[
                'error'=>true,
                'data'=>null,
                'msg'=>'Id Field Required'
            ]; 
            return response()->json($response,500);
        }

        $this->userbillingaddress=UserBillingAddress::where('user_id',$user->id)->where('id',$id)->first();

        if($this->userbillingaddress)
        {
            $status=$this->userbillingaddress->delete();
            if($status)
            {
                $response=[
                    'error'=>true,
                    'data'=>null,
                    'msg'=>'Billing Address Deleted Successfully !!'
                ];
                return response()->json($response,200);
            }
            else
            {
                $response=[
                    'error'=>true,
                    'data'=>null,
                    'msg'=>'Sorry !! There Was A Problem While Deleting Billing Address'
                ];
                return response()->json($response,500);
            }
        }
        else
        {
            $response=[
                'error'=>true,
                'data'=>null,
                'msg'=>'Sorry !! Invalid Data Id'
            ];
            return response()->json($response,500);
        }
    }
}
