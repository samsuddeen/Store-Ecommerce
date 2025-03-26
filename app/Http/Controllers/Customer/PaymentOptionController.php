<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentOptionController extends Controller
{
    public function paymentOption()
    {
        return view('frontend.customer.paymentOptions');
    }

}
