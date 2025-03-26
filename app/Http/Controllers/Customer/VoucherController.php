<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function voucher()
    {
        $current_year = Carbon::now();        
        $vauchers = Coupon::where('to', '>=', $current_year)->get();
        return view('frontend.customer.voucher', compact('vauchers'));
    }
}
