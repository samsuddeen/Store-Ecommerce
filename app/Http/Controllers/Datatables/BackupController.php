<?php

namespace App\Http\Controllers\Datatables;


use App\Datatables\BackupDatatables;
use App\Http\Controllers\Controller;

class BackupController extends Controller
{
    private $datatable;
    public function __construct(BackupDatatables $datatables)
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
