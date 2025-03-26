<?php

namespace App\Http\Controllers\Seller;

use App\Data\Date\DateData;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Data\Filter\FilterData;
use App\Exports\SellerUserExport;
use App\Data\Seller\UsersearchData;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class SellerUserSearchController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax())
        {
            // dd($request->all());
            $filters = (new FilterData($request))->getData();
            if(!Arr::get($filters, 'type')){
                $filters['type'] = null;
            }
            $filters = (new FilterData($request))->getData();
            $seller=auth()->guard('seller')->user();
            
            $data=(new UsersearchData($filters))->sellerUserReport($seller);
            session()->forget('usersearch_csv');
            session()->put('usersearch_csv',$data);
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('user_name', function ($row) {
                return  ucfirst($row->user->name);
            })
            ->addColumn('keyword', function ($row) {
                return $row->search_keyword;
            })
            ->addColumn('search_date', function ($row) {
                return  $row->created_at;
            })
            ->rawColumns(['user_name','keyword','search_date'])
            ->make(true);
        }
        $data['filters'] = (new FilterData($request))->getData();
       
        $dateData = new DateData();
        
        $data['months'] = $dateData->getMonths();
        $data['years'] = $dateData->getYears();
        return view('seller.report.usersearch.index',$data);
    }

    public function download()
    {
        return Excel::download(new SellerUserExport(), 'User Search Report.csv');
    }
}
