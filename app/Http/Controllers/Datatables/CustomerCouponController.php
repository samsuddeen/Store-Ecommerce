<?php

namespace App\Http\Controllers\Datatables;


use App\Http\Controllers\Controller;
use App\Datatables\CustomerCouponDatatables;

class CustomerCouponController extends Controller
{
    private $datatable;
    public function __construct(CustomerCouponDatatables $datatables)
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
        return $this->datatable->getData();
    }
}
