<?php

namespace App\Http\Controllers\Datatables;



use App\Datatables\BannerDatatables;
use App\Http\Controllers\Controller;

class BannerController extends Controller
{
    private $datatable;
    public function __construct(BannerDatatables $datatables)
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
