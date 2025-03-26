<?php

namespace App\Datatables;

use App\Enum\Notification\PushNotificationEnum;
use App\Helpers\Utilities;
use App\Models\Location;
use App\Models\Marketing\NewsLetter;
use App\Models\Notification\PushNotification;
use Yajra\DataTables\Facades\DataTables;

class NewsLetterDatatables implements DatatablesInterface
{

    public function getData()
    {
        $data = NewsLetter::latest();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $edit =  Utilities::button(href: route('news-letter.edit', $row->id), icon: "edit", color: "primary", title: 'Edit Notification');
                $show =  Utilities::button(href: route('news-letter.show', $row->id), icon: "eye", color: "info", title: 'Show Notification');
                $delete = Utilities::delete(href: route('news-letter.destroy', $row->id), id: $row->id);
                return  $edit . '' .''.$show.''. $delete;
            })
            ->addColumn('status',function($row){
                return $this->getStatus($row);
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    private function getStatus(NewsLetter $newsLetter)
    {
        $status = "";
        switch ($newsLetter->status) {
            case PushNotificationEnum::PUSHED:
                $status = '<div class="badge bg-primary">Published</div>';
                break;
            case PushNotificationEnum::NOT_PUSHED:
                $status = '<div class="badge bg-danger">Not Published</div>';
                break;
            case PushNotificationEnum::DRAFT:
                $status = '<div class="badge bg-info">Draft</div>';
                break;
            
            default:
                # code...
                break;
        }
        return $status;
    }
}
