<?php

namespace App\Actions;

use App\Models\seller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Stmt\Return_;

class ChangeSellerPassword
{
    public function changePassword($request, $token)
    {
        $seller = seller::where('verify_otp', $token)->first();
        if ($seller) {           
            $token_expiry_date = ($seller->updated_at)->addMinutes(15);
            $current_date = Carbon::now();                        
            if ($token_expiry_date >= $current_date) {
                $status = seller::where('email', $seller->email)->update(['password' => Hash::make($request->password)]);
                return $status;
            } else {
                $status = 2;
                return $status;
            }
        }
    }
}
