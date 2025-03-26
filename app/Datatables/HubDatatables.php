<?php

namespace App\Datatables;

use App\Helpers\Utilities;
use App\Models\Admin\Hub\Hub;
use App\Models\Countrey;
use App\Models\Location;
use Yajra\DataTables\Facades\DataTables;

class HubDatatables implements DatatablesInterface
{

    public function getData()
    {
        $data = Hub::latest();
        // dd($data);
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('near_place', function ($row) {
               return "<a href='".route('near-place.index', ['hub_id'=>$row->id])."' >".count($row->nearPlace)."</a>";
            })
            ->addColumn('action', function ($row) {
                $edit =  Utilities::button(href: route('hub.edit', $row->id), icon: "edit", color: "primary", title: 'Edit hub');
                $show = Utilities::delete(href: route('hub.destroy', $row->id), id: $row->id);
                return  $edit . '' . $show;
            })
            ->addColumn('status',function($row){
                $action = '';
                $action .='<span class="text-';
                $action .=($row->status==1) ? 'success"' :'danger"';
                $action .='>';
                $action .=($row->status==1) ? 'Active' : 'Inactive';
                $action .='</span>';
                return $action;
            })
            ->rawColumns(['action', "near_place",'status'])
            ->make(true);
    }

   
}
