<?php

namespace App\Datatables;

use App\Helpers\Utilities;
use App\Models\Payment\PaymentMethod;
use App\Models\Tag;
use Yajra\DataTables\Facades\DataTables;

class PaymentMethodDatatables implements DatatablesInterface
{

    public function getData()
    {
        $data = PaymentMethod::latest();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('status', function ($row) {
                $status = '';
                $status = $this->getStatus($row->status);
                $action = '<div class="d-flex">' . $status ;
                if($row->is_default == true){
                    $action .='<span class="badge bg-info">Default</span>';
                }
                $action .='<div class="dropdown"><button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown"><i data-feather="more-vertical"></i></button><div class="dropdown-menu dropdown-menu-end">';
                $action .=$this->getActions($row);

                $action .= '</div></div></div>';
                return $action;
            })
            ->addColumn('action', function ($row) {
                $edit =  Utilities::button(href: route('payment-method.edit', $row->id), icon: "edit", color: "primary", title: 'Edit ?Banner');
                $delete = Utilities::delete(href: route('payment-method.destroy', $row->id), id: $row->id);
                return  $edit . '' . $delete;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }



    private function getStatus($status)
    {
        $return_status = '';
        switch ($status) {
            case 0:
                $return_status =  '<span class="badge bg-danger">Inactive</span>';
                break;
            case 1:
                $return_status =  '<span class="badge bg-primary">Active</span>';
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
            case 0:
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="1" data-order_id="'.$row->id.'" href="#"><i data-feather="crosshair" class="me-50"></i><span>Active</span></a>';
                break;
            case 1:
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="0" data-order_id="'.$row->id.'" href="#"><i data-feather="crosshair" class="me-50"></i><span>Inactive</span></a>';
                break;
            default:
                # code...
                break;
        }
        return $action;
    }
}
