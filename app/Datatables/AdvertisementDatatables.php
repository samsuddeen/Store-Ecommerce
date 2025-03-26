<?php

namespace App\Datatables;

use App\Helpers\Utilities;
use App\Models\Advertisement;
use Yajra\DataTables\Facades\DataTables;

class AdvertisementDatatables implements DatatablesInterface
{

    public function getData()
    {
        $data = Advertisement::latest();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('image', function ($row) {
                return Utilities::image($row->image);
            })
            ->addColumn('action', function ($row) {
                $edit =  Utilities::button(href: route('advertisement.edit', $row->id), icon: "edit", color: "primary", title: 'Edit advertisement');
                $show = Utilities::delete(href: route('advertisement.destroy', $row->id), id: $row->id);
                return  $edit . '' . $show;
            })
            ->rawColumns(['action', 'image'])
            ->make(true);
    }
}
