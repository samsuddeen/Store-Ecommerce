<?php

namespace App\Http\Controllers\Seller;

use App\Models\Product;
use App\Helpers\Utilities;
use App\Data\Date\DateData;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Data\Filter\FilterData;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Data\Seller\SellerProductReport;
use Yajra\DataTables\Facades\DataTables;
use App\Exports\SellerProductExport;
class SellerProductReportController extends Controller
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
            $seller=auth()->guard('seller')->user();

            $data = (new SellerProductReport($filters))->sellerProductReport($seller);
            session()->forget('product_csv');
            session()->put('product_csv',$data);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('name_element', function ($row) {
                    return ucfirst($row->name);
                })
                ->addColumn('category', function ($row) {
                    return ucfirst($row->category->title);
                })
                ->addColumn('stock', function ($row) {
                    return $row->stocks[0]->id;
                })
                ->addColumn('status', function ($row) {
                    return $row->publishStatus;
                })
                ->addColumn('price', function ($row) {

                    return $row->getPrice->price;
                })
                ->rawColumns(['name_element','category','stock','status','price'])
                ->make(true);
        }
        $data['filters'] = (new FilterData($request))->getData();
       
        $dateData = new DateData();
        
        $data['months'] = $dateData->getMonths();
        $data['years'] = $dateData->getYears();
        return view('seller.report.product-report.index',$data);
    }
    
    public function download()
    {
        return Excel::download(new SellerProductExport(), 'Product Report.csv');
    }
}
