<?php

namespace App\Datatables;

use App\Helpers\Utilities;
use App\Models\Category;
use Yajra\DataTables\Facades\DataTables;

class CategoryDatatables implements DatatablesInterface
{

    public function getData()
    {
        $data = Category::withCount('children')->latest();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('total_children', function ($row) {
                return $row->children_count;
            })
            ->addColumn('image', function ($row) {
                return Utilities::image($row->image ?? asset('dummy2.png')) ;
            })
            ->addColumn('icon',function($row){
                return $row->icon;
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
                $view =  Utilities::button(href: route('category.showcat', $row->id), icon: "eye", color: "success process-status", title: 'View Category');
                $edit =  Utilities::button(href: route('category.edit', $row->id), icon: "edit", color: "primary process-status", title: 'Edit Category');
                $delete = $row->children_count ?
                    null :
                    Utilities::delete(href: route('category.destroy', $row->id), id: $row->id);
                return  $view.''.$edit . '' . $delete;
            })
            ->rawColumns(['action', 'icon','image', 'total_children','status'])
            ->make(true);
    }
}
