<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Order;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class OrderReportExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function view():View
    {
        $orders = session('admin_order_report');
        return view('admin.Excel.orderreport',compact('orders'));

    }


}
