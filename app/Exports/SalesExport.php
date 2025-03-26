<?php

namespace App\Exports;

use App\Models\FiscalYear;
use App\Models\JournalVouchers;
use Illuminate\Contracts\View\View;
use App\Models\Transaction\Transaction;
use Maatwebsite\Excel\Concerns\FromView;

class SalesExport implements FromView
{

    public function view(): View
    {
        // $sales = Transaction::get();
        $sales = session('admin_searchkeyword_report');
        return view('admin.Excel.salesexcel1',compact('sales'));
    }
}
