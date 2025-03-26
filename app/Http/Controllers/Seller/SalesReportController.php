<?php

namespace App\Http\Controllers\Seller;

use App\Data\Date\DateData;
use Illuminate\Support\Arr;
use App\Exports\SellerSalesReport;
use Illuminate\Http\Request;
use App\Data\Seller\SalesData;
use App\Data\Filter\FilterData;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;

class SalesReportController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $filters = (new FilterData($request))->getData();
            if (!Arr::get($filters, 'type')) {
                $filters['type'] = null;
            }
            $filters = (new FilterData($request))->getData();
            $seller = auth()->guard('seller')->user();

            $data = (new SalesData($filters))->SellerSalesData($seller);
           
            session()->forget('csv_data');
            session()->put('csv_data', $data);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('order_by', function ($row) {
                    return "<a href='#'>" . $row->order->user->name . "[" . $row->order->user->phone . "]</a>";
                })
                ->addColumn('transaction_no', function ($row) {
                    return  $row->transaction_no;
                })
                ->addColumn('total_quantity', function ($row) {
                    return $row->sellerOrder->qty ?? 'Not Defined';
                })
                ->addColumn('total_discount', function ($row) {
                    return  '$.' . formattedNepaliNumber($row->sellerOrder->total_discount) ?? "Not Defined";
                })
                ->addColumn('total_price', function ($row) {
                    return  '$.' . formattedNepaliNumber($row->sellerOrder->subtotal) ?? "Not Defined";
                })
                ->addColumn('delivery_date', function ($row) {
                    return ($row->created_at->format('d M Y') . '[' . $row->created_at->format('H:i') . ']') ?? "Not Defined";
                })
                ->addColumn('ref_id', function ($row) {
                    // $show =  '<a href="'.route('admin.viewOrder', $row->order->ref_id).'">'.$row->order->ref_id.'</a>';
                    $show =  '<a href="#">' . $row->order->ref_id . '</a>';
                    return  $show;
                })
                ->rawColumns(['delivery_date', 'status', 'order_by', 'ref_id', 'transaction_no', 'total_quantity', 'total_discount', 'total_price'])
                ->make(true);
        }
        $data['filters'] = (new FilterData($request))->getData();

        $dateData = new DateData();

        $data['months'] = $dateData->getMonths();
        $data['years'] = $dateData->getYears();
        return view('seller.report.sales-report.index', $data);
    }

    public function download()
    {
        return Excel::download(new SellerSalesReport(), 'Sales Report.csv');
    }
}
