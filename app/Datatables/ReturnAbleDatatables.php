<?php

namespace App\Datatables;

use App\Data\Order\ReturnedOrderData;
use App\Helpers\Utilities;
use App\Models\Customer\ReturnOrder;
use Yajra\DataTables\Facades\DataTables;

class ReturnAbleDatatables implements DatatablesInterface
{

    protected $filters;
    function __construct($filters = [])
    {
        $this->filters = $filters;
    }
    public function getData()
    {
        $data = (new ReturnedOrderData($this->filters))->getData();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('status', function ($row) {
                $status = '';
                if ((int)$row->is_new == 1) {
                    $status = '<div class="d-flex"><span class="badge bg-primary pending-status">Pending</span><span class="badge bg-warning success-status">New</span></div>';
                } else {
                    $status = $this->getStatus($row->status);
                }

                $action = '<div class="d-flex">' . $status . '<div class="dropdown"><button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown"><i data-feather="more-vertical"></i></button><div class="dropdown-menu dropdown-menu-end">';

                $action .= $this->getActions($row);

                $action .= '</div></div></div>';
                return $action;
            })
            ->addColumn('customer', function ($row) {
                return '<a href="' . route('customer.show', $row->user_id) . '">' . $row->owner->name ?? null . '</a>';
            })
            ->addColumn('qty', function ($row) {
                return $row->qty;
            })
            ->addColumn('price', function ($row) {
                return 'Rs.' . formattedNepaliNumber($row->orderAsset->price) ?? null;
            })
            ->addColumn('total', function ($row) {
                return 'Rs.' . formattedNepaliNumber($row->orderAsset->price * $row->qty) ?? null;
            })
            ->addColumn('product', function ($row) {
                return '<a href="' . route('product.show', $row->product_id) . '">' . $row->orderAsset->product_name ?? null . '</a>';
            })
            ->addColumn('action', function ($row) {
                $show =  Utilities::button(href: route('returnable.show', $row->id), icon: "eye", color: "info success-status", title: 'Show Order');
                return  $show;
            })
            ->rawColumns(['action', 'customer', 'product', 'status', 'price', 'qty', 'total'])
            ->make(true);
    }
    private function getStatus($status)
    {
        $return_status = '';
        switch ($status) {
            case 1:
                $return_status =  '<div class="badge bg-primary pending-status">PENDING</div>';
                break;
            case 2:
                $return_status =  '<div class="badge bg-info success-status">APPROVED</div>';
                break;
            case 3:
                $return_status =  '<div class="badge bg-info reject-status">RETURNED</div>';
                break;
            case 4:
                $return_status =  '<div class="badge bg-warning pending-status">REJECTED</div>';
                break;
            case 5:
                $return_status =  '<div class="badge bg-success process-status  ">RECEIVED</div>';
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
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="APPROVED" data-returned_id="' . $row->id . '" href="#"><i data-feather="pie-chart" class="me-50"></i><span>APPROVED</span></a>';
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="RETURNED" data-returned_id="' . $row->id . '" href="#"><i data-feather="truck" class="me-50"></i><span>RETURNED</span></a>';
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="REJECTED" data-returned_id="' . $row->id . '" href="#"><i data-feather="truck" class="me-50"></i><span>REJECT</span></a>';
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="RECEIVED" data-returned_id="' . $row->id . '" href="#"><i data-feather="alert-octagon" class="me-50"></i><span>RECEIVED</span></a>';
                break;
            case 2:
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="RETURNED" data-returned_id="' . $row->id . '" href="#"><i data-feather="truck" class="me-50"></i><span>RETURNED</span></a>';
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="RECEIVED" data-returned_id="' . $row->id . '" href="#"><i data-feather="alert-octagon" class="me-50"></i><span>RECEIVED</span></a>';
                break;
            case 3:
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="RECEIVED" data-returned_id="' . $row->id . '" href="#"><i data-feather="alert-octagon" class="me-50"></i><span>RECEIVED</span></a>';
                break;
            default:
                # code...
                break;
        }
        return $action;
    }
}
