<?php

namespace App\Http\Controllers\Seller;

use App\Data\Date\DateData;
use Illuminate\Support\Arr;
use App\Models\New_Customer;
use Illuminate\Http\Request;
use App\Data\Seller\SalesData;
use App\Data\Filter\FilterData;
use App\Data\Customer\CustomerData;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Data\Seller\CustomerReportData;
use App\Exports\SellerCustomerReport;
use Yajra\DataTables\Facades\DataTables;

class CustomerReportController extends Controller
{
    public function index(Request $request)
    {   
        if($request->ajax())
        {
            $filters = (new FilterData($request))->getData();
            if(!Arr::get($filters, 'type')){
                $filters['type'] = null;
            }
            $seller=auth()->guard('seller')->user();
            $customer=(new CustomerReportData($filters))->CustomerReportData($seller);
            session()->forget('customer_csv');
            session()->put('customer_csv',$customer);
                return DataTables::of($customer)
                ->addIndexColumn()
                ->addColumn('id',function($row){
                    return $row->id;
                })
                ->addColumn('name',function($row){
                    return $row->name;
                })
                ->addColumn('email',function($row){
                    return $row->email;
                })
                ->addColumn('phone',function($row){
                    return $row->phone;
                })
                ->addColumn('address',function($row){
                    return $row->area;
                })
                ->addColumn('created_at',function($row){
                    return $row->created_at;
                })
                ->rawColumns(['id','name','email','phone','address','created_at'])
                ->make(true);

        }

        $data['filters'] = (new FilterData($request))->getData();
        $dateData = new DateData();
        $area=New_Customer::whereNotNull('area')->get();
        $data['area'] =$area->unique('area')->pluck('area');
        $data['months'] = $dateData->getMonths();
        $data['years'] = $dateData->getYears();
        // dd($data);
        return view('seller.report.customer-report.index',$data);

    }

    public function download()
    {
        return Excel::download(new SellerCustomerReport(), 'Customer Report.csv');
    }
}
