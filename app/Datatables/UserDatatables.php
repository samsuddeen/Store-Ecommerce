<?php

namespace App\Datatables;

use App\Helpers\Utilities;
use App\Models\User;
use Faker\Provider\Lorem;
use Yajra\DataTables\Facades\DataTables;

class UserDatatables implements DatatablesInterface
{

    public function getData()
    {
        $data = User::with('roles:id,name')->latest();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($row){
                return ucfirst($row->name);
            })
            ->addColumn('action', function ($row) {
                $edit =  Utilities::button(href: route('user.edit', $row->id), icon: "edit", color: "primary", title: 'Edit User');
                $show = Utilities::button(href: route('user.show', $row->id), icon: "eye", color: "primary", title: 'Show User');
                return  $edit . '' . $show;
            })
            ->editColumn('status', function ($row) {
                return Utilities::status(status: $row->status, id: $row->id);
            })
            ->addColumn('roles', function ($row) {
                return ucfirst($row->roles->implode('name', ','));
            })
            ->addColumn('created_at',function($row){
                return $row->created_at->format('Y-m-d H:i:s');
            })
            ->rawColumns(['name','action', 'roles','status','created_at'])
            ->make(true);
    }
}



