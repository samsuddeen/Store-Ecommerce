<?php

namespace App\Http\Controllers\Customer;

use App\User;
use customer;
use Illuminate\Support\Str;
use App\Models\New_Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

class ResetPasswordController
{

    public function getPassword($tokens)
    {
        $customer = New_Customer::where('verify_otp', $tokens)->first();
        if ($customer != null) {
            return view('auth.reset-password')->with('token',  $tokens);
        } else {
            return redirect('/')->with('error', 'The link is expired, Please Resend Link.');
        }
    }

    public function updatePassword(Request $request, $token)
    {
        $request->validate([
            'password' => 'required|min:8',
            'password_confirmation' => 'required|min:8|same:password',
        ]);
        $data = New_Customer::where('verify_otp', $token)->first();
        if ($data) {
            $token_expiry_date = ($data->updated_at)->addMinutes(15);             
            $current_date = Carbon::now();
            if ($token_expiry_date >= $current_date) {
                New_customer::where('email', $data->email)
                ->update(['password' => Hash::make($request->password)]);         
                return redirect('customer/login')->with('success', 'Your password has been changed, Please Log In.');
            }
            else
            {
                return redirect()->route('getpass')->with('error', 'Your email has been expired, Please sent new email');
            }
        }
    }
  
}
