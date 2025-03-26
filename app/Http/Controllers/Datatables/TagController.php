<?php

namespace App\Http\Controllers\Datatables;


use App\Datatables\TagDatatables;
use App\Http\Controllers\Controller;

class TagController extends Controller
{
    private $datatable;
    public function __construct(TagDatatables $datatables)
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
