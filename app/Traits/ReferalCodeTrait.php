<?php
namespace App\Traits;

use App\Models\New_Customer;
use Illuminate\Support\Str;

trait ReferalCodeTrait{

    public function generatereferalCode()
    {
        $code=strtoupper(Str::random(3) . rand(111, 999));
        
        $code=$this->uniqueReferalCode($code);
       
        return $code;
    }

    public function uniqueReferalCode($codeValue)
    {
        if(New_Customer::where('referal_code',$codeValue)->count() >0)
        {
            $codeValue=$codeValue."-".rand(1,9999);
            $this->uniqueReferalCode($codeValue);
        }
        return $codeValue;
    }
}