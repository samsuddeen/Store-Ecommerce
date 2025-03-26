<?php

namespace App\Http\Controllers\Datatables;


use App\Http\Controllers\Controller;
use App\Datatables\PaymentMethodDatatables;

class PaymentMethodController extends Controller
{
    private $datatable;
    public function __construct(PaymentMethodDatatables $datatables)
    {
        $this->datatable = $datatables;
        // dd($this->datatable);
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
