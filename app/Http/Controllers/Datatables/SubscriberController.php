<?php

namespace App\Http\Controllers\Datatables;

use App\Http\Controllers\Controller;
use App\Datatables\SubscriberDatatables;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    private $datatable;
    public function __construct(SubscriberDatatables $datatables)
    {
        $this->datatable = $datatables;
    }
    public function index(){
        return   $this->datatable->getData();
    }
}
