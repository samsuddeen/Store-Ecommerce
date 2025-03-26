<?php

namespace App\Datatables;

use App\Helpers\Utilities;
use App\Models\Setting\SocialSetting;
use Yajra\DataTables\Facades\DataTables;

class SocialSettingDatatables implements DatatablesInterface
{

    public function getData()
    {
        $data = SocialSetting::latest();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('url', function ($row) {
              return "<a href='$row->url' targt='_blank'>$row->url</a>";
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
                $edit =  Utilities::button(href: route('social-setting.edit', $row->id), icon: "edit", color: "primary", title: 'Edit ?Banner');
                // $delete = Utilities::delete(href: route('social-setting.destroy', $row->id), id: $row->id);
                return  $edit ;
            })
            ->rawColumns(['action', 'status', 'url'])
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
}
