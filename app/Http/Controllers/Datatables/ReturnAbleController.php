<?php
namespace App\Http\Controllers\Datatables;


use Illuminate\Http\Request;
use App\Data\Filter\FilterData;
use App\Http\Controllers\Controller;
use App\Datatables\ReturnAbleDatatables;
use App\Data\SellerOrderreturnAction\SellerReturnOrderData;

class ReturnAbleController extends Controller
{
    private $datatable;
    public function index(Request $request)
    {
        $filters = (new FilterData($request))->getData();
        $datatables = new ReturnAbleDatatables($filters);
        $this->datatable = $datatables;
        return   $this->datatable->getData();
    }

    public function sellerReturnOrder(Request $request)
    {
        $seller=auth()->guard('seller')->user();
        $data=(new SellerReturnOrderData($seller))->getData();
        return $data;
    }
}
