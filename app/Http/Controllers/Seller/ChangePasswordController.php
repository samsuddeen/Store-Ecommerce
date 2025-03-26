<?php

namespace App\Http\Controllers\Seller;

use App\Models\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ChangePasswordController extends Controller
{
    public function changePassword(Request $request)
    {
        
        $test = Validator::make($request->all(), [
            'password' => 'required|min:6|confirmed',
        ]);

        if ($test->fails()) {
            foreach ($test->errors()->toArray() as $error) {
                $msg = $error[0];
                break;
            }
            session()->flash('error', $msg);
            return back();
        }

        $seller = Seller::where('id', auth()->guard('seller')->user()->id)->first();
        if (Hash::check($request->current_password, $seller->password)) {
            $input['password'] = bcrypt($request->password);
            $seller->update($input);
            session()->flash('success', 'Your Password has been successfully changed');
            return back();
        } else {
            session()->flash('error', 'OOPs, Your Current Password is Wrong. Please try again');
            return back();
        }
    }
}
