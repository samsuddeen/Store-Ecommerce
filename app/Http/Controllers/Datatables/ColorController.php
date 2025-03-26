<?php

namespace App\Http\Controllers\Datatables;

use App\Datatables\ColorDatatables;
use App\Http\Controllers\Controller;

class ColorController extends Controller
{
    private $datatable;
    public function __construct(ColorDatatables $datatables)
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
