<?php

namespace App\Datatables;

use App\Helpers\Utilities;
use App\Models\RFQ;
use Yajra\DataTables\Facades\DataTables;

class QuoteDatatables implements DatatablesInterface
{

    public function getData()
    {
        $data = RFQ::latest();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $delete = Utilities::delete(href: route('quote.destroy', $row->id), id: $row->id);
                return  $delete;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
