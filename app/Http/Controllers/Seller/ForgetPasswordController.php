<?php

namespace App\Http\Controllers\Seller;

use App\Models\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Actions\ChangeSellerPassword;

class ForgetPasswordController extends Controller
{
    public function sellerFormPassword(Request $request)
    {
        return view('frontend.seller.forget-password');
    }

    public function selllerPasswordMail(Request $request)
    {
        
        $request->validate([
            'email' => 'required|email'
        ]);

        // $customer = New_Customer::where('email', $request->email)->first();      
        $seller = Seller::where('email', $request->email)->first();

        if ($seller != null) {
            $seller->verify_otp = rand(100000, 999999);

            $message_data = [
                'verification_code' => $seller->verify_otp,
                'send_from' => env('MAIL_FROM_ADDRESS'),
                'send_to' => $seller->email,
            ];

            $this->sendMail($message_data);
            $seller->save();
            $request->session()->flash('success','Reset Password Link Has Been Sent To Your Mail !!');
            return redirect('/')->with('succes', 'We sent you a link for change passaword in your email.');
        } else {
            $request->session()->flash('error','Something Went Wrong !!');
            return back()->with('error', 'Your email is not matched with Our Record.');
        }
    }

    public function sendMail($message_data)
    {
        Mail::send('frontend.seller.email.forgetPassword', $message_data, function ($message) use ($message_data) {
            $message->from($message_data['send_from']);
            $message->to($message_data['send_to']);
        });
    }

    public function resetPasswordForm($token)
    {
        return view('frontend.seller.reset-password-form')->with('token', $token);
    }


    public function changePassword(Request $request, $token)
    {
        $request->validate([
            'password' => 'required|confirmed|min:6',
        ]);

        $change_password = new ChangeSellerPassword();
        $result = $change_password->changePassword($request, $token);

        if ($result == 0) {
            return back()->with('error', 'OOPs, Something is wrong. Please try again.');
        } elseif ($result == 1) {    
            return redirect()->route('sellerLogin')->with('success', 'Successfully changed your password');
        }  
        return redirect()->route('sellerPassword.forget')->with('error', 'Your email has been expired please sent new email');
    }
}
