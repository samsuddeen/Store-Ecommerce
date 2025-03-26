<?php

namespace App\Http\Controllers\Seller;

use App\Models\Local;
use App\Models\Seller;
use App\Models\Province;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Location;
use Illuminate\Support\Facades\Hash;
use App\Models\Order\Seller\SellerOrder;

class SellerProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->guard('seller')->user(); 
        $province=Province::where('id',$user->province_id)->first();
        $district=District::where('id',$user->district_id)->first();
        $total_delivered = SellerOrder::where('seller_id', auth()->guard('seller')->user()->id)->where('status', '5')->get();
        $total_orders = SellerOrder::where('seller_id', auth()->guard('seller')->user()->id)->get();        
        $user_area = Local::where('id', $user->area)->orWhere('local_name', $user->area)->first();
        $user_address = Location::where('id', $user->address)->orWhere('title', $user->address)->first();        
        $provinces = Province::where('publishStatus', true)->get();
        return view('seller.profile.profile', compact('province','district','user', 'total_delivered', 'total_orders', 'provinces', 'user_area', 'user_address'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return back();
    }

    /**
     * Store a newly created resource in storage.   
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        dd($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {                        
        
        $request->validate([
            'name' => 'required',
            'phone'=> 'required',
            'email' => 'required',
            'province_id' => 'required',
            'district_id' => 'required',
            'area' => 'required',
            'password' => 'required',
        ]);

        $seller = Seller::where('id', auth()->guard('seller')->user()->id)->first();
        if (Hash::check($request->password,$seller->password)) {
            $input = $request->all();                        
            $input['password'] = bcrypt($request->password);
            // dd($input);
            $seller->fill($input);
            $seller->save();
            session()->flash('success', 'Your profile is updated successfuly.');
            return back();
        }
         else
        {            
            session()->flash('error', 'Your entered Password is wrong, Please try again.');
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
