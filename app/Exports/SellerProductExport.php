<?php

namespace App\Exports;

use App\Models\Product;
use App\Models\FiscalYear;
use App\Models\JournalVouchers;
use Illuminate\Contracts\View\View;
use App\Models\Transaction\Transaction;
use App\Models\Order\Seller\SellerOrder;
use Maatwebsite\Excel\Concerns\FromView;

class SellerProductExport implements FromView
{
   
    public function view(): View
    {
       
        // $seller_product=Product::where('seller_id',auth()->guard('seller')->user()->id)->get();
        $seller_product=session('product_csv');
        return view('seller.report.sellerproductexcel',compact('seller_product'));
    }
}