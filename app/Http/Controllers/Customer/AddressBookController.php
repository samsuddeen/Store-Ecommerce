<?php

namespace App\Http\Controllers\Customer;

use App\Models\Local;
use App\Models\District;
use App\Models\Province;
use App\Models\New_Customer;
use Illuminate\Http\Request;
use App\Models\UserShippingAddress;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Location;
use App\Models\UserBillingAddress;
use Illuminate\Support\Facades\Auth;

class AddressBookController extends Controller
{
    public function addressBook()
    {
        if (auth()->guard('customer')->user()) {
            $user = New_Customer::where('id', auth()->guard('customer')->user()->id)->first();
            $billingAddresses = UserBillingAddress::where('user_id', Auth::guard('customer')->user()->id)->get();
            $consumer = New_Customer::where('id', auth()->guard('customer')->user()->id)->first();
            $provinces = Province::where('publishStatus', 1)->get();
            $districts = District::where('publishStatus', 1)->get();
            $areas = City::get();
            $additional_address = Location::all();
            return view('frontend.customer.billing-addressbook', compact('billingAddresses', 'consumer', 'provinces', 'districts', 'areas', 'additional_address'));
        }
    }

    public function shippingAddressBook(Request $request)
    {
       
        if (auth()->guard('customer')->user()) {
            $user = auth()->guard('customer')->user();
            $shippingAddresses = UserShippingAddress::where('user_id', Auth::guard('customer')->user()->id)->get();
            $consumer = New_Customer::where('id', auth()->guard('customer')->user()->id)->first();
            
            $provinces = Province::where('publishStatus', 1)->get();
           
            $districts = District::where('publishStatus', 1)->get();
                
            $areas = City::get();
            $additional_address = Location::all();
            return view('frontend.customer.shipping-addressbook', compact('shippingAddresses', 'consumer', 'provinces', 'districts', 'areas', 'additional_address'));
        }
        else
        {
            $request->session()->flash('success','Plz Login First !!');
            return redirect()->route('Clogin');
        }
    }

    public function deleteShippingAddress(Request $request, $id)
    {
        $shippingAddress = UserShippingAddress::findOrFail($id);
        try {
            $shippingAddress->delete();
            $request->session()->flash('success', 'Successfully Deleted, Shipping Address');
            return back();
        } catch (\Throwable $th) {
            $request->session()->flash('error', 'OOPs Please Try Again.');
            return back();
        }
    }

    public function deleteBillingAddress(Request $request, $id)
    {
        $billingAddresses = UserBillingAddress::findOrFail($id);
        try {
            $billingAddresses->delete();
            $request->session()->flash('success', 'Successfully Deleted, Shipping Address');
            return back();
        } catch (\Throwable $th) {
            $request->session()->flash('error', 'OOPs Please Try Again.');
            return back();
        }
    }
}
