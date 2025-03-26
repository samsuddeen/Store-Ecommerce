<?php

namespace App\Datatables;

use App\Enum\Setting\ContactTypeEnum;
use App\Helpers\Utilities;
use App\Models\Setting\ContactSetting;
use App\Models\Setting\SocialSetting;
use Yajra\DataTables\Facades\DataTables;

class ContactSettingDatatables implements DatatablesInterface
{

    public function getData()
    {
        $data = ContactSetting::latest();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('url', function ($row) {
                return "<a href='$row->url' targt='_blank'>$row->url</a>";
            })
            ->addColumn('type', function ($row) {
                return $this->getType($row->type);
            })
            ->addColumn('status', function ($row) {
                $status = '';
                $status = $this->getStatus($row->status);
                $action = '<div class="d-flex">' . $status;
                if ($row->is_default == true) {
                    $action .= '<span class="badge bg-info">Default</span>';
                }
                $action .= '<div class="dropdown"><button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown"><i data-feather="more-vertical"></i></button><div class="dropdown-menu dropdown-menu-end">';
                $action .= $this->getActions($row);

                $action .= '</div></div></div>';
                return $action;
            })
            ->addColumn('action', function ($row) {
                $edit =  Utilities::button(href: route('contact-setting.edit', $row->id), icon: "edit", color: "primary", title: 'Edit ?Banner');
                $delete = Utilities::delete(href: route('contact-setting.destroy', $row->id), id: $row->id);
                return  $edit . '' . $delete;
            })
            ->rawColumns(['action', 'status', 'url', 'type'])
            ->make(true);
    }



    private function getStatus($status)
    {
        $return_status = '';
        switch ($status) {
            case 1:
                $return_status =  '<span class="badge bg-success">Active</span>';
                break;
            case 2:
                $return_status =  '<span class="badge bg-danger">Inactive</span>';
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
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="2" data-order_id="' . $row->id . '" href="#"><i data-feather="crosshair" class="me-50"></i><span>Inactive</span></a>';
                break;
            case 2:
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="1" data-order_id="' . $row->id . '" href="#"><i data-feather="crosshair" class="me-50"></i><span>Active</span></a>';
                break;
            default:
                # code...
                break;
        }
        return $action;
    }
    private function getType($type)
    {
        $return_string = "";
        switch ($type) {
            case ContactTypeEnum::LAND_LINE:
                $return_string = "<div class='badge bg-primary'>Land Line</div>";
                break;
            case ContactTypeEnum::MOBILE_NO:
                $return_string = "<div class='badge bg-info'>Mobile No</div>";
                break;
            case ContactTypeEnum::WHATSAPP:
                $return_string = "<div class='badge bg-primary'>Whats App</div>";
                break;
            case ContactTypeEnum::VIBER:
                $return_string = "<div class='badge bg-secondary'>Viber</div>";
                break;
            default:
                # code...
                break;
        }
        return $return_string;
    }
}
