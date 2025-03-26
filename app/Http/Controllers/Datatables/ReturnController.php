<?php

namespace App\Http\Controllers\datatables;

use Illuminate\Http\Request;
use App\Datatables\ReturnDatatables;
use App\Http\Controllers\Controller;

class ReturnController extends Controller
{
    private $datatable;
    public function __construct(ReturnDatatables $datatables)
    {
        $this->datatable = $datatables;
    }
    public function index(){
        return   $this->datatable->getData();
    }
}
