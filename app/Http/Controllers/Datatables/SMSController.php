<?php

namespace App\Http\Controllers\Datatables;


use App\Datatables\SMSDatatables;
use App\Http\Controllers\Controller;

class SMSController extends Controller
{
    private $datatable;
    public function __construct(SMSDatatables $datatables)
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
