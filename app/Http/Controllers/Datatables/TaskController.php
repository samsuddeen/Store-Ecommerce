<?php

namespace App\Http\Controllers\Datatables;

use App\Datatables\TaskDatatables;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TaskController extends Controller
{   
    private $datatable;
    
    public function __construct(TaskDatatables $datatables)
    {
        $this->datatable = $datatables;
    }

    public function index()
    {
        return $this->datatable->getData();
    }
}
