<?php

namespace App\Exports;

use App\Models\FiscalYear;
use App\Models\JournalVouchers;
use Illuminate\Contracts\View\View;
use App\Models\Transaction\Transaction;
use App\Models\Order\Seller\SellerOrder;
use Maatwebsite\Excel\Concerns\FromView;

class SellerSalesReport implements FromView
{

    public function view(): View
    {
        // $seller_order=SellerOrder::where('seller_id',auth()->guard('seller')->user()->id)->where('status',5)->get();
        // $seller_order=$seller_order->map(function($item){
        //     return $item->order_id;
        // });

        // $sales=Transaction::whereIn('order_id',$seller_order)->get();
        $sales = session('csv_data');
        return view('seller.report.salesexcel', compact('sales'));
    }
}
