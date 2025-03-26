<?php

namespace App\Datatables;

use App\Enum\Setting\PayoutPeriodEnum;
use App\Helpers\Utilities;
use App\Models\Backup\BackupPeriod;
use App\Models\Setting\PayoutSetting;
use App\Models\Tag;
use Yajra\DataTables\Facades\DataTables;

class BackupPeriodDatatables implements DatatablesInterface
{

    public function getData()
    {
        $data = BackupPeriod::latest();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('period', function ($row) {
                return $this->getPeriod($row);
            })
            ->addColumn('status', function ($row) {
                $status = '';
                $status = $this->getStatus($row->status);
                $action = '<div class="d-flex">' . $status;
                if ((bool)$row->is_default == true) {
                    $action .= '<span class="badge bg-info">Default</span>';
                }
                $action .= '<div class="dropdown"><button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown"><i data-feather="more-vertical"></i></button><div class="dropdown-menu dropdown-menu-end">';
                $action .= $this->getActions($row);

                $action .= '</div></div></div>';
                return $action;
            })
            ->addColumn('action', function ($row) {
                $edit =  Utilities::button(href: route('backup-period.edit', $row->id), icon: "edit", color: "primary", title: 'Edit ?Banner');
                $delete = Utilities::delete(href: route('backup-period.destroy', $row->id), id: $row->id);
                return  $edit . '' . $delete;
            })
            ->rawColumns(['action', 'period', 'status'])
            ->make(true);
    }



    private function getStatus($status)
    {
        $return_status = '';
        switch ($status) {
            case 1:
                $return_status =  '<span class="badge bg-primary">Active</span>';
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
    private function getPeriod($row)
    {
        $period = '';
        switch ($row->period) {
            case PayoutPeriodEnum::DAILY:
                $period = "Daily";
                break;
            case PayoutPeriodEnum::WEEKLY:
                $period = "Weekly";
                break;
            case PayoutPeriodEnum::MONTHLY:
                $period = "Monthly";
                break;
            case PayoutPeriodEnum::QUATERLY:
                $period = "Quarterly";
                break;
            case PayoutPeriodEnum::HALFLY:
                $period = "Halfly";
                break;
            case PayoutPeriodEnum::YEARLY:
                $period = "Yearly";
                break;
            default:
                # code...
                break;
        }
        return $period;
    }
}
