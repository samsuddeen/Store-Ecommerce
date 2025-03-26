<?php

namespace App\Http\Controllers\Customer;

use App\Events\LogEvent;
use App\Models\New_Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Actions\Seller\LoginSeller;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\FacebookRequest;
use App\Actions\Customer\LoginCustomer;
use Illuminate\Support\Facades\Session;
use App\Data\Customer\SocialLogin\SocialLoginData;
use App\Models\Countrey;

class LoginController extends Controller
{


    public function loginWithGithub()
    {
        $user = (new SocialLoginData)->githubLogin();
        Auth::guard('customer')->login($user);
        request()->session()->flash('success', 'Login Successfull !!');
        return redirect()->route('Cdashboard');
    }

    public function loginWithFacebook()
    {
       try{
        $user = (new SocialLoginData)->facebookLogin();
      
        if($user['if_id']===true)
        {
             return redirect()->route('facebook.verifyUser');
        }

        
        Auth::guard('customer')->login($user['user']);
        request()->session()->flash('success', 'Login Successfull !!');
        return redirect()->route('Cprofile');
       }catch(\Throwable $th)
       {
        request()->session()->flash('error', 'Something Went Wrong');
        return redirect()->route('Clogin');
       }
    }

    // public function loginWithGoogle()
    // {
    //     try{
    //         $user = (new SocialLoginData)->googleLogin();
    //         request()->session()->flash('success', 'Login Successfull !!');
    //         Auth::guard('customer')->login($user);
    //         return redirect()->route('Cprofile');
    //     }
    //     catch(\Throwable $th)
    //     {
    //         request()->session()->flash('error', 'Something Went Wrong');
    //     return redirect()->route('Clogin');
    //     }
    // }

    public function loginWithGoogle()
{
    try {
        // Get the user from Google login
        $user = (new SocialLoginData)->googleLogin();

        // Check if the user is a wholeseller
        if (!$user->wholeseller) {
            request()->session()->flash('error', 'Only wholesalers are allowed to log in.');
            return redirect()->route('Clogin');
        }

        // Log in the wholeseller
        Auth::guard('customer')->login($user);

        // Flash success message and redirect to profile
        request()->session()->flash('success', 'Login successful!');
        return redirect()->route('Cprofile');
    } catch (\Throwable $th) {
        // Handle errors
        request()->session()->flash('error', 'Something went wrong.');
        return redirect()->route('Clogin');
    }
}


    public function sellerLogin()
    {
        Auth::guard('seller');
        return view('frontend.seller.login');
    }

    public function login()
    {

        if (auth()->guard('customer')->check()) {
            request()->session()->flash('success', 'Plz Login first !!');
            return redirect()->route('Cdashboard');
        }
        return view('frontend.customer.login');
    }

    public function customerLogin(Request $request)
    {
       
        $request->validate([
            'email' => 'required',
            'password' => 'required',
            'g-recaptcha-response'=>'nullable'
        ]);
        return (new LoginCustomer)->toResponse($request);
    }

    public function sellerDashboardLogin(Request $request)
    {
       
        $request->validate(([
            'email' => 'required',
            'password' => 'required',
            'g-recaptcha-response'=>'nullable'
        ]));
        return (new LoginSeller)->toResponses($request);
    }

    public function logout()
    {
        LogEvent::dispatch('Customer Logout', 'Customer Logout', route('Clogout'));
        Session::flush();
        Auth::guard('customer')->logout();
        return redirect()->route('Clogin');
    }

    public function verifyFacebookSessionData(Request $request)
    {
        $data=$request->session()->get('facebookdata');
        return view('frontend.facebookemail');
    }

    public function getFacebookSessionData(FacebookRequest $request)
    {
        DB::beginTransaction();
        try{
            $data=$request->session()->get('facebookdata');
            $already_email=New_Customer::where('email',$request->email)->first();
            if($already_email)
            {
                request()->session()->flash('error', 'Email Already Exists !!');
                return redirect()->route('Clogin');
            }
            $customer=New_Customer::find($data['user_id']);
            if(!$customer)
            {
                request()->session()->flash('error', 'Something Went Wrong');
                return redirect()->route('Clogin');
            }
            $customer->update([
                'email'=>$request->email,
                'fb_status'=>1
            ]);
            DB::commit();
            Auth::guard('customer')->login($customer);
            request()->session()->flash('success', 'Login Successfull !!');
            return redirect()->route('Cprofile');
        }catch(\Throwable $th){
            request()->session()->flash('error', 'Something Went Wrong');
            return redirect()->route('Clogin');
        }
    }

    public function getwholesellercountrydata(Request $request){
        $country=Countrey::where('id',$request->country_id)->first();
        if(!$country){
            $response=[
                'error'=>true,
                'data'=>null,
                'msg'=>'Something Went Wrong !!'
            ];
            return response()->json($response,200);
        }
        $response=[
            'error'=>false,
            'selectedDataImage'=>$country->flags ?? null,
            'spanCode'=>$country->country_zip ?? null,
            'msg'=>'Data Fetched !!'
        ];
        return response()->json($response,200);
    }
}
