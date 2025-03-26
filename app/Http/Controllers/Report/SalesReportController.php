<?php

namespace App\Http\Controllers\Report;

use App\Models\Order;
use App\Models\OrderAsset;
use App\Data\Date\DateData;
use Illuminate\Support\Arr;
use App\Exports\SalesExport;
use Illuminate\Http\Request;
use App\Data\Sales\SalesData;
use App\Data\Filter\FilterData;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Transaction\Transaction;
use Yajra\DataTables\Facades\DataTables;

class SalesReportController extends Controller
{

    public function index(Request $request)
    {    
        if ($request->ajax()) {
            $filters = $request->all();
        
            if (!Arr::get($filters, 'type')) {
                $filters['type'] = null;
            }
        
            $data = (new SalesData($request->all()))->salesData();
        
            session()->forget('admin_sales_report');
            session()->put('admin_sales_report', $data);
        
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('order_by', function ($row) {
                    // Check if the order and user exist
                    if ($row->order && $row->order->user) {
                        return "<a href='#'>" . $row->order->user->name . " [" . $row->order->user->phone . "]</a>";
                    } else {
                        return "Not Defined";
                    }
                })

                ->addColumn('transaction_no', function ($row) {
                    return $row->transaction_no;
                })
                ->addColumn('total_quantity', function ($row) {
                    return $row->order ? $row->order->total_quantity : "Not Defined"; 
                })

                ->addColumn('total_discount', function ($row) {
                    return $row->order ? 'Rs.' . formattedNepaliNumber($row->order->total_discount) : "Not Defined"; // Check if order exists
                })

                ->addColumn('total_price', function ($row) {
                    return $row->order ? 'Rs.' . formattedNepaliNumber($row->order->total_price) : "Not Defined"; // Check if order exists
                })

                ->addColumn('delivery_date', function ($row) {
                    return ($row->created_at->format('d M Y') . '[' . $row->created_at->format('H:i') . ']') ?? "Not Defined";
                })
                ->addColumn('ref_id', function ($row) {
                    // Check if order and ref_id exist
                    if ($row->order && $row->order->ref_id) {
                        $show = '<a href="' . route('admin.viewOrder', $row->order->ref_id) . '">' . $row->order->ref_id . '</a>';
                        return $show;
                    } else {
                        return "Not Defined";
                    }
                })
                ->rawColumns(['status', 'order_by', 'ref_id', 'transaction_no', 'total_quantity', 'total_discount', 'total_price', 'delivery_date'])
                ->make(true);
        }
        
        $data['filters'] = $request->all();
        $dateData = new DateData();
        $data['months'] = $dateData->getMonths();
        $data['years'] = $dateData->getYears();
        
        return view('report.sales-report.index', $data);
    }


    public function exportexcel()
    {
        return Excel::download(new SalesExport(), 'Reports.csv');
    }

    
}
