<?php

namespace App\Datatables;

use App\Helpers\Utilities;
use App\Models\Subscribe;
use Yajra\DataTables\Facades\DataTables;

class SubscriberDatatables implements DatatablesInterface
{

    public function getData()
    {
        $data = Subscribe::latest();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $delete = Utilities::delete(href: route('subscriber.destroy', $row->id), id: $row->id);
                return  $delete;
            })
            ->rawColumns(['action', 'image'])
            ->make(true);
    }
}
