<?php

namespace App\Http\Controllers\Report;

use App\Data\Date\DateData;
use Illuminate\Support\Arr;
use App\Models\New_Customer;
use Illuminate\Http\Request;
use App\Data\Filter\FilterData;
use App\Exports\CustomerExport;
use App\Data\Order\OrderData;
use App\Http\Controllers\Controller;
use App\Models\District;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Province;
use App\Exports\OrderReportExport;
use App\Models\Order;

class OrderReportController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $filters = $request->all();

            if (!Arr::get($filters, 'type')) {
                $filters['type'] = null;
            }

            $order = (new OrderData($request->all()))->orderReport();
            session()->forget('admin_order_report');
            session()->put('admin_order_report',$order);
            return DataTables::of($order)
                ->addIndexColumn()
                ->addColumn('id', function ($row) {
                    return $row->id;
                })  
                ->addColumn('ref_id', function ($row) {
                    return $row->ref_id;
                })
                ->addColumn('total_quantity', function ($row) {
                    return $row->total_quantity;
                })
                ->addColumn('total_price', function ($row) {
                    return $row->total_price;
                })
                ->addColumn('total_discount', function ($row) {
                    return $row->total_discount;
                })
                ->addColumn('coupon_discount_price', function ($row) {
                    return $row->coupon_discount_price;
                })
                ->addColumn('payment_with', function ($row) {
                    return $row->payment_with;
                })
                ->rawColumns(['id', 'name', 'email', 'phone', 'address', 'created_at'])
                ->make(true);
        }
        $data['filters'] = $request->all();
        $dateData = new DateData();
        $data['province'] = Province::get();
        $data['district'] = District::get();
        $data['months'] = $dateData->getMonths();
        $data['years'] = $dateData->getYears();
        $data['areas'] = Order::select('area')->distinct('area')->get();

        return view('report.order-report.index', $data);

    }

    public function exportOrderReport()
    {
        return Excel::download(new OrderReportExport(), 'order-report.xlsx');
    }
}
