<?php

namespace App\Datatables;

use App\Data\Refund\RefundData;
use App\Helpers\Utilities;
use App\Models\Customer\ReturnOrder;
use Yajra\DataTables\Facades\DataTables;

class RefundDatatables implements DatatablesInterface
{

    protected $filters;
    function __construct($filters=null)
    {
        $this->filters = $filters;
    }
    public function getData()
    {
        $data = (new RefundData($this->filters))->getData();
        // $data=($data)->where('status',1);
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('status', function ($row) {
                $status = '';
                if ((int)$row->is_new == 1) {
                    $status = '<div class="d-flex"><span class="badge bg-primary pending-status">Pending</span><span class="badge bg-warning success-status">New</span></div>';
                } else {
                    $status = $this->getStatus($row->status,$row->paid_by);
                }
                $action = '<div class="d-flex">' . $status . '<div class="dropdown"><button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown"><i data-feather="more-vertical"></i></button><div class="dropdown-menu dropdown-menu-end">';

                $action .=$this->getActions($row);

                $action .= '</div></div></div>';
                return $action;
            })  
            ->addColumn('customer',function($row){
                return '<a href="'.route('customer.show', $row->returnOrder->user_id ?? 0).'">'.$row->returnOrder->owner->name ?? null .'</a>';
            })
            ->addColumn('qty',function($row){
                return $row->returnOrder->qty;
            })  
            ->addColumn('price',function($row){
                return 'Rs.'. formattedNepaliNumber($row->returnOrder->orderAsset->price) ?? null;
            })  
            ->addColumn('total',function($row){
                return 'Rs.'. formattedNepaliNumber($row->returnOrder->orderAsset->price * $row->returnOrder->qty) ?? null;
            })  
            ->addColumn('product',function($row){
                return '<a href="'.route('product.show', $row->returnOrder->product_id ?? 0).'">'.$row->returnOrder->orderAsset->product_name ?? null . '</a>';
            })
            ->addColumn('action', function ($row) {
                $show =  Utilities::button(href: route('returnable.show', $row->id), icon: "eye", color: "info success-status", title: 'Show Order');
                return  $show;
            })
            ->rawColumns(['action', 'customer', 'product', 'status', 'price', 'qty', 'total'])
            ->make(true);
    }
    private function getStatus($status,$paid)
    {
       $return_status = '';
        switch ($status) {
            case 1:
                $return_status =  '<div class="badge bg-primary pending-status">PENDING</div>';
                break;
            case 2:
               
                $return_status =  '<div class="badge bg-info success-status">PAID</div><span class="badge bg-primary">'.$paid.'</span>';
                break;
            case 3:
                $return_status =  '<div class="badge bg-danger success-status">REJECTED</div>';
                break;
            default:
                # code...
                break;
        }
        return $return_status;
    }

    private function getActions($row)
    {
        $action = '';
        switch ($row->status) {
            case 1:
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-amount="'.($row->returnOrder->amount ?? 0).'" data-bs-target="#shareProject" data-type="REJECTED" data-returned_id="'.$row->id.'" href="#"><i data-feather="truck" class="me-50"></i><span>REJECT</span></a>';
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-amount="'.($row->returnOrder->amount ?? 0).'" data-bs-target="#shareProject" data-type="PAID" data-returned_id="'.$row->id.'" href="#"><i data-feather="alert-octagon" class="me-50"></i><span>Pay</span></a>';
                break;
            default:
                # code...
                break;
        }
        return $action;
    }

    public function getPaidRefundData()
    {
        $data = (new RefundData($this->filters))->getRefundpaidData();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('status', function ($row) {
                
                $status = '';
                if ((int)$row->is_new == 1) {
                    $status = '<div class="d-flex"><span class="badge bg-primary pending-status">Pending</span><span class="badge bg-warning success-status">New</span></div>';
                } else {
                    $status = $this->getStatus($row->status,$row->paid_by);
                }
                $action = '<div class="d-flex">' . $status . '<div class="dropdown"><button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown"><i data-feather="more-vertical"></i></button><div class="dropdown-menu dropdown-menu-end">';

                $action .=$this->getActions($row);

                $action .= '</div></div></div>';
                return $action;
            })  
            ->addColumn('customer',function($row){
                return '<a href="'.route('customer.show', $row->user->id ?? 0).'">'.$row->user->name ?? null .'</a>';
            })
            ->addColumn('order',function($row){
                return '<a href="'.route('admin.viewOrder', $row->returnOrder->getOrderAsset->order->ref_id ?? 0).'">'.'View Order'?? null .'</a>';
            })
            ->addColumn('action', function ($row) {
                $show =  Utilities::button(href: route('refunddireact.show', $row->id), icon: "eye", color: "info success-status", title: 'Show Order');
                return  $show;
            })
            ->rawColumns(['action', 'customer', 'order', 'status', 'price', 'qty', 'total'])
            ->make(true);
    }

}
