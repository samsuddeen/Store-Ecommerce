<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeliveryCharge;
use Illuminate\Http\Request;

class DeliveryChargeController extends Controller
{
    public function deliverCharge()
    {
        $delivery = DeliveryCharge::get();

        $user = \Auth::user();

        return $user;
        return $delivery;
    }
}
