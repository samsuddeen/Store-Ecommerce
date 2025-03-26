<?php

namespace App\Http\Controllers\Datatables;

use Illuminate\Http\Request;
use App\Datatables\MenuDatatables;
use App\Http\Controllers\Controller;


class MenuController extends Controller
{
    private $datatable;
    public function __construct(MenuDatatables $datatables)
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
