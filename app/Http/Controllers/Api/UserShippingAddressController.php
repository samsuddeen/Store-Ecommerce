<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\{
    City,
    District,
    Province,
    UserShippingAddress,
    User,
    Local,
    Setting
};
use Illuminate\Support\Facades\DB;

class UserShippingAddressController extends Controller
{
    protected $usershippingaddress = null;

    public function __construct(UserShippingAddress $usershippingaddress)
    {
        $this->usershippingaddress = $usershippingaddress;
    }

    public function shippingaddress()
    {
        
        try {
            $country=Province::with('districts','districts.localarea','districts.localarea.getRouteCharge')->get(); 
            if ($country) {
                $response = [
                    'error' => false,
                    'data' => $country,
                    'msg' => 'Address '
                ];
            } else {
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => 'Sorry ! No Data Found '
                ];
            }

            return response()->json($response, 200);
        } catch (\Exception $ex) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => $ex->getMessage()
            ];
            return response()->json($response, 200);
        }
    }

    public function addShippingAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email',
            'province' => 'required',
            'phone' => 'required',
            'district' => 'required',
            'area' => 'required',
            // 'additional_address' => 'required',
            'zip' => 'nullable',
            // 'area_id' => 'required|int|exists:locations,id'
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
        DB::beginTransaction();
        try {
            $status = $this->usershippingaddress->create($data);
            $response = [
                'error' => false,
                'data' => $status,
                'msg' => 'Shipping Address Added Successfully !!'
            ];
            DB::commit();
            return response()->json($response, 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json($th->getMessage(), 500);
        }
    }

    public function getUserShippingAddress(Request $request)
    {
        $user = \Auth::user();
        if (!$user) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Sorry !! User Not Found'
            ];
            return response()->json($response, 500);
        }

        try {
            $shippingAddress = UserShippingAddress::where('user_id', $user->id)->get();
            $data = [];
            $mimimum_order_cost = Setting::where('key', 'minimum_order_price')->pluck('value')->first();
            $cart=$user->getCart;
            $shipping_chargeNew=0;
            if ($user->wholeseller) {
                if ($cart->total_price < $mimimum_order_cost) {
                    $response = [
                        'error' => true,
                        'data' => null,
                        'msg' => 'The minimum order amount should be Rs. ' . $mimimum_order_cost . ' !!'
                    ];
                    return response()->json($response, 200);
                }
                $wholeSellerShippingCharge = Setting::where('key', 'wholseller_shipping_charge')->first()->value;
                $shipping_chargeNew=$wholeSellerShippingCharge ?? 0;
                
            } else {
                foreach($cart->cartAssets as $cartAsset){
                    $shipping_chargeNew=$shipping_chargeNew+($cartAsset->product->shipping_charge * $cartAsset->qty);
                }
                
            }
            foreach ($shippingAddress as $key => $address) {
                // $charge = $this->getAreaCharge($address);
                $data[$key]['id'] = $address->id;
                $data[$key]['user_id'] = $address->user_id;
                $data[$key]['name'] = $address->name;
                $data[$key]['email'] = $address->email;
                $data[$key]['phone'] = $address->phone;
                $data[$key]['province'] = $address->province;
                $data[$key]['district'] = $address->district;
                $data[$key]['area'] = $address->area ?? '';
                $data[$key]['additional_address'] = $address->additional_address;
                $data[$key]['zip'] = $address->zip;
                $data[$key]['charge'] = $shipping_chargeNew ?? 0;
            }
            
            if ($shippingAddress == null) {
                $response = [
                    'error' => false,
                    'data' => null,
                    'msg' => 'User Shipping Address '
                ];
            }

            $response = [
                'error' => false,
                'data' => $data,
                'msg' => 'User Shipping Address '
            ];

            return response()->json($response, 200);
        } catch (\Exception $ex) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => $ex->getMessage()
            ];
            
            return response()->json($response, 200);
        }
    }

    public function getAreaCharge($address)
    {   
        if($address->getLocation)
        {
            return $address->getLocation->deliveryRoute->charge;
        }else{
            return Setting::where('key','default_shipping_charge')->pluck('value')->first();
        }
    }

    public function updateShippingAddress(Request $request, $id)
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
            'area_id' => 'nullable|int|exists:locations,id'
        ]);



        if ($validator->fails()) {
            $response = [
                'error' => true,
                'data' => null,
                'message' => $validator->errors()
            ];
            return response()->json($response, 500);
        }

        // return "ok";
        $user = \Auth::user();
        if (!$user) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Sorry !! User Not Found'
            ];
            return response()->json($response, 500);
        }

        $this->usershippingaddress = UserShippingAddress::where('user_id', $user->id)->where('id', $id)->first();
        $data = $request->all();
        $data['user_id'] = $user->id;
        try {
            $this->usershippingaddress->fill($data);
            $status = $this->usershippingaddress->save();
            $response = [
                'error' => false,
                'data' => $status,
                'msg' => 'Shipping Address Updated Successfully !!'
            ];
            return response()->json($response, 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json($th->getMessage(), 200);
        }
    }

    public function deleteShippingAddress(Request $request, $id)
    {
        $user = \Auth::user();

        if ($id === null) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Id Field Required'
            ];
            return response()->json($response, 500);
        }

        DB::beginTransaction();
        try {
            $this->usershippingaddress = UserShippingAddress::where('user_id', $user->id)->where('id', $id)->first();

            if ($this->usershippingaddress) {
                $status = $this->usershippingaddress->delete();
                if ($status) {
                    $response = [
                        'error' => true,
                        'data' => null,
                        'msg' => 'Shipping Address Deleted Successfully !!'
                    ];

                    DB::commit();
                    return response()->json($response, 200);
                } else {
                    $response = [
                        'error' => true,
                        'data' => null,
                        'msg' => 'Sorry !! There Was A Problem While Deleting Shipping Address'
                    ];
                    return response()->json($response, 500);
                }
            } else {
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => 'Sorry !! Invalid Data Id'
                ];
                return response()->json($response, 500);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            $response = [
                'error' => true,
                'data' => null,
                'msg' => $th->getMessage()
            ];
            return response()->json($response, 500);
        }
    }

    public function getChargeRoute(Request $request, $id)
    {
        try {
            $local_area = City::where('id', $id)->first();
            $location = $local_area->getRouteCharge;
            $main_data = [];
            $default_shipping_charge = Setting::where('key','default_shipping_charge')->pluck('value')->first();
            foreach ($location as $key => $l) {
                $main_data[$key]['id'] = $l->id;
                $main_data[$key]['local_id'] = $l->local_id;
                $main_data[$key]['title'] = $l->title;
                $main_data[$key]['slug'] = $l->slug;
                $main_data[$key]['image'] = $l->image;
                $main_data[$key]['charge'] = $l->deliveryRoute->charge ?? $default_shipping_charge;
            }
            $response = [
                'error' => false,
                'data' => $main_data,
                'msg' => 'Area With Charge'
            ];
            return response()->json($response, 200);
        } catch (\Exception $ex) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => $ex->getMessage()
            ];
            return response()->json($response, 200);
        }
    }
}
