<?php

namespace App\Datatables;

use App\Enum\Seller\SellerStatusEnum;
use App\Helpers\Utilities;
use App\Models\Seller;
use Yajra\DataTables\Facades\DataTables;

class SellerDatatables implements DatatablesInterface
{

    public function getData()
    {
        $data = Seller::latest();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('document', function ($row) {
                return '<a href="" class="btn btn-primary add-document" data-bs-toggle="modal" data-bs-target="#exampleModal" data-seller_id="'.$row->id.'">'.$row->documents->count().'</a>';
            })
            ->addColumn('status', function ($row) {
                $action = Utilities::status(status: $row->status, id: $row->id);
                if((int)$row->is_new == 1){
                    $action .= '<span class="badge" style="background-color:#7367f0">New</span>';
                }
                $action = '<div class="d-flex">' . $action . '<div class="dropdown"><button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown"><i data-feather="more-vertical"></i></button><div class="dropdown-menu dropdown-menu-end">';
                $action .= $this->getActions($row);
                return $action;
            })
            ->addColumn('action', function ($row) {
                $edit =  Utilities::button(href: route('seller.edit', $row->id), icon: "edit", color: "primary", title: 'Edit Seller');
                $show = Utilities::button(href: route('seller.show', $row->id), icon: "eye", color: "info", title: 'Show Seller');
                return  $edit . '' . $show;
            })
            ->rawColumns(['action', 'document', 'status'])
            ->make(true);
    }

    private function getActions($row)
    {
        $action = '';
        switch ($row->status) {
            case SellerStatusEnum::ACTIVE:
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="2" data-seller_id="' . $row->id . '" href="#"><i data-feather="truck" class="me-50"></i><span>Inactive</span></a>';
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="3" data-seller_id="' . $row->id . '" href="#"><i data-feather="truck" class="me-50"></i><span>Suspend</span></a>';
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="4" data-seller_id="' . $row->id . '" href="#"><i data-feather="truck" class="me-50"></i><span>Block</span></a>';
                break;
            case SellerStatusEnum::INACTIVE:
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="1" data-seller_id="' . $row->id . '" href="#"><i data-feather="truck" class="me-50"></i><span>Active</span></a>';
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="3" data-seller_id="' . $row->id . '" href="#"><i data-feather="truck" class="me-50"></i><span>Suspend</span></a>';
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="4" data-seller_id="' . $row->id . '" href="#"><i data-feather="truck" class="me-50"></i><span>Block</span></a>';
                break;
            case SellerStatusEnum::SUSPEND:
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="1" data-seller_id="' . $row->id . '" href="#"><i data-feather="truck" class="me-50"></i><span>Active</span></a>';
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="4" data-seller_id="' . $row->id . '" href="#"><i data-feather="truck" class="me-50"></i><span>Block</span></a>';
                break;
            case SellerStatusEnum::BLOCKED:
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="1" data-seller_id="' . $row->id . '" href="#"><i data-feather="truck" class="me-50"></i><span>Active</span></a>';
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="3" data-seller_id="' . $row->id . '" href="#"><i data-feather="truck" class="me-50"></i><span>Suspend</span></a>';
                break;
            default:
                # code...
                break;
        }
        return $action;
    }


}
