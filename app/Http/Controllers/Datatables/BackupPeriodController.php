<?php

namespace App\Http\Controllers\Datatables;


use App\Http\Controllers\Controller;
use App\Datatables\BackupPeriodDatatables;

class BackupPeriodController extends Controller
{
    private $datatable;
    public function __construct(BackupPeriodDatatables $datatables)
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
