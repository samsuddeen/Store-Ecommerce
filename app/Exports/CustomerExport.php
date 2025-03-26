<?php
namespace App\Exports;

use App\Models\New_Customer;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CustomerExport implements FromView
{

    public function view(): View
    {
        // $customer = New_Customer::get();
        $customer=session('admin_customer_report');        
        return view('admin.excel.customerexcel',compact('customer'));
    }
}