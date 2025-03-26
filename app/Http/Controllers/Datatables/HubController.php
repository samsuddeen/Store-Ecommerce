<?php

namespace App\Http\Controllers\Datatables;


use App\Datatables\HubDatatables;
use App\Http\Controllers\Controller;


class HubController extends Controller
{
    private $datatable;
    public function __construct(HubDatatables $datatables)
    {
        $this->datatable = $datatables;
    }
    /**
     * Display a listing of the resource.
     *P
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return   $this->datatable->getData();
    }

    
}
