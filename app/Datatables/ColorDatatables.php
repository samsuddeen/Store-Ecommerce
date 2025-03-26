<?php

namespace App\Datatables;

use App\Helpers\Utilities;
use App\Models\Color;
use Yajra\DataTables\Facades\DataTables;

class ColorDatatables implements DatatablesInterface
{

    public function getData()
    {
        $data = Color::latest();
        return DataTables::of($data)
            ->addIndexColumn()

            ->addColumn('action', function ($row) {
                $edit =  Utilities::button(href: route('color.edit', $row->id), icon: "edit", color: "primary", title: 'Edit Color');
                $show = Utilities::button(href: route('color.show', $row->id), icon: "eye", color: "primary", title: 'Show Color');
                // $delete = Utilities::button(href: route('color.destroy', $row->id), icon: "trash", color: "danger", title: 'Delete Color');
                $delete = '<form action="' . route('color.destroy', $row->id) . '" method="POST">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger" title="Delete Color" onclick="return confirm(\'Are you sure you want to delete this color?\'); event.stopPropagation();">
                                <i data-feather="trash"></i>
                            </button>
                        </form>';
                        $delete='';
                    $show='';
                return  $edit . '' . $show . '' . $delete;
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
            ->rawColumns(['action','status'])
            ->make(true);
    }
}
