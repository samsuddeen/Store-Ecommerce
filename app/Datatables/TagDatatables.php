<?php

namespace App\Datatables;

use App\Helpers\Utilities;
use App\Models\Tag;
use Yajra\DataTables\Facades\DataTables;

class TagDatatables implements DatatablesInterface
{

    public function getData()
    {
        $data = Tag::latest();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('image', function ($row) {
                return Utilities::image($row->thumbnail);
            })
            ->addColumn('action', function ($row) {
                $edit =  Utilities::button(href: route('tag.edit', $row->id), icon: "edit", color: "primary", title: 'Edit ?Banner');
                $delete = Utilities::delete(href: route('tag.destroy', $row->id), id: $row->id);
                return  $edit . '' . $delete;
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
            ->rawColumns(['action', 'image','status'])
            ->make(true);
    }
}
