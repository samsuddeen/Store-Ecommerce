<?php

namespace App\Datatables;

use App\Helpers\Utilities;
use App\Models\Location;
use Yajra\DataTables\Facades\DataTables;

class LocationDatatables implements DatatablesInterface
{

    public function getData()
    {
        $data = Location::latest();
        return DataTables::of($data)
        
            ->addIndexColumn()
            ->addColumn('belongs_to', function ($row) {
                return $row->local->local_name ?? null;
            })
            ->addColumn('near_to', function ($row) {
                return $row->nearPlace->hub->title ?? null;
            })
            ->addColumn('charge', function ($row) {
                return $row->deliveryRoute->charge ?? null;
            })
            ->addColumn('action', function ($row) {
                $edit =  Utilities::button(href: route('location.edit', $row->id), icon: "edit", color: "primary", title: 'Edit location');
                $show = Utilities::delete(href: route('location.destroy', $row->id), id: $row->id);
                return  $edit . '' . $show;
            })
            ->addColumn('status',function($row){
                $action = '';
                $action .='<span class="text-';
                $action .=($row->publishStatus==1) ? 'success"' :'danger"';
                $action .='>';
                $action .=($row->publishStatus==1) ? 'Active' : 'Inactive';
                $action .='</span>';
                return $action;
            })
            ->rawColumns(['action', 'belongs_to', 'near_to', 'charge','status'])
            ->make(true);
    }
}
