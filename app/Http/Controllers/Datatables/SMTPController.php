<?php

namespace App\Http\Controllers\Datatables;


use App\Datatables\SMTPDatatables;
use App\Http\Controllers\Controller;

class SMTPController extends Controller
{
    private $datatable;
    public function __construct(SMTPDatatables $datatables)
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
