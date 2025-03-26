<?php
namespace App\Data\Filter;

use Carbon\Carbon;
use Illuminate\Http\Request;

class FilterData
{
    protected $filters;
    function __construct(Request $request)
    {
        $this->filters = array_merge([
            'year'=>Carbon::now()->format('Y'),
            'month'=>(int)Carbon::now()->format('m'),
            'per'=>$request->per ?? 10,
            'filter_type'=>$request->filter_type ?? 1,
            'search_string'=>$request->search_string ?? null,
            'type'=>$request->type ?? null,
            'refId' => $request->refid ?? null,
            'customerName' => $request->customerName ?? null,
            'fromDate' => $request->fromDate ?? null,
            'toDate' => $request->toDate ?? null,
        ], $request->all());
    }
    public function getData($id=null)
    {
        $this->filters['filterCustomerId']=$id ?? null;
        return $this->filters;
    }

 
    
}