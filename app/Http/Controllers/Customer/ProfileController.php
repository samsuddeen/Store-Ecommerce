<?php

namespace App\Http\Controllers\Customer;

use App\Enum\Customer\CustomerStatusEnum;
use App\Models\User;
use App\Models\Local;
use App\Models\Country;
use App\Events\LogEvent;
use App\Models\District;
use App\Models\Location;
use App\Models\Province;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\New_Customer;
use App\Models\UserShippingAddress;

class ProfileController extends Controller
{
    protected $user;
    public function __construct(New_Customer $user)
    {
        $this->user=$user;
    }
    public function profile()
    {
        $countries = Country::where('status',1)->get();
        $provinces = Province::where('publishStatus','1')->get();
        // dd(auth()->guard('customer')->user());
        // LogEvent::dispatch('Profile View', 'Profile View', route('Cprofile'));
        return view('frontend.customer.profile',compact('countries','provinces'));
    }
    public function editProfile(Request $request)
    {        
        // dd($request->all());
        $customer=auth()->guard('customer')->user();
        $user=New_Customer::where('id',$customer->id)->first();
        if($request->has('photo')){
            $file = $request->photo;
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/photos', $filename);
            $data['photo'] = $path;
        }
        $area=City::where('id',$request->area)->first();
        $data['area']=$area->city_name ?? '';
        $location=Location::where('id',$request->additional_address)->first();
        $data['zip_code']=$location->zip_code ?? '';
        $data['name']=$request->name;
        $data['phone']=$request->phone ?? null;
        $data['address']=$request->address;
        $data['province']=$request->province ?? '';
        $data['district']=$request->district ?? '';
        $user->update($data);

        $shipping_address = UserShippingAddress::where('user_id',$user->id)->first();
        $area_name = City::where('id',$request->area)->first()->city_name;
        if(!$shipping_address)
        {
            $shipping_address_data = [
                'user_id' => $user->id,
                'name' => $request->name ?? $user->name,
                'email' => $user->email,
                'phone'=> $request->phone ?? $user->phone,
                'province' => $request->province ?? $user->province,
                'district' => $request->district ?? $user->district,
                'area' => $area_name,
                'additional_address' => $request->address ?? $user->address,
                'zip' => $request->zip ?? $user->zip,
                'area_id' => $request->area
            ];
            UserShippingAddress::create($shipping_address_data);
        }

        $request->session()->flash('success', 'Successfully Updated Your Profile.');
        return redirect()->back();
        try {
            LogEvent::dispatch('Profile Updated', 'Profile Updated', route('editCProfile'));
            $user->update($data);
            $request->session()->flash('success', 'Successfully Updated Your Profile.');
            return redirect()->back();
        } catch (\Throwable $th) {
            $request->session()->flash('error',$th->getMessage());
            return back();
        }            
    }
}
