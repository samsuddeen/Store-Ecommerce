<?php

namespace App\Datatables;

use App\Helpers\Utilities;
use App\Models\Task\Task;
use Faker\Provider\Lorem;
use Yajra\DataTables\Facades\DataTables;

class TaskDatatables implements DatatablesInterface
{
    
    public function getData()
    {   
        $data = Task::with(['action','order','assignedBy','createdBy','product'])->latest();
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('status', function ($row) {
                return $row->status;
            })
            ->addColumn('action', function ($row) {
                $edit =  Utilities::button(href: route('task.edit', $row->id), icon: "edit", color: "primary", title: 'Edit User');
                $show = Utilities::button(href: route('task.show', $row->id), icon: "eye", color: "primary", title: 'Show User');
                return  $edit . '' . $show;
            })


            ->rawColumns(['action','status'])
            ->make(true);
    }
}

