<?php

namespace App\Http\Controllers\Datatables;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Data\Filter\FilterData;
use App\Datatables\LogDatatables;
use App\Http\Controllers\Controller;

class LogController extends Controller
{
    private $datatable;
    public function __construct()
    {

    }
    /**
     * Display a listing of the resource.
     *P
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $filters = (new FilterData($request))->getData();
 
        if(!Arr::get($filters, 'type')){
            $filters['type'] = null;
        }
        $this->datatable = new LogDatatables($filters);
        return   $this->datatable->getData();
    }
}
