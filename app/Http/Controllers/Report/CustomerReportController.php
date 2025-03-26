<?php

namespace App\Http\Controllers\Report;

use App\Data\Date\DateData;
use Illuminate\Support\Arr;
use App\Models\New_Customer;
use Illuminate\Http\Request;
use App\Data\Filter\FilterData;
use App\Exports\CustomerExport;
use App\Data\Customer\CustomerData;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use App\Models\District;
use App\Models\Province;

class CustomerReportController extends Controller
{
    public function index(Request $request)
    {
   
        if ($request->ajax()) {
            $filters = $request->all();

            if (!Arr::get($filters, 'type')) {
                $filters['type'] = null;
            }

            $customer = (new CustomerData($request->all()))->customerReport();
            session()->forget('admin_customer_report');
            session()->put('admin_customer_report',$customer);
            return DataTables::of($customer)
                ->addIndexColumn()
                ->addColumn('id', function ($row) {
                    return $row->id;
                })
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('email', function ($row) {
                    return $row->email;
                })
                ->addColumn('phone', function ($row) {
                    return $row->phone;
                })
                ->addColumn('address', function ($row) {
                    return $row->area;
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at;
                })
                ->rawColumns(['id', 'name', 'email', 'phone', 'address', 'created_at'])
                ->make(true);
        }
        $data['filters'] = $request->all();
        $dateData = new DateData();
        $data['area'] = New_Customer::whereNotNull('area')->get();
        $data['district'] = District::get();
        $data['province'] = Province::get();
        $data['months'] = $dateData->getMonths();
        $data['years'] = $dateData->getYears();
        return view('report.customer-report.index', $data);
    }

    public function exportCustomer()
    {
        return Excel::download(new CustomerExport(), 'Customer Report.csv');
    }
}
