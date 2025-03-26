<?php

namespace App\Datatables;

use App\Helpers\Utilities;
use App\Models\DeliveryRoute;
use App\Models\Location;
use Yajra\DataTables\Facades\DataTables;

class DeliveryRouteDatatables implements DatatablesInterface
{

    public function getData()
    {
        $data = DeliveryRoute::latest();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('from', function($row){
                return $row->from->title;
            })
            ->addColumn('to', function($row){
                if($row->is_location==true){
                    return $row->to->title;
                }else{
                    return $row->to->local_name;
                }

            })
            ->addColumn('action', function ($row) {
                $edit =  Utilities::button(href: route('delivery-route.edit', $row->id), icon: "edit", color: "primary", title: 'Edit Delivery Route');
                $show = Utilities::delete(href: route('delivery-route.destroy', $row->id), id: $row->id);
                return  $edit . '' . $show;
            })
            ->rawColumns(['action', 'from', 'to'])
            ->make(true);
    }
}
