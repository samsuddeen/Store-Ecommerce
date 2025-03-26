<?php

namespace App\Datatables;

use App\Helpers\Utilities;
use App\Models\Banner;
use App\Models\Brand;
use Yajra\DataTables\Facades\DataTables;

class BannerDatatables implements DatatablesInterface
{

    public function getData()
    {
        $data = Banner::latest();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('image', function ($row) {
                return Utilities::image($row->image);
            })
            ->addColumn('action', function ($row) {
                $edit =  Utilities::button(href: route('banner.edit', $row->id), icon: "edit", color: "primary", title: 'Edit ?Banner');
                $show = Utilities::button(href: route('banner.show', $row->id), icon: "eye", color: "primary", title: 'Show Banner')
                ;
                $delete = Utilities::delete(href: route('banner.destroy', $row->id), id: $row->id);
                return  $edit . '' . $show .''. $delete;
            })
            ->rawColumns(['action', 'image'])
            ->make(true);
    }
}
