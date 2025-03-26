<?php

namespace App\Datatables;

use App\Helpers\Utilities;
use Illuminate\Support\Arr;
use App\Models\New_Customer;
use App\Data\Customer\CustomerData;
use Yajra\DataTables\Facades\DataTables;

class CustomerDatatables implements DatatablesInterface
{
    protected $filters;
    function __construct($filters)
    {
        $this->filters = $filters;
    }
    public function getData()
    {
        if (Arr::get($this->filters, 'social')) {
            $data = (new CustomerData($this->filters))->getSocial();
        } else if (Arr::get($this->filters, 'type')) {
            if(Arr::get($this->filters, 'type') == '3')
            {
                $data = (new CustomerData($this->filters))->getTitle();

            }else{
                $data = (new CustomerData($this->filters))->getCustomers();
            }
        } else {
            $data = New_Customer::where('id','!=',63)->latest();
        }
        
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('email', function($row){
                return strtolower($row->email);
            })
            ->addColumn('action', function ($row) {
                $edit =  Utilities::button(href: route('customer.edit', $row->id), icon: "edit", color: "primary", title: 'Edit Customer');
                $show = Utilities::button(href: route('customer.show', $row->id), icon: "eye", color: "primary", title: 'Show Customer');
                return  $edit . '' . $show;
            })
            ->addColumn('created_at', function($row){
                return $row->created_at->format('d M Y h:i A');
            })
            ->addColumn('type', function($row){
                $type='Customer';
                if($row->wholeseller=='1')
                {
                    $type='Whole Seller';
                }
                return $type    ;
            })
            ->editColumn('status', function ($row) {
                $action = Utilities::status(status: $row->status, id: $row->id);
                $action = '<div class="d-flex">' . $action . '<div class="dropdown"><button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown"><i data-feather="more-vertical"></i></button><div class="dropdown-menu dropdown-menu-end">';
                $action .= $this->getActions($row);
                return $action;
            })
            ->rawColumns(['action', 'roles', 'status','type'])
            ->make(true);
    }
    private function getActions($row)
    {
        $action = '';
        switch ($row->status) {
            case 0:
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="inactive" data-order_id="' . $row->id . '" href="#"><i data-feather="truck" class="me-50"></i><span>Active</span></a>';

                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="active" data-order_id="' . $row->id . '" href="#"><i data-feather="pie-chart" class="me-50"></i><span>Inactive</span></a>';
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="blocked" data-order_id="' . $row->id . '" href="#"><i data-feather="truck" class="me-50"></i><span>Blocked</span></a>';
                break;
            case 1:
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="inactive" data-order_id="' . $row->id . '" href="#"><i data-feather="truck" class="me-50"></i><span>Active</span></a>';

                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="active" data-order_id="' . $row->id . '" href="#"><i data-feather="pie-chart" class="me-50"></i><span>Inactive</span></a>';
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="blocked" data-order_id="' . $row->id . '" href="#"><i data-feather="truck" class="me-50"></i><span>Blocked</span></a>';
                break;
            case 2:
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="inactive" data-order_id="' . $row->id . '" href="#"><i data-feather="truck" class="me-50"></i><span>Active</span></a>';

                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="active" data-order_id="' . $row->id . '" href="#"><i data-feather="pie-chart" class="me-50"></i><span>Inactive</span></a>';
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="blocked" data-order_id="' . $row->id . '" href="#"><i data-feather="truck" class="me-50"></i><span>Blocked</span></a>';
                break;
            case 3:
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="inactive" data-order_id="' . $row->id . '" href="#"><i data-feather="truck" class="me-50"></i><span>Active</span></a>';

                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="active" data-order_id="' . $row->id . '" href="#"><i data-feather="pie-chart" class="me-50"></i><span>Inactive</span></a>';
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="blocked" data-order_id="' . $row->id . '" href="#"><i data-feather="truck" class="me-50"></i><span>Blocked</span></a>';
                break;
            case 4:
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="inactive" data-order_id="' . $row->id . '" href="#"><i data-feather="truck" class="me-50"></i><span>Active</span></a>';

                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="active" data-order_id="' . $row->id . '" href="#"><i data-feather="pie-chart" class="me-50"></i><span>Inactive</span></a>';
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="blocked" data-order_id="' . $row->id . '" href="#"><i data-feather="truck" class="me-50"></i><span>Blocked</span></a>';
                break;
            default:
                # code...
                break;
        }
        return $action;
    }
}
