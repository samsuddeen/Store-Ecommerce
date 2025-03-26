<?php

namespace App\Http\Controllers\Datatables;

use App\Datatables\UserDatatables;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $datatable;
    public function __construct(UserDatatables $datatables)
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
