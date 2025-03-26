<?php

namespace App\Http\Controllers\Report;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Data\Date\DateData;
use App\Data\Filter\FilterData;
use Maatwebsite\Excel\Facades\Excel;
use App\Data\Admin\TransactionReport;
use App\Data\Admin\TransactionExport;
use Yajra\DataTables\Facades\DataTables;
use App\Exports\SellerProductExport;
class TransactionReportController extends Controller
{
    public function index(Request $request)
    {

        if($request->ajax())
        {
            $filters = (new FilterData($request))->getData();
            if(!Arr::get($filters, 'type')){
                $filters['type'] = null;
            }
            
            $filters = (new FilterData($request))->getData();
           
            $data = (new TransactionReport($filters))->orderProductReport();
            session()->forget('transaction_csv');
            session()->put('transaction_csv',$data);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('order_id', function ($row) {
                    return $row->ref_id;
                })
                ->addColumn('payment_with', function ($row) {
                    return ucfirst($row->payment_with);
                })
                ->rawColumns(['order_id','payment_with'])
                ->make(true);
        }
        $data['filters'] = (new FilterData($request))->getData();
       
        $dateData = new DateData();
        
        $data['months'] = $dateData->getMonths();
        $data['years'] = $dateData->getYears();
        return view('report.transactionreport.index',$data);
    }

    public function exportProduct()
    {
        return Excel::download(new TransactionExport(), 'Transaction.CSV');
    }
}
