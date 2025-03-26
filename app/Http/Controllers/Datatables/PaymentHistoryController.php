<?php

namespace App\Http\Controllers\Datatables;

use Illuminate\Http\Request;
use App\Data\Filter\FilterData;
use App\Http\Controllers\Controller;
use App\Datatables\PaymentHistoryDatatables;

class PaymentHistoryController extends Controller
{
    private $datatable;
    public function index(Request $request)
    {
        $filters = (new FilterData($request))->getData();
        $this->datatable = new PaymentHistoryDatatables($filters);
        return  $this->datatable->getData();
    }
}
