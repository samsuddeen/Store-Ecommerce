<?php

namespace App\Http\Controllers\datatables;

use Illuminate\Http\Request;
use App\Data\Filter\FilterData;
use App\Datatables\RefundDatatables;
use App\Http\Controllers\Controller;
use App\Datatables\ReturnAbleDatatables;

class RefundController extends Controller
{
    private $datatable;
    public function index(Request $request)
    {
        $filters = (new FilterData($request))->getData();
        $datatables = new RefundDatatables($filters);
        $this->datatable = $datatables;
        return   $this->datatable->getData();
    }

    public function refundPaid(Request $request)
    {
        $filters = (new FilterData($request))->getData();
        $datatables = new RefundDatatables($filters);
        $this->datatable = $datatables;
        return   $this->datatable->getPaidRefundData();
    }
}
