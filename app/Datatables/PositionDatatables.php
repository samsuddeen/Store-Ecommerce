<?php

namespace App\Datatables;

use App\Helpers\Utilities;
use App\Models\Position;
use Yajra\DataTables\Facades\DataTables;

class PositionDatatables implements DatatablesInterface
{

    public function getData()
    {
        $data = Position::latest();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $edit =  Utilities::button(href: route('position.edit', $row->id), icon: "edit", color: "primary", title: 'Edit position');
                $show = Utilities::delete(href: route('position.destroy', $row->id), id: $row->id);

                return  $edit . '' . $show;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
