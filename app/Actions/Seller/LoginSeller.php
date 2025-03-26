<?php

namespace App\Actions\Seller;

use App\Enum\Seller\SellerStatusEnum;
use App\Models\Seller;
use App\Events\LogEvent;
use App\Models\LogActivity;
use App\Mail\CustomerConfirmation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Observers\VerifyOtp\DeleteOtp;

class LoginSeller
{
    public function toResponses($request)
    {
        $check = Seller::where('email', $request->email)->orWhere('phone', $request->email)->first();

        if ($check == null) {
            return back()->with('error', 'The email or phone is not match with our record.');
        }        
        if ($check->status == SellerStatusEnum::ACTIVE) {
            if ($check->email_verified_at !== null) {
                if (Auth::guard('seller')->attempt(['email' => $check->email, 'password' => $request->password])) {
                    return redirect()->intended(route('sellerDashboard'));
                }
                return back()->withInput($request->only('email', 'remember'))->with('error', 'Your Password is Wrong, Please try again.');;;
            } else {
                $this->showOTPFormSeller($request->email);
                $request->session()->flash('success', 'we sent you OTP Code, Please check your email or phone.');
                return redirect()->route('seller.otp.form');
            }
        } else {
            if (Auth::guard('seller')->attempt(['email' => $check->email, 'password' => $request->password])) {
                return view('frontend.seller.inactive-dashboard');
            }
            return back()->withInput($request->only('email', 'remember'))->with('error', 'Your Password is Wrong, Please try again.');;;
        }
    }

    public function showOTPFormSeller($email_or_phone)
    {
        $seller = seller::where('email', $email_or_phone)->orWhere('phone', $email_or_phone)->first();
        if ($seller != null) {
            $seller->verify_otp = rand(100000, 999999);
            $message = "Your OTP for " . config('app.name') . " Customer Registration is :" . $seller->verify_otp;
            $this->sendSMS($seller->phone, $seller->name, $message);
            Mail::to($seller->email)->send(new CustomerConfirmation($seller));
            $seller->save();
        }
        session()->put('seller_otp_form', 'show');
    }

    public function sendSMS($phone, $user, $message)
    {
        $args = http_build_query(array(
            'token' => 'v2_cW6mkM6ZFC29LdP3NNDWyalAoZf.2SJT',
            'from'  => 'InfoSMS',
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
