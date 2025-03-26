<?php

namespace App\Http\Controllers\Datatables;

use App\Datatables\PositionDatatables;
use App\Http\Controllers\Controller;

class PositionController extends Controller
{
    private $datatable;
    public function __construct(PositionDatatables $datatables)
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
