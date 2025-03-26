<?php

namespace App\Http\Controllers\Customer;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Mail\forgetpassword;
use App\Models\New_Customer;
use Illuminate\Http\Request;
use App\Mail\CustomerConfirmation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;


class ForgotPasswordController
{
  public function getEmail()
  {    
    
    return view('auth.passwords.email');           
  }
  
 public function postEmail( Request $request)
  { 
      $request->validate([
          'email'=>'required|email'
      ]);

      $customer = New_Customer::where('email', $request->email)->first();              

      if ($customer != null) {
          $customer->verify_otp = rand(100000, 999999);
          Mail::to($customer->email)->send(new forgetpassword($customer->verify_otp));
          $customer->save();
          $request->session()->flash('success','Reset Password Link Has Been Sent To Your Mail !!');
          return redirect('/')->with('succes', 'We sent you a link for change passaword in your email.');
      } else {
        $request->session()->flash('error','Something Went Wrong !!');
          return back()->with('error', 'Your email is not matched with Our Record.');
      }
  }

}
