<?php

namespace App\Http\Controllers\Datatables;

use Illuminate\Http\Request;
use App\Datatables\QuoteDatatables;
use App\Http\Controllers\Controller;

class QuoteController extends Controller
{
    private $datatable;
    public function __construct(QuoteDatatables $datatables)
    {
        $this->datatable = $datatables;
    }

    public function index(){
        return $this->datatable->getdata();
    }
}
