<?php

namespace App\Http\Controllers\Datatables;


use App\Datatables\TrashDatatables;
use App\Http\Controllers\Controller;


class TrashController extends Controller
{
    private $datatable;
    public function __construct(TrashDatatables $datatables)
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
