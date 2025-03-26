<?php

namespace App\Datatables;

use App\Helpers\Utilities;
use App\Models\Admin\Hub\Hub;
use App\Models\Countrey;
use App\Models\Location;
use Yajra\DataTables\Facades\DataTables;

class CountryDatatables implements DatatablesInterface
{

    public function getData()
    {
        $data = Countrey::get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('flag', function ($row) {
               return $row->flags ?? null;
            })
            ->addColumn('name', function ($row) {
                return $row->name;
             })
             ->addColumn('zipcode', function ($row) {
                return $row->country_zip;
             })
            ->addColumn('action', function ($row) {
                $edit =  Utilities::button(href: route('countries.edit', $row->id), icon: "edit", color: "primary", title: 'Edit hub');
                $show = Utilities::delete(href: route('countries.destroy', $row->id), id: $row->id);
                return  $edit . '' . $show;
            })
            ->addColumn('status',function($row){
                $action = '';
                $action .='<span class="text-';
                $action .=($row->status=='Active') ? 'success"' :'danger"';
                $action .='>';
                $action .=($row->status=='Active') ? 'Active' : 'Inactive';
                $action .='</span>';
                return $action;
            })
            ->rawColumns(['action', "flag",'status','zipcode','name'])
            ->make(true);
    }

   
}
