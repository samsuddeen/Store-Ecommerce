<?php

namespace App\Http\Controllers\Report;

use App\Data\Date\DateData;
use Illuminate\Http\Request;
use App\Data\Filter\FilterData;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Data\Log\LogReportData;
use App\Models\Log\Log;
use App\Models\New_Customer;
use App\Models\User;

class LogReportController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $filters = $request->all();
            $data=(new LogReportData($filters))->getData();
            session()->forget('admin_searchkeyword_report');
            session()->put('admin_searchkeyword_report',$data);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('id', function ($row) {
                    return $row->id;
                })
                ->addColumn('log_from', function ($row) {
                    return ($row->guard=='web') ? 'Admin' :$row->guard;
                })
                ->addColumn('name', function ($row) {
                    switch($row->guard)
                    {
                        case 'customer':
                            return $row->user->name ?? null;
                            break;
                        case 'seller':
                            return $row->seller->name ?? null;
                            break;
                        case 'web':
                            return $row->admin->name ?? null;
                            break;
                    }
                   
                })
                ->addColumn('log_detail', function ($row) {
                    $show_url = route('log.show',$row->id);
                    return '<a href="' . $show_url . '" target="_blank">' . $row->log_title . '</a>';
                })
                ->rawColumns(['id', 'log_from', 'name', 'log_detail'])
                ->make(true);
        }

    
        $data['filters'] = (new FilterData($request))->getData();

        $dateData = new DateData();
        $data['months'] = $dateData->getMonths();
        $data['years'] = $dateData->getYears();
        // dd($customers);
        return view("report.log.index" ,$data);
    }

    public function show($id)
    {
        $log = Log::find($id);
        if($log->guard == 'customer')
        {
            $user = New_Customer::where('id',$log->log_id)->first();
        }else{
            $user = User::where('id',$log->log_id)->first();
        }
        $actions = Log::where('log_id',$user->id)->latest()->paginate(30);
        return view('report.log.show',compact('log','user','actions'));
    }
}
