<?php

namespace App\Exports;

use App\Models\FiscalYear;
use App\Models\JournalVouchers;
use App\Models\Admin\SearchKeyword;
use Illuminate\Contracts\View\View;
use App\Models\Transaction\Transaction;
use App\Models\Order\Seller\SellerOrder;
use Maatwebsite\Excel\Concerns\FromView;

class SellerUserExport implements FromView
{
   
    public function view(): View
    {
        // $seller_order=SellerOrder::where('seller_id',auth()->guard('seller')->user()->id)->where('status',5)->get();
        // $user_id=collect($seller_order)->unique('user_id');
        // $user_id=$user_id->map(function($item){
        //     return $item->user_id;
        // });
        
        
        // $keywords=SearchKeyword::whereIn('customer_id',$user_id)->get();
        $keywords=session('usersearch_csv');

        return view('seller.report.usersearchexcel',compact('keywords'));
    }
}