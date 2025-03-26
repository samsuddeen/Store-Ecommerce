<?php
namespace App\Data\Admin;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TransactionExport implements FromView{

    public function view():View
    {        
        $product = session('transaction_csv'); 
        if($product)
        {
            return view('report.transactionreport.excel',compact('product'));
        }
    }
}