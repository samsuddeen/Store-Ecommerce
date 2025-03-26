<?php

namespace App\Exports;

use App\Models\FiscalYear;
use App\Models\JournalVouchers;
use Illuminate\Contracts\View\View;
use App\Models\Transaction\Transaction;
use App\Models\Order\Seller\SellerOrder;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\New_Customer;
class SellerCustomerReport implements FromView
{
   
    public function view(): View
    {
        // $seller_order=SellerOrder::where('seller_id',auth()->guard('seller')->user()->id)->get();
        // $user_order=collect($seller_order)->unique('user_id');
        // $user=collect($user_order)->pluck('user_id');
        // $customer=New_Customer::whereIn('id',$user)->get();
        $customer=session('customer_csv');

        return view('seller.report.customerexcel',compact('customer'));
    }
}