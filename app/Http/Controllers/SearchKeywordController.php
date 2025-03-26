<?php

namespace App\Http\Controllers;

use DataTables;
use App\Data\Date\DateData;
use App\Models\New_Customer;
use Illuminate\Http\Request;
use App\Data\Admin\UserSearch;
use App\Data\Filter\FilterData;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\SearchKeyword;
use App\Exports\SearchKeywordExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class SearchKeywordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $kewwords = SearchKeyword::all();
        if ($request->ajax()) {
            $filters = $request->all();
            $data = (new UserSearch($filters))->userSearch();

            session()->forget('admin_searchkeyword_report');
            session()->put('admin_searchkeyword_report',$data);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('id', function ($row) {
                    return $row->id;
                })
                ->addColumn('search_by', function ($row) {
                    return (New_Customer::where('id', $row->customer_id)->first()->name) ?? 'Guest';
                })
                ->addColumn('keyword', function ($row) {
                    return $row->search_keyword ?? null;
                })
                ->addColumn('search_from', function ($row) {
                    return $row->full_address ?? null;
                })
                ->addColumn('browser', function ($row) {
                    return $row->browser;
                })
                ->addColumn('system', function ($row) {
                    return $row->system;
                })
                ->addColumn('searched_date', function ($row) {
                    return $row->created_at;
                })
                ->rawColumns(['id', 'search_by', 'keyword', 'search_from', 'browser', 'system', 'searched_date'])
                ->make(true);
        }

        $searchKeywords = collect($kewwords)->groupBy('search_keyword');
        $customers = collect($kewwords)->groupBy('customer_id');
        $addresses = collect($kewwords)->groupBy('full_address');
        $data['filters'] = (new FilterData($request))->getData();

        $dateData = new DateData();
        $data['months'] = $dateData->getMonths();
        $data['years'] = $dateData->getYears();
        // dd($customers);
        return view("report.searchkeyword.index", compact('searchKeywords', 'customers', 'addresses',),$data);
    }

    public function exportexcel()
    {
        return Excel::download(new SearchKeywordExport(), 'SearchKeyword Report.csv');
    }
}
