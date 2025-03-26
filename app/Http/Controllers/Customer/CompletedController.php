<?php

namespace App\Http\Controllers\Customer;

use App\Enum\Order\OrderStatusEnum;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Customer\ReturnOrder;
use App\Models\OrderAsset;
use Carbon\Carbon;

class CompletedController extends Controller
{
    public function completed()
    {
        $returningProducts = ReturnOrder::orderByDesc('created_at')->get();
        $orders = Order::where('user_id', auth()->guard('customer')->user()->id)->where('pending', '0')->where('status', OrderStatusEnum::DELIVERED)->where('deleted_by_customer', '0')->orderBy('created_at','DESC')->paginate(10);

        $current_date = strtotime(date('Y-m-d'));
        $no_days = 15;
        return view('frontend.customer.completed', compact('orders', 'current_date', 'returningProducts'));
    }
}
