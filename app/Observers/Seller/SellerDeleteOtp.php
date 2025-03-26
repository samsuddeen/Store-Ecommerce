<?php

namespace App\Observers\Seller;

use App\Models\New_Customer;

final class SellerDeleteOtp{

    public function __invoke()
    {
        $this->deleteSellerOtp();
    }

    private function deleteSellerOtp()
    {
        
        $time=time('H:i:s');
        info($time);
        // $customer=New_Customer::whereNotNull('verify_otp')->get();    
    }
}