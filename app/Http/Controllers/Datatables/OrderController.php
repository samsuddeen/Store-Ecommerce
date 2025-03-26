<?php

namespace App\Http\Controllers\Datatables;

use App\Data\Filter\FilterData;
use Illuminate\Http\Request;
use App\Datatables\OrderDatatables;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    private $datatable;
    public function __construct()
    {
       
    }
    /**
     * Display a listing of the resource.
     *P
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $filters = (new FilterData($request));
        $datatable = new OrderDatatables($filters);
        $this->datatable = $datatable;
        return   $this->datatable->getData();
    }

    public function getInhouseOrder(Request $request)
    {
       
        $filters = (new FilterData($request));
        $datatable = new OrderDatatables($filters);
        $this->datatable = $datatable;
        return   $this->datatable->getInhouseData();
    }

    public function sellerListOrder(Request $request)
    {
        
        $filters = (new FilterData($request));
        $datatable = new OrderDatatables($filters);
        $this->datatable = $datatable;
        return   $this->datatable->getOrderSeller();
    }

    public function sellerOrderView(Request $request)
    {
        $filters = (new FilterData($request));
        $datatable = new OrderDatatables($filters);
        $this->datatable = $datatable;
        return   $this->datatable->getsellerOrderView();
    }

    public function sellerOrderViewList(Request $request,$id)
    {
        
        $filters = (new FilterData($request));
        $datatable = new OrderDatatables($filters);
        $this->datatable = $datatable;
        return   $this->datatable->getSellerOrderListView($id);
    }

    public function allDelivery(Request $request)
    {
       
        $filters = (new FilterData($request));
        $datatable = new OrderDatatables($filters);
        $this->datatable = $datatable;
        return   $this->datatable->getAllDelivery();
    }
}
