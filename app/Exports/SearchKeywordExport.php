<?php
namespace App\Exports;

use App\Models\FiscalYear;
use App\Models\JournalVouchers;
use Illuminate\Contracts\View\View;
use App\Models\Transaction\Transaction;
use Maatwebsite\Excel\Concerns\FromView;

class SearchKeywordExport implements FromView
{

    public function view(): View
    {        
        $keywords = session('admin_searchkeyword_report');
        return view('admin.excel.searchreport',compact('keywords'));
    }
}
    