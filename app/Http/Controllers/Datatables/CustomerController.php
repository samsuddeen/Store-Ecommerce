<?php

namespace App\Http\Controllers\Datatables;

use App\Data\Filter\FilterData;
use App\Datatables\CustomerDatatables;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerController extends Controller
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
        $datatable = new CustomerDatatables($filters);
        $this->datatable = $datatable;
        return $this->datatable->getData();
    }
}
