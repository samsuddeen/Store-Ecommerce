<?php

namespace App\Datatables;

use App\Enum\Setting\PayoutPeriodEnum;
use App\Helpers\Utilities;
use App\Models\Backup\Backup;
use App\Models\Backup\BackupPeriod;
use App\Models\Setting\PayoutSetting;
use App\Models\Tag;
use Yajra\DataTables\Facades\DataTables;

class BackupDatatables implements DatatablesInterface
{

    public function getData()
    {
        $data = Backup::latest();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('date', function ($row) {
                return $row->created_at->format('d M, Y') ;
            })
            ->addColumn('file_name', function ($row) {
                return '<a href="#">'.$row->file_name.'</a>';
            })

            ->addColumn('is_manual', function ($row) {
                return ((int)$row->is_manual == 1 ) ? '<div class="badge bg-primary">Yes</div>' : '<div class="badge bg-danger">No</div>' ;
            })
            ->addColumn('action', function ($row) {
                $delete = Utilities::delete(href: route('backup.destroy', $row->id), id: $row->id);
                $download = '<a href="'.asset('storage/backup/'.$row->file_name).'" class="btn btn-sm btn-$color me-1" title="" target="_blank" download>Download</a>';
                return  $download.' '.$delete;
            })
            ->rawColumns(['action', 'is_manual', 'date', 'file_name'])
            ->make(true);
    }
}
