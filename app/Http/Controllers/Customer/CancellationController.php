<?php

namespace App\Http\Controllers\Customer;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Enum\Order\OrderStatusEnum;
use App\Http\Controllers\Controller;

class CancellationController extends Controller
{
    public function cancel(){
        $orders = Order::where('user_id', auth()->guard('customer')->user()->id)->where('status', OrderStatusEnum::CANCELED)->where('deleted_by_customer', '0')->paginate(10);
        return view('frontend.customer.cancellation',compact('orders'));
    }
}