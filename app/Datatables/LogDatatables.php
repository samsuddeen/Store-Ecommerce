<?php

namespace App\Datatables;

use App\Data\Log\LogData;
use App\Enum\Notification\PushNotificationEnum;
use App\Helpers\Utilities;
use App\Models\Location;
use App\Models\Notification\PushNotification;
use Yajra\DataTables\Facades\DataTables;

class LogDatatables implements DatatablesInterface
{
    protected $filters;
    function __construct($filters=[])
    {
        $this->filters = $filters;
    }
    public function getData()
    {
    
        $data = (new LogData($this->filters))->getData();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('title',function($row){
                return $row->log_title;
            })
            ->addColumn('action',function($row){
                return $row->action;
            })
            ->addColumn('url',function($row){
                return $row->url;
            })
            ->rawColumns(['action', 'title', 'url'])
            ->make(true);
    }
}
