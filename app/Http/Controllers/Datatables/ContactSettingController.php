<?php

namespace App\Http\Controllers\Datatables;

use App\Datatables\ContactSettingDatatables;
use App\Http\Controllers\Controller;

class ContactSettingController extends Controller
{
    private $datatable;
    public function __construct(ContactSettingDatatables $datatables)
    {
        $this->datatable = $datatables;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return   $this->datatable->getData();
    }
}
