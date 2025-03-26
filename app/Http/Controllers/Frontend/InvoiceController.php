<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\New_Customer;

class InvoiceController extends Controller
{
    public function index(Request $request,$order)
    {
       
        $order_id=$order;
        $user=auth()->guard('customer')->user();
        
        if(!$user)
        {
            $order=Order::where('id',$order_id)->where('user_id',1)->first();
        }
        else
        {
            $order=Order::where('id',$order_id)->where('user_id',$user->id)->first();
        }
        return view('frontend.invoice.index',compact('order'));
    }

    public function indexinvoiceGuest(Request $request,$order)
    {
       
        
        $order_id=$order;
        $user=New_Customer::where('id',63)->first();
        
        if(!$user)
        {
            $order=Order::where('id',$order_id)->where('user_id',1)->first();
        }
        else
        {
            $order=Order::where('id',$order_id)->where('user_id',$user->id)->first();
        }
        return view('frontend.invoice.indexguestAll',compact('order'));
    }

    public function indexGuest(Request $request,$order)
    {
       
        $order_id=$order;
        $user=New_Customer::where('id', 63)->first();
        if(!$user)
        {
            $order=Order::where('id',$order_id)->where('user_id',1)->first();
        }
        else
        {
            $order=Order::where('id',$order_id)->where('user_id',$user->id)->first();
        }
        return view('frontend.invoice.indexguest',compact('order'));
    }

    public function indexGuestBulk(Request $request,$order)
    {
       
        $order_id=$order;
        $user=New_Customer::where('id', 63)->first();
        if(!$user)
        {
            $order=Order::where('id',$order_id)->where('user_id',1)->first();
        }
        else
        {
            $order=Order::where('id',$order_id)->where('user_id',$user->id)->first();
        }
        return view('frontend.invoice.indexguestAll',compact('order'));
    }
}
