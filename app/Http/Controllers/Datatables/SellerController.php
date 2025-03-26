<?php

namespace App\Http\Controllers\Datatables;


use App\Datatables\SellerDatatables;
use App\Http\Controllers\Controller;


class SellerController extends Controller
{
    private $datatable;
    public function __construct(SellerDatatables $datatables)
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
        return  $this->datatable->getData();
    }
}
