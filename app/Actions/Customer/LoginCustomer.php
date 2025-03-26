<?php

namespace App\Actions\Customer;

use App\Events\LogEvent;
use session;
use App\Models\Seller;
use App\Models\LogActivity;
use App\Models\New_Customer;
use GuzzleHttp\Psr7\Request;
use App\Mail\CustomerConfirmation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;


class LoginCustomer
{
    public function toResponse($request)
    {
        $check = New_Customer::where('email', $request->email)->orWhere('phone', $request->email)->first();
        if ($check == null) {
            return back()->with('error', 'The email or phone is not match with our record.');
        }

        if ($check->status == '0') {
            return back()->with('error', 'User is INACTIVE by admin please contact admin.');
        }
        if ($check->status == '2') {
            return back()->with('error', 'User is BLOCKED by admin please contact admin.');
        }


            if ($check->email_verified_at == null) {
                $this->showOTPForm($request->email);
                session()->put('customer_otp_form', 'show');
                return redirect()->route('customer.verify')->with('error', 'Please verify your account before login');
            } else            
            LogEvent::dispatch('Customer LogIn', 'Customer LogIn', route('loginaccess'));
                if (Auth::guard('customer')->attempt(['email' => $check->email, 'password' => $request->password], $request->remember)) {
                return redirect()->intended(route('Cprofile'));
            } else {
                return back()->with('error', 'Your Password is Wrong, Please try again.')->withInput($request->only('email', 'remember'));
            }
    }

    public function showOTPForm($email_or_phone)
    {
        $customer = New_Customer::where('email', $email_or_phone)->orWhere('phone', $email_or_phone)->first();
        if ($customer != null) {
            $customer->verify_otp = rand(100000, 999999);
            $message = "Your OTP for " . config('app.name') . " Customer Registration is :" . $customer->verify_otp;
            $this->sendSMS($customer->phone, $customer->name, $message);
            Mail::to($customer->email)->send(new CustomerConfirmation($customer));
            $customer->save();
        } else {
            return back()->with('error', 'Your email is not matched with Our Record.');
        }
    }

    public function sendSMS($phone, $user, $message)
    {
        $args = http_build_query(array(
            'token' => 'v2_cW6mkM6ZFC29LdP3NNDWyalAoZf.2SJT',
            'from'  => 'Glass Pipe',
            'to'    =>  $phone,
            'text'  => 'Dear ' . $user . ',' . $message
        ));

        $url = "http://api.sparrowsms.com/v2/sms/";
        
        # Make the call using API.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // Response
        $response = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
    }
}
