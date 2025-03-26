<?php

namespace App\Http\Controllers\Datatables;

use Illuminate\Http\Request;
use App\Datatables\ReviewDatatables;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
{
    private $datatable;
    public function __construct(ReviewDatatables $datatables)
    {
        $this->datatable = $datatables;
    }
    public function index(){
        return   $this->datatable->getData();
    }
}
