<?php

namespace App\Http\Controllers\Admin\Log;

use App\Data\Date\DateData;
use App\Data\Filter\FilterData;
use App\Data\User\UserTypes;
use App\Models\Log\Log;
use App\Events\LogEvent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;

class LogController extends Controller
{
   
    public function index(Request $request)
    {
        
        $filters = (new FilterData($request))->getData();
        if(!Arr::get($filters, 'type')){
            $filters['type'] = null;
        }
        $data['filters'] = $filters;
        $data['user_types'] = (new UserTypes)->getData();
        $dateData = new DateData();
        $data['months'] = $dateData->getMonths();
        $data['years'] = $dateData->getYears();

        LogEvent::dispatch('Log List', 'View All Log List', route('log.index'));
    
        return view("admin.log.index", $data);
    }
    public function destroy(Log $log)
    {
        try{
            $log->delete();
            session()->flash('success',"Log deleted successfully");
            return redirect()->route('log.index');
        } catch (\Throwable $th) {
            session()->flash('error',$th->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
