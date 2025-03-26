<?php

namespace App\Datatables;

use App\Helpers\Utilities;
use App\Models\Brand;
use Yajra\DataTables\Facades\DataTables;

class BrandDatatables implements DatatablesInterface
{

    public function getData()
    {
        $data = Brand::latest();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('image', function ($row) {

                return Utilities::image($row->logo ?? asset('frontend/dummynotfound.png'));
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
            ->addColumn('action', function ($row) {
                $edit =  Utilities::button(href: route('brand.edit', $row->id), icon: "edit", color: "primary", title: 'Edit brand');
                $show = Utilities::button(href: route('brand.show', $row->id), icon: "eye", color: "primary", title: 'Show brand');
                $delete = Utilities::delete(href: route('brand.destroy', $row->id), id: $row->id);
                return  $edit . '' . $show .$delete;
            })
            ->rawColumns(['action', 'image','status'])
            ->make(true);
    }
}
